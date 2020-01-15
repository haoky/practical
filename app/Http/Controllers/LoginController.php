<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Session;
class LoginController extends Controller
{
    //连接 微信
    function connect_wechat(Request $request){
        echo $request->echostr;
    }
    //接收xml数据包
    function post_wechat(Request $request){
        //接收xml包
        $poststr=file_get_contents("php://input");
        $postobj=simplexml_load_string($poststr,"SimpleXMLElement",LIBXML_NOCDATA);
        //是关注事件  且没有关注过
        if($postobj->MsgType=='event'&&$postobj->Event=='subscribe'){
            //将标识和openid存入redis  
            $EventKey=ltrim($postobj->EventKey,'qrscene_');
            if($EventKey){
                Cache::put((string)$EventKey, (string)$postobj->FromUserName, 20);
                echo "<xml>
                    <ToUserName><![CDATA[".$postobj->FromUserName."]]></ToUserName>
                    <FromUserName><![CDATA[".$postobj->ToUserName."]]></FromUserName>
                    <CreateTime>".time()."</CreateTime>
                    <MsgType><![CDATA[text]]></MsgType>
                    <Content><![CDATA[正在登录中请稍后]]></Content>
                </xml>";die;
            }
        }
        //关注事件  用户关注过
        if($postobj->MsgType=='event'&&$postobj->Event=='SCAN'){
            //将标识和openid存入redis  
            $EventKey=$postobj->EventKey;
            if($EventKey){
                Cache::put((string)$EventKey, (string)$postobj->FromUserName, 20);
                echo "<xml>
                    <ToUserName><![CDATA[".$postobj->FromUserName."]]></ToUserName>
                    <FromUserName><![CDATA[".$postobj->ToUserName."]]></FromUserName>
                    <CreateTime>".time()."</CreateTime>
                    <MsgType><![CDATA[text]]></MsgType>
                    <Content><![CDATA[正在登录中请稍后]]></Content>
                </xml>";die;
            }

        }
    }
    //js轮询检测是否扫码
    function check_Ewm(){
        $str=request()->input('str');
        $checkstr=Cache::get($str);
        if(!$checkstr){
            echo '未扫码';die;
        }else{
            echo '扫码成功';die;
        }

    }
    function login(){
        $token=$this->getToken();
        $url="https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$token";
        $str=md5(time().rand(1000,9999));
        $str="aaa";
        $postdata=" {\"action_name\": \"QR_LIMIT_STR_SCENE\", \"action_info\": {\"scene\": {\"scene_str\": \"".$str."\"}}}";
        $data=self::curlpost($url,$postdata);
        $data=json_decode($data,true);
        $data['str']=$str;
        $url="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".$data['ticket']; 
        return view('login',['data'=>$data]);
    }
    function login_do(){
        $data=request()->all();
        $check_user=DB::table('user')->where(['user_name'=>$data['user_name']])->first();
        if(!$check_user){
            return redirect('login/login')->withErrors(['账号错误']);
        }elseif($check_user->user_pwd!=$data['user_pwd']){    //密码错误
            //次数超过3次   封停账号  存入错误时间
            if($check_user->user_error_num<=0&&time()<=$check_user->time+3600){
                $min=60-floor((time()-$check_user->time)/60);
                return redirect('login/login')->withErrors(['账号已锁定,还有'.$min.'分钟']);
            }elseif(time()>$check_user->time+3600&&$check_user->user_error_num<=0){  //错误时间到了   清零次数
                DB::table('user')->where(['user_name'=>$data['user_name']])->update(['user_error_num'=>2]);
                return redirect('login/login')->withErrors(['您还有2次机会']);
            }
                //累加次数  刷新错误时间
            $error_num=$check_user->user_error_num-1;
            DB::table('user')->where(['user_name'=>$data['user_name']])->update(['user_error_num'=>$error_num,'time'=>time()]);
            return redirect('login/login')->withErrors(['密码错误，您还有'.$error_num.'次机会']);
        }
        //密码正确  判断是否是在错误时间内
        if(time()<$check_user->time+3600&&$check_user->user_error_num<=0){
            $min=60-floor((time()-$check_user->time)/60);
            return redirect('login/login')->withErrors(['账号已锁定,还有'.$min.'分钟']);
        }else{
            $sessionid=Session::getId();
            DB::table('user')->where(['user_name'=>$data['user_name']])->update(['session_id'=>$sessionid]);
            //清空错误时间 错误次清零
            session(['user_name'=>$data['user_name']]);
            DB::table('user')->where(['user_name'=>$data['user_name']])->update(['user_error_num'=>3,'time'=>null,'operation_time'=>time()]);
            echo '登录成功';
        }
    }
    function check(){
        echo '列表';die;
    }


    public static function curlget($url){
        //初始化
        $ch=curl_init();
        //设置curl
        curl_setopt($ch,CURLOPT_URL,$url);//请求路径
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //返回数据格式
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
        //执行
        $result=curl_exec($ch);
        //关闭
        curl_close($ch);
        return $result;
    }
    public static function curlpost($url,$postdata){
        //初始化
        $ch=curl_init();
        //设置
        curl_setopt($ch,CURLOPT_URL,$url);  //请求地址
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);  //返回数据格式
        curl_setopt($ch,CURLOPT_POST,1);    //POST提交方式
        curl_setopt($ch,CURLOPT_POSTFIELDS,$postdata);
        //访问https网站
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
        //执行curl
        $result=curl_exec($ch);
        //退出
        curl_close($ch);
        return $result;
    }
    /**
     * 获取access_token令牌
     */
    public static function getToken()
    {
        //缓存里有数据 直接读缓存
        $accees_token = "";
        $access_token = Cache::get("access_token");
        if(empty($access_token)){
            //缓存里没有数据 调用接口获取 存入缓存
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx586fdcac9f5a167f&secret=fdc2cc3c2bb15f81f89dcf5325ce0b6b";
            //发请求
            $data = file_get_contents($url);
            $data = json_decode($data,true);
            $access_token = $data['access_token'];
            //存储2小时
            Cache::put("access_token",$access_token,7200);
        }
        return $access_token;
    }
}

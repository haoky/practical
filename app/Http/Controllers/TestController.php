<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use App\Test;
use Illuminate\Support\Facades\Redis;
class TestController extends Controller
{
    //web2
    function admin(){

        return view('Test/admin');
    }
    //登录接口
    function user_list(){
        $user_list=DB::table('user')->get()->toarray();
        return json_encode($user_list);
    }
    //错误次数修改
    function error_upd(){
        $user_id=request()->input('user_id');
        $error_num=request()->input('error_num');
        $res=DB::table('user')->where(['user_id'=>$user_id])->update(['user_error_num'=>$error_num]);
        if($res!==false){
            echo json_encode(['code'=>1]);
        }else{
            echo json_encode(['code'=>2]);
        }
    }
    //用户错误限制
    function error_num(){
        $data=request()->all();
        $check_user=DB::table('user')->where(['user_name'=>$data['user_name']])->first();
        if(!$check_user){
            echo '账号错误';die;
        }else{
            $check_user_pwd=DB::table('user')->where($data)->first();
            if($check_user_pwd){
                if($check_user_pwd->user_error_num<=0){
                    echo '账号已被锁定';die;
                }
                Redis::set('user_name',$data['user_name']);
              
                 echo '登录成功';die;
                
            }else{
                $number=$check_user->user_error_num-1;
                if($number>=0){
                    DB::table('user')->where(['user_name'=>$data['user_name']])->update(['user_error_num'=>$number]);
                    echo '密码错误,您还可以输错'.$number.'次';die;
                }else{
                    echo '密码错误次数过多，账号已被锁定';die;
                }
            }
        }
    }
    //解锁
    function v_unlock(){
        $user_id=request()->input('user_id');
        $check_errot=DB::table('user')->where('user_id',$user_id)->first();
        if($check_errot->user_error_num>0){
            echo json_encode(['code'=>2,'font'=>'无需解锁']);die;
        }
        $res=DB::table('user')->where('user_id',$user_id)->update(['user_error_num'=>10]);
        if($res!==false){
            echo json_encode(['code'=>1,'font'=>'解锁成功，该用户还有10次机会']);die;
        }
    }
    
}

<?php

namespace App\Http\Controllers\Authion;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class Index extends Controller
{
    //竞拍商品展示
    function index(){
       
        $res=DB::table('goods_auction')->get();
        // print_r($res);die;
        return view('Authion/index',['data'=>$res]);
    }
    //参与竞拍
    function join_auction($goods_auction_id){
        $where=[
            'auction.goods_auction_id'=>$goods_auction_id,
        ];
        
        $res=DB::table('auction')->where($where)->join('goods_auction','goods_auction.goods_auction_id','=','auction.goods_auction_id')->orderby('goods_price','desc')->first();
        //如果没有人参加过竞拍  将底价返回
        if(empty($res)){
            $res=DB::table('goods_auction')->where(['goods_auction_id'=>$goods_auction_id])->first();
            $price=$res->Upset_price;
            //判断是否已经超过过期时间
            if(time()<$res->begin_time){
                echo '此商品未开始拍卖';die;
            }elseif(time()>$res->end_time){
                echo '此商品已结束';die;
            }
        }else{  //有人参加竞拍  将最高价返回
            //判断是否过期  是否已经开始拍卖
            if(time()<$res->begin_time){
                echo '此商品未开始拍卖';die;
            }elseif(time()>$res->end_time){
                echo '此商品已结束拍卖';die;
            }
            $price=$res->goods_price;
        }
        return view('Authion/add_authion',['data'=>$res,'price'=>$price]);
    }
    //参与竞拍执行
    function add_join_auction(){
       
        $user_name=Redis::get('user_name');
        $user_id=DB::table('user')->where('user_name',$user_name)->first(['user_id']);
        $price=request()->input('goods_price');
        $goods_auction_id=request()->input('goods_auction_id');
        $res=DB::table('auction')->where(['auction.goods_auction_id'=>$goods_auction_id])->join('goods_auction','goods_auction.goods_auction_id','=','auction.goods_auction_id')->orderby('goods_price','desc')->first();
        $aa=$res->goods_price+$res->minimum_price;
        if($price<$res->goods_price){
            echo '价格必须大于当前价格';die;
        }elseif($price<$res->goods_price+$res->minimum_price){
            echo '每次最少加价'.$res->minimum_price;die;
        }elseif(time()>$res->end_time){
            echo '竞拍已结束';die;
        }else{
            $where=[
                'user_id'=>$user_id->user_id,
                'goods_auction_id'=>$goods_auction_id,
                'goods_price'=>$price,
            ];
            $res=DB::table('auction')->insert($where);
            if($res){
                echo '竞价成功';die;
            }
        }
    }
    //竞价列表
    function join_auction_list(){
        $res=DB::table('auction')->join('goods_auction','goods_auction.goods_auction_id','=','auction.goods_auction_id')->get();
        return view('Authion/join_auction_list',['data'=>$res]);
    }
    //出价记录
    function record($goods_auction_id){
        $user_name=Redis::get('user_name');
        $user_name=DB::table('user')->where('user_name',$user_name)->first(['user_name']);
        $res=DB::table('auction')->where('goods_auction_id',$goods_auction_id)->orderby('goods_price','desc')->get()->toarray();
        //   print_r($res);die;
        foreach($res as $k=>$v){
            $price=$res[0]->goods_price;
        }
        
        return view('Authion/record',['data'=>$res,'user_name'=>$user_name->user_name,'price'=>$price]);
    }
}

<?php

namespace App\Http\Controllers\Authion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class Admin extends Controller
{
    //添加竞拍商品
    function add_authion_goods(){
        return view('Authion/add_authion_goods');
    }
    function add_do_authion_goods(){
        $data=request()->all();
        $data['begin_time']=strtotime($data['begin_time']);
        $data['end_time']=strtotime($data['end_time']);
        $res=DB::table('goods_auction')->insert($data);
        if($res){
            echo '添加竞拍商品成功';
        }
    }
    
}

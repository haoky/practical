<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<table>
        <tr>
            <td>商品名称</td>
            <td>底价</td>
            <td>时间</td>
            <td>状态</td>
            <td>操作</td>
        </tr>
        @foreach($data as $k=>$v)
        <tr>
            <td>{{$v->goods_name}}</td>
            <td>{{$v->Upset_price}}</td>
            <td>{{date("y-m-d h:i:s",$v->time)}}</td>
            <td><?php if($v->end_time>time()&&time()>$v->begin_time){echo '进行中';}?></td>
            <td><a href="{{url('authion/index/record',['goods_auction_id'=>$v->goods_auction_id])}}">出价记录</a></td>
        </tr>
        @endforeach
    </table>
</body>
</html>
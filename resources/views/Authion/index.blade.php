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
            <td>开始时间</td>
            <td>结束时间</td>
            <td>状态</td>
            <td>操作</td>
        </tr>
        @foreach($data as $k=>$v)
        <tr>
            <td>{{$v->goods_name}}</td>
            <td>{{$v->Upset_price}}</td>
            <td>{{date("Y-m-d H:i:s",$v->begin_time)}}</td>     
            <td>{{date("Y-m-d H:i:s",$v->end_time)}}</td>     
            <td> @if(time()>$v->end_time) 已结束@elseif(time()<$v->begin_time) 未开始 @elseif(time()>$v->begin_time&&time()<$v->end_time)echo '进行中';@endif</td>
            <td><a href="{{url('authion/index/join_auction',['goods_auction_id'=>$v->goods_auction_id])}}">参与竞拍</a></td>
        </tr>
        @endforeach
    </table>

</body>
</html>
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
            <td>状态</td>
            <td>价格</td>
            <td>竞拍人</td>
            <td>时间</td>
        </tr>
        @foreach($data as $k=>$v)
        <tr>
            <td>@if($price==$v->goods_price) 领先 @else 出局 @endif</td>
            <td>{{$v->goods_price}}</td>
            <td>{{$user_name}}</td>
            <td>{{$v->time}}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<form action="{{url('authion/index/add_join_auction')}}">
<input type="hidden" name="goods_auction_id" value="{{$data->goods_auction_id}}">
    <p>我要加价</p>
    <p>当前价格：{{$price}}</p>
    价格<input type="text" placeholder="价格不低于当前价格" name="goods_price"><br>
    <input type="submit" value="出价">
    </form>
</body>
</html>
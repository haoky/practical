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
    <form action="{{url('authion/admin/add_do_authion_goods')}}" method="get">
   
        <tr>
            <td>商品名称</td>
            <td><input type="text" name="goods_name"></td>
        </tr>
        <tr>
            <td>保证金</td>
            <td><input type="text" name="guarantee"></td>
        </tr>
        <tr>
            <td>底价</td>
            <td><input type="text" name="Upset_price"></td>
        </tr>
        <tr>
            <td>最低加价</td>
            <td><input type="text" name="minimum_price"></td>
        </tr>
        <tr>
            <td>竞拍时间</td>
            <td><input type="text" name="begin_time" placeholder="开始时间">---<input type="text" name="end_time" placeholder="结束时间"></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" ></td>
        </tr>
        
 
    </form>
    </table>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<p>
@if(!empty($errors->first()))
    {{$errors->first()}}
@endif
</p>
<form action="{{url('login/login_do')}}" method="post">
<input type="hidden" value="{{$data['str']}}">
    @csrf
    账号<input type="text" name="user_name" id="user_name"><br>
    密码<input type="password" name="user_pwd" id="user_pwd"><br>
    <img src="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket={{$data['ticket']}}" width="150px" height="150px">
    <input type="button" value="登录" id="button">
</form>
</body>
</html>
<script src="/jquery.js"></script>
<script>


    var _t=setInterval(function () {
        check();
    }, 2000);

        function check(){
            var str=$("[type='hidden']").val()
            $.ajax({
                url:"{{url('login/check_Ewm')}}",
                data:{str:str},
                success:function(res){
                    if(res=='扫码成功'){
                        alert('登录成功')
                        clearInterval(_t);
                        location.href="http://ks.com/login/check"
                    }
                }
            })
        }
        

   

</script>
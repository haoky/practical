<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <p>后台布局 &nbsp;&nbsp控制台&nbsp;&nbsp商品管理&nbsp;&nbsp用户&nbsp;&nbsp其它系统</p>
    <div >
        管理员管理
    </div>
    <div id="user">
        用户管理
    </div>
    <div>云市场</div>
    <div>
        发布商品
    </div>
    <div>
    <table border=1 style="display:none;">
        <tbody id="tr">
            <td>用户名称</td>
            <td>错误次数</td>
            <td>操作</td>
        </tbody>
        <tbody id="tbody">

        </tbody>
        
    </table>
    </div>
</body>
</html>
<script src="/jquery.js"></script>
<script>
    $(document).on('click','#user',function(){
        if($(this).next().children('a').text()=='用户列表'){
            $(this).next().remove()
            $(this).next().remove()
            $('table').hide();
            return 
        }
        var div='<div><a href="javascript:;" class="user_list">用户列表</a></div><div>添加新用户</div>'
        $(this).after(div);
    })
    var tr="";
    $(document).on('click','.user_list',function(){
        $.ajax({
            url:"{{url('api/test/user_list')}}",
            dataType:"json",
            success:function(res){
                $("._tr").remove();
                $.each(res,function(i,v){
                    tr='<tr user_id='+v.user_id+' class="_tr">\
                            <td>'+v.user_name+'</td>\
                            <td><input type="text" id="_input" style="display:none;"><span id="span">'+v.user_error_num+'</span></td>\
                            <td><a href="javascript:;" id="v_unlock">解锁</a></td>\
                        </tr>'
                        
                        $('#tbody').append(tr);
                })
                
               
                
                $('table').show();
            //    console.log(tr)
            }
        })
    })
     $(document).on('click','#span',function(){
         $(this).hide();
         $(this).prev().show();
        
     })
     //解锁
     $(document).on('click','#v_unlock',function(){
        var user_id=$(this).parents('tr').attr('user_id');
        $.ajax({
            url:"{{url('/test/v_unlock')}}",
            data:{user_id:user_id},
            dataType:"json",
            success:function(res){
                if(res.code==1){
                    $(this).parent().prev().children('span').text(10)
                    alert(res.font)
                }else{
                    alert(res.font)
                }
            }
        })
     })
     
     $(document).on('blur','#_input',function(){
        //获取id
        var user_id=$(this).parents('tr').attr('user_id');
        var error_num=$(this).val();
        var _this=$(this);
        if(error_num==''){
            _this.hide();
            _this.next().show();
            return 
        }
        $.ajax({
                url:"{{url('test/error_upd')}}",
                dataType:"json",
                data:{user_id:user_id,error_num:error_num},
                success:function(res){
                    if(res.code==1){
                        _this.hide();
                        _this.next().text(error_num)
                        _this.next().show();
                    }
                }
            })
        
     })
</script>
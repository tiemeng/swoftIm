<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>注册</title>
    <link rel="stylesheet" href="/public/layui/css/layui.css" media="all">
</head>
<body>
<div class="layui-row">
    <div class="layui-col-xs12 layui-col-sm12 layui-col-md12 layui-col-lg6" style="padding-top: 50px;padding-right: 20px;">
        <form class="layui-form " onsubmit="return create()">
            <div class="layui-form-item">
                <label class="layui-form-label">组名称</label>
                <div class="layui-input-block">
                    <input type="text" name="name" required  lay-verify="required" placeholder="请输入组名" autocomplete="off" class="layui-input">
                </div>
            </div>
            <?php if($type == 2):?>
            <div class="layui-form-item">
                <label class="layui-form-label">头像</label>
                <div class="layui-input-block">
                    <button type="button" class="layui-btn" id="avatar">
                        <i class="layui-icon">&#xe62f;</i>  上传头像
                    </button><br>
                    <img id="img" src="" style="display: none;width: 100px;">
                </div>
            </div>
            <?php endif;?>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <input type="hidden" name="avatar">
                    <button class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="/public/layui/layui.js"></script>
<script>
    var storage=window.localStorage;
    layui.use('layer', function(){
        layer = layui.layer;
    });
    layui.use('upload', function(){
        var upload = layui.upload;
        upload.render({
            elem: '#avatar'
            ,url: '/upload'
            ,before: function(obj){ //obj参数包含的信息，跟 choose回调完全一致，可参见上文。
                layer.load(); //上传loading
            }
            ,done: function(res, index, upload){
                layer.closeAll('loading'); //关闭loading
                if (res.code == 200){
                    $('#img').attr('src', res.data.url).show();
                    $('input[name="avatar"]').val(res.data.url);
                }else{
                    layer.msg(res.msg,function(){});
                }

            }
            ,error: function(index, upload){
                layer.closeAll('loading'); //关闭loading
                layer.msg("网络繁忙",function(){});
            }
        });
    });
    function create(){
        let name = $('input[name="name"]').val();
        let avatar = $('input[name="avatar"]').val();
        let token = storage.getItem('token');
        $.post('/createGroup?token='+token+"&type=<?=$type?>",{name:name,avatar:avatar},(res)=>{
            if(res.code == 200){
                layer.msg('创建成功',{icon:1},()=>{
                    if(res.data.type == 'group'){
                        parent.parent.layui.layim.addList(res.data);
                        parent.location.href = '/find?token='+token+'&type=group';
                    }else{
                        parent.parent.location.reload();
                    }

                })
            }else{
                layer.msg(res.msg);
            }
        },'json');
        return false;
    }
</script>
</body>
</html>
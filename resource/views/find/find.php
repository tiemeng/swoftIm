<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>查找</title>
    <link rel="stylesheet" href="/public/layui/css/layui.css" media="all">
</head>
<body>
<div class="layui-row">
    <div class="layui-tab layui-tab-brief">
        <ul class="layui-tab-title">
            <li <?php if ($type == 'user' || $type == ''):?>class="layui-this"<?php endif;?> >找人</li>
            <li <?php if ($type == 'group'):?>class="layui-this"<?php endif;?> >找群</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item  <?php if ($type == 'user' || $type == ''):?>layui-show<?php endif;?>   ">
                <div>
                    <input  style="float: left;width: 80%;" type="text" id="user-wd" required lay-verify="required" placeholder="请输入ID/昵称" autocomplete="off" class="layui-input"
                            <?php if ($type == 'user'):?>value="<?=$wd;?>"<?php endif;?>
                    >
                    <button onclick="createUserGroup()" style="float: right;width: 10%"  class="layui-btn layui-btn-warm">
                        创建分组
                    </button>
                    <button onclick="findUser()" style="float: left;width: 10%;margin-left: 0"  class="layui-btn">
                        <i class="layui-icon">&#xe615;</i> 查找
                    </button>
                </div>
                <div class="layui-row">
                    <?php foreach($userList as $k=>$v): ?>
                        <div class="layui-col-md4" style="border-bottom: 1px solid #f6f6f6">
                        <div class="layui-card">
                            <div class="layui-card-header">
                                <?=$v['nickname']?>(<?=$v['id']?>)
                            </div>
                            <div class="layui-card-body">
                                <img style="width: 75px;height: 75px;object-fit: cover;" src="<?=$v['avatar']?>" alt="">
                                <button onclick="addFriend(<?=$v['id']?>,'<?=$v['nickname']?>','<?=$v['avatar']?>')" style="float: right" class="layui-btn layui-btn-normal layui-btn-sm">
                                    <i class="layui-icon">&#xe654;</i> 添加
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach ?>
                </div>
            </div>
            <div class="layui-tab-item  <?php if ($type == 'group'):?>layui-show<?php endif;?>  ">
                <div>
                    <input  style="float: left;width: 80%;" type="text" id="group-wd" required lay-verify="required" placeholder="请输入群Id/群名称" autocomplete="off" class="layui-input"
                            <?php if ($type == 'group'):?>value="<?=$wd;?>"<?php endif;?>

                    >
                    <button onclick="createGroup()" style="float: right;width: 10%"  class="layui-btn layui-btn-warm">
                        <i class="layui-icon">&#xe654;</i> 创建群
                    </button>
                    <button onclick="findGroup()" style="float: left;width: 10%;margin-left: 0"  class="layui-btn">
                        <i class="layui-icon">&#xe615;</i> 查找群
                    </button>
                </div>

                <?php foreach($groupList as $k=>$v): ?>

                    <div class="layui-col-md4" style="border-bottom: 1px solid #f6f6f6">
                        <div class="layui-card">
                            <div class="layui-card-header"> <?=$v['name']??''?>(<?=$v['id']?>)</div>
                            <div class="layui-card-body">
                                <img style="width: 75px;height: 75px;object-fit: cover;" src="<?=$v['avatar']?>" alt="">
                                <button onclick="joinGroup(<?=$v['id']?>)" style="float: right" class="layui-btn layui-btn-normal layui-btn-sm">
                                    <i class="layui-icon">&#xe654;</i> 加入
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>

            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="/public/layui/layui.js"></script>
<script>
    var layer;
    var storage=window.localStorage;

    layui.use('layer', function(){
        layer = layui.layer;
    });
    layui.use('element', function(){
        var element = layui.element;
    });
    function findUser() {
        wd = $('#user-wd').val();
        window.location.href="/find?token="+storage.getItem('token')+"&type=user&wd="+wd
    }
    function findGroup() {
        wd = $('#group-wd').val();
        window.location.href="/find?token="+storage.getItem('token')+"&type=group&wd="+wd
    }

    function addFriend(id,nickname,avatar) {
        layui.use('layim', function(layim){
            layim.add({
                type: 'friend' //friend：申请加好友、group：申请加群
                ,username: nickname //好友昵称，若申请加群，参数为：groupname
                ,avatar: avatar //头像
                ,submit: function(group, remark, index){ //一般在此执行Ajax和WS，以通知对方
                    var data = {cmd:"home.addFriend",to_user_id:id,to_friend_group_id:group,remark:remark,token:storage.getItem('token')}
                    parent.sendMessage(parent.websocket,JSON.stringify(data))
                    layer.close(index); //关闭改面板
                }
            });
        });
    }

    function joinGroup(id) {
        $.post("/joinGroup?token="+storage.getItem('token'),{gid:id},(res)=>{
            if(res.code == 200){
                layer.msg('添加群成功');
                parent.layui.layim.addList(res.data);
                let joinNotify = {cmd:"home.joinGroup","gid":id,token:storage.getItem('token')};
                parent.sendMessage(parent.websocket,JSON.stringify(joinNotify));
            }else{
                layer.msg(res.msg);
            }
        },'json');
    }

    function createUserGroup(){
        layer.open({
            type: 2,
            title: '创建用户组',
            shadeClose: true,
            shade: 0.8,
            area: ['40%', '70%'],
            content: '/createGroup?token='+storage.getItem('token')+"&type=1" //iframe的url
        });
    }

    function createGroup() {
        layer.open({
            type: 2,
            title: '创建群',
            shadeClose: true,
            shade: 0.8,
            area: ['40%', '70%'],
            content: '/createGroup?token='+storage.getItem('token')+"&type=2" //iframe的url
        });
    }
</script>
</body>
</html>
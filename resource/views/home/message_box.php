<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>消息盒子</title>
    <link rel="stylesheet" href="/public/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="/public/bootstrap-3.3.7/css/bootstrap.css">
    <style>
        .layim-msgbox{margin: 15px;}
        .layim-msgbox li{position: relative; margin-bottom: 10px; padding: 0 130px 10px 60px; padding-bottom: 10px; line-height: 22px; border-bottom: 1px dotted #e2e2e2;}
        .layim-msgbox .layim-msgbox-tips{margin: 0; padding: 10px 0; border: none; text-align: center; color: #999;}
        .layim-msgbox .layim-msgbox-system{padding: 0 10px 10px 10px;}
        .layim-msgbox li p span{padding-left: 5px; color: #999;}
        .layim-msgbox li p em{font-style: normal; color: #FF5722;}

        .layim-msgbox-avatar{position: absolute; left: 0; top: 0; width: 50px; height: 50px;}
        .layim-msgbox-user{padding-top: 5px;}
        .layim-msgbox-content{margin-top: 3px;}
        .layim-msgbox .layui-btn-small{padding: 0 15px; margin-left: 5px;}
        .layim-msgbox-btn{position: absolute; right: 0; top: 12px; color: #999;}
    </style>
</head>
<body>
<ul class="layim-msgbox" id="LAY_view">
    <?php foreach($list as $k=>$v): ?>
        <?php if ($v['type'] == 0):?>
            <li data-uid="<?=$v['fromId']?>" data-fromgroup="<?=$v['gid']?>">
                <a href="javascript:;">
                    <img style="width: 40px;height: 40px" src="<?=$v['avatar']?>" class="layui-circle layim-msgbox-avatar"></a>
                <p class="layim-msgbox-user">
                    <a href="javascript:;" ><?=$v['nickname']?></a>
                    <span><?=$v['createdAt']?></span></p>
                <p class="layim-msgbox-content">申请添加你为好友
                    <span>附言: <?=$v['remark']?></span></p>
                <p class="layim-msgbox-btn">
                    <?php if ($v['status'] == 0):?>
                    <button class="layui-btn layui-btn-small" onclick='agree(<?=$v['id']?>,$(this),"<?=$v['avatar']?>","<?=$v['nickname']?>")'>同意</button>
                    <button class="layui-btn layui-btn-small layui-btn-primary" onclick="refuse(<?=$v['id']?>,$(this))">拒绝</button>
                    <?php else:?>
                    <span>已<?php echo $v['status'] == 1 ? '同意' : '拒绝'?></span>
                    <?php endif;?>
                </p>
            </li>
        <?php else:?>
        <li class="layim-msgbox-system">
                <p>
                    <em>系统：</em><?=$v['nickname']?> 已经<?php echo $v['status'] == 1 ? '同意' : '拒绝'?>你的好友申请
                    <span><?=$v['createdAt']?></span></p>
            </li>
        <?php endif;?>
    <?php endforeach ?>

</ul>
<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="/public/layui/layui.js"></script>
<script>
    $(()=>{
        $.post('/setRead?token='+parent.locastorage.getItem('token'))
    });
    var layer;
    layui.use('layer', function(){
        layer = layui.layer;
    });
    function refuse(id,obj) {
        parent.layer.close();
        $.post("/refuse?token="+parent.locastorage.getItem('token'),{id:id},(res)=>{
            if(res.code == 200){
                layer.msg(res.msg);
                obj.parent().html('<span>已拒绝</span>');
                parent.sendMessage(parent.websocket,JSON.stringify({cmd:"home.refuseFriend",id:id}))
            }else{
                layer.msg(res.msg);
            }
        },'json');
    }
    function agree(id,obj,avatar,nickname){
        parent.layui.layim.setFriendGroup({
            type: 'friend'
            ,username: nickname
            ,avatar: avatar //头像
            ,group: parent.layui.layim.cache().friend
            ,submit: function(group, index){
                parent.layer.close(index);
                $.post("/addFriend?token="+parent.locastorage.getItem('token'),{id:id,gid:group},(res)=>{
                    if (res.code == 200){
                        let uid = obj.parents('li').attr('data-uid');
                        let fromgroup = obj.parents('li').attr('data-fromgroup');
                        parent.sendMessage(parent.websocket, JSON.stringify({cmd:"home.addList",id:uid,gid:fromgroup,token:parent.locastorage.getItem('token')}));
                        parent.layui.layim.addList(res.data); //将刚通过的好友追加到好友列表
                        obj.parent().html('<span>已同意</span>');
                    } else {
                        layer.msg(res.msg,function(){});
                    }
                },'json');
            }
        });
    }
</script>
</body>
</html>
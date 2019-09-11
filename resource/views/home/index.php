<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>基于swoft和layim聊天系统</title>

    <link rel="stylesheet" href="/public/layui/css/layui.css">
    <style>
        html {
            background-color: #333;
        }
    </style>
</head>
<body >
<span style="color: #3FDD86" onclick="logout()">退出</span>
<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="/public/layui/layui.js"></script>
<script>

    var locastorage = window.localStorage;
    var websocket;

    function sendMessage(socket,data){
        socket.send(data);
    }
    function logout(){
        $.post('/logout?token='+locastorage.getItem('token'),{},(res)=>{
            if(res.code == 200){
                layer.msg('退出成功',{icon:1,time:2000},()=>{
                    locastorage.clear();
                    window.location = "/login";
                });
            }else{
                layer.msg(res.msg,{icon:2});
            }
        },'json');
    }
    layui.use('layim', function (layim) {
        var token = locastorage.getItem('token');
        //基础配置
        layim.config({
            //初始化接口
            init: {
                url: '/getList?token=' + token
                , data: {}
            }
            //查看群员接口
            , members: {
                    url: '/getMembers?token=' + token
                , data: {}
            }
            //上传图片接口
            , uploadImage: {
                url: '/upload' //（返回的数据格式见下文）
                , type: '' //默认post
            }
            //上传文件接口
            , uploadFile: {
                url: '/upload/file' //（返回的数据格式见下文）
                , type: '' //默认post
            }
            //扩展工具栏
            , tool: [{
                alias: 'code'
                , title: '代码'
                , icon: '&#xe64e;'
            }]
            //,brief: true //是否简约模式（若开启则不显示主面板）
            //,title: 'WebIM' //自定义主面板最小化时的标题
            //,right: '100px' //主面板相对浏览器右侧距离
            //,minRight: '90px' //聊天面板最小化时相对浏览器右侧距离
            , initSkin: '5.jpg' //1-5 设置初始背景
            //,skin: ['aaa.jpg'] //新增皮肤
            //,isfriend: false //是否开启好友
            //,isgroup: false //是否开启群组
            //,min: true //是否始终最小化主面板，默认false
            , notice: true //是否开启桌面消息提醒，默认false
            //,voice: false //声音提醒，默认开启，声音文件为：default.wav

            , msgbox: "/message?token="+locastorage.getItem('token') //消息盒子页面地址，若不开启，剔除该项即可
            , find: "/find?token="+locastorage.getItem('token') //发现页面地址，若不开启，剔除该项即可
            // , chatLog: '/chatLog.html?token='+locastorage.getItem('token') //聊天记录页面地址，若不开启，剔除该项即可

        });
        websocket = new WebSocket('ws://127.0.0.1:18308/chat?token=' + locastorage.getItem('token'));
        websocket.onopen = function (evt) {

            console.log(evt);
        };
        websocket.onmessage = (evt) => {
            let data =$.parseJSON(evt.data);
            switch (data.type) {
                case 'error':
                    layer.msg(data.msg,{icon:1,time:2000},()=>{
                        window.location = "/";
                    });
                    break;
                case 'layer':
                    layer.msg(data.msg,{icon:2,time:2000});
                    break;
                case "msgBox" :
                    //为了等待页面加载，不然找不到消息盒子图标节点
                    setTimeout(function(){
                        if(data.count > 0){
                            layim.msgbox(data.count);
                        }
                    },1000);
                    break;
                case 'joinGroup':
                    console.info(data.data);
                    layim.getMessage({
                        system: true //系统消息
                        ,gid: 7
                        ,type: "group"
                        ,content: '贤心加入群聊'
                    });
                    break;
                case "friend":
                case "group":
                    layim.getMessage(data); //res.data即你发送消息传递的数据（阅读：监听发送的消息）
                    break;
                case 'online':
                    layim.on('online',(data)=>{

                    });
                    break;
                case 'addList':
                    layim.addList(data.data);
                    break;
            }
        };

        //监听在线状态的切换事件
        layim.on('online', function (data) {
            //console.log(data);
        });

        //监听签名修改
        layim.on('sign', function (value) {
            $.post('/updateSign?token='+token,{sign:value},(res)=>{
                layer.msg(res.msg);
            },'json');
        });

        //监听自定义工具栏点击，以添加代码为例
        layim.on('tool(code)', function (insert) {
            layer.prompt({
                title: '插入代码'
                , formType: 2
                , shade: 0
            }, function (text, index) {
                layer.close(index);
                insert('[pre class=layui-code]' + text + '[/pre]'); //将内容插入到编辑器
            });
        });

        //监听layim建立就绪
        layim.on('ready', function (res) {

            <?php
                if($count > 0):
            ?>
            layim.msgbox(<?=$count?>);
            <?php endif;?>

        });

        //监听发送消息
        layim.on('sendMessage', function (data) {
            data.token = locastorage.getItem('token');
            data.cmd = 'home.message';
            sendMessage(websocket,JSON.stringify(data));
        });

        //监听查看群员
        layim.on('members', function (data) {
            //console.log(data);
        });

        //监听聊天窗口的切换
        layim.on('chatChange', function (res) {
            var type = res.data.type;
            if (type === 'friend') {
                //模拟标注好友状态
                //layim.setChatStatus('<span style="color:#FF5722;">在线</span>');
            }
        });


    });
</script>
</body>
</html>

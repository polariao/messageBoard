<?php
require './JSSDK.php';
//设置一下时区
date_default_timezone_set('Asia/Shanghai');
$jssdk = new JSSDK("wxd98e82c05e957857", "1c2cfd172651fb31bc532ee9a3f34b46");
$signPackage = $jssdk->GetSignPackage();

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,height=device-height, user-scalable=no,initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title>软件学院留言系统</title>
    <link href="admin/weui.min.css" rel="stylesheet" />
    <link href="admin/bootstrap.min.css" rel="stylesheet" type="text/css" >
    <script src="admin/jquery-2.1.1.js" type="text/javascript" ></script>
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css">
<!--    <script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>-->
    <script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js" type="text/javascript"></script>
    <link rel="stylesheet" href="https://cdn.bootcss.com/weui/1.1.3/style/weui.min.css">
    <link rel="stylesheet" href="https://cdn.bootcss.com/jquery-weui/1.2.1/css/jquery-weui.min.css">
    <style>
        #ul{position: fixed; top: 0; left: 0; right: 0; z-index: 100;font-size: 17px;}
        .li{width: 50%; text-align: center;background: #83DAFE}
        .content{position: absolute; top: 54px; left: 0; right: 0; bottom: 0; overflow: auto;}
        .lab{text-align:right;font-size:17px;line-height:43px;font-weight:normal;}
        .main{display: none;}
        .pl{
            width: 120px;
            overflow:hidden;
            text-overflow:ellipsis;
            white-space: nowrap;/*加宽度width属来兼容部分浏览*/
        }
    </style>
</head>
<body>

<hr>
<ul class="nav nav-tabs" id="ul">
    <li class="li active"><a href="#">我要留言</a></li>
    <li class="li"><a href="#">我的留言</a></li>
</ul>

<div class="content">
    <div class="main">

        <div class="container">
            <div>

                <form class="form-horizontal" action="inMysql.php" method="post">

                    <div class="form-group">
                        <label for="name" style="margin-left: -18px" class="col-sm-2 col-xs-4 control-label lab">留言标题</label>
                        <div class="col-sm-10 col-xs-8">
                            <input type="text" class="form-control"  name="title" style="height:40px;" placeholder="请输入标题">
                        </div>
                    </div>
                    <div class="form-group">
                        <label style="margin-left: -18px" class="col-sm-2  col-xs-4 control-label lab" >留言内容</label>
                        <div class="col-sm-10 col-xs-8">
                            <textarea class="form-control" style="height:85px;" name="content" placeholder="请输入内容"></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" style="margin-left: -18px" class="col-sm-2 col-xs-4 control-label lab">留言姓名</label>
                        <div class="col-sm-10 col-xs-8">
                            <input type="text" class="form-control"  name="name" style="height:40px;" placeholder="(选填：默认为匿名)">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" style="margin-left: -18px" class="col-sm-2 col-xs-4 control-label lab">留言电话</label>
                        <div class="col-sm-10 col-xs-8">
                            <input type="text" class="form-control"  name="tel" style="height:40px;" placeholder="(选填：默认为匿名)">
                        </div>
                    </div>
                    <div class="form-group">
                        <label style="margin-left: -18px" class="col-sm-2 col-xs-4 control-label lab" style="font-size: 16px;">上传图片:</label>
                        <div class="col-sm-10 col-xs-7 label">
                            <div id="pic">
                            </div>
                            <div class="weui-uploader__input-box" style="width:64px;height:64px" id="btn">
                            </div>
                        </div>
                    </div>
                    <input type="submit" style="font-size: 20px;" value="立即提交"  class="weui-btn weui-btn_primary" />

                </form>
            </div>
        </div>

    </div>
    <div class="main">
        <table class="table">
            <thead>
            <tr>
                <th style="width:20%">序号</th>
                <th style="width:30%">标题</th>
                <th style="width:30%">时间</th>
                <th style="width:20%">状态</th>
            </tr>
                <?php
                    require './Conn.php';
                    $obj = new Conn;
                    $mysqli =  $obj->Con();
                    $sql = "select * from message order by status asc ,id desc";
                    $result = $mysqli->query($sql);
                    $arr = [];
                    while ($row = $result->fetch_assoc()){    // fetch_assoc() 从结果集中取得一行作为关联数组
                       $arr[]=$row;
                    }
                    $mysqli->close();
                for ($m=0;$m<count($arr);$m++) {
                   $id = $arr[$m]['id'];
                echo '<tr>';
                echo "<th style='font-size:12px;font-weight:normal;width:20%'>
                            <a style='height: 80px;' href='./show.php?id=$id'>$id</a>
                      </th>";
                echo "<th style='font-size:12px;font-weight:normal;width:30%'>
                            <a style='height: 80px;' href='./show.php?id=$id'> 
                                <p class='pl'>".$arr[$m]['title']."</p>
                            </a>
                      </th>";
                echo "<th style='font-size:12px;font-weight:normal;width:30%'>
                             <a style='height: 80px;' href='./show.php?id=$id'> 
                                ".$arr[$m]['create_time']."
                              </a>
                       
                       </th>";
                if ($arr[$m]['status']==2){
                    echo '<th style="font-size:12px;color:green;font-weight:normal;width:20%">已回复</th>';
                }else{
                    echo '<th style="font-size:12px;color:red;font-weight:normal;width:20%">未回复</th>';
                }
                echo "</tr>";

            }

            ?>
            </thead>
        </table>
    </div>

</div>



</body>
</html>
<script>
    $(function(){
        $('li').click(function(){
            $(this).addClass('active').siblings().removeClass('active')
            load($('li').index(this));
        })
        load(0)
        function load(e){
            $('.main').hide()
            $('.main').eq(e).show()
        }
    });

    // 图片处理
    $("#btn").click(function () {
        wx.config({
            debug: false,
            appId: '<?php echo $signPackage["appId"];?>',
            timestamp: <?php echo $signPackage["timestamp"];?>,
            nonceStr: '<?php echo $signPackage["nonceStr"];?>',
            signature: '<?php echo $signPackage["signature"];?>',
            jsApiList: [
                // 所有要调用的 API 都要加到这个列表中
                'chooseImage',
                'previewImage',
                'uploadImage',
                'downloadImage'
            ]
        });

        wx.ready(function () {
            wx.checkJsApi({
                jsApiList: [
                    'chooseImage',
                    'previewImage',
                    'uploadImage',
                    'downloadImage'
                ],
                success: function (res) {
                    //alert(JSON.stringify(res));
                    //alert(JSON.stringify(res.checkResult.getLocation));
                    if (res.checkResult.getLocation == false) {
                        alert('你的微信版本太低，不支持微信JS接口，请升级到最新的微信版本！');
                        return;
                    }else{
                        wxChooseImage();
                    }
                }
            });
        });
        wx.error(function(res){
            // config信息验证失败会执行error函数，如签名过期导致验证失败，具体错误信息可以打开config的debug模式查看，也可以在返回的res参数中查看，对于SPA可以在这里更新签名。
            alert("验证失败，请重试！");
            wx.closeWindow();
        });

    });
    var images = {
        localId: [],
        serverId: []
    };
    //拍照或从手机相册中选图接口
    function wxChooseImage() {

        wx.chooseImage({
            success: function(res) {
                images.localId = res.localIds;
                alert('已选择 ' + res.localIds.length + ' 张图片');

                if (images.localId.length == 0) {
                    alert('请先使用 chooseImage 接口选择图片');
                    return;
                }
                var i = 0, length = images.localId.length;
                var pathUrl =[];
                images.serverId = [];
                function upload() {
                    //图片上传
                    wx.uploadImage({
                        localId: images.localId[i],
                        success: function(res) {
                            var serverId = res.serverId; // 返回图片的服务器端ID

                            if (images.localId[i]) {
                                $("#pic").append("<img  class='weui-uploader__input-box' style='width:64px;height:64px'  src='"+images.localId[i]+"'>");
                            }
                            $.ajax({
                                url: "./demodel.php",
                                type: "post",
                                async: false,
                                dataType: "html",
                                data: {
                                    serverId: serverId,
                                },
                                success: function (data) {
                                    var  mydata = JSON.parse(data);
                                    if(mydata.msg == '1'){
                                        pathUrl[i]=mydata.path;
                                        i++;
                                    }
                                },
                                error: function (XMLHttpRequest, textStatus, errorThrown) {
                                    alert(errorThrown);
                                },
                            });



                            if (i < length) {
                                upload();
                            }else {
                                alert('成功上传'+length+'张')
                                $.ajax({
                                    url: "./session.php",
                                    type: "post",
                                    async: false,
                                    dataType: "html",
                                    data: {
                                        pathUrl:pathUrl,
                                    },
                                    success: function (res) {
                                    }
                                });
                            }
                        },
                        fail: function(res) {
                            alert(JSON.stringify(res));
                        }
                    });
                }
                upload();
            }
        });
    }



    // $(function(){
    //     $('#tijiao').click(function(){
    //         var name = $('#name').val();
    //         $.post('inMysql.php', {'name' : name},
    //             function (data) {
    //                 // alert(data);
    //                 $('.weui-dialog__bd').text(data);
    //                 $('#tan').css('display','block');
    //                 $('.weui-dialog__btn').click(function(){
    //                     window.location.reload();
    //                 })
    //
    //             }
    //         )
    //     })
</script>
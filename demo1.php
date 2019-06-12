<?php
require './JSSDK.php';
//设置一下时区
date_default_timezone_set('Asia/Shanghai');
$jssdk = new JSSDK("wxd98e82c05e957857", "1c2cfd172651fb31bc532ee9a3f34b46");
$signPackage = $jssdk->GetSignPackage();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>留言中心</title>
</head>
<!-- head 中 -->
<link rel="stylesheet" href="https://cdn.bootcss.com/weui/1.1.3/style/weui.min.css">
<link rel="stylesheet" href="https://cdn.bootcss.com/jquery-weui/1.2.1/css/jquery-weui.min.css">
<body>

<div class="weui-tab">

    <div class="weui-navbar">
        <a style="height: 60px;font-size: 50px;" class="weui-navbar__item weui-bar__item--on" href="#tab1">
            我要留言
        </a>
        <a style="height: 60px;font-size: 50px;" class="weui-navbar__item" href="#tab2">
            我的留言
        </a>
    </div>
    <div style="height: 100px;"></div>
    <div class="weui-tab__bd">
        <div id="tab1" class="weui-tab__bd-item weui-tab__bd-item--active">
            <p style="font-size: 40px;">填写留言信息：</p>
            <form method="post" action="inMysql.php">
            <div style="font-size: 35px" class="weui-cells__title">留言标题</div>
            <div class="weui-cells">
                <div class="weui-cell">
                    <div class="weui-cell__bd">
                        <input style="height: 50px" name="title" class="weui-input" type="text" placeholder="请输入标题">
                    </div>
                </div>
            </div>
                <div class="weui-cells__title">文本框</div>
                <div class="weui-cells">
                    <div class="weui-cell">
                        <div class="weui-cell__bd">
                            <input class="weui-input" type="text" placeholder="请输入文本">
                        </div>
                    </div>
                </div>
            <div style="height: 40px;"></div>
            <div style="font-size: 35px" class="weui-cells__title">留言内容</div>
            <div class="weui-cells weui-cells_form">
                <div class="weui-cell">
                    <div class="weui-cell__bd">
                        <textarea name="content" style="height: 100px" class="weui-textarea" placeholder="请输入内容" rows="3"></textarea>
                        <div class="weui-textarea-counter"></div>
                    </div>

                </div>
            </div>
            <div style="height: 40px;"></div>
            <div class="weui-cells weui-cells_form">
                <div class="weui-cell">
                    <div class="weui-cell__bd">
                        <div class="weui-uploader">
                            <div class="weui-uploader__hd">
                                <p style="font-size: 35px" class="weui-uploader__title">图片上传</p>
                            </div>

                            <a style="margin-left: 150px" id="btn"><img src="/image.png"></a>
                            <div style="margin-top:20px;margin-left: 20px" id="pic"></div>
                        </div>
                    </div>
                </div>
            </div>


            <input type="submit" style="font-size: 50px" value="立即提交"  class="weui-btn weui-btn_primary" />
            </form>
        </div>
        <div id="tab2" class="weui-tab__bd-item">
            <p style="font-size: 40px;">全部留言</p>
            <div class="weui-panel weui-panel_access">
                <div class="weui-panel__bd">
                    <?php
                    require './Conn.php';
                    $obj = new Conn;
                    $mysqli =  $obj->Con();
                    $sql = "select * from message order by id desc";
                    $result = $mysqli->query($sql);
                    $arr = [];
                    while ($row = $result->fetch_assoc()){    // fetch_assoc() 从结果集中取得一行作为关联数组
                       $arr[]=$row;
                    }
                    $mysqli->close();
                    for ($m=0;$m<count($arr);$m++) {
                        $id = $arr[$m]['id'];
                    echo "<a style='height: 80px;' href='./show.php?id=$id' class='weui-media-box weui-media-box_appmsg'>";
                     echo '  <div class="weui-media-box__bd">
                            <p style="font-size: 30px;margin-left: 20px" class="weui-media-box__title">'.$arr[$m]['title'].'</p>
                            <p style="margin-left: 80%">'.$arr[$m]['create_time'].'</p>
                        </div>
                    </a>';

                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- body 最后 -->
<script src="https://cdn.bootcss.com/jquery/1.11.0/jquery.min.js"></script>
<script src="https://cdn.bootcss.com/jquery-weui/1.2.1/js/jquery-weui.min.js"></script>
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js"></script>
<script type="text/javascript">
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
                                $("#pic").append("<img style='margin-left: 20px' width='400px' height='400px' src='"+images.localId[i]+"'>");
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


</script>
</body>
</html>
<?php
require './Conn.php';

$id = $_GET['id'];
/*
* mysqli
* 数据库地址，登陆账号，密码，数据库名称
*/
$obj = new Conn;
$mysqli =  $obj->Con();
$sql = "select * from message where id=".$id;
$result = $mysqli->query($sql);
$rel = $result->fetch_assoc();
$mysqli->close();
/**
 * 获取回复内容
 */
$obj = new Conn;
$mysqli2 =  $obj->Con();
$sql2 = "select * from return1 where mes_id=".$id;
$result2 = $mysqli2->query($sql2);
$return = [];
while ($rel2 = $result2->fetch_assoc()){
    $return[] = $rel2;
}
$mysqli2->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>留言详情</title>
</head>
<style type="text/css">
    .photo-mask {
        position: fixed;
        z-index: 10;
        bottom: 0;
        top: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.8);
        filter: alpha(opacity=20);
        -moz-opacity: 0.8;
        opacity: 0.8;
        display: none;
    }

    .photo-panel {
        position: absolute;
        display: none;
        clear: both;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        z-index: 10;
    }

    .photo-panel .photo-div,
    .photo-panel .photo-bar {
        width: 100%;
    }

    .photo-panel .photo-div {
        width: 960px;
        height: 560px;
        z-index: 11;
        margin: auto;
        position: relative;
    }

    .photo-panel .photo-close {
        background: url(images/close.png);
        width: 56px;
        height: 56px;
        position: absolute;
        margin-left: 664px;
    }

    .photo-panel .photo-close:hover {
        background: url(images/close_ch.png);
        width: 56px;
        height: 56px;
        position: absolute;
        margin-left: 664px;
    }

    .photo-panel .photo-bar-tip {
        width: 700px;
        height: 44px;
        position: absolute;
        margin-top: -64px;
        padding: 10px;
    }

    .photo-panel .photo-bar-tip:hover {
        width: 700px;
        height: 44px;
        position: absolute;
        margin-top: -64px;
        background: #000;
        filter: alpha(opacity=20);
        -moz-opacity: 0.8;
        opacity: 0.8;
        color: #fff;
        padding: 10px;
    }

    .photo-panel .photo-img {
        width: 720px;
        float: left;
        height: 560px;
        background: #fff;
    }

    .photo-panel .photo-view-w {
        width: 720px;
        height: 560px;
        text-align: center;
        vertical-align: middle;
        display: table-cell;
    }

    .photo-panel .photo-view-h {
        width: 720px;
        height: 560px;
        text-align: center;
        vertical-align: middle;
    }

    .photo-panel .photo-view-w img {
        max-width: 700px;
        height: auto;
        vertical-align: middle;
        text-align: center;
        max-height: 540px;
        margin: 10px;
        -moz-box-shadow: 5px 5px 5px #a6a6a6;
        /* 老的 Firefox */
        box-shadow: 5px 5px 5px #a6a6a6;
        -webkit-animation: swing 1s .2s ease both;
        -moz-animation: swing 1s .2s ease both;
    }

    .photo-panel .photo-view-h img {
        max-width: 700px;
        height: 540px;
        margin: 10px;
        -moz-box-shadow: 5px 5px 5px #a6a6a6;
        /* 老的 Firefox */
        box-shadow: 5px 5px 5px #a6a6a6;
        -webkit-animation: swing 1s .2s ease both;
        -moz-animation: swing 1s .2s ease both;
    }

    .photo-panel .photo-left,
    .photo-panel .photo-right {
        width: 120px;
        float: left;
        margin-top: 220px;
    }

    .photo-panel .arrow-prv {
        background: url(images/l.png);
        width: 120px;
        height: 120px;
    }

    .photo-panel .arrow-prv:hover {
        background: url(images/l_ch.png);
        width: 120px;
        height: 120px;
        cursor: pointer;
    }

    .photo-panel .arrow-next {
        background: url(images/r.png);
        width: 120px;
        height: 120px;
    }

    .photo-panel .arrow-next:hover {
        background: url(images/r_ch.png);
        width: 120px;
        height: 120px;
        cursor: pointer;
    }

    .demo {
        width: 800px;
        margin: 10px auto;
    }

    .demo li {
        overflow: hidden;
        width:200px;
        height:200px;
        float: left;
    }

    .demo li img {
        /*max-width:100%;*/
        /*max-height:100%;*/
        width: auto;
        height: auto;
    }
</style>
<!-- head 中 -->
<link rel="stylesheet" href="https://cdn.bootcss.com/weui/1.1.3/style/weui.min.css">
<link rel="stylesheet" href="https://cdn.bootcss.com/jquery-weui/1.2.1/css/jquery-weui.min.css">
<body>

<div class="weui-form-preview">
    <div class="weui-form-preview__bd">
        <div class="weui-form-preview__item">
            <label style="font-size: 40px;" class="weui-form-preview__label">标题:</label>
            <span style="font-size: 40px;text-align: left" class="weui-form-preview__value"><?php echo $rel['title'] ?></span>
        </div>
        <div class="weui-form-preview__item">
            <label style="font-size: 40px;" class="weui-form-preview__label">内容:</label>
            <span style="font-size: 40px;text-align: left" class="weui-form-preview__value"><?php echo $rel['content'] ?></span>
        </div>
        <div class="weui-form-preview__item">
            <label style="font-size: 40px;" class="weui-form-preview__label">图片:</label>
            <script src="/demos/googlegg.js"></script>
            <div class="demo">
                <div style="margin-left: 20%;width: 80%">
                    <ul>
                    <?php
                    $array = explode(',',$rel['imageurl']);
                    for ($m=0;$m<count($array);$m++){
//                        echo  '<img  style="margin-right: 70%" width="200px" height="200px" src= ''>';
                        echo "<li style='margin-left: 5px;margin-top: 5px'><img  src=".$array[$m]."></li>";
                    }
                    ?>
                    </ul>
                </div>



            </div>
            <div class="photo-mask"></div>
            <div class="photo-panel">
                <div class="photo-div">
                    <div class="photo-left">
                        <div class="arrow-prv"></div>
                    </div>
                    <div class="photo-img">
                        <div class="photo-bar">
                            <div class="photo-close"></div>
                        </div>
                        <div class="photo-view-h">
                            <img src="http://b.zol-img.com.cn/sjbizhi/images/9/800x1280/1471524533521.jpg" />
                        </div>
                    </div>
                    <div class="photo-right">
                        <div class="arrow-next"></div>
                    </div>
                </div>
            </div>

            <script src="js/jquery.min.js"></script>
            <script>
                var img_index = 0;
                var img_src = "";

                $(function() {
                    //计算居中位置
                    var mg_top = ((parseInt($(window).height()) - parseInt($(".photo-div").height())) / 2);

                    $(".photo-div").css({
                        "margin-top": "" + mg_top + "px"
                    });
                    //关闭
                    $(".photo-close").click(function() {
                        $(".photo-mask").hide();
                        $(".photo-panel").hide();
                    });
                    //下一张
                    $(".photo-panel .photo-div .arrow-next").click(function() {
                        img_index++;
                        if(img_index >= $(".demo li img").length) {
                            img_index = 0;
                        }
                        img_src = $(".demo li img").eq(img_index).attr("src");
                        photoView($(".demo li img"));
                    });
                    //上一张
                    $(".photo-panel .photo-div .arrow-prv").click(function() {
                        img_index--;
                        if(img_index < 0) {
                            img_index = $(".demo li img").length - 1;
                        }
                        img_src = $(".demo li img").eq(img_index).attr("src");
                        photoView($(".demo li img"));
                    });
                    //如何调用？
                    $(".demo li img").click(function() {
                        $(".photo-mask").show();
                        $(".photo-panel").show();
                        img_src = $(this).attr("src");
                        img_index = $(this).index();
                        photoView($(this));
                    });

                });
                //自适应预览
                function photoView(obj) {
                    if($(obj).width() >= $(obj).height()) {
                        $(".photo-panel .photo-div .photo-img .photo-view-h").attr("class", "photo-view-w");
                        $(".photo-panel .photo-div .photo-img .photo-view-w img").attr("src", img_src);
                    } else {
                        $(".photo-panel .photo-div .photo-img .photo-view-w").attr("class", "photo-view-h");
                        $(".photo-panel .photo-div .photo-img .photo-view-h img").attr("src", img_src);
                    }
                }
            </script>
        </div>

        </div>
        <hr>
        <div class="weui-form-preview__item">
            <label style="font-size: 40px;" class="weui-form-preview__label">所有回复:</label>
        </div>
        <div class="weui-form-preview__item">
            <?php
            if ($return){
                for ($n=0;$n<count($return);$n++){
                    echo '
                     <span style="font-size: 40px;text-align:left;margin-left: 20%" class="weui-form-preview__value">'. $return[$n]['content'] .'</span>
                    <p style="margin-left: 60%;font-size: 30px">'.$return[$n]['re_time'].'</p>
                    <hr>
                    ';
                }
            }else{
                echo ' <p style="margin-left: 20%;font-size: 30px">暂无回复</p>';
            }
            ?>

        </div>

    </div>

</div>
<!-- body 最后 -->
<script src="https://cdn.bootcss.com/jquery/1.11.0/jquery.min.js"></script>
<script src="https://cdn.bootcss.com/jquery-weui/1.2.1/js/jquery-weui.min.js"></script>
<script>


</script>
</body>
</html>

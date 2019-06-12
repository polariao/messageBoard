<?php
require './Conn.php';

$code = $_GET['code'];//获取code
$weixin =  file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx4e51c7d2349c2a9b&secret=7bb74128d3fd4bf0dcefe2e3622b408b&code=".$code."&grant_type=authorization_code");//通过code换取网页授权access_token
$jsondecode = json_decode($weixin); //对JSON格式的字符串进行编码
$array = get_object_vars($jsondecode);//转换成数组
$openid = $array['openid'];//输出openid
//根据openid获取用户身份
/*
* mysqli
* 数据库地址，登陆账号，密码，数据库名称
*/
$obj = new Conn;
$mysqli =  $obj->Con();
$sql = "select * from openid where openid='$openid'";
$result = $mysqli->query($sql);
$rel = $result->fetch_assoc();
$mysqli->close();
/**
 * 如果openid表中不存在此用户openid，将其写入数据库
 */
if (!$rel){
    $obj = new Conn;
    $mysqli =  $obj->Con();
    $sql = "INSERT INTO openid(
                                 openid,
                                 status
                      )VALUES (
                                 '{$openid}',
                                 '0'
                                
                      )";
    $result = $mysqli->query($sql);
    $mysqli->close();
}

if ($rel&&$rel['status']==1){
    ?>
    <script>
        var openid = '<?php echo $openid ?>';
        window.location.href='/title.php?openid='+openid
    </script>";
    <?php
}else{
    ?>
    <script>
        var openid = '<?php echo $openid ?>';
        window.location.href='/demo.php?openid='+openid
    </script>";
    <?php
}
<?php
/*
* mysqli
* 数据库地址，登陆账号，密码，数据库名称
*/
require './Conn.php';
date_default_timezone_set('Asia/Shanghai');
session_start();
$time = date('Y-m-d H:i:s',time());
if ($_POST['title']&&$_POST['content']){
    $title = $_POST['title'];
    $content = $_POST['content'];
    $name = $_POST['name'];
    $tel = $_POST['tel'];
    if (empty($name)){
        $name='匿名';
    }

    //判断session是否存在/过期
    if ($_SESSION['pathUrl']&&$_SESSION['expire_time']>time()){
        $imageurl = implode(",", $_SESSION['pathUrl']);
        $obj = new Conn;
        $mysqli =  $obj->Con();
        $sql = "INSERT INTO message(
                         title,
                          content,
                          imageurl,
                          create_time,
                          name
              )VALUES (
                         '{$title}',
                         '{$content}',
                         '{$imageurl}',
                         '{$time}',
                         '{$name}'
              )";
        $result = $mysqli->query($sql);
        if ($result){
            echo "<script>
                            alert('留言成功，感谢你提的宝贵意见');
                            window.location.href='/demo.php';
                       </script>";
        }
        /* close connection */
        $mysqli->close();
    }else {
        $obj = new Conn;
        $mysqli =  $obj->Con();
        $sql = "INSERT INTO message(
                           title,
                           content,
                           create_time,
                           name

               )VALUES (
                          '{$title}',
                          '{$content}',
                          '{$time}',
                          '{$name}'


              )";
        $result = $mysqli->query($sql);
        if ($result){
            echo "<script>
                            alert('留言成功，感谢你提的宝贵意见');
                            window.location.href='/demo.php';
                       </script>";
        }
        /* close connection */
        $mysqli->close();
    }
}else{
    echo "<script>
                            alert('数据不完整');
                            window.location.href='/demo.php';
                       </script>";
}




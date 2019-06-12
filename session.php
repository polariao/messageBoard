<?php
/*
* mysqli
* 数据库地址，登陆账号，密码，数据库名称
*/
date_default_timezone_set('Asia/Shanghai');
session_start();
$_SESSION['pathUrl'] = $_POST['pathUrl'];
$_SESSION['expire_time'] = time()+60;
$success = array('session_path' =>$_SESSION['pathUrl'] , 'msg' => '1');
echo json_encode($success);

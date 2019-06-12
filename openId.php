<?php
$appId='wxd98e82c05e957857';
$redirect_uri='http://a.huangjinao.club/getOpenId.php';
header("Location:https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appId&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect");
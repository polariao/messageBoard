<?php
require './JSSDK.php';
//设置一下时区
date_default_timezone_set('Asia/Shanghai');
$jssdk = new JSSDK("wx4e51c7d2349c2a9b", "7bb74128d3fd4bf0dcefe2e3622b408b");
$access_token = $jssdk->getAccessToken();
/**
 * 下载保存微信图片素材
 * @param  [string] $serverid 微信服务器上的素材ID
 * @return [string] 返回保存本地之后的图片路径
 */
/*  获取临时的文件  */

$id =$_POST['serverId'];
$url = "https://api.weixin.qq.com/cgi-bin/media/get?access_token=".$access_token."&media_id=".$id;
$arr = downloadWeixinFile($url);
$filename =$id.'.jpg';			//保存的文件名
$dateStr = date('Ymd').'/'.mt_rand(1000,9999);
$file_dir =  './demoUpload/'.$dateStr.'/'; //保存文件的目录
if (!is_dir($file_dir)){       			//创建保存文件的目录
    mkdir(iconv("GBK","UTF-8", $file_dir),0777,true);
}
$path = $file_dir.$filename;			//文件路径
if(file_exists($path)){
    unlink($path); 						//如果文件已经存在则删除已有的
}
saveWeixinFile($path,$arr['body']);
$raw_success = array('path' =>$path, 'msg' => '1');
echo json_encode($raw_success);


function downloadWeixinFile($url)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_NOBODY, 0);    //只取body头
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $package = curl_exec($ch);
    $httpinfo = curl_getinfo($ch);
    curl_close($ch);
    $imageAll = array_merge(array('header' => $httpinfo), array('body' => $package));
    return $imageAll;
}


function saveWeixinFile($filename, $filecontent)
{
    $local_file = fopen($filename, 'w');
    if (false !== $local_file){
        if (false !== fwrite($local_file, $filecontent)) {
            fclose($local_file);
        }
    }
}
<?php
session_start();
class Index{
    public function index1()
    {
        if (isset($_GET['echostr'])) {
    //1.将timestamp,nonce,toke按字典顺序排序
            $timestamp = $_GET['timestamp'];
            $nonce = $_GET['nonce'];
            $token = 'imooc';
            $signature = $_GET['signature'];
            $array = array($timestamp, $nonce, $token);
            sort($array);
    //2.将排序后的三个参数拼接之后用sha1加密
    //3.将加密后的字符串与signature进行对比，判断该请求是否来自微信
            if (sha1(implode('', $array)) == $signature && $_GET['echostr']) {
    //第一次接入微信api接口的时候
                header('content-type:text');
                echo $_GET['echostr'];
                exit;
            }
        } else {
            $this->responseMsg();
        }

    }
    public function responseMsg(){
        //1、获取到微信推送过来的post数据（xml格式）
        $postArr = file_get_contents("php://input");
        //2、处理消息内容，并设置恢复类型和内容
        $postObj = simplexml_load_string($postArr,'SimpleXMLElement', LIBXML_NOCDATA);
        //判断该数据包是否是订阅的事件推送
        if (strtolower( $postObj->MsgType ) == 'event'){
            //如果是关注事件
            if ( strtolower($postObj->Event )  == 'subscribe'){
                    $content = '感谢你的关注666';
                $this->responseText($postObj,$content);
            }
        }
        /**
         * 消息为文本
         * */
        if(strtolower( $postObj->MsgType ) == 'text'){
           if ($postObj->Content == '留言测试'){
                $arr = array([
                    'title'=>'测试',
                    'description'=>'进入测试',
                    'picUrl'=>'http://f.hiphotos.baidu.com/lvpics/w=600/sign=b7c305c7aad3fd1f3609a13a004f25ce/dcc451da81cb39db83b5f1bfd2160924aa1830e5.jpg',
                    'url'=>'http://a.huangjinao.club/demo.php'
                ]);
                //回复用户消息
                $this->response($postObj,$arr);
            }elseif ($postObj->Content == '回复测试'){
                $arr = array([
                    'title'=>'回复测试',
                    'description'=>'进入回复测试',
                    'picUrl'=>'http://f.hiphotos.baidu.com/lvpics/w=600/sign=b7c305c7aad3fd1f3609a13a004f25ce/dcc451da81cb39db83b5f1bfd2160924aa1830e5.jpg',
                    'url'=>'http://a.huangjinao.club/title.php'
                ]);
                //回复用户消息
                $this->response($postObj,$arr);
            }elseif ($postObj->Content == 'open'){
               $arr = array([
                   'title'=>'OpenId测试',
                   'description'=>'进入OpenId测试',
                   'picUrl'=>'http://f.hiphotos.baidu.com/lvpics/w=600/sign=b7c305c7aad3fd1f3609a13a004f25ce/dcc451da81cb39db83b5f1bfd2160924aa1830e5.jpg',
                   'url'=>'http://a.huangjinao.club/openId.php'
               ]);
               //回复用户消息
               $this->response($postObj,$arr);
           }


        }

    }
        /**
         * 回复文本消息
         */
        public function responseText($postObj,$content){
            $toUser = $postObj->FromUserName;
            $fromUser = $postObj->ToUserName;
            $time = time();
            $msgType = 'text';

            $template = "<xml>
                          <ToUserName><![CDATA[%s]]></ToUserName>
                          <FromUserName><![CDATA[%s]]></FromUserName>
                          <CreateTime>%s</CreateTime>
                          <MsgType><![CDATA[%s]]></MsgType>
                          <Content><![CDATA[%s]]></Content>
                        </xml>";
            $info = sprintf($template,$toUser,$fromUser,$time,$msgType,$content);
            echo $info;
        }


    public function response($postObj,$arr){

        $toUser = $postObj->FromUserName;
        $fromUser = $postObj->ToUserName;

        $template = " <xml>
                          <ToUserName><![CDATA[%s]]></ToUserName>
                          <FromUserName><![CDATA[%s]]></FromUserName>
                          <CreateTime>%s</CreateTime>
                          <MsgType><![CDATA[%s]]></MsgType>
                          <ArticleCount>".count($arr)."</ArticleCount>
                          <Articles>";

        foreach ($arr as $k=>$v){
            $template .= "<item>
                              <Title><![CDATA[".$v['title']."]]></Title>
                              <Description><![CDATA[".$v['description']."]]></Description>
                              <PicUrl><![CDATA[".$v['picUrl']."]]></PicUrl>
                              <Url><![CDATA[".$v['url']."]]></Url>
                              </item>";
        }

        $template .= "  </Articles>
                            </xml>";
        echo sprintf($template, $toUser,$fromUser,time(),'news');

    }




    /**
     * @param $url  接口 url
     * @param string $type 请求类型
     * @param string $res  返回数据类型
     * @param string $arr  post请求参数
     * @return mixed
     */
    public function http_curl($url='',$type='get',$res='json',$arr=''){
        //1、初始化
        $ch = curl_init();
        //2、设置参数
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在
        if ($type=='post') {
            curl_setopt($ch,CURLOPT_POST,1);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$arr);

        }
        //3、采集
        $re = curl_exec($ch);
        if ($res=='json') {
            if(curl_errno($ch)){
                //请求失败
                return curl_error($ch);
            }else{
                return json_decode($re, true);
            }
        }
        //4、关闭
        curl_close($ch);


    }
    //返回access_token
    public function getWxAccessToken(){
        if ($_SESSION['access_token'] && $_SESSION['expire_time']>time()){
            return $_SESSION['access_token'];
        }else{
//            //session不存在或者已经过期
            $appId='wx4e51c7d2349c2a9b';
            $appSecret='7bb74128d3fd4bf0dcefe2e3622b408b';
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appId.'&secret='.$appSecret;
            $res = $this->http_curl($url,'get','json');
            $accessToken = $res['access_token'];
            //将重新获取到的access_token存到session中
            $_SESSION['access_token'] = $accessToken;
            $_SESSION['expire_time']=time()+7000;
            return $accessToken;

        }

    }


}
$new = new Index();
$new->index1();

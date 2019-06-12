<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0; minimum-scale=1.0; maximum-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" type="text/css" href="static/css.css">
    <title>软件学院留言列表页</title>
    <style>
        .pl{
            width: 260px;
            overflow:hidden;
            text-overflow:ellipsis;
            white-space: nowrap;/*加宽度width属来兼容部分浏览*/
        }
    </style>
</head>
<body class="wrapper">
    <div class="top">
        <a class="topA" >
            <img alt="" height="30" src="static/images/liebiao.png" width="30" />
        </a>
        <p><span>南工软件留言汇总</span></p>
    </div>
    
    <div class="mkImgList_1" style="min-height: 400px">
        <ul>
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
               echo "<li >";
               if ($arr[$m]['status']==0){
                   echo "<div class='fl mr10' >
                            <img alt = '' width = '30' height = '30' src = 'static/images/message1.png' />
                        </div >";

                   echo "<h2  style='height: 20px;overflow:hidden;font-weight: bolder'>

                            <a href='./adminShow.php?id=$id' ><p class='pl'>
                                ".$arr[$m]['title']."
                          &nbsp;&nbsp;
                       </p> </h2 >";
               }elseif ($arr[$m]['status']==1){
                   echo "<div class='fl mr10' >
                            <img alt = '' width = '30' height = '30' src = 'static/images/message3.png' />
                        </div >";
                   echo "<h2 style='height: 20px;overflow:hidden;font-weight: bolder' >
                            <a href='./adminShow.php?id=$id' ><p class='pl'>
                                ".$arr[$m]['title']."
                         </p> &nbsp;
                        </h2 >";
               }else{
                   echo "<div class='fl mr10' >
                            <img alt = ''  width = '30' height = '30' src = 'static/images/message2.png' />
                        </div >";
                   echo "<h2 style='height: 20px;overflow:hidden;text-overflow: ellipsis;white-space: nowrap;-webkit-line-clamp:2;font-weight: bolder' >
                            <a href='./adminShow.php?id=$id' ><p class='pl'>
                                ".$arr[$m]['title']."</p>
                           &nbsp;&nbsp;
                        </h2 >";
               }
              echo  "<p style='margin-top: 5px;color: #8D8D8D; text-overflow: -o-ellipsis-lastline;overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-line-clamp: 2;
		-webkit-box-orient: vertical;margin-left: 40px;max-height: 60px;min-height:20px'>".$arr[$m]['content']."</p >";
               echo " <p style='margin-left: 60%'>".$arr[$m]['create_time']."</p>";
            echo "</a> 
                    </li >";
            }
                ?>
        </ul>
    </div>

    <div class="h10"></div>
<div class="foor">
    <p class="fs12">南阳理工学院软件学院</p>
</div>
</body>
</html>

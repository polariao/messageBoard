<?php
require './Conn.php';

date_default_timezone_set('Asia/Shanghai');
if (!empty($_POST['content'])) {
    $content = $_POST['content'];
    $id = $_POST['id'];
    $obj = new Conn;
    $mysqli =  $obj->Con();
    $re_time = date('Y-m-d H:i:s',time());
    $sql = "INSERT INTO return1(
                          mes_id,
                          content,
                          re_time
              )VALUES (
                         '{$id}',  
                         '{$content}',
                         '{$re_time}'
              )";
    $result = $mysqli->query($sql);
    if ($result) {
        /**
         * 更改状态
         */
        $obj = new Conn;
        $mysqli =  $obj->Con();
        $sql = "update message set status=2 where id=".$id;
        $res = $mysqli->query($sql);
        if ($res){
            echo "<script>
                            alert('回复成功');
                             window.location.href='/title.php'; 
                       </script>";
        }

        $mysqli->close();

    }
}else{
    echo "<script>
                            alert('请填写回复内容');
                            window.history.back(-1);
                       </script>";
}
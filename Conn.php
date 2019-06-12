<?php
class Conn{
    public function Con(){
        $mysqli = new mysqli("localhost", "root", "100521", "message");
        $mysqli->set_charset("utf8");
        return $mysqli;
    }
}


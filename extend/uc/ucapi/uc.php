<?php
namespace uc\ucapi;

class uc 
{
public function __construct(){
    //BASE_PATＨ在入品的配置文件中建立一个常量，并赋值
	define('BASE_PATH',dirname(__FILE__));
    require_once BASE_PATH . '/config.inc.php';
    require_once BASE_PATH . '/uc_client/client.php';
    } 
}

?>
<?php  

/**
 *
 * 连接数据库
 * 
 */


// 获得配置信息
$configs = include '../../config/config.php';


@mysql_connect($configs['host'], $configs['username'], $configs['password']);
mysql_select_db($configs['database']);
mysql_query("set names ".$configs['charset']);


?>
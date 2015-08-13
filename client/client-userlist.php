<?php 
header("Content-Type: text/html; charset=utf-8");


//
// 调用接口
// 进行业务处理
//


$action = "userlist";
$returntype = "json";
// 从用户列表中查询满足条件:"username=wangfan" 的用户
$condition_type = "username";  // 查询条件类型
$condition_val = "wangfan";    // 查询条件值


// 接口地址
$url = "http://hostname/controller.php";  
// 请求参数
// $post_data = "action=".$action."&returntype=".$returntype;
$post_data = "action=".$action."&returntype=".$returntype."&condition_type=".$condition_type."&condition_val=".$condition_val;

// curl实现对接口的调用
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_NOBODY, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
$res = curl_exec($curl);
curl_close($curl);

// echo "<pre>";
var_dump($res);
// echo "</pre>";
exit();






// 解析响应数据(json => array)
$res = json_decode($res);
$res = object_array($res);

if (is_array($res)) {
    // 获取响应结果
} else {
    $res = "出现错误，可能是接口地址错误或网络中断等原因";
}

echo "结果是: " . $res;


// PHP要用json格式的数据，通过json_decode()转出来的数组并不是标准的array，所以需要用下面的函数进行转换
function object_array($array){
  	if(is_object($array)){
    	$array = (array)$array;
  	}
  	if(is_array($array)){
    	foreach($array as $key=>$value){
      		$array[$key] = object_array($value);
    	}
  	}
  	return $array;
} 

?>
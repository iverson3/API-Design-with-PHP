<?php  
header("Content-Type:text/html; charset=utf-8");


// ------------------------------------
// API接口的入口 控制器
// ------------------------------------


// action参数值 就是对应的model类文件的文件名

$action = isset($_GET['action'])? $_GET['action'] : "null";
if ($action == "null") {
	$action = isset($_POST['action'])? $_POST['action'] : "null";
}

if ($action == "null") {
	// 非法的请求(没有系统参数)
	echo "错误的请求";
} else {
	$action = ucfirst($action);
	$file = "./model/".$action.".model.php"; 
	if (file_exists($file)) {
		require_once $file;
		$model = new $action();
		// 调用接口的处理函数，进行业务数据处理
		$model->work();
		// 返回处理结果给客户端
		$model->response();
	} else {
		// 非法的系统参数值(action参数值非法)
		echo "非法的系统参数";
	}
}


?>
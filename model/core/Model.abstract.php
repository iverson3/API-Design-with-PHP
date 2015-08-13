<?php  


//
// Model模型抽象父类
//


abstract class Model {

	// 系统提供的可供选择的两种数据格式
	private $types = array('json', 'xml');

	// 响应数据格式
	private $returntype = "";

	// 状态码
	private $code = 0;

	// 提示信息
	private $message = "";

	// 响应数据(适用于查询请求)
	private $data = array();


	// 构造函数，初始化操作，确定响应数据格式
	public function __construct(){
		// 默认响应数据为json格式 (系统参数之一 响应数据格式:returntype)
		$returntype = isset($_GET['returntype'])? $_GET['returntype'] : "null";
		if ($returntype == "null") {
			$returntype = isset($_POST['returntype'])? $_POST['returntype'] : $this->types[0];
		}
		$this->returntype = $returntype;
		
		// 系统参数之returntype 非法
		if (!in_array($this->returntype, $this->types)) {
			$this->setRes(300, "returntype非法的参数值");

			// 直接进行错误响应(使用json格式)
			$res = $this->json();
			// 将错误信息响应给客户端
			echo $res;
			exit();
		}
		// 连接数据库
		include_once("./conn.php");
	}

	/**
	 * 主要的业务和数据处理函数
	 */
	public abstract function work();

	/**
	 * 响应函数
	 */
	public function response(){
		if ($this->returntype == "json") {
			$res = $this->json();
		} else {
			$res = $this->xml();
		}
		// 将封装好的数据响应给客户端
		echo $res;
		exit();
	}

	/*
	*  将响应的数据 封装成json格式的数据(按json格式输出响应数据)
	*   
	*  return string (封装之后 json格式的数据)
	*/
	private function json(){
		$result = array(
			'code'    => $this->code,
			'message' => $this->message,
			'data'    => $this->data
		);
		return json_encode($result);
	}

	/*
	*  将响应的数据 封装成xml格式的数据(按xml格式输出响应数据)
	*   
	*  return string (封装之后 xml格式的数据)
	*/
	private function xml(){
		$result = array(
			'code'    => $this->code,
			'message' => $this->message,
			'data'    => $this->data
		);
		// 拼接xml字串
		$res  = "<?xml version='1.0' encoding='UTF-8'?>\n";
		$res .= "<root>\n";
		$res .= $this->xmlToEncode($result);
		$res .= "</root>";
		
		return $res;
	}

	/*
	*  将数组类型的数据 封装成标准xml格式的数据
	*  通过"递归"实现xml数据的封装
	*
	*  @param  $data   数组格式的数据
	*  return  string (封装之后得到的xml格式数据)
	*/
	private function xmlToEncode($data){
		$xml  = "";
		$attr = "";
		foreach ($data as $key => $value) {
			if(is_numeric($key)){
				$attr = " id='{$key}'";
				$key  = "item";
			}
			$xml .= "<{$key}{$attr}>\n";
			$xml .= is_array($value)? $this->xmlToEncode($value): $value;
			$xml .= "</{$key}>\n";
		}
		return $xml;
	}

	/**
	 * 设置响应数据的值
	 * 
	 * @param integer $code    状态码
	 * @param string  $message 提示信息
	 * @param array   $data    数据
	 */
	public function setRes($code = 0, $message = "", $data = array()){
		$this->code    = $code;
		$this->message = $message;
		$this->data    = $data;
	}

	/**
	 * 获取提交的参数 (get和post两种方式都尝试)
	 * 
	 * @param  string $key 参数的键
	 * @return string      参数的值
	 */
	public function getParam($key){
		$value = isset($_GET[$key])? $_GET[$key] : "0";
		if ($value == "0") {
			$value = isset($_POST[$key])? $_POST[$key] : "0";
		}
		return $value;
	}

}


?>
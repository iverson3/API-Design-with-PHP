<?php  

/**
 * 用户登录验证
 */

require_once "./core/Model.abstract.php";

class Login extends Model {

	private $username = "";
	private $password = "";

	public function __construct(){
		parent::__construct();
		// 获取参数值
		$this->username = $this->getParam('username');
		$this->password = $this->getParam('password');
		
		if ($this->username == "0" || $this->password == "0") {
			$this->setRes(350, "参数不足");
			$this->response();
		}
	}

	/**
	 * 主要的业务和数据处理函数
	 */
	public function work(){
		$sql = "select password from wf_user where username = '".$this->username."'";
		$res = mysql_query($sql);
		if ($res) {
			if (mysql_num_rows($res) > 0) {
				$row = mysql_fetch_array($res);
				if ($row['password'] == md5($this->password)) {
					// 验证通过
					$this->setRes(200, "登录验证通过");
				} else {
					// 密码错误
					$this->setRes(360, "密码错误");
				}
			} else {
				// 查询结果集为空，即用户名不存在
				$this->setRes(340, "用户名不存在");
			}
		} else {
			// 查询失败
			$this->setRes(400, "查询失败");
		}
	}

}


?>
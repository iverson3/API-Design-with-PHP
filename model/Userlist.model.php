<?php  

/**
 * 获取用户列表
 */

require_once "./core/Model.abstract.php";

class Userlist extends Model {

	// 条件类型 (作为获取用户列表时的条件[数据表中的某个字段])
	private $condition_type = "";
	// 条件值
	private $condition_val = "";

	// 对应的数据表中的可作为查询字段的所有字段
	private $types = array('id', 'username', 'role');
	private $int_types = array('id', 'role');  // int类型的字段

	public function __construct(){
		parent::__construct();
		// 获取参数值
		$this->condition_type = $this->getParam('condition_type');
		$this->condition_val  = $this->getParam('condition_val');

		// 判断condition_type参数值是否在预定义的字段集types中
		if ($this->condition_type !== "0" && !in_array($this->condition_type, $this->types)) {
			$this->setRes(360, "参数值超出预定范围");
			$this->response();
		}
	}

	/**
	 * 主要的业务和数据处理函数
	 */
	public function work(){
		if ($this->condition_type === "0") {
			// 为空则进行无条件查询(所有用户)
			$sql = "select id,username,role from wf_user";
		} else {
			// 判断存在"条件类型"的同时是否同时存在"条件值"
			if ($this->condition_val === "0") {
				$this->setRes(350, "参数不足");
				$this->response();
			} else {
				// 按照指定的条件进行查询   (*** 这里需要对condition_type在数据表中的字段类型进行判断 决定对condition_val是否加单引号 ***)
				if (in_array($this->condition_type, $this->int_types)) {
					$sql = "select id,username,role from wf_user where ".$this->condition_type." = ".$this->condition_val."";
				} else {
					$sql = "select id,username,role from wf_user where ".$this->condition_type." = '".$this->condition_val."'";
				}
			}
		}
		$res = mysql_query($sql);
		if ($res) {
			if (mysql_num_rows($res) > 0) {
				$arr = array();
				// 将结果集封装成二维数组
				while ($row = mysql_fetch_assoc($res)) {
					$arr[] = $row;
				}
				$this->setRes(200, "成功获取", $arr);
			} else {
				// 查询结果集为空
				$this->setRes(340, "用户列表为空");
			}
		} else {
			// 查询失败
			$this->setRes(400, "查询失败");
		}
	}

}


?>
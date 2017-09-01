<?php
namespace Home\Model;
use Think\Model;

class AnalysisModel{

	/*
	*	判断用户是否登录
	* 	参  数：
	* 	返回值：登录 -> true   未登录 -> false
	 */
	public function isLogin(){
		if($_SESSION['user_name']){
			return true;
		}else{
			return false;
		}
	}


	/*
	*	获取用户的最新检测数据
	* 	参  数：
	*  	返回值：返回最近检测的upload_info_id    也是scan_summary  upinfo_id
	 */
	public function new_detection(){
		$uid = $_SESSION['user_id'];

		$Model = M('upload_info');
		$u_info_id = $Model->where('user_id='.$uid)->field('id')->select();

		$Model2 = M('scan_summary');
		foreach($u_info_id as $k => $v){
			$info_id = $Model2->where('upinfo_id='.$v['id'].' and scan_status=1')->field('upinfo_id')->select();
			if(!empty($info_id)){
				$info[] = $info_id[0]['upinfo_id'];
			}
			// echo $Model2->_sql();
		}

		$pos = array_search(max($info), $info);
		return $info[$pos];
	}

	/*
	*	根据upload_info id 获取scan_summary  id
	* 	参  数：upload_info id -> upinfo_id
	*  	返回值：返回scan_summary  id
	 */
	public function getSummaryId($upinfo_id){
		$Model = M('scan_summary');

		$summary_id = $Model->where('scan_status=1 and upinfo_id='.$upinfo_id)->field('id')->select();
		// echo $Model->_sql();
		return $summary_id[0]['id'];
	}

	/*
	*	查询代码分析总览视图--文件分类视图数据 
	*	参  数：$id -> summary_id   
	* 	返回值：返回漏洞数组 数组下标是文件类型，值是漏洞个数   [php] => 18
	 */
	public function file_class($id){
		$Model = M('scan_data');
		
		$res_scan_data = $Model->where("summary_id = '%s' and file_type_name != ''",$id)->field("file_type_name,count('file_type_name') as file_type_num")->group('file_type_name')->select();

		if($res_scan_data){
			$arr = array();
			foreach($res_scan_data as $key => $value){
				$arr[$value['file_type_name']] = $value['file_type_num'];
			}  
		}

		return $arr;
	}

	/*
	*	总览视图、分览视图  -- 查询程度分类视图数据 
	* 	参  数：$id -> summary_id      $code  -> file_type_name 
	*  	返回值：返回漏洞数组 数组下标是漏洞等级，值是漏洞个数  [高] => 21
	 */
	public function degree_class($id,$code){
		$Model = M('scan_data');

		if($code){
			$res_scan_data = $Model->where("summary_id={$id} and file_type_name='{$code}'")->field("leak_grade,count('leak_grade') as leak_grade_num")->group('leak_grade')->select();	
			//echo $Model->_sql();
		}else{
			$res_scan_data = $Model->where('summary_id='.$id)->field("leak_grade,count('leak_grade') as leak_grade_num")->group('leak_grade')->select();
			//echo $Model->_sql();
		}

		if($res_scan_data){
			$arr = array();
			foreach($res_scan_data as $key => $value){
				$arr[$value['leak_grade']] = $value['leak_grade_num'];
			}  
		}

		return $arr;
	}


	/*
	*	总览视图 、分览视图 -- 风险分类视图数据   
	* 	参  数：$id->summary_id    $code->file_type_name  $level->leak_grade
	*  	返回值：返回风险数组 数组下标是漏洞名称，值是漏洞个数  [SQL注入] => 23
	 */
	public function risk_class($id,$code,$level){
		$Model = M('scan_data');
// $code = 'h'; $level = 3;
		if($code){
			$code_sql = " and file_type_name='{$code}'";
		}else{
			$code_sql = '';
		}

		if($level){
			$level_sql = " and leak_grade={$level}";
		}else{
			$level_sql = '';
		}

		$res_scan_data = $Model->where("summary_id={$id}".$code_sql.$level_sql)->field("summary_id,leak_name,count('leak_name') as leak_name_num")->group('leak_name')->select();

		if($res_scan_data){
			$arr = array();
			foreach($res_scan_data as $key => $value){
				$arr[$value['leak_name']] = $value['leak_name_num'];
			}  
		}

		return $arr;		
	}


	/*
	*	分览视图  --- 查询漏洞的详细信息表格。风险程度、漏洞名称、漏洞位置、代码片段
	* 	参  数：$id->summary_id、$code->file_type_name、$level->leak_grade、$risk->leak_name    
	*  	返回值：
	 */
	public function branch_detail_class($id,$code,$level,$risk){
		$Model = M('scan_data');

		if($code){
			$code_sql = " and file_type_name='{$code}'";
		}else{
			$code_sql = '';
		}

		if($level=='' || $level == '5'){
			$level_sql = '';
		}else{
			$level_sql = " and leak_grade={$level}";
		}

		if($risk){
			$risk_sql = " and leak_name='{$risk}'";
		}else{
			$risk_sql = '';
		}

		$res_scan_data = $Model->where("summary_id={$id}".$code_sql.$level_sql.$risk_sql)->field("id,summary_id,leak_name,file_type_name,leak_defect_des,leak_audit_res,leak_modify_sug,leak_grade,leak_file_pos,leak_line_num,leak_sort,code_part")->order('leak_sort')->select();
		// echo $Model->_sql();die();

		return $res_scan_data;

	}


	/*
	*	根据info_id查询文件路径
	* 	参  数： $info_id
	*  	返回值：文件路径
	 */
	public function GetTaskname($info_id){
		$Model = M('upload_info');

		$res = $Model->where("id={$info_id}")->field('upload_file_path')->select();
		// echo $Model->_sql();die();
		$file_path = explode('.',$res[0]['upload_file_path']);

		return $file_path[0];
	}


	/*
	*	修改漏洞等级、审计结果
	*	参  数：
	* 	返回值：
	 */
	public function EditLeakgrade($arr){
		$Model = M('scan_data');

		$res = $Model->where("id={$arr['id']}")->save($arr);//根据主键id修改漏洞等级和审计结果
		if($res){
			return true;
		}else{
			return false;
		}
	}


	/*
	*	根据scan_data id查询用户审计结果
	* 	参  数：$id --> scan_data id
	*  	返回值：返回obsec_scan_data 用户审计数据
	 */
	public function selectLeak($id){
		$Model = M('scan_data');

		$res = $Model->where("id={$id}")->select();
		return $res;
	}


	/*
	*	根据id 修改scan_data 用户审计状态
	*	参  数：$id => scan_data id
	* 	返回值：true 修改成功   false  修改失败
	 */
	public function editLeakStatus($id){
		$Model = M('scan_data');
		$arr['leak_sort'] = 1;
		$res = $Model->where("id={$id}")->save($arr);//根据主键id修改漏洞等级和审计结果
		if($res){
			return true;
		}else{
			return false;
		}		
	}


	/*
	*	根据summary_id 和leak_grade 查询所有审计数据
	* 	参  数：
	*  	返回值：
	 */
	public function selgradedata($summary_id,$leak_grade){
		$Model = M('scan_data');

		$res = $Model->where("summary_id={$summary_id} and leak_grade={$leak_grade}")->select();

		return $res;
	}













}
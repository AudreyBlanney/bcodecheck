<?php
namespace Home\Controller;
use Common\Controller\BaseController;
class ScanController extends BaseController {
    public function scan(){
        $Model = M('upload_info');
        $user_id = $_SESSION['user_id'];
        //用户扫描文件总数
        $result['scan_num'] = $Model->table('__UPLOAD_INFO__ as a ')
            ->join('__SCAN_SUMMARY__ as b ON a.id = b.upinfo_id')
            ->where("a.user_id = '%s' and b.scan_status = '%s'",$user_id,1)->count();
        //代码总行数
        $result['code_line_num'] = $Model->table('__UPLOAD_INFO__ as a ')
            ->join('__SCAN_SUMMARY__ as b ON a.id = b.upinfo_id')
            ->where("user_id = '%s' and b.scan_status = '%s'",$user_id,1)->sum('b.code_line_num');
        //漏洞总个数
        $result['leak_num'] = $Model->table('__UPLOAD_INFO__ as a ')
            ->join('__SCAN_SUMMARY__ as b ON a.id = b.upinfo_id')
            ->where("user_id = '%s' and b.scan_status = '%s'",$user_id,1)->sum('b.leak_num');
        //漏洞文件类型总个数
        $result['leak_file_num'] = $Model->table('__UPLOAD_INFO__ as a ')
            ->join('__SCAN_SUMMARY__ as b ON a.id = b.upinfo_id')
            ->where("user_id = '%s' and b.scan_status = '%s'",$user_id,1)->sum('b.leak_file_num');
        //漏洞缺陷种类个数
        $result['leak_defect_num'] = $Model->table('__UPLOAD_INFO__ as a ')
            ->join('__SCAN_SUMMARY__ as b ON a.id = b.upinfo_id')
            ->where("user_id = '%s' and b.scan_status = '%s'",$user_id,1)->sum('b.leak_defect_num');
        //数据整理
        if(empty($result['code_line_num'])){
            $result['code_line_num'] = 0;
        }
        if(empty($result['leak_num'])){
            $result['leak_num'] = 0;
        }
        if(empty($result['leak_file_num'])){
            $result['leak_file_num'] = 0;
        }
        if(empty($result['leak_defect_num'])){
            $result['leak_defect_num'] = 0;
        }

        $this->assign('scan_result',$result);
        $this->display();
    }

    //获取任务名称是否重复/是否超出上传次数
    public function repeat_scan(){
        $Model = M('upload_info');
        $Model_data = M('upload_data');
        $user_id = $_SESSION['user_id'];
        $scan_name = $_POST['scan_name'];
        $upload_date = date('Y-m-d');

        //判断任务名是否有特殊字符
        $regex = "/\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\-|\=|\\\|\|/";
        if(preg_match($regex,$scan_name)){
            $prompt = array('success' => true,'res' => '任务名格式不对，请重新输入');
            $this->ajaxReturn($prompt);
        }

        //获取任务名称是否重复
        $result = $Model->where("user_id = '%s' and task_name = '%s'",$user_id,$scan_name)->field('task_name')->find();
        if($result && !empty($result['task_name'])){
            $prompt = array('success' => true,'res' => '任务名称重复，请重新上传');
            $this->ajaxReturn($prompt);
        }

        //获取是否超出上传次数
        $res_count = $Model->where("user_id = '%s' and upload_date = '%s'",$user_id,$upload_date)->count();
        $upload_num = $Model_data->where("user_id = '%s'",$user_id)->field('upload_num')->find();

        if($res_count >= $upload_num['upload_num']){
            $prompt = array('success' => true,'res' => '今天已超出上传次数'.$upload_num['upload_num'].'次，请明天再传');
            $this->ajaxReturn($prompt);
        }
        $prompt = array('success' => false);
        $this->ajaxReturn($prompt);
    }
}

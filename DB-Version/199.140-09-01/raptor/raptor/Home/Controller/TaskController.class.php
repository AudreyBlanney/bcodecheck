<?php
namespace Home\Controller;
use Common\Controller\BaseController;
class TaskController extends BaseController {
    public function task(){
        $this->display();
    }
    //获取数据
    public function task_data(){
        $Model = M('upload_info');
        $user_id = $_SESSION['user_id'];
        $upload_date = date('Y-m-d');
        //用户扫描文件总数
        $task_result['data'] = $Model->table('__UPLOAD_INFO__ as a ')
            ->join('__SCAN_SUMMARY__ as b ON a.id = b.upinfo_id')
            ->where("a.user_id = '%s' and a.upload_date = '%s'" ,$user_id,$upload_date)->order('a.upload_time desc')->field('a.task_name,b.scan_status,b.scan_sped')->select();
        //数据整理
        $scan_num = count($task_result['data']);
        $scan_yc = 0;
        foreach($task_result['data'] as $task_key => $task_value){
            if($task_value['scan_status'] == 1 || $task_value['scan_status'] == 2){
                $scan_yc++;
            }
        }
        if($scan_num == $scan_yc){
            $task_result['res'] = true;
        }else{
            $task_result['res'] = false;
        }
        $this->ajaxReturn($task_result,'json');
    }

}
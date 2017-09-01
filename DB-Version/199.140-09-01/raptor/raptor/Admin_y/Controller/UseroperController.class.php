<?php
namespace Admin\Controller;
use Common\Controller\BaseadminController;
class UseroperController extends BaseadminController {
    public function useroper(){
        $this->display();
    }

    //获取数据
    public function useroper_data(){
        $Model = M('user');
        $result = $Model->table('__USER__ AS a')
        ->join('__UPLOAD_INFO__ AS b ON a.id = b.user_id')
        ->join('__SCAN_SUMMARY__ AS c ON b.id = c.upinfo_id')
        ->field('a.user_name,a.register_type,a.corporate_name,a.email,a.phone,b.upload_file_size,c.scan_status,c.code_line_num,
        c.leak_file_type,c.scan_end_time,b.upload_time')->select();
        //数据整理
        foreach($result as $oper_key => &$oper_value){
            $oper_value['remark'] =$oper_key+1;
            if($oper_value['register_type'] == 1){
                $oper_value['register_type'] = '个人用户';
            }elseif($oper_value['register_type'] == 2){
                $oper_value['register_type'] = '企业用户';
            }elseif($oper_value['register_type'] == 3){
                $oper_value['register_type'] = '系统用户';
            }
            if($oper_value['scan_status'] == 1){
                $oper_value['scan_status'] = '成功';
            }elseif($oper_value['scan_status'] == 2){
                $oper_value['scan_status'] = '失败';
            }elseif($oper_value['scan_status'] = 3){
                $oper_value['scan_status'] = '扫描中';
            }

            if($oper_value['scan_end_time']){
                $hour=floor((strtotime($oper_value['scan_end_time'])-strtotime($oper_value['upload_time']))%86400/3600);
                $minute=floor((strtotime($oper_value['scan_end_time'])-strtotime($oper_value['upload_time']))%86400/60);
                $second=floor((strtotime($oper_value['scan_end_time'])-strtotime($oper_value['upload_time']))%86400%60);
                $hour = $hour ? $hour : 0;
                $minute = $minute ? $minute : 0;
                $second = $second ? $second : 0;
                $oper_value['code_long'] = $hour.'时'.$minute.'分'.$second.'秒';
            }else{//判断如果正在扫描中或者扫描失败没有结束时间的情况
                $oper_value['code_long'] = '0时0分0秒';
            }
        }
        $this->ajaxReturn($result,'json');
    }
}
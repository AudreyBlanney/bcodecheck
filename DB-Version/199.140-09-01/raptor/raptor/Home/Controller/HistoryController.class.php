<?php
namespace Home\Controller;
use Common\Controller\BaseController;
class HistoryController extends BaseController {
    public function history(){
        $this->display();
    }
    //获取扫描数据
    public function analytics_data(){
        $Model= M('upload_info');
        $user_id = $_SESSION['user_id'];
        $result = $Model->table('__UPLOAD_INFO__ as a ')
            ->join('__SCAN_SUMMARY__ as b ON a.id = b.upinfo_id')->where('a.user_id = "%s"',$user_id)->order('a.upload_time desc')
            ->field('a.id,a.user_id,a.task_name,b.leak_file_type,a.upload_time,b.scan_status,b.leak_num,b.code_line_num')->select();
        //数据整理
        foreach($result as $res_key => &$res_value){
            $res_value['found_name'] = $_SESSION['user_name'];
            $res_value['leak_num'] = $res_value['leak_num']."(共计{$res_value['code_line_num']}行)";
            if(empty($res_value['leak_file_type']) && $res_value['scan_status'] == 1){
                $res_value['leak_file_type'] = '未发现问题';
            }
            if($res_value['scan_status'] == 1){
                $res_value['scan_status'] = '成功';
                $res_value['dw_pre'] = "
                                <a href='javascript:;' info_id='".$res_value['id']."' class='download download_word' style=''><span title='word下载'>下载</span></a>&nbsp;
                                <a href='/Home/Analysis/issues?info_id=".$res_value['id']."'><span class='look' title='查看'>查看</span></a>&nbsp;
                                <a href='javascript:;' info_id='".$res_value['id']."' class='del_data'><span class='del' title='删除'>删除</span></a>
                                ";
            }elseif($res_value['scan_status'] == 2){
                $res_value['scan_status'] = '失败';
                $res_value['dw_pre'] = "
                                <a href='javascript:;'><span class='download' title='word下载'>下载</span></a>&nbsp;
                                <a href='javascript:;'><span class='look' title='查看'>查看</span></a>&nbsp;
                                <a href='javascript:;' info_id='".$res_value['id']."' class='del_data'><span class='del' title='删除'>删除</span></a>
                                ";
            }elseif($res_value['scan_status'] == 3){
                $res_value['scan_status'] = '扫描中 ...';
                $res_value['dw_pre'] = "
                                <a href='javascript:;'><span class='download' title='word下载'>下载</span></a>&nbsp;
                                <a href='javascript:;'><span class='look' title='查看'>查看</span></a>&nbsp;
                                <a href='javascript:;'><span class='del' title='删除'>删除</span></a>
                                ";
            }
        }
        $this->ajaxReturn($result,'json');
    }

    //删除数据
    public function del_data(){
        $tranDb = new \Think\Model();
        $upload_file = C('UPLOAD_FILE');
        $user_name = $_SESSION['user_name'];
        $tranDb->startTrans();
        $info_id = $_POST['info_id'];
        $summary_id = $tranDb->table('__SCAN_SUMMARY__')->where("upinfo_id = '%s'",$info_id)->field('id')->find();
        $task_name= $tranDb->table('__UPLOAD_INFO__')->where("id = '%s'",$info_id)->field('task_name')->find();
        $info_res = $tranDb->table('__UPLOAD_INFO__')->where("id = '%s'",$info_id)->delete();
        $summary_res = $tranDb->table('__SCAN_SUMMARY__')->where("upinfo_id = '%s'",$info_id)->delete();
        $tranDb->table('__SCAN_DATA__')->where("summary_id = '%s'",$summary_id['id'])->delete();

        $file_info = $upload_file.$user_name.'/'.$task_name['task_name'];
        exec("sudo chmod -R 777 {$file_info}");
        $execstr = "sudo rm -rf ".$file_info;
        exec($execstr,$res,$status);

        if($summary_id && $info_res && $summary_res && $task_name && $status == 0){
            $res_data = true;
            $tranDb->commit();
        }else{
            $res_data = false;
            $tranDb->rollback();
        }
        $this->ajaxReturn($res_data,'json');
    }
}
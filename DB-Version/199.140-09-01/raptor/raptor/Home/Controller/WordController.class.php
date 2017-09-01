<?php
namespace Home\Controller;
use Common\Controller\BaseController;
class WordController extends BaseController {
    public function word(){
        $info_id = $_GET['info_id'];
       // $info_id = 38;
        $Model= M('upload_info');
        $Bread_Model = M('scan_summary');
        //获取项目范围信息
        $project_fw = $Model->table('__UPLOAD_INFO__ AS a')
            ->join('__SCAN_SUMMARY__ AS b ON a.id = b.upinfo_id')->where("a.id = '%s'",$info_id)
            ->field('a.task_name,a.upload_file_path,b.leak_file_type,b.code_line_num,b.leak_num')->find();
        $this->assign('project_fw',$project_fw); //项目范围
        $this->assign('info_id',$info_id); //info_id

        //获取文件类型信息
        $file_type = $Model->table('__UPLOAD_INFO__ AS a')
            ->join('__SCAN_SUMMARY__ AS b ON a.id = b.upinfo_id')
            ->join('__SCAN_DATA__ AS c ON b.id = c.summary_id')
            ->where("a.id = '%s' and file_type_name != '%s'",$info_id,'')->field("file_type_name,count(c.file_type_name) file_num")
            ->group('file_type_name')->select();
        //数据整理
        $file_con = '';
        foreach($file_type as $file_key => $file_value){
            $file_con += $file_value['file_num'];
        }
        $this->assign('file_con',$file_con); //文件类型总数
        $this->assign('res_file',$file_type); //文件类型
        $this->assign('file_json',json_encode($file_type)); //文件类型

        //获取程度类型数据
        $grade_type = $Model->table('__UPLOAD_INFO__ AS a')
            ->join('__SCAN_SUMMARY__ AS b ON a.id = b.upinfo_id')
            ->join('__SCAN_DATA__ AS c ON b.id = c.summary_id')
            ->where("a.id = '%s' and file_type_name != '%s'",$info_id,'')->field("leak_grade,count(c.leak_grade) grade_num")
            ->group('leak_grade')->select();
        //数据整理
        $grade_con = '';
        foreach($grade_type as $grade_key => &$grade_value){
            $grade_con += $grade_value['grade_num'];
            if($grade_value['leak_grade'] == 1){
                $grade_value['leak_grade'] = '高';
            }elseif($grade_value['leak_grade'] == 2){
                $grade_value['leak_grade'] = '中';
            }elseif($grade_value['leak_grade'] == 3 ){
                $grade_value['leak_grade'] = '低';
            }elseif($grade_value['leak_grade'] == 4){
                $grade_value['leak_grade'] = '忽略';
            }

        }
        $this->assign('grade_con',$grade_con); //程度类型总数
        $this->assign('res_grade',$grade_type); //程度类型

        //风险分类视图
        $risk_type = $Model->table('__UPLOAD_INFO__ AS a')
            ->join('__SCAN_SUMMARY__ AS b ON a.id = b.upinfo_id')
            ->join('__SCAN_DATA__ AS c ON b.id = c.summary_id')
            ->where("a.id = '%s' and file_type_name != '%s'",$info_id,'')->field("leak_name,count(c.leak_name) risk_num")
            ->group('leak_name')->select();
        $risk_con = '';
        $leak_name = '';
        foreach($risk_type as $risk_key => $risk_value){
            $risk_con += $risk_value['risk_num'];
            $leak_name .= $risk_value['leak_name'].',';
        }
        $this->assign('risk_con',$risk_con); //风险分类视图总数
        $this->assign('res_risk',$risk_type); //风险分类类型

        //获取分览视图数据
        $bread_res = $Bread_Model->table('__SCAN_SUMMARY__ AS a')
            ->join('__SCAN_DATA__ AS b ON a.id = b.summary_id')
            ->where("a.upinfo_id = '%s'",$info_id)->field('b.*')
            ->select();

        //数据整理
        $file_type_array = explode(',',$project_fw['leak_file_type']); //文件类型数组
        $risk_type_array = explode(',',trim($leak_name,',')); //风险类型数组
        foreach($bread_res as $bre_key => &$bre_value){
            if($bre_value['leak_grade'] == 1){
                $bre_value['leak_grade'] = '高';
            }elseif($bre_value['leak_grade'] == 2){
                $bre_value['leak_grade'] = '中';
            }elseif($bre_value['leak_grade'] == 3 ){
                $bre_value['leak_grade'] = '低';
            }elseif($grade_value['leak_grade'] == 4){
                $bre_value['leak_grade'] = '忽略';
            }
            foreach($file_type_array as $far_key => $far_value) { //循环文件类型
                foreach ($risk_type_array as $rsk_key => $rsk_value) { //循环风险类型
                    if(in_array($rsk_value,$bre_value) && $far_value == $bre_value['file_type_name']){
                        $risk_description[$far_value][$rsk_value][] = $bread_res[$bre_key];
                    }

                }
            }
        }

        $this->assign('risk_description',$risk_description);
        //获取导出报告时间
        $this->assign('year',date('Y'));
        $this->assign('month',date('m'));
        $this->display();
    }
}
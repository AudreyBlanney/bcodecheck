<?php
namespace Admin\Controller;
use Common\Controller\BaseadminController;
class StaticangController extends BaseadminController {
    public function staticang(){
        $Model = M('upload_info');
        for($i = 7;$i >= 1;$i--){
            $result[$i] = $Model->where("upload_date = '%s'",date('Y-m-d',strtotime("-{$i} day")))
                ->field("id,count(id) as scan_num,sum(upload_file_size) scan_size")->select();
        }

        //数据整理
        $date_str = '';$scan_sum = '';$scan_size = '';
        foreach($result as $res_key => $res_value){
            if(!empty($res_value)){
                foreach($res_value as $scan_key => $scan_value){
                    $res_zk[date('Y-m-d',strtotime("-{$res_key} day"))]['scan_sum'] = $scan_value['scan_num'];
                    $res_zk[date('Y-m-d',strtotime("-{$res_key} day"))]['scan_size'] = $scan_value['scan_size'];
                }
            }else{
                $res_zk[date('Y-m-d',strtotime("-{$res_key} day"))]['scan_sum'] = 0;
                $res_zk[date('Y-m-d',strtotime("-{$res_key} day"))]['scan_size'] = 0;
            }
            $date_str .= "'".date('Y-m-d',strtotime("-{$res_key} day"))."'".',';
        }
        foreach($res_zk as $zk_key => $zk_value){
            $scan_sum .= !empty($zk_value['scan_sum']) ? $zk_value['scan_sum'].',' : 0 .',';
            $scan_size .= !empty($zk_value['scan_size']) ? round($zk_value['scan_size'],2).',' : 0 .',';
        }
        $this->assign('date_str',trim($date_str,',')); //获取日期
        $this->assign('scan_sum',trim($scan_sum,',')); //获取扫描数量
        $this->assign('scan_size',trim($scan_size,',')); //获取扫描大小
        $this->display();
    }
    //获取表格数据
    public function staticang_data(){
        $Model = M('user');
        $result = $Model->table('__USER__ AS a')->join('__UPLOAD_INFO__ AS b ON a.id = b.user_id')
            ->field("a.user_name,a.register_type,a.corporate_name,count(b.id) as scan_num,sum(b.upload_file_size) scan_size")
            ->group('a.id')->select();
        //数据整理
        foreach($result as $us_key => &$us_value){
            if($us_value['register_type'] == 1){
                $us_value['register_type'] = '个人用户';
            }elseif($us_value['register_type'] == 2){
                $us_value['register_type'] = '企业用户';
            }elseif($us_value['register_type'] == 3){
                $us_value['register_type'] = '系统用户';
            }
            $us_value['scan_size'] = round($us_value['scan_size'],2);
        }
        $this->ajaxReturn($result,'json');
    }
}
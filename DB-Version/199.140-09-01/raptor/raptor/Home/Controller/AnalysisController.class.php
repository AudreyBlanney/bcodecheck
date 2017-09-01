<?php
namespace Home\Controller;
use Common\Controller\BaseController;
class AnalysisController extends BaseController {
    public function codeAnalysis(){
        $this->display();
    }
    //代码分析  --总览视图
    public function issues(){
        $login = D('Analysis');
        $isLogin = $login->isLogin();

        if($isLogin){
            if($_GET['info_id']){
                $info_id = $_GET['info_id'];
            }else{
                $info_id = $login->new_detection();//获取最近检测的upload_id
                if(empty($info_id)){
                    $info_id = '-1';
                }
            }

            $this->assign('info_id',$info_id);
            $this->display('issues');
        }else{
            $this->redirect('Home/Index/index');//如果未登录。跳转到登录页
        }

    }


    //代码分析  --总览视图
    public function issues2(){
    	$login = D('Analysis');
    	$isLogin = $login->isLogin();

        $info_id = $_GET['info_id'];

        $summary_id = $login->getSummaryId($info_id);

        $file_class = $login->file_class($summary_id);             //文件分类视图 
        $degree_clas = $login->degree_class($summary_id);         //程度分类视图

        foreach($degree_clas as $k => $v){
            if($k == '1'){
                $degree_class['高'] = $v;
            }else if($k == '2'){
                $degree_class['中'] = $v;
            }else if($k == '3'){
                $degree_class['低'] = $v;
            }else if($k == '4'){
                $degree_class['忽略'] = $v;
            }
        }
        $risk_class = $login->risk_class($summary_id);             //风险分类视图数据

        $result = [
            'data2' => $file_class,
            'data4' => $degree_class,
            'data6' => $risk_class,
        ];
        echo json_encode($result);

    }

    //代码分析  ---分览视图
    public function issues_bro(){
        //{ ["php"]=> int(7466) ["js"]=> int(4) }   头部展示的语言格式
        $login = D('Analysis');

        $isLogin = $login->isLogin();
        if($isLogin){
            if($_GET['info_id']){
                $info_id = $_GET['info_id'];
            }else{
                $info_id = $login->new_detection();//获取最近检测的upload_id
                if(empty($info_id)){
                    $info_id = '-1';
                }
            }

            $summary_id = $login->getSummaryId($info_id);

            $file_class = $login->file_class($summary_id);

            $this->assign('file_class',$file_class);
            $this->assign('info_id',$info_id);
            $this->display('issues_bro');
        }else{
            $this->redirect('Home/Index/index');//如果未登录。跳转到登录页
        }

    }

    //代码分析  ---分览视图
    public function issues_bro2(){
        $login = D('Analysis');

        $info_id = $_GET['info_id'];
        $code = $_GET['codetype'];
        $level = !empty($_GET['level']) ? $_GET['level'] : '';
        if(!$code){
            $code = '';
        }

        $level = $_GET['level'];

        if($level == '高'){
            $level = '1';
        }else if($level == '中'){
            $level = '2';
        }else if($level == '低'){
            $level = '3';
        }else if($level == '忽略'){
            $level = '4';
        }else{
            $level = '';
        }

        $summary_id = $login->getSummaryId($info_id);//获取summary_id

        $file_class = $login->file_class($summary_id);//获取文件分类

        $grade = $login->degree_class($summary_id,$code);//获取程度分类

        foreach($grade as $k => $v){
            if($k == '1'){
                $gradeType['高'] = $v;
            }else if($k == '2'){
                $gradeType['中'] = $v;
            }else if($k == '3'){
                $gradeType['低'] = $v;
            }else if($k == '4'){
                $gradeType['忽略'] = $v;
            }
        }
        $riskType = $login->risk_class($summary_id,$code,$level);//获取风险分类

        $risk = $_GET['risk'];

        $risk_detail = $login->branch_detail_class($summary_id,$code,$level,$risk);//风险分类信息表格详情

        $result = [
            'owner_grade' => $gradeType,
            'owner_risk' => $riskType,
            'owner_data' => $risk_detail
        ];
        // var_dump($result);
        echo json_encode($result);
    }

    //修改漏洞等级和审计结果
    public function UpdateGrade(){
        $login = D('Analysis');
        $info_id = $_POST['info_id'];

        if($_POST['grade'] == '高'){
            $arr['leak_grade'] = 1;
        }else if($_POST['grade'] == '中'){
            $arr['leak_grade'] = 2;
        }else if($_POST['grade'] == '低'){
            $arr['leak_grade'] = 3;
        }else if($_POST['grade'] == '忽略'){
            $arr['leak_grade'] = 4;
        }
        $type = $_POST['data_type'];
        $arr['leak_audit_res'] = $_POST['describe'];
        $arr['id'] = $_POST['id'];

        $leak_grade = $login->selectLeak($arr['id']);
        $leak_gra1 = $leak_grade[0]['leak_grade'];
        $summary_id = $login->getSummaryId($info_id);
        $leak_data = $login->selgradedata($summary_id,$leak_gra1);

        $res = $login->EditLeakgrade($arr);
        $leak_grade2 = $login->selectLeak($arr['id']);
        $leak_gra2 = $leak_grade2[0]['leak_grade'];

        if($leak_gra1 != $leak_gra2){
            $next_id = "/next_id/".$_POST['next_id'];
        }

        if($res){
            $this->redirect('Home/Analysis/online_right/info_id/'.$info_id.'/id/'.$arr['id'].'/data_type/'.$type.'/status/true'.$next_id);
        }else{
            $this->redirect('Home/Analysis/online_right/info_id/'.$info_id.'/id/'.$arr['id'].'/data_type/'.$type.'/status/true'.$next_id);
        }
    }


    //获取分览表格
    public function owerdata(){
        $codetype = $_GET['codetype']; //文件类型 java php ...
        $level = !empty($_GET['level']) ? trim($_GET['level']) : 'all'; //等级 高 中 低
        $risk = !empty($_GET['risk']) ? trim($_GET['risk']) : '';
        $info_id = $_GET['info_id']; //查看文件id
        if($level != 'all'){
            if($level == '高'){
                $level = 1;
            }elseif($level == '中'){
                $level = 2;
            }elseif($level == '低'){
                $level = 3;
            }elseif($level == '忽略'){
                $level = 4;
            }
            $leak_grade = " and c.leak_grade = $level";
        }
        if($risk){
            $leak_name = " and c.leak_name = '{$risk}'";
        }
        $Model = M('upload_info');
        $res_data = $Model->table('__UPLOAD_INFO__ AS a')
            ->join('__SCAN_SUMMARY__ AS b ON a.id = b.upinfo_id')
            ->join('__SCAN_DATA__ AS c ON b.id = c.summary_id')
            ->where("a.id = '%s' and c.file_type_name = '%s' $leak_grade $leak_name",$info_id,$codetype)
            ->field('c.leak_grade,c.leak_name,c.leak_file_pos,c.leak_line_num,c.code_part')
            ->select();
        //数据整理
        foreach($res_data as $data_key => &$data_value){
            $data_value['leak_file_pos'] = $data_value['leak_file_pos'].'#L'.$data_value['leak_line_num'];
            if($data_value['leak_grade'] == 1){
                $data_value['leak_grade'] = '高';
            }elseif($data_value['leak_grade'] == 2){
                $data_value['leak_grade'] = '中';
            }elseif($data_value['leak_grade'] == 3){
                $data_value['leak_grade'] = '低';
            }elseif($data_value['leak_grade'] == 4){
                $data_value['leak_grade'] = '忽略';
            }
        }
        $this->ajaxReturn($res_data,'json');
    }

    //获取在线审计数据
    function online_data(){
        $info_id = $_POST['info_id']; //获取审计文件id
        $grade_status = !empty($_POST['grade_status']) ? $_POST['grade_status'] :'';
        if($grade_status == 5 || !$grade_status){
            $leak_grade_status = "";
        }else{
            $leak_grade_status = " and leak_grade = $grade_status";
        }
        $user_id = $_SESSION['user_id'];
        $Model = M('upload_info');

        //漏洞数据处理
        $risk_type = $Model->table('__UPLOAD_INFO__ AS a')
            ->join('__SCAN_SUMMARY__ AS b ON a.id = b.upinfo_id')
            ->join('__SCAN_DATA__ AS c ON b.id = c.summary_id')
            ->where("a.id = '%s'",$info_id)->field("leak_name")
            ->group('leak_name')->select();
        //获取漏洞文件数据
        $res_data = $Model->table('__UPLOAD_INFO__ AS a')
            ->join('__SCAN_SUMMARY__ AS b ON a.id = b.upinfo_id')
            ->join('__SCAN_DATA__ AS c ON b.id = c.summary_id')
            ->where("a.id = '%s' and a.user_id = '%s' $leak_grade_status",$info_id,$user_id)
            ->field('c.leak_name,c.leak_file_pos,c.leak_line_num,c.leak_sort,c.id,c.leak_sort')
            ->select();
;
        //获取漏洞等级数据
        $leak_grade = $Model->table('__UPLOAD_INFO__ AS a')
            ->join('__SCAN_SUMMARY__ AS b ON a.id = b.upinfo_id')
            ->join('__SCAN_DATA__ AS c ON b.id = c.summary_id')
            ->where("a.id = '%s'",$info_id)->field("leak_grade,count(leak_grade) as leak_grade_num")
            ->group('leak_grade')->select();

        //数据整理
        foreach($leak_grade as $grade_key => &$grade_value) {
            if ($grade_value['leak_grade'] == 1) {
                $grade_status_num['high'] = $grade_value['leak_grade_num'];
            }elseif($grade_value['leak_grade'] == 2){
                $grade_status_num['mi'] = $grade_value['leak_grade_num'];
            }elseif($grade_value['leak_grade'] == 3){
                $grade_status_num['low'] = $grade_value['leak_grade_num'];
            }elseif($grade_value['leak_grade'] == 4){
                $grade_status_num['gnore'] = $grade_value['leak_grade_num'];
            }
            $grade_status_num['grade_con'] += $grade_value['leak_grade_num'];
        }
        if(!empty($res_data)){
            foreach($res_data as $online_key => $online_value){
                foreach($risk_type as $risk_key => $risk_value){
                    if($online_value['leak_name'] == $risk_value['leak_name']){
                        $risk_online[$online_value['leak_name']][$online_value['id']] = array(
                            'file_name' => $online_value['leak_file_pos']."(".$online_value['leak_line_num'].")",
                            'leak_sort' => $online_value['leak_sort']
                            );
                    }
                }
            }

            $i = 0;
            foreach($risk_online as $ro_key => $ro_value){
                //获取二级文件（文件名城）
                $online_file = array();
                $leak_sort_count = 0;
                foreach($ro_value as $rv_key => $rv_value){
                    //获取审计过的个数
                    if($rv_value['leak_sort'] == 1){
                        $leak_sort_count +=1;
                    }
                    $online_file_data = array(
                        'tid' => "M00$rv_key",
                        'name' => $rv_value['file_name'],
                        'data_id' => $rv_key,
                        'info_id' => $info_id,
                        'grade_status' => $grade_status,
                        'leak_sort' =>$rv_value['leak_sort'],
                        'childlist' => ''
                    );
                    array_push($online_file,$online_file_data);
                }
                //获取一级文件（漏洞名称）
                $online_array[$i] = array(
                    'tid' => "M0$i",
                    'name' => $ro_key.$leak_sort_count.'/'.count($ro_value)
                );
                $online_array[$i]['childlist'] = $online_file;
                $i+=1;
            }
        }

        $online_res = array(
            'online_res' => $online_array,
            'grade_status_num' => $grade_status_num
        );
       echo $this->ajaxReturn($online_res,'json');
    }

    //用户数据审计
    public function online_right(){
        $data_id = $_GET['data_id'];
        $info_id = $_GET['info_id'];
        $xs_view = $_GET['xs_view']; //兄弟节点
        $grade_status = $_GET['grade_status']; //漏洞等级
        $Model_info = M('upload_info');
        $Model_data = M('scan_data');
        $upload_file = C('UPLOAD_FILE'); //上传文件路径
        $user_name = $_SESSION['user_name'];
        //获取任务名称/文件名称
        $task_res = $Model_info->where("id = '%s'",$info_id)->field('task_name,upload_file_name')->find();
        //获取文件信息
        $leak_file_ifno = $Model_data->where("id = '%s'",$data_id)->field('leak_file_pos,leak_line_num,leak_defect_des,leak_grade,file_type_name,code_part,leak_audit_res,leak_sort')->find();
        $path_info = $upload_file.$user_name.'/'.$task_res['task_name'].'/'.rtrim($task_res['upload_file_name'],'.zip').'/'.$leak_file_ifno['leak_file_pos'];
        $content = is_file($path_info) ? file_get_contents($path_info) : '';
        $track_file[] = array(
            'file_type_name' =>$leak_file_ifno['file_type_name'],
            'leak_line_num' =>$leak_file_ifno['leak_line_num'],
            'code_part' =>$leak_file_ifno['code_part']
        );
        $content_array = $content ? explode(PHP_EOL,htmlspecialchars($content)) : ''; //\t数据分割整理数据
        $this->assign('leak_line_num',$leak_file_ifno['leak_line_num']); //获取行号
        $this->assign('leak_defect_des',$leak_file_ifno['leak_defect_des']); //漏洞缺陷描述
        $this->assign('leak_modify_sug',$leak_file_ifno['leak_modify_sug']); //漏洞修改建议
        $this->assign('leak_grade',$leak_file_ifno['leak_grade']); //漏洞等级
        $this->assign('leak_audit_res',$leak_file_ifno['leak_audit_res']); //审计结果
        $this->assign('track_file',json_encode($track_file)); //文件跟踪
        $this->assign('data_id',$data_id); //data_id
        $this->assign('xs_view',$xs_view); //兄弟节点
        $this->assign('grade_status',$grade_status); //漏洞等级
        $this->assign('content_array',$content_array); //获取文件内容

        $this->display();
    }
    //更新审计结果
    public function upload_data(){
        $data_id = $_POST['data_id'];
        $xs_view = $_POST['xs_view'];
        $grade_status = $_POST['grade_status'];
        $grade = !empty($_POST['grade']) ? $_POST['grade'] : ''; //高中低
        $describe = !empty($_POST['describe']) ? $_POST['describe'] : ''; //审计结果
        $Model = M('scan_data');
        $data['leak_grade'] = $grade;
        $data['leak_audit_res'] = $describe;
        $data['leak_sort'] = 1;
        $up_res = $Model->where("id = '%s'",$data_id)->save($data);
        if($up_res === false){
            $prompt = array('success' => false,'res' => '审计失败，请重新审计');
            die(json_encode($prompt));
        }else{
            $prompt = array('success' => true,'xs_view' => $xs_view,'grade_status' => $grade_status);
            die(json_encode($prompt));
        }

    }

}
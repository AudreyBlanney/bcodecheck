<?php
    include("session.php");
    if (!empty($_SESSION['current_scan_report'])) {
        if (file_exists($_SESSION['current_scan_report'])) {
          $data = json_decode(file_get_contents($_SESSION['current_scan_report']), true);
        } else {
          $_SESSION['current_scan_report'] = '';
        }
    } else {
        error_log("[ERROR] session: current_scan_report is null.");
    }

    $ownerViewData = Array();//分览总数据{1.程度视图[高：1，中：2，低：3]，2.风险视图[Xss：1，sql注入：2，低：3]，3.$data}
    $codetype_param = $_GET['codetype'];//java、php
    $level = $_GET['level'];//等级高中低
    $git_url = '';
    for ($i=0; $i < count($data['warnings']); $i++) { 
        if(strtolower(trim(substr(strrchr($data['warnings'][$i]['file'],'.'),1))) == $codetype_param && 'all' == $level){
            $data['warnings'][$i]['file'] = ltrim($data['warnings'][$i]['file'], '/') . '#L' . $data['warnings'][$i]['line'];
          if($data['warnings'][$i]['severity'] == '高'){
            $data['warnings'][$i]['code'] = '<pre><code style="white-space:nowrap" class="code-level code-level-h'. pathinfo($data['warnings'][$i]['file'], PATHINFO_EXTENSION) .'">' . htmlentities($data['warnings'][$i]['code']) . '</code></pre>';
          }else if($data['warnings'][$i]['severity'] == '中'){
            $data['warnings'][$i]['code'] = '<pre><code style="white-space:nowrap" class="code-level code-level-m'. pathinfo($data['warnings'][$i]['file'], PATHINFO_EXTENSION) .'">' . htmlentities($data['warnings'][$i]['code']) . '</code></pre>';
          }else{
            $data['warnings'][$i]['code'] = '<pre><code style="white-space:nowrap" class="code-level code-level-l'. pathinfo($data['warnings'][$i]['file'], PATHINFO_EXTENSION) .'">' . htmlentities($data['warnings'][$i]['code']) . '</code></pre>';
          }

            $data['warnings'][$i]['link'] = '<a target="_blank" href="' . $data['warnings'][$i]['link'] . '">'. $data['warnings'][$i]['link'] .'</a>';
           $ownerViewData[] = $data['warnings'][$i];//分览数据
        }else if(strtolower(trim(substr(strrchr($data['warnings'][$i]['file'],'.'),1))) == $codetype_param && $data['warnings'][$i]['severity'] == $level){
            $data['warnings'][$i]['file'] = ltrim($data['warnings'][$i]['file'], '/') . '#L' . $data['warnings'][$i]['line'];

          if($data['warnings'][$i]['severity'] == '高'){
            $data['warnings'][$i]['code'] = '<pre><code style="white-space:nowrap" class="code-level code-level-h'. pathinfo($data['warnings'][$i]['file'], PATHINFO_EXTENSION) .'">' . htmlentities($data['warnings'][$i]['code']) . '</code></pre>';
          }else if($data['warnings'][$i]['severity'] == '中'){
            $data['warnings'][$i]['code'] = '<pre><code style="white-space:nowrap" class="code-level code-level-m'. pathinfo($data['warnings'][$i]['file'], PATHINFO_EXTENSION) .'">' . htmlentities($data['warnings'][$i]['code']) . '</code></pre>';
          }else{
            $data['warnings'][$i]['code'] = '<pre><code style="white-space:nowrap" class="code-level code-level-l'. pathinfo($data['warnings'][$i]['file'], PATHINFO_EXTENSION) .'">' . htmlentities($data['warnings'][$i]['code']) . '</code></pre>';
          }

            $data['warnings'][$i]['link'] = '<a target="_blank" href="' . $data['warnings'][$i]['link'] . '">'. $data['warnings'][$i]['link'] .'</a>';
           $ownerViewData[] = $data['warnings'][$i];//分览数据
        }
    }
    $result = [
        $ownerViewData
    ];

    echo json_encode($ownerViewData);

    //请求参数过滤$data的json返回$code_data的json
    //根据传过来的参数来展示视图、列表

 ?>
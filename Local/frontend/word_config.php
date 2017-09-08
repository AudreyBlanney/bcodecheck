<?php
/**
 * User: maaidong
 * Date: 2017/3/31
 * Time: 16:29
 */
/*$word_array = array(
    array(
        'number' => '1.',
        'title' => '概述',
        'cd' => array(
           array(
               'number' => '1.1',
               'title' => '项目目的',
               'cd' => array(),
           ),
            array(
                'number' => '1.2',
                'title' => '项目概述',
                'content' => '',
                'cd' => array(),
            ),
            array(
                'number' => '1.3',
                'title' => '项目范围',
                'content' => '',
                'cd' => array(
                    array(
                        'number' => '1.3.1',
                        'title' => '审计过程',
                        'content' => '',
                    ),
                    array(
                        'number' => '1.3.2',
                        'title' => '审计工具',
                        'content' => '',
                    ),
                    array(
                        'number' => '1.3.3',
                        'title' => '审计方法',
                        'content' => '',
                    ),
                    array(
                        'number' => '1.3.4',
                        'title' => '参与人员',
                        'content' => '',
                    )
                ),
            ),
       ),
    ),

    array(
        'number' => '2.',
        'title' => '审计结果',
        'cd' => array(
            array(
                'number' => '2.1',
                'title' => '审计结果统计',
                'content' => '',
                'cd' => array(),
            ),
            array(
                'number' => '2.2',
                'title' => '审计结果总述1',
                'content' => '',
                'cd' => array(
                    array(
                        'number' => '2.2.1',
                        'title' => '风险情况综述',
                        'content' => '',
                    ),
                    array(
                        'number' => '2.2.2',
                        'title' => '风险情况详述',
                        'content' => '',
                    ),
                ),
            ),
            array(
                'number' => '2.3',
                'title' => '源码审计结果',
                'content' => '',
                'cd' => array(
                    array(
                        'number' => '2.3.1',
                        'title' => '风险情况综述1',
                        'content' => '',
                    ),
                    array(
                        'number' => '2.3.2',
                        'title' => '风险情况详述1',
                        'content' => '',
                    ),
                ),
            ),
            array(
                'number' => '2.4',
                'title' => '审计结论',
                'content' => '',
                'cd' => array(),
            ),
        ),
    ),

    array(
        'number' => '3.',
        'title' => '审计修复建议',
        'content' => '',
        'cd' => array(),
    ),

    array(
        'number' => '4.',
        'title' => '附录',
        'content' => '',
        'cd' => array(),
    ),
);*/

//附录内容
$fulu_content = array(
    '版面风格' => array(
        array(
            'num' => 1,
            'content' => '代码的编写格式是否一致？',
            'state' => '是'
        ),
        array(
            'num' => 2,
            'content' => '代码的编写格式是否有助于代码的维护？',
            'state' => '是'
        ),
        array(
            'num' => 3,
            'content' => '代码的编写格式是否有助于代码的可读性？',
            'state' => '是'
        ),
        array(
            'num' => 4,
            'content' => '每行最多字包含一条语句吗？',
            'state' => '是'
        ),
        array(
            'num' => 5,
            'content' => '注释风格是否一致？',
            'state' => '是'
        ),
        array(
            'num' => 6,
            'content' => '注释风格是否易于注释的维护？',
            'state' => '是'
        ),
    ),

    '代码' => array(
        array(
            'num' => 1,
            'content' => '定义的程序名是否有意义？',
            'state' => '是'
        ),
        array(
            'num' => 2,
            'content' => '程序接口清晰明确吗？',
            'state' => '是'
        ),
        array(
            'num' => 3,
            'content' => '标识符的命名是否清晰？',
            'state' => '是'
        ),
        array(
            'num' => 4,
            'content' => '变量命名适当吗？',
            'state' => '是'
        ),
        array(
            'num' => 5,
            'content' => '循环计数器的变量名称是有意义的吗？',
            'state' => '是'
        ),
        array(
            'num' => 6,
            'content' => '是否用定义的常量代替实际的数据或字符？',
            'state' => '是'
        ),
        array(
            'num' => 7,
            'content' => '是否睡数据类型，常量，本地变量，实例',
            'state' => '是'
        ),
        array(
            'num' => 8,
            'content' => '数据类型和数据声明是否合理正确？',
            'state' => '是'
        ),
        array(
            'num' => 9,
            'content' => '所有参数都定义了，或者计算了吗？',
            'state' => '是'
        ),
        array(
            'num' => 10,
            'content' => '所有定义的数据都使用了吗？',
            'state' => '是'
        ),
        array(
            'num' => 11,
            'content' => '所有引用的之程序都定义了吗？',
            'state' => '是'
        ),
        array(
            'num' => 12,
            'content' => '所有定义的之程序都是用了吗？',
            'state' => '是'
        ),
    ),

    '注释' => array(
        array(
            'num' => 1,
            'content' => '注释是否有助于他人对代码的理解？',
            'state' => '是'
        ),
        array(
            'num' => 2,
            'content' => '注释是否解释了代码的目的？',
            'state' => '是'
        ),
        array(
            'num' => 3,
            'content' => '注释是否是最新的？',
            'state' => '是'
        ),
        array(
            'num' => 4,
            'content' => '注释是否清晰正确？',
            'state' => '是'
        ),
        array(
            'num' => 5,
            'content' => '注释是否都有用？',
            'state' => '是'
        ),
        array(
            'num' => 6,
            'content' => '是否对代码含义进行了注释？',
            'state' => '是'
        ),
        array(
            'num' => 7,
            'content' => '声明全局变量时，是否给予了注释？',
            'state' => '是'
        ),
        array(
            'num' => 8,
            'content' => '是否说明了每个子程序的目的？',
            'state' => '是'
        ),
        array(
            'num' => 9,
            'content' => '是否对输入输出参数进行了说明？',
            'state' => '是'
        ),
        array(
            'num' => 10,
            'content' => '是否描述了文件目的？',
            'state' => '是'
        ),
        array(
            'num' => 11,
            'content' => '是否记录了作者名？',
            'state' => '是'
        ),
    ),

    '安全' => array(
        array(
            'num' => 1,
            'content' => '索引，下标是否经过了数组，记录或文件的边界测试？',
            'state' => '是'
        ),
        array(
            'num' => 2,
            'content' => '是否验证了导入的数据或输入的参数的正确性和完整性？',
            'state' => '是'
        ),
        array(
            'num' => 3,
            'content' => '所有输出变量是否都被赋值？',
            'state' => '是'
        ),
        array(
            'num' => 4,
            'content' => '在每个声明中的数据是否被正确操作？',
            'state' => '是'
        ),
        array(
            'num' => 5,
            'content' => '分配的内存空间是否都被释放？',
            'state' => '是'
        ),
        array(
            'num' => 6,
            'content' => '对于外部设备接入是否有超时设计或错误陷阱？',
            'state' => '是'
        ),
        array(
            'num' => 7,
            'content' => '在操作文件是否判断了文件存在与否',
            'state' => '是'
        ),
        array(
            'num' => 8,
            'content' => '在程序结束是所有的文件和设备是否都保持了正确状态？',
            'state' => '是'
        ),
        array(
            'num' => 9,
            'content' => 'Log敏感信息泄露',
            'state' => '是'
        ),
        array(
            'num' => 10,
            'content' => 'https校验错误忽略漏洞',
            'state' => '是'
        ),
        array(
            'num' => 11,
            'content' => 'sql注入漏洞',
            'state' => '是'
        ),
        array(
            'num' => 12,
            'content' => 'https空校验漏洞',
            'state' => '是'
        ),
        array(
            'num' => 13,
            'content' => 'Provider组件暴露漏洞',
            'state' => '是'
        ),
        array(
            'num' => 14,
            'content' => 'Fragment注入漏洞',
            'state' => '是'
        ),
        array(
            'num' => 15,
            'content' => 'WebView远程代码执行',
            'state' => '是'
        ),
        array(
            'num' => 16,
            'content' => 'ContentResolver暴露漏洞',
            'state' => '是'
        ),
        array(
            'num' => 17,
            'content' => 'https通信没有校验服务器证书',
            'state' => '是'
        ),
        array(
            'num' => 18,
            'content' => 'https通信允许所有的服务器证书',
            'state' => '是'
        ),
        array(
            'num' => 19,
            'content' => 'Activity安全漏洞',
            'state' => '是'
        ),
        array(
            'num' => 20,
            'content' => 'Service安全漏洞',
            'state' => '是'
        ),
        array(
            'num' => 21,
            'content' => '使用不安全的加密模式',
            'state' => '是'
        ),
        array(
            'num' => 22,
            'content' => 'Receiver安全漏洞',
            'state' => '是'
        ),
        array(
            'num' => 23,
            'content' => '存在外部可访问的表单',
            'state' => '是'
        ),
        array(
            'num' => 24,
            'content' => '本地代码执行漏洞',
            'state' => '是'
        ),
        array(
            'num' => 25,
            'content' => 'KeyStore风险',
            'state' => '是'
        ),
        array(
            'num' => 26,
            'content' => '外部URL可控的Webview',
            'state' => '是'
        ),
        array(
            'num' => 27,
            'content' => 'Url用户敏感信息泄露',
            'state' => '是'
        ),
        array(
            'num' => 28,
            'content' => '全局可写文件',
            'state' => '是'
        ),
        array(
            'num' => 29,
            'content' => '全局可读文件',
            'state' => '是'
        ),
        array(
            'num' => 30,
            'content' => 'Activity组件暴露风险',
            'state' => '是'
        ),
        array(
            'num' => 31,
            'content' => '私有文件泄露风险',
            'state' => '是'
        ),
        array(
            'num' => 32,
            'content' => '私有配置文件写风险',
            'state' => '是'
        ),
        array(
            'num' => 33,
            'content' => '用户自定义权限滥用风险',
            'state' => '是'
        ),
        array(
            'num' => 34,
            'content' => '外部存储使用风险',
            'state' => '是'
        ),
        array(
            'num' => 35,
            'content' => '广播信息泄露风险',
            'state' => '是'
        ),
        array(
            'num' => 36,
            'content' => 'Intent泄露用户敏感信息',
            'state' => '是'
        ),
        array(
            'num' => 37,
            'content' => '同源绕过漏洞',
            'state' => '是'
        ),
    ),
);
$fulu_table = '';
foreach($fulu_content as $fulu_key => $fulu_value){
    $fulu_list_title = '<tr style="height:25.5pt">
                      <td width="329" colspan="2" style="width:246.4pt;border:solid windowtext 1.0pt;border-left:double windowtext 1.5pt;background:#E6E6E6;padding:0cm 5.4pt 0cm 5.4pt;height:25.5pt">
                            <p class="MsoNormal" align="center" style="margin-top:0cm;margin-right:5.25pt;margin-bottom:0cm;margin-left:5.25pt;margin-bottom:.0001pt;text-align:center"><b><span style="font-family:宋体">'.$fulu_key.'</span></b></p>
                      </td>
                      <td width="85" style="width:63.8pt;border-top:solid windowtext 1.0pt;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:double windowtext 1.5pt;padding:0cm 5.4pt 0cm 5.4pt;height:25.5pt">
                            <p class="MsoNormal" align="center" style="margin-top:0cm;margin-right:5.25pt;margin-bottom:0cm;margin-left:5.25pt;margin-bottom:.0001pt;text-align:center"><span lang="EN-US">&nbsp;</span></p>
                      </td>
                </tr>';
    $fulu_list_content = '';
    foreach($fulu_value as $fuc_key => $fuc_value){
        $fulu_list_content .= '<tr style="height:25.5pt">
                                  <td width="44" style="width:33.0pt;border-top:none;border-left:double windowtext 1.5pt;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;background:#E6E6E6;padding:0cm 5.4pt 0cm 5.4pt;height:25.5pt">
                                        <p class="MsoNormal" align="center" style="margin-top:0cm;margin-right:5.25pt;margin-bottom:0cm;margin-left:5.25pt;margin-bottom:.0001pt;text-align:center"><b><span lang="EN-US">'.$fuc_value['num'].'</span></b></p>
                                  </td>
                                  <td width="285" style="width:213.4pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:25.5pt">
                                        <p class="MsoNormal" align="center" style="margin-top:0cm;margin-right:5.25pt;margin-bottom:0cm;margin-left:5.25pt;margin-bottom:.0001pt;text-align:center"><span style="font-family:宋体">'.$fuc_value['content'].'</span></p>
                                  </td>
                                  <td width="85" style="width:63.8pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:double windowtext 1.5pt;padding:0cm 5.4pt 0cm 5.4pt;height:25.5pt">
                                        <p class="MsoNormal" align="center" style="margin-top:0cm;margin-right:5.25pt;margin-bottom:0cm;margin-left:5.25pt;margin-bottom:.0001pt;text-align:center"><span style="font-family:宋体">'.$fuc_value['state'].'</span></p>
                                  </td>
                            </tr>';
    }
    $fulu_table .= $fulu_list_title.$fulu_list_content;
}

//目录实体内容
$word_content = array(
    '1.' => array(
        'grade' => 1,
        'title' => '概述',
        'content' => '',
    ),
    '1.1' => array(
        'grade' => 2,
        'title' => '项目目的',
        'content' => '&nbsp;&nbsp;此次源代码审计工作针对'."{$_SESSION['word_name']}".'，通过分析当前应用系统的源代码与业务流程，从应用系统结构、安全性等方面，检查其脆弱性和缺陷。在明确当前安全现状和需求的情况下，对下一步的编码安全规范性建设进行指导，保障系统上线后安全稳定运行。以此来展示Bcodecheck代码审计设备的功能与性能，以及匠迪科技在代码审计领域的相关技术。',
    ),
    '1.2' => array(
        'grade' => 2,
        'title' => '项目概述',
        'content' => '&nbsp;&nbsp;<span face="宋体"><span lang="EN-US">&nbsp;</span>本文档即为匠迪科技代码审计团队利用<span lang="EN-US">Bcodecheck</span>代码审计设备在进行代码审计工作完成后所提交的报告资料，用于对<span lang="EN-US" style="color:red">'."{$_SESSION['word_name']}".'</span>的安全状况从代码层面做出分析和建议，以此来展示匠迪科技的代码审计工作的相关情况。</span><br>
&nbsp;&nbsp;本次源代码审计工作主要突出代码编写的缺陷和脆弱性，以OWASP 2013 TOP 10为检查依据，针对OWASP统计的问题作重点检查。',
    ),
    '1.3' => array(
        'grade' => 2,
        'title' => '项目范围',
        'content' => $data_total_number_html,
    ),
    '1.3.1' => array(
        'grade' => 3,
        'title' => '审计过程',
        'content' => '1.准备阶段：<br>
		                &nbsp;&nbsp;源代码审计需求分析，制定源代码审计方案。<br>
                        2.分析阶段：<br>
                                &nbsp;&nbsp;环境部署，专业的Bcodecheck工具扫描。<br>
                        3.人工检查：<br>
                                &nbsp;&nbsp;漏洞分析验证，风险定级，提出修复建议。<br>
                        4.生成报告：<br>
                                &nbsp;&nbsp;报告编写，提交报告。',
    ),
    '1.3.2' => array(
        'grade' => 3,
        'title' => '审计工具',
        'content' => '<table class="MsoNormalTable" border="0" cellspacing="0" cellpadding="0" width="544" style="width:408.0pt;margin-left:-.25pt;border-collapse:collapse;mso-yfti-tbllook:
                         1184;mso-padding-alt:0cm 5.4pt 0cm 5.4pt">
                             <tbody><tr style="mso-yfti-irow:0;mso-yfti-firstrow:yes;height:15.0pt">
                              <td width="84" nowrap="" style="width:63.0pt;border:solid windowtext 1.0pt;
                              mso-border-alt:solid windowtext .5pt;background:#4472C4;padding:0cm 5.4pt 0cm 5.4pt;
                              height:15.0pt">
                              <p class="MsoNormal" align="center" style="text-align:center"><b><span style="font-size:11.0pt;font-family:&quot;微软雅黑&quot;,sans-serif;color:white">审计工具<span lang="EN-US"><o:p></o:p></span></span></b></p>
                              </td>
                              <td width="460" style="width:345.0pt;border:solid windowtext 1.0pt;border-left:
                              none;mso-border-top-alt:solid windowtext .5pt;mso-border-bottom-alt:solid windowtext .5pt;
                              mso-border-right-alt:solid windowtext .5pt;background:#4472C4;padding:0cm 5.4pt 0cm 5.4pt;
                              height:15.0pt">
                              <p class="MsoNormal" align="center" style="text-align:center"><b><span style="color:white">用途和说明<span lang="EN-US"><o:p></o:p></span></span></b></p>
                              </td>
                             </tr>
                             <tr style="mso-yfti-irow:1;mso-yfti-lastrow:yes;height:57.0pt">
                              <td width="84" nowrap="" style="width:63.0pt;border:solid windowtext 1.0pt;
                              border-top:none;mso-border-left-alt:solid windowtext .5pt;mso-border-bottom-alt:
                              solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;background:
                              white;padding:0cm 5.4pt 0cm 5.4pt;height:57.0pt">
                              <p class="MsoNormal" align="center" style="text-align:center"><span lang="EN-US" style="font-size:11.0pt;color:black">Bcodeheck<o:p></o:p></span></p>
                              </td>
                              <td width="460" style="width:345.0pt;border-top:none;border-left:none;
                              border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
                              mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
                              padding:0cm 5.4pt 0cm 5.4pt;height:57.0pt">
                              <p class="MsoNormal" align="center" style="text-align:center"><span style="color:black">匠迪技术开发的静态、白盒应用源代码审计工具，提供<span lang="EN-US">java</span>、<span lang="EN-US">php</span>、<span lang="EN-US">c/c++</span>等<span lang="EN-US">13</span>种代码安全的自动扫描检查。依据<span lang="EN-US">CVE</span>、<span lang="EN-US">OWASP</span>、<span lang="EN-US">SANS</span>等公共漏洞，结合积累的审计规则，发现潜在的安全漏洞问题，帮助企业和个人整改不安全的编码。<span lang="EN-US"><o:p></o:p></span></span></p>
                              </td>
                             </tr>
                            </tbody>
                        </table>',
    ),
    '1.3.3' => array(
        'grade' => 3,
        'title' => '审计方法',
        'content' => ' &nbsp;&nbsp;本次源代码审计采用工具测试和人工测试结合的方法进行，依照OWASP 2013 TOP 10所披露的脆弱性，根据业务流来检查目标系统的脆弱性、缺陷以及结构上的问题。<br>
本次源代码审计分为三个阶段：<br>
①信息收集<br>
 &nbsp;&nbsp;通过匠迪云安全扫描界面载入系统源代码进行信息收集，熟悉待审计源码的结构设计、功能模块，输入输出流，Bcodecheck自动确定审计检查重点。<br>
②代码安全性分析<br>
 &nbsp;&nbsp;使用Bcodecheck对源代码进行静态扫描；对扫描结果进行人工分析、整理，然后根据严重问题对源代码进行人工审计，主要包含以下内容：<br>
 &nbsp;&nbsp;输入/输出验证：SQL注入、跨站脚本、RCE漏洞等未能较好的控制用户提交的内容造成的安全问题。<br>
 &nbsp;&nbsp;安全功能：请求的参数没有限制范围导致信息泄露，Cookie超时机制和有效域控制，权限控制、通信加密、文件权限等方面的内容。<br>
 &nbsp;&nbsp;安全机制：忽略处理的异常、异常处理不恰当造成的信息泄露或是不便于进行错误定位等问题。<br>
③问题验证<br>
 &nbsp;&nbsp;对审计工具扫描的结果进行人工整理、分析，得出初步的风险问题，利用Notepad++载入源代码进行人工查找、分析风险代码的上下文，直观的确定代码中存在的安全问题。<br>
 &nbsp;&nbsp;说明：由于客户对代码的保密性过高，无法对源代码进行动态调试，造成部分安全问题无法验证，只是对安全缺陷代码做详细的分析，可由开发人员参考审计报告进行动态调试。',
    ),
    '2.' => array(
        'grade' => 1,
        'title' => '审计结果',
        'content' => '<p class="MsoNormal" style="text-indent:24.0pt;mso-char-indent-count:2.0">匠迪云安全代码安全审查系统主要通过<b style="mso-bidi-font-weight:normal"><span style="color:red">总览视图</span></b>和<b style="mso-bidi-font-weight:normal"><span style="color:red">分览视图</span></b>，以总分的方式展现本次代码扫描的结果。审计情况概述如下表所示：</p>',
    ),
    '2.1' => array(
        'grade' => 2,
        'title' => '审计结果统计-总览视图',
        'content' =>'<p class="MsoNormal" style="text-indent:24.0pt;mso-char-indent-count:2.0">总览视图主要从整个项目出发，通过文件分类视图、程度分类视图和风险分类是图三个维度介绍了本次代码审计所发现的问题情况。</p><br>'.
                        $_SESSION['audit_results'],
    ),
    '2.2' => array(
        'grade' => 2,
        'title' => '审计结果-分览视图',
        'content' =>$risk_description,
    ),
    '3.' => array(
        'grade' => 1,
        'title' => '审计修复建议',
        'content' => '&nbsp;&nbsp;经过本次代码审计，发现被检测代码存在的一些问题或缺陷，并针对发现的问题提出改进建议，供开发人员、管理人员参考。<br>
                    &nbsp;&nbsp;1.对用户的输入进行验证或过滤，用户的输入主要包括以下几类：<br>
                    &nbsp;&nbsp; a)访问请求中URL的参数部分；<br>
                    &nbsp;&nbsp;b)HTML表单通过POST或GET请求提交的数据；<br>
                    &nbsp;&nbsp;c)在客户端临时保存的数据、文件等；<br>
                    &nbsp;&nbsp;d)数据库查询。<br>
                    &nbsp;&nbsp;2.安全功能方面：<br>
                    &nbsp;&nbsp;a)不要过于信任应用程序访问控制规则。<br>
                    &nbsp;&nbsp;b)身份鉴别系统和会话管理可能会被绕过或是被篡改。<br>
                    &nbsp;&nbsp;c)存储的敏感信息可能被抽取。<br>
                    &nbsp;&nbsp;d)app广播发送的数据安全问题。<br>
                    &nbsp;&nbsp;3.其它：<br>
                    &nbsp;&nbsp;a)服务器：设置应用所在目录的读写权限；<br>
                    &nbsp;&nbsp;b)app服务器软件：不要开启目录浏览、写入、脚本资源访问等功能；<br>
                    &nbsp;&nbsp;c)错误处理：必须关闭详细错误显示，比较好的处理方式是开启错误重定向功能在出错后重定向到指定位置，并且这个位置不能把异常信息发送给客户端展现给用户；<br>
                    &nbsp;&nbsp;d)代码质量：主要是指可用性、可维护性、运行效率、重复代码量等等指标，高质量的代码不仅易于维护，而且运行效率高，因为当受到拒绝服务攻击时可以有效降低对系统的影响。好的代码依赖于合理的系统架构、优秀的程序编写人员和严谨的工作作风；<br>
                    &nbsp;&nbsp;e)系统上线前进行全面的测试；<br>
                    &nbsp;&nbsp;f)制定完善的开发文档。',
    ),
    '4.' => array(
        'grade' => 1,
        'title' => '附录',
        'content' => '<div align="center">
                    <table class="aff4" border="1" cellspacing="0" cellpadding="0" width="414" style="border-collapse:collapse;border:none">
                         <tbody>
                             <tr style="height:25.5pt">
                                  <td width="329" colspan="2" style="width:246.4pt;border-top:double windowtext 1.5pt;border-left:double windowtext 1.5pt;border-bottom:none;border-right:none;background:#D9D9D9;padding:0cm 5.4pt 0cm 5.4pt;height:25.5pt">
                                        <p class="MsoNormal" align="center" style="margin-top:0cm;margin-right:5.25pt;margin-bottom:0cm;margin-left:5.25pt;margin-bottom:.0001pt;text-align:center"><b><span style="font-family:宋体">问题</span></b></p>
                                  </td>
                                  <td width="85" style="width:63.8pt;border-top:double windowtext 1.5pt;border-left:none;border-bottom:none;border-right:double windowtext 1.5pt;background:#D9D9D9;padding:0cm 5.4pt 0cm 5.4pt;height:25.5pt">
                                        <p class="MsoNormal" align="center" style="margin-top:0cm;margin-right:5.25pt;margin-bottom:0cm;margin-left:5.25pt;margin-bottom:.0001pt;text-align:center"><b><span style="font-family:宋体">是</span><span lang="EN-US">/</span></b><b><span style="font-family:宋体">否</span></b></p>
                                  </td>
                             </tr>
                             '.$fulu_table.'
                        </tbody>
                        </table>
                    </div>
                    <p class="western" style="margin-bottom: 0in; line-height: 0.17in">
                    <font face="宋体"><span lang="zh-CN">注：“是”代表安全，“否”代表不安全。<br>
                        名词解释：<br>
                        A.CVE 的英文全称是“Common Vulnerabilities & Exposures”公共漏洞和暴露。CVE就好像是一个字典表，为广泛认同的信息安全漏洞或者已经暴露出来的弱点给出一个公共的名称。<br>
                        B.OWASP开放式Web应用程序安全项目<br>
                        C.SANS中国漏洞信息库
                        </span></font>
                    </p>',
    ),


);
<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html  lang="en">
<head>
    <title>匠迪云</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="">
    <meta name="description" content="">

</head>
<body>
QQQQ
<?php echo ($project_fw); ?>
<?php echo ($project_fw['task_name']); ?>
/Public
<!--<div class=WordSection1>
    &lt;!&ndash;word首页标题信息&ndash;&gt;
    <p align=center style='text-align:center'><span lang=EN-US style='font-size:30.0pt'>121</span></p>
    <p align=center style='text-align:center'><span style='font-size:30.0pt'>代码审计报告</span></p>
    <div style='margin-top:225.0pt;margin-bottom:112.5pt'>
        <p align=center style='text-align:center'>
                <span lang=EN-US>
                <span style='mso-no-proof:yes'>
                    <img width=147 height=37 id="_x0000_i1064" src="http://10.1.1.140/images/logo.png" alt="http://10.1.1.140/images/logo.png">
                </span>
                </span>
        </p>
        <p align=center style='text-align:center'><span style='font-size:18.0pt'>北京匠迪技术有限公司</span></p>
        <p class=MsoNormal align=center style='text-align:center'>
            <b><span lang=EN-US style='font-size:16.0pt'>2017</span></b>
            <span lang=EN-US> </span>
            <b><span style='font-size:16.0pt'>年</span></b>
            <b><span lang=EN-US style='font-size:16.0pt'>08</span></b><span lang=EN-US> </span>
            <b><span style='font-size:16.0pt'>月</span></b>
            <span lang=EN-US><o:p></o:p></span></p>
    </div>
    &lt;!&ndash;end&ndash;&gt;
    &lt;!&ndash;概述&ndash;&gt;
    <div>
        <h1><span face=宋体><span lang=EN-US style='font-size:22.0pt'>1.</span></span>
            <span face=宋体><span style='font-size:22.0pt'>概述 <span lang=EN-US>&nbsp;</span></span></span><span lang=EN-US><o:p></o:p></span>
        </h1>
    </div>
    <div>
        <h2><span lang=EN-US style='font-size:16.0pt'><span face=宋体>1.1</span></span>
            <span face=宋体><span style='font-size:16.0pt'>项目目的 <span lang=EN-US>&nbsp;</span></span></span><span lang=EN-US><o:p></o:p></span>
        </h2>
        <p style='line-height:150%'>
                <span face=宋体><span lang=EN-US>&nbsp;&nbsp;</span>此次源代码审计工作针对<span lang=EN-US>121</span>
                    ，通过分析当前应用系统的源代码与业务流程，从应用系统结构、安全性等方面，检查其脆弱性和缺陷。在明确当前安全现状和需求的情况下，
                    对下一步的编码安全规范性建设进行指导，保障系统上线后安全稳定运行。以此展示<span lang=EN-US>Bcodecheck</span>代码审计设备的
                    功能与性能，以及匠迪科技在代码审计领域的相关技术。 </span><span lang=EN-US><o:p></o:p>
                </span>
        </p>
    </div>
    <div>
        <h2><span face=宋体><span lang=EN-US style='font-size:16.0pt'>1.2</span></span>
            <span face=宋体><span style='font-size:16.0pt'>项目概述 <span lang=EN-US>&nbsp;</span></span></span><span lang=EN-US><o:p></o:p></span>
        </h2>
        <p style='line-height:150%'>
                <span face=宋体><span lang=EN-US>&nbsp;&nbsp;<span face=宋体>&nbsp;</span>
                本文档即为匠迪科技代码审计团队利用<span lang=EN-US>Bcodecheck</span>代码审计设备在进行代码审计工作完成后所提交的报告资料，用于对<span
                            lang=EN-US style='color:red'>121</span>的安全状况从代码层面做出分析和建议，以此来展示匠迪科技的代码审计工作的相关情况。</span><span
                        lang=EN-US><br>&nbsp;&nbsp;</span>本次源代码审计工作主要突出代码编写的缺陷和脆弱性，以<span lang=EN-US>OWASP TOP 10</span>为检查依据，针对<span
                        lang=EN-US>OWASP</span>统计的问题作重点检查。 </span><span lang=EN-US><o:p></o:p></span>
        </p>
    </div>
    <div>
        <h2><span face=宋体><span lang=EN-US style='font-size:16.0pt'>1.3</span></span><span
                face=宋体><span style='font-size:16.0pt'>项目范围 <span lang=EN-US>&nbsp;</span></span></span><span
                lang=EN-US><o:p></o:p></span>
        </h2>

        <table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0 width="100%"
               style='width:100.0%;border-collapse:collapse;mso-yfti-tbllook:1184;mso-padding-alt:0cm 5.4pt 0cm 5.4pt'>
            <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes;height:18.0pt'>
                <td width="100%" nowrap colspan=2 style='width:100.0%;border:solid windowtext 1.0pt;
                    mso-border-alt:solid windowtext .5pt;background:#4472C4;padding:0cm 5.4pt 0cm 5.4pt;height:18.0pt'>
                    <p class=MsoNormal align=center style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto;text-align:center'>
                            <span face=宋体><b><span style='font-family:"微软雅黑",sans-serif;color:white'>项目基本信息表</span></b><span lang=EN-US><u2:p></u2:p></span>
                    </p>
                </td>
            </tr>
            <tr style='mso-yfti-irow:1;height:18.0pt'>
                <td width="58%" nowrap style='width:58.44%;border:solid windowtext 1.0pt;border-top:none;mso-border-left-alt:solid windowtext .5pt;
                    mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;background:#4472C4;padding:0cm 5.4pt 0cm 5.4pt;height:18.0pt'>
                    <p class=MsoNormal align=center style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto;text-align:center'><b>
                        <span style='font-family:"微软雅黑",sans-serif;color:white'>扫描代码名称</span></b><span lang=EN-US><u2:p></u2:p></span>
                    </p>
                </td>
                <td width="41%" nowrap style='width:41.56%;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
                    mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:18.0pt'>
                    <p class=MsoNormal align=center style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto;text-align:center'>
                        <span lang=EN-US style='font-size:11.0pt;font-family:等线;color:black'><?php echo ($project_fw["task_name"]); ?><u2:p></u2:p></span>
                    </p>
                </td>
            </tr>
            <tr style="mso-yfti-irow:2;height:18.0pt">
                <td width="58%" nowrap="" style="width:58.44%;border:solid windowtext 1.0pt;
                              border-top:none;mso-border-left-alt:solid windowtext .5pt;mso-border-bottom-alt:
                              solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;background:
                              #4472C4;padding:0cm 5.4pt 0cm 5.4pt;height:18.0pt">
                    <p class="MsoNormal" align="center" style="text-align:center"><b><span style="font-family:&quot;微软雅黑&quot;,sans-serif;color:white">问题代码文件类型<span lang="EN-US"><o:p></o:p></span></span></b></p>
                </td>
                <td width="41%" nowrap="" style="width:41.56%;border-top:none;border-left:none;
                              border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
                              mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
                              padding:0cm 5.4pt 0cm 5.4pt;height:18.0pt">
                    <p class="MsoNormal" align="center" style="text-align:center"><span lang="EN-US" style="color:black"><?php echo ($project_fw["leak_file_type"]); ?><o:p></o:p></span></p>
                </td>
            </tr>
            <tr style="mso-yfti-irow:3;height:18.0pt">
                <td width="58%" nowrap="" style="width:58.44%;border:solid windowtext 1.0pt;
                              border-top:none;mso-border-left-alt:solid windowtext .5pt;mso-border-bottom-alt:
                              solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;background:
                              #4472C4;padding:0cm 5.4pt 0cm 5.4pt;height:18.0pt">
                    <p class="MsoNormal" align="center" style="text-align:center"><b><span style="font-family:&quot;微软雅黑&quot;,sans-serif;color:white">代码总行数<span lang="EN-US"><o:p></o:p></span></span></b></p>
                </td>
                <td width="41%" nowrap="" style="width:41.56%;border-top:none;border-left:none;
                              border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
                              mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
                              padding:0cm 5.4pt 0cm 5.4pt;height:18.0pt">
                    <p class="MsoNormal" align="center" style="text-align:center"><span lang="EN-US" style="color:black"><?php echo ($project_fw["code_line_num"]); ?><o:p></o:p></span></p>
                </td>
            </tr>
            <tr style="mso-yfti-irow:4;mso-yfti-lastrow:yes;height:18.0pt">
                <td width="58%" nowrap="" style="width:58.44%;border:solid windowtext 1.0pt;
                              border-top:none;mso-border-left-alt:solid windowtext .5pt;mso-border-bottom-alt:
                              solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;background:
                              #4472C4;padding:0cm 5.4pt 0cm 5.4pt;height:18.0pt">
                    <p class="MsoNormal" align="center" style="text-align:center"><b><span style="font-family:&quot;微软雅黑&quot;,sans-serif;color:white">发现问题总数<span lang="EN-US"><o:p></o:p></span></span></b></p>
                </td>
                <td width="41%" nowrap="" style="width:41.56%;border-top:none;border-left:none;
                              border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
                              mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
                              padding:0cm 5.4pt 0cm 5.4pt;height:18.0pt">
                    <p class="MsoNormal" align="center" style="text-align:center"><span lang="EN-US" style="color:black"><?php echo ($project_fw["leak_num"]); ?></span><span style="color:black">个<span lang="EN-US"><o:p></o:p></span></span></p>
                </td>
            </tr>
        </table>
    </div>
    <div>
        <h3><span lang=EN-US style='font-size:16.0pt'>1.3.1</span></span>
            <span face=宋体><span style='font-size:16.0pt'>审计过程 <span lang=EN-US>&nbsp;</span></span></span><span lang=EN-US><o:p></o:p></span>
        </h3>
        <p style='line-height:150%'><span face=宋体><span lang=EN-US>1.</span>准备阶段：<span lang=EN-US><br>&nbsp;&nbsp;</span>源代码审计需求分析，制定源代码审计方案。
                <span lang=EN-US><br>
                    2.</span>分析阶段：<span lang=EN-US><br>&nbsp;&nbsp;</span>环境部署，专业的<span lang=EN-US>Bcodecheck</span>工具扫描。<span
                    lang=EN-US><br>
                    3.</span>人工检查：<span lang=EN-US><br>
                    &nbsp;&nbsp;</span>漏洞分析验证，风险定级，提出修复建议。<span lang=EN-US><br>
                    4.</span>生成报告：<span lang=EN-US><br>
                    &nbsp;&nbsp;</span>报告编写，提交报告。 </span><span lang=EN-US><o:p></o:p></span>
        </p>
    </div>

    <div>
        <h3><span face=宋体><span lang=EN-US style='font-size:16.0pt'>1.3.2</span></span><span
                face=宋体><span style='font-size:16.0pt'>审计工具 <span lang=EN-US>&nbsp;</span></span></span><span
                lang=EN-US><o:p></o:p></span>
        </h3>

        <table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0 width=544
               style='width:408.0pt;margin-left:-.25pt;border-collapse:collapse;mso-yfti-tbllook:1184;mso-padding-alt:0cm 5.4pt 0cm 5.4pt'>
            <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes;height:15.0pt'>
                <td width=84 nowrap style='width:63.0pt;border:solid windowtext 1.0pt;mso-border-alt:solid windowtext .5pt;
                background:#4472C4;padding:0cm 5.4pt 0cm 5.4pt;height:15.0pt'>
                    <p class=MsoNormal align=center style='mso-margin-top-alt:auto;mso-margin-bottom-alt:
                    auto;text-align:center'><span face=宋体><b><span style='font-size:11.0pt;
                    font-family:"微软雅黑",sans-serif;color:white'>审计工具</span></b><span lang=EN-US><u2:p></u2:p></span></p>
                </td>
                <td width=460 style='width:345.0pt;border:solid windowtext 1.0pt;border-left:none;
                mso-border-top-alt:solid windowtext .5pt;mso-border-bottom-alt:solid windowtext .5pt;
                mso-border-right-alt:solid windowtext .5pt;background:#4472C4;padding:0cm 5.4pt 0cm 5.4pt;height:15.0pt'>
                    <p class=MsoNormal align=center style='mso-margin-top-alt:auto;mso-margin-bottom-alt:
                    auto;text-align:center'><b><span style='color:white'>用途和说明</span></b><span lang=EN-US><u2:p></u2:p></span>
                    </p>
                </td>
            </tr>
            <tr style='mso-yfti-irow:1;mso-yfti-lastrow:yes;height:57.0pt'>
                <td width=84 nowrap style='width:63.0pt;border:solid windowtext 1.0pt;
                border-top:none;mso-border-left-alt:solid windowtext .5pt;mso-border-bottom-alt:
                solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;background:
                white;padding:0cm 5.4pt 0cm 5.4pt;height:57.0pt'>
                    <p class=MsoNormal align=center style='mso-margin-top-alt:auto;mso-margin-bottom-alt:
                    auto;text-align:center'><span lang=EN-US style='font-size:11.0pt;color:black'>Bcodeheck<u2:p></u2:p></span>
                    </p>
                </td>
                <td width=460 style='width:345.0pt;border-top:none;border-left:none;
                border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
                mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
                padding:0cm 5.4pt 0cm 5.4pt;height:57.0pt'>
                    <p class=MsoNormal align=center style='mso-margin-top-alt:auto;mso-margin-bottom-alt:
                            auto;text-align:center'><span style='color:black'>匠迪技术开发的静态、白盒应用源代码审计工具，提供<span
                            lang=EN-US>java</span>、<span lang=EN-US>php</span>、<span lang=EN-US>c/c++</span>等<span
                            lang=EN-US>13</span>种代码安全的自动扫描检查。依据<span lang=EN-US>CVE</span>、<span
                            lang=EN-US>OWASP</span>、<span lang=EN-US>SANS</span>等公共漏洞，结合积累的审计规则，发现潜在的安全漏洞问题，帮助企业和个人整改不安全的编码。</span><span
                            lang=EN-US><u2:p></u2:p></span></p>
                </td>
            </tr>
        </table>
    </div>
    <div>

        <h3><span lang=EN-US style='font-size:16.0pt'>1.3.3</span></span><span face=宋体><span
                style='font-size:16.0pt'>审计方法 <span lang=EN-US>&nbsp;</span></span></span><span
                lang=EN-US><o:p></o:p></span></h3>

        <p style='line-height:150%'><span face=宋体><span lang=EN-US>&nbsp;&nbsp;</span>本次源代码审计采用工具测试和人工测试结合的方法进行，依照<span
                lang=EN-US>OWASP TOP 10</span>所披露的脆弱性，根据业务流来检查目标系统的脆弱性、缺陷以及结构上的问题。<span
                lang=EN-US><br>
                </span>本次源代码审计分为三个阶段：<span lang=EN-US><br>
                ①</span>信息收集<span lang=EN-US><br>
                &nbsp;&nbsp;</span>匠迪<span lang=EN-US>Bcodecheck</span>通过源代码安全扫描界面上传业务系统源代码进行信息收集。<span
                lang=EN-US>Bcodecheck</span>会自动分析待审计源码的结构设计、功能模块，输入输出流，以及确定审计检查重点。<span
                lang=EN-US><br>
                ②</span>代码安全性分析<span lang=EN-US><br>
                &nbsp;&nbsp;</span>使用<span lang=EN-US>Bcodecheck</span>对源代码进行静态扫描；对扫描结果进行人工确认，其中暴露出来的高危问题将对源代码进行人工审计，主要包含以下内容：<span
                lang=EN-US><br>
                &nbsp;&nbsp;</span><span lang=EN-US style='font-family:Symbol;mso-ascii-font-family:
                宋体;mso-hansi-font-family:宋体;mso-char-type:symbol;mso-symbol-font-family:Symbol'><span
                style='mso-char-type:symbol;mso-symbol-font-family:Symbol'>ü</span></span>输入<span
                lang=EN-US>/</span>输出验证：<span lang=EN-US>SQL</span>注入、跨站脚本、<span lang=EN-US>RCE</span>漏洞等未能较好的控制用户提交的内容造成的安全问题。<span
                lang=EN-US><br>
                &nbsp;&nbsp;</span><span lang=EN-US style='font-family:Symbol;mso-ascii-font-family:
                宋体;mso-hansi-font-family:宋体;mso-char-type:symbol;mso-symbol-font-family:Symbol'><span
                style='mso-char-type:symbol;mso-symbol-font-family:Symbol'>ü</span></span>安全功能：请求的参数没有限制范围导致信息泄露，<span
                lang=EN-US>Cookie</span>超时机制和有效域控制，权限控制、通信加密、文件权限等方面的内容。<span lang=EN-US><br>
                &nbsp;&nbsp;</span><span lang=EN-US style='font-family:Symbol;mso-ascii-font-family:
                宋体;mso-hansi-font-family:宋体;mso-char-type:symbol;mso-symbol-font-family:Symbol'><span
                style='mso-char-type:symbol;mso-symbol-font-family:Symbol'>ü</span></span>安全机制：忽略处理的异常、异常处理不恰当造成的信息泄露或是不便于进行错误定位等问题。<span
                lang=EN-US><br>
                ③</span>问题验证<span lang=EN-US><br>
                &nbsp;&nbsp;</span>对审计工具扫描的结果进行人工整理、分析，得出初步的风险问题，利用<span lang=EN-US>Bcodecheck</span>的在线审计工作台载入问题源代码进行人工查找、分析风险代码的上下文，直观的确定代码中存在的安全问题。<span
                lang=EN-US><br>
                &nbsp;&nbsp;</span>说明：由于客户源代码的实际运行环境，无法对源代码进行动态调试，造成部分安全问题无法验证，我们只是对安全缺陷代码做详细的分析，部分问题可由开发人员参考审计报告进行动态调试。
                </span><span lang=EN-US><o:p></o:p></span>
        </p>
    </div>
    &lt;!&ndash;审计结果&ndash;&gt;
    <div>
        <h1><span face=宋体><span lang=EN-US style='font-size:22.0pt'>2.</span></span><span
                face=宋体><span style='font-size:22.0pt'>审计结果 <span lang=EN-US>&nbsp;</span></span></span><span
                lang=EN-US><o:p></o:p></span>
        </h1>
        <p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto;
                text-indent:24.0pt;mso-char-indent-count:2.0;line-height:150%'><span face=宋体>匠迪源代码安全审查系统主要通过<b
                style='mso-bidi-font-weight:normal'><span style='color:red'>总览视图</span></b>和<b
                style='mso-bidi-font-weight:normal'><span style='color:red'>分览视图</span></b>，以总分的方式展现本次代码扫描的结果。审计情况概述如下表所示：</span>
        </p>
    </div>
    &lt;!&ndash;总览视图&ndash;&gt;
    <div>
        <h2 style='line-height:150%'><span face=宋体><span lang=EN-US style='font-size:
            16.0pt;line-height:150%'>2.1</span></span><span face=宋体><span style='font-size:
            16.0pt;line-height:150%'>总览视图 <span lang=EN-US>&nbsp;</span></span></span><span
                lang=EN-US><o:p></o:p></span>
        </h2>


    </div>



</div>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js" ></script>
<script type="text/javascript" src="/Public/Js/FileSaver.js"></script>
<script type="text/javascript">
    //主体函数，即将内容加入到word中
    function wordExport(fileName) {
         fileName = typeof fileName !== 'undefined' ? fileName : "导出";
            var body = $('html').html();
            var blob = new Blob([body], {
                type: "application/msword;charset=utf-8"
            });
         saveAs(blob, fileName + ".doc");
         }
       // wordExport('docName');

</script>-->
</body>
</html>
#!/usr/bin/python
# -- coding:utf-8 --
import json as jsoner
from codescan import *
from externalscan import *
from fsb import *
from gitrob import *
import log
import code as c

import sys;
sys.path.append("../")
from config import execute_db,fetchall_db

rulepacks = ['common', 'android', 'php', 'actionscript','asp','c','csharp','ruby','ObjectiveC']
plugin_rulepacks = ['fsb_android', 'fsb_injection', 'fsb_crypto', 'fsb_endpoint', 'fsb_privacy', 'gitrob']

json = {
    "scan_info": {
        "app_path": "",
        "security_warnings": "",
        "start_time": "",
        "end_time": "",
        "duration": "",
        "version": "0.0.1"
        },
    "warnings": [],
    "ignored_warnings": [],
    "errors": [],
    }

def issue_ignored(snippet, rulepack):
    ignored_issue_flag = False
    snippet = snippet.strip()
    with codecs.open('rules/ignore_list.rulepack', 'r', encoding='utf8') as f:
        ignored_rulepack = jsoner.loads(f.read())

    ignored_plugins = ignored_rulepack['plugins']

    for ignored_plugin in ignored_plugins:
        if ignored_plugin['name'] == rulepack:
            ignored_plugin_index = ignored_plugins.index(ignored_plugin)
            ignored_patterns = ignored_plugins[ignored_plugin_index]['patterns']
            for ignored_pattern in ignored_patterns:
                if ignored_pattern['match_type'] == 'regex':
                    try:
                        ignored_regex_raw = base64.b64decode(ignored_pattern['value'])
                    except:
                        ignored_regex_raw = ignored_pattern['value']
                    ignored_regex_compiled = re.compile(ignored_regex_raw, re.IGNORECASE)
                    if ignored_regex_compiled.search(snippet):
                        ignored_issue_flag = True
                elif ignored_pattern['match_type'] == 'start':
                    if snippet.startswith(ignored_pattern['value']):
                        ignored_issue_flag = True
                elif ignored_pattern['match_type'] == 'end':
                    if snippet.endswith(ignored_pattern['value']):
                        ignored_issue_flag = True
    return ignored_issue_flag

def scan_all(scan_path, repo_path, repo_type, task_name, upinfo_id, sumid):
    counter_start = time.clock()
    results = []

    start_time = time.strftime("%a, %d %b %Y %I:%M:%S %p", time.localtime())
    all_file = c.file_all(os.path.abspath(scan_path))
    c.explore(os.path.abspath(scan_path),all_file,upinfo_id,sumid)

    i = 5
    for rulepack in rulepacks:
        #规则匹配
        rule_path = 'rules/%s.rulepack' % rulepack
        report_path = scan_path + '/%s_report.json' % rulepack
        log.logger.debug('scanning with [%s] rulepack' % (rulepack))
        result = Scanner(scan_path, rule_path, report_path, all_file*len(rulepacks))
        # import pdb
        # pdb.set_trace()
        #文件扫描
        i = i+1
        per = i * 50 / len(rulepacks)
        if per < 100:
            scan_sped = str(per)+"%"
            sql = '''UPDATE obsec_scan_summary SET scan_sped="%s" WHERE id="%s" AND upinfo_id="%s";''' % \
                  (scan_sped,sumid,upinfo_id)
            execute_db(sql)
        else:
            pass

        if len(result.issues) > 0:
            for issue in result.issues:
                if not issue_ignored(issue['code'], issue['plugin']):
                    results.append(issue)

    log.logger.debug("scanning with [gitrob] plugin")
    for rulepack in plugin_rulepacks:
        if rulepack.startswith('gitrob'):
            rule_path = 'rules/%s.rulepack' % rulepack
            gitrob_results = gitrob_scan(scan_path, rule_path)

            if len(gitrob_results) > 0:
                for issue in gitrob_results:
                    results.append(issue)

    log.logger.debug("scanning with [fsb] plugin")
    for rulepack in plugin_rulepacks:
        if rulepack.startswith('fsb_'):
            rule_path = 'rules/%s.rulepack' % rulepack
            fsb_results = fsb_scan(scan_path, rule_path)

            if len(fsb_results) > 0:
                for issue in fsb_results:
                    results.append(issue)

    log.logger.debug("scanning with [scanjs] plugin")
    js_results = scanjs(scan_path)
    if len(js_results) > 0 and js_results != 'error':
        for js_issue in js_results:
            results.append(js_issue)

    log.logger.debug("scanning with [brakeman] plugin")
    ror_results = recur_scan_brakeman(scan_path)
    if len(ror_results) > 0 and ror_results != 'error':
        for ror_result in ror_results:
            results.append(ror_result)

    log.logger.debug("scanning with [rips] plugin")
    php_results = scan_phprips(scan_path)
    if len(php_results) > 0 and php_results != 'error':
        for php_result in php_results:
            results.append(php_result)

    file_types = []
    waring_types = []
    if len(results) > 0:
        l = 0
        for result in results:
            code = result["code"]#漏洞片段
            if result["severity"] == "高":
                severity = 1
            elif result["severity"] == "中":
                severity = 2
            elif result["severity"] == "低":
                severity = 3
            else:
                severity = 4

            waring_type = result["warning_type"]#漏洞名称
            waring_types.append(waring_type)

            file = result["file"]#文件路径
            if file.split('/')[0:7] == scan_path.split('/'):
                f = file.split('/')[7:]
                new_file = '/'.join(f)
            elif file[0] == '/':
                new_file = file[1:]
            else:
                new_file = file

            file_n = os.path.splitext(file)[1]
            if file_n:
                file_type = file_n.split('.')[1]#文件后缀
            else:
                file_type = ''
            file_types.append(file_type)

            line = result["line"]#漏洞行号
            message = result["message"]#缺陷描述
            link = result["link"]#修改建议
            rem = result['remediation']  # 参考规范

            sql = '''INSERT INTO obsec_scan_data (summary_id,leak_name,file_type_name,leak_grade,leak_file_pos,
    code_part,leak_defect_des,leak_modify_sug,leak_line_num,remediation) VALUES ("%s","%s","%s","%s","%s",concat("%s"),concat("%s"),
    concat("%s"),"%s",concat("%s"))''' % (int(sumid), waring_type, file_type, severity, new_file, code, message, link, line,rem)
            execute_db(sql)

            l = l + 1
            per = i * 90 / len(results)
            if per < 100 and per > 77:
                scan_sped = str(per) + "%"
                sql = '''UPDATE obsec_scan_summary SET scan_sped="%s" WHERE id="%s" AND upinfo_id="%s";''' % \
                      (scan_sped, sumid, upinfo_id)
                execute_db(sql)
            else:
                pass

    # 统计源码总行数
    if os.path.exists(os.path.dirname(scan_path)):
        cou = os.popen('cloc %s'% scan_path+'.zip').read()
        if int(cou.split()[0]) == 0:
            count = 0
        else:
            count = int(cou.split()[-2])

    counter_end = time.clock()
    file_type = list(set(file_types))
    new_file_type = [x for x in file_type if x != '']
    waring_type = len(list(set(waring_types)))

    sql = '''SELECT count(*) from obsec_scan_data WHERE summary_id=%s;''' % int(sumid)
    sql_total = fetchall_db(sql)
    total_issues = int(sql_total[0][0])

    json["scan_info"]["app_path"] = repo_path
    json["scan_info"]["repo_type"] = repo_type
    json["scan_info"]["security_warnings"] = count
    json["scan_info"]["start_time"] = start_time
    json["scan_info"]["end_time"] = time.strftime("%a, %d %b %Y %I:%M:%S %p", time.localtime())
    json["scan_info"]["duration"] = str(counter_end - counter_start)
    json["scan_info"]["version"] = "0.0.1"
    json["warnings"] = results#漏洞信息
    json["warnings_type"] = waring_type
    json["file_type_total"] = new_file_type
    json["leak_lines"] = total_issues
    json["ignored_warnings"] = ""
    json["errors"] = ""
    return json

def scan_zip(upload_id, zip_name, file_path,userid,summ_id):
    log.logger.debug("==============New Scan: [zip] ===================")
    extracted_path = os.path.abspath(file_path)
    repo_type = "zip"
    if os.path.exists(extracted_path):
        log.logger.debug("Now scanning: %s" % zip_name)
        results = scan_all(extracted_path,upload_id,repo_type,zip_name,userid,summ_id)
        log.logger.debug("Scan complete! Deleting ...")
        return results

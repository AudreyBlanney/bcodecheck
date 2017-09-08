#!/usr/bin/python
# -- coding:utf-8 --
import os, sys, hashlib, shutil, codecs, re, base64
import pygit2 as git
import json as jsoner
from codescan import *
from externalscan import *
from fsb import *
from gitrob import *
import log
import code

rulepacks = ['common', 'android', 'php', 'actionscript','asp','c','csharp','ruby','ObjectiveC']
plugin_rulepacks = ['fsb_android', 'fsb_injection', 'fsb_crypto', 'fsb_endpoint', 'fsb_privacy', 'gitrob']

data = {
     "json": {
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
    },

    #进度条json
    "pro_json":{
        "name":"",
        "progress":"",
        "status":"3",
    },
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

def scan_all(scan_path, repo_path, repo_type,report_dir,report_enter):
    counter_start = time.clock()
    
    results = []
    js_results = []
    ror_results = []
    php_results = []
    fsb_results = []
    total_issues = 0

    report_dir = report_dir.split('/')[5]

    start_time = time.strftime("%a, %d %b %Y %I:%M:%S %p", time.localtime())
    all_file = code.all_file(os.path.abspath(scan_path))
    code.explore(os.path.abspath(scan_path),all_file,report_enter,report_dir)

    i = 5
    for rulepack in rulepacks:
        #规则匹配
        rule_path = 'rules/%s.rulepack' % rulepack
        report_path = scan_path + '/%s_report.json' % rulepack
        log.logger.debug('scanning with [%s] rulepack' % (rulepack))
        result  = Scanner(scan_path, rule_path, report_path,all_file*len(rulepacks))

        #文件扫描
        i =i+1
        per = i *60 / len(rulepacks)
        if per<100:
            data["pro_json"]["progress"] = str(per)+"%"
            data["pro_json"]["name"]=str(report_dir)
            data["pro_json"]["status"]="3"
            js = jsoner.dumps(data["pro_json"])
            a = open(report_enter, 'w')
            a.write(js)
            a.close()
        else:
            pass

        if len(result.issues) > 0:
            for issue in result.issues:
                #print issue['code'], issue['plugin'], issue_ignored(issue['code'], issue['plugin'])
                if not issue_ignored(issue['code'], issue['plugin']):
                    results.append(issue)
                    total_issues += 1

    log.logger.debug("scanning with [gitrob] plugin")
    for rulepack in plugin_rulepacks:
        if rulepack.startswith('gitrob'):
            rule_path = 'rules/%s.rulepack' % rulepack
            gitrob_results = gitrob_scan(scan_path, rule_path)

            if len(gitrob_results) > 0:
                for issue in gitrob_results:
                    results.append(issue)
                    total_issues += 1

    log.logger.debug("scanning with [fsb] plugin")
    for rulepack in plugin_rulepacks:
        if rulepack.startswith('fsb_'):
            rule_path = 'rules/%s.rulepack' % rulepack
            fsb_results = fsb_scan(scan_path, rule_path)

            if len(fsb_results) > 0:
                for issue in fsb_results:
                    results.append(issue)
                    total_issues += 1

    log.logger.debug("scanning with [scanjs] plugin")
    js_results = scanjs(scan_path)
    if len(js_results) > 0 and js_results != 'error':
        for js_issue in js_results:
            results.append(js_issue)
            total_issues += 1
    
    log.logger.debug("scanning with [brakeman] plugin")
    ror_results = recur_scan_brakeman(scan_path)
    if len(ror_results) > 0 and ror_results != 'error':
        for ror_result in ror_results:
            results.append(ror_result)
            total_issues += 1

    log.logger.debug("scanning with [rips] plugin")
    php_results = scan_phprips(scan_path)
    if len(php_results) > 0 and php_results != 'error':
        for php_result in php_results:
            results.append(php_result)
            total_issues += 1

   # if repo_path[-4:len(repo_path)] == '.zip':
   #     for result in results:
   #         result['file'] = result['file'].replace(repo_path.rstrip('.zip'), repo_path)
   # f = open('raptor/num.txt','r')
   # lin = f.read()
   # f.close()
   # f=open('raptor/num.txt','w')
   # f.write('0')
    # 统计源码总行数
    if os.path.exists(os.path.dirname(scan_path)):
        cou = os.popen('cloc %s'% scan_path).read()
        count = int(cou.split()[-2])
    else:
        dirzip = scan_path+'.zip'
        cou = os.popen('cloc %s' % dirzip).read()
        count = int(cou.split()[-2])

    total_issues = str(total_issues)+"(共计%s行)"%count
    counter_end = time.clock()
    data["json"]["scan_info"]["app_path"] = repo_path
    data["json"]["scan_info"]["repo_type"] = repo_type
    data["json"]["scan_info"]["security_warnings"] = total_issues
    data["json"]["scan_info"]["start_time"] = start_time
    data["json"]["scan_info"]["end_time"] = time.strftime("%a, %d %b %Y %I:%M:%S %p", time.localtime())
    data["json"]["scan_info"]["duration"] = str(counter_end - counter_start)
    data["json"]["scan_info"]["version"] = "0.0.1"
    data["json"]["warnings"] = results
    data["json"]["ignored_warnings"] = ""
    data["json"]["errors"] = ""
    return data

def clone(repo_name, internal):
    clone_directory = os.environ['git_clone_dir']
    uniq_path = hashlib.sha224(repo_name).hexdigest()
    
    if os.path.isdir(os.path.join(clone_directory, uniq_path)):
        shutil.rmtree(os.path.join(clone_directory, uniq_path))

    if internal:
        repo_url = '%s/%s.git' % (os.environ['int_git_url'], repo_name)
    else:
        repo_url = '%s/%s.git' % (os.environ['ext_git_url'], repo_name)

    try:
        clone_dir = clone_directory
        if not os.path.isdir(clone_dir):
            os.makedirs(clone_dir)
        repo_path = os.path.join(clone_dir, uniq_path)

        if internal==True:
            username = os.environ['int_git_user']
            password = os.environ['int_git_token']
            login_info = git.UserPass(username, password)
            git_obj = git.clone_repository(repo_url, repo_path, credentials=login_info)
        else:
            #username = os.environ['ext_git_user']
            #password = os.environ['ext_git_token']
            git_obj = git.clone_repository(repo_url, repo_path)

        return repo_path
    except Exception, e:
        if str(e).find('Unexpected HTTP status code: 404'):
            log.logger.error("repo doesn't exists")
            return "Repo doesn't exists"
        log.logger.error(e)

#def delete_residue(path, report_files):
    #shutil.rmtree(path)

def start(repo_path, report_dir, internal):
    log.logger.debug("==============New Scan: [github] ===================")
    log.logger.debug("Now cloning: %s" % (repo_path))
    cloned_path = clone(repo_path, internal)
    if internal:
        repo_type = "internal"
    else:
        repo_type = "external"
    if os.path.isdir(cloned_path):
        log.logger.debug("[INFO] Now scanning: %s" % repo_path)
        results = scan_all(cloned_path, repo_path, repo_type)
        log.logger.debug("[INFO] Scan complete! Deleting ...")
        delete_residue(cloned_path, rulepacks)
        return results

def scan_zip(upload_id, zip_name, report_dir,report_enter):
    log.logger.debug("==============New Scan: [zip] ===================")
    extracted_path = os.path.join(os.path.abspath(os.environ['zip_upload_dir']), upload_id)
    repo_type = "zip"
    if os.path.exists(extracted_path):
        log.logger.debug("Now scanning: %s" % zip_name)
        results = scan_all(extracted_path, zip_name, repo_type,report_dir,report_enter)
        log.logger.debug("Scan complete! Deleting ...")
        #delete_residue(extracted_path, zip_name)
        return results

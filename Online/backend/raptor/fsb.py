#!/usr/bin/python
# -- coding:utf-8 --
'''

INFO
----
This module is roughly based on the logic of the widely acclaimed FindSecurityBugs plugin (authored by @hexstream) 
for FindBugs Java scanner. FindBugs & FindSecurityBugs requires compiled java bytecode and hence
the scan results might be more accurate than this plugin.  

'''
import os, sys, re, json, base64, codecs
import log

def isIgnored(path):
    fhandle = open('rules/ignore_list.rulepack', 'r')
    contents = fhandle.read()
    fhandle.close()
    ignored_rules = json.loads(contents)
    files = ignored_rules['files']
    directories = ignored_rules['directories']
    for f in files:
        if f in path:
            return True
    for d in directories:
        if d in path:
            return True
    return False

def isUnique(total_issues, filename, linenum):
    if len(total_issues) == 0:
        return True
    for issue in total_issues:
        if issue["file"] == filename and issue["line"] == linenum:
            #print "found duplicate"
            return False
        else:
            return True

def get_localImports(fpath):
    imports = []
    sig_import = 'import\s[^;]*;'
    fhandle = codecs.open(fpath, 'r', encoding='utf8')
    lines = [line for line in fhandle]
    for line in range(0, len(lines)):
        pattern = re.compile(sig_import, re.IGNORECASE)
        if pattern.search(lines[line]):
            package = lines[line].replace(';', '').replace('import', '').strip().replace('.', '/')
            if '*' in package:
                imports.append(package.replace('*',''))
            else:
                imports.append(package)
    return imports

def scan_localImports(imports=[], rule={}, path=''):
    import_file_paths = []
    flag = ''
    for directory, sub_dir, files in os.walk(path):
        if isIgnored(directory): # ignore test directories
            continue
        for item in imports:
            item = item.lower().lstrip('/').rstrip('/')
            if directory.lower().rfind(item) > -1: # check if the imported package is a local directory
                for import_dir, import_subDir, import_files in os.walk(directory):
                    for import_file in import_files:
                        import_file_paths.append(os.path.join(import_dir, import_file))
            else: # else check if it is java source file
                for _file in files:
                    file_path = os.path.join(directory, _file)
                    if file_path.lower().rfind(item + '.java') > -1:
                        import_file_paths.append(file_path)

    for imported_file in import_file_paths:
        condition_check = match_condition(imported_file, rule)
        if condition_check:
            flag = condition_check, imported_file
        else:
            flag = False
    
    return flag

def load_fsb_rules(fname):
    file = open(fname, 'r')
    return json.loads(file.read())

def match_condition(fpath, rule):
    fhandle = codecs.open(fpath, 'r', encoding='utf8')
    lines = [line for line in fhandle]
    for line in range(0, len(lines)):
        for condition in rule['condition']:
            condition_sig = base64.b64decode(condition['signature'])
            pattern = re.compile(condition_sig, re.IGNORECASE)
            if pattern.search(lines[line]) and not (lines[line].strip().startswith('//') or lines[line].strip().startswith('/*')):
                return line + 1, lines[line].strip()
    return False

def fsb_scan(root_path, rules_path):
    global fsb_issues, fsb_rules
    fsb_issues = []
    fsb_rules = load_fsb_rules(rules_path) #load all the FindSecurityBug rules
    for directory, sub_dirs, files in os.walk(root_path):
        for _file in files:
            file_path = os.path.join(directory, _file)
            if isIgnored(file_path):
                continue
            if os.path.splitext(_file)[1] in fsb_rules['file_types']:
                fhandle = codecs.open(file_path, 'r', encoding='utf8')
                lines = [line for line in fhandle]
                for line in range(0, len(lines)):
                    try:
                        current_line = lines[line]
                        delim_line = '%s**^^**%d**^^**%s' % (file_path, line, current_line)
                        scan_line(delim_line, file_path, root_path)
                    except Exception as e:
                        print "[ERROR] plugin:fsb, msg: skipped line #%d in %s, error: %s" % (line, file_path, str(e))
                        pass
                fhandle.close()
    return fsb_issues

def scan_line(delim_line, fpath, root_path):
    fsb_issue = {}
    line_obj = delim_line
    line_obj = line_obj.split('**^^**')
    file_path = line_obj[0]
    line_num = line_obj[1]
    line_content = line_obj[2]

    for rule in fsb_rules['rules']:
        if rule['enabled'] == 'true':
            rule_signature = base64.b64decode(rule['signature'])
            pattern = re.compile(rule_signature, re.IGNORECASE)
            if pattern.search(line_content) and not (line_content.strip().startswith('//') or line_content.strip().startswith('/*')):
                if match_condition(fpath, rule) or scan_localImports(get_localImports(fpath), rule, root_path):
                    fsb_issue['warning_type'] =  str(rule['title'].encode('utf-8'))
                    fsb_issue['warning_code'] = str(rule['id'])
                    fsb_issue['message'] = str(rule['description'].encode('utf-8'))
                    fsb_issue['file'] = re.sub('\/var\/raptor\/(clones|uploads)\/[a-zA-Z0-9]{56}\/', '', fpath.replace(os.getcwd(), '').replace(root_path, ''))
                    fsb_issue['line'] = int(line_num) + 1
                    fsb_issue['link'] = str(rule['link'])
                    fsb_issue['code'] = line_content.strip('\n').strip('\r').strip('\t').strip(' ')
                    fsb_issue['severity'] = str(rule['severity'].encode('utf-8'))
                    fsb_issue['plugin'] = str(fsb_rules['plugin_type'])
                    fsb_issue['signature'] = str(rule['signature'])
                    fsb_issue['location'] = ''
                    fsb_issue['user_input'] = ''
                    fsb_issue['render_path'] = ''
                    if isUnique(fsb_issues, fsb_issue['file'], fsb_issue['line']):
                        fsb_issues.append(fsb_issue)

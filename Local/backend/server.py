#!/usr/bin/python
#-- coding:utf-8 --
from raptor import init as init
from flask import Flask, request, jsonify, Response, redirect, url_for
from werkzeug.contrib.fixers import ProxyFix
from werkzeug import secure_filename
import sys, os, json, threading, hashlib, shutil, zipfile, requests, time, datetime
from raptor import log
from raptor import re_move_file
import time
import httplib



app = Flask(__name__)
app.debug=True

@app.errorhandler(500)
def server_error(e):
    return 'Internal Server Error', 500

@app.route('/', methods=['GET'])
def help():
    return '<h1>Usage</h1><h3>/scan/?r=&lt;git-repo-path&gt;&amp;p=&lt;report-save-directory&gt;</h3>'

@app.route('/heartbeat', methods=['GET'])
def heartbeat():
    return '{"status":"true", "time":%s}' % (str(int(time.time())))

#server-side call; nginx route not required
@app.route('/internal/scan/', methods=['GET'])
def internal_repo_scan():
    repo = request.args.get('r')
    report_directory = request.args.get('p')
    json_results = init.start(repo, report_directory, internal=True)
    
    if not os.path.exists(os.path.dirname(report_directory)):
        os.makedirs(os.path.dirname(report_directory), mode=0777)
    
    results = str(json.dumps(json_results))
    
    fhandle = open(report_directory, "w")
    content = fhandle.write(results)
    fhandle.close()
    log.logger.debug("Report created at %s" % (report_directory))
    return jsonify(json_results)

#server-side call; nginx route not required
@app.route('/external/scan/', methods=['GET'])
def external_repo_scan():
    repo = request.args.get('r')
    report_directory = request.args.get('p')
    json_results = init.start(repo, report_directory, internal=False)

    if not os.path.exists(os.path.dirname(report_directory)):
        os.makedirs(os.path.dirname(report_directory), mode=0777)
    
    results = str(json.dumps(json_results))
    
    fhandle = open(report_directory, "w")
    content = fhandle.write(results)
    fhandle.close()
    log.logger.debug("Report created at %s" % (report_directory))
    return jsonify(json_results)

@app.route('/purge/', methods=['GET'])
def delete_report():
    resp_content = 'null'
    report_path = os.path.abspath(request.args.get('path'))
    if os.path.exists(report_path) and report_path.startswith('/var/raptor/scan_results'):
        try:
            if os.path.isdir(report_path):
                shutil.rmtree(report_path)
                resp_content = "Success"
            elif os.path.isfile(report_path):
                os.remove(report_path)
                resp_content = "Success"
        except Exception as e:
            log.logger.error("%s: %s" % (report_path, str(e)))
            resp_content = "Failure"
    else:
        resp_content = "Failure"
    return resp_content


UPLOAD_FOLDER = os.path.abspath(os.environ['zip_upload_dir'])
ALLOWED_EXTENSIONS = set(['zip'])

try:
    os.makedirs(UPLOAD_FOLDER)
except Exception as e:
    if ' File exists: ' in str(e):
        log.logger.debug("%s" % str(e))
    else:
        raise e

app.config['UPLOAD_FOLDER'] = UPLOAD_FOLDER

def allowed_file(filename):
    return '.' in filename and \
           filename.rsplit('.', 1)[1] in ALLOWED_EXTENSIONS

def unzip_thread(fname, path='.'):
    def unzip(fname, path='.'):
        try:
            if zipfile.is_zipfile(fname):
                zip = os.popen('unzip %s -d %s' % (fname, path)).read()
                #if zip == 0:
                   # re_move_file.Dirs(path)
                return True
                #z.extractall(path)
                #os.remove(fname)
                #re_move_file.Dirs(path)
            else:
                return False
        except Exception as e:
            log.logger.error(e)
            return False
    t = threading.Thread(target=unzip, args=(fname, path))
    t.start()

#exposed via nginx route
@app.route("/raptor/upload", methods=['POST'])
def index():
    user = request.form.get('usr')
    if user and request.method == 'POST':
        upld_file = request.files['file']
        scan_name = request.form.get('scan_name')
        if not scan_name:
            scan_name = 'untitled-%s' % (str(datetime.datetime.now().strftime('%d_%m_%Y_%H_%m_%S')))

        if upld_file and allowed_file(upld_file.filename):
            try:
                filename = secure_filename(upld_file.filename)
                save_path = os.path.abspath(os.path.join(app.config['UPLOAD_FOLDER'], filename))
                upld_file.save(save_path)
		#new_fname = hashlib.sha224(open(save_path, 'rb').read()).hexdigest()+'_'+random.randint(0,9999)+'.zip'
		t = str(int(time.time()))
                new_fname = hashlib.sha224(open(save_path, 'rb').read()+t).hexdigest()+'.zip'
                new_path = os.path.abspath(os.path.join(app.config['UPLOAD_FOLDER'], new_fname))
		size = os.path.getsize(save_path);
		zip_size = float(size);
                os.rename(save_path, new_path)
                unzip_thread(new_path, os.path.join(UPLOAD_FOLDER, new_fname.rstrip('.zip')))
                return redirect('/scan.php?scan_name=%s&upload_id=%s&zip_name=%s&zip_size=%s' % (scan_name, new_fname.rstrip('.zip'), upld_file.filename,zip_size), code=302)
            except Exception as e:
                log.logger.error(e)


#server-side call; nginx route not required
@app.route('/zip/scan/', methods=['POST'])
def zip_scan():
    upload_id = request.form.get('upload_id')#md5 json上级文件夹名
    zip_name = request.form.get('zip_name')#压缩文件名称
    report_directory = request.form.get('p')#json文件路径
    report_enter = request.form.get('enter')#文件扫描进度.json文件
    scan_name = request.form.get('user_name')
    file_size = request.form.get('file_size')
    report_data = request.form.get('report_data')

    if not os.path.exists(os.path.dirname(report_enter)):#校验是否有文件路径
        os.makedirs(os.path.dirname(report_enter), mode=0777)# 创建json文件夹

    json_results = init.scan_zip(upload_id, zip_name, report_directory,report_enter)

    if not os.path.exists(os.path.dirname(report_directory)):
        os.makedirs(os.path.dirname(report_directory), mode=0777)



    if json_results:
        results = str(json.dumps(json_results["json"]))
    else:
        results = str(json_results["json"])

    fhandle = open(report_directory, "w")
    content = fhandle.write(results)
    fhandle.close()

    #分割ｊｓｏｎ文件时间戳
    tsplit = report_directory.split('/')[7].split('.')[0]
    creatime = int(tsplit)

    #判断扫描后是否产生json文件，如果成功将修改进度条的状态或百分比
    if fhandle:
        json_results["pro_json"]['progress']="100%"
        json_results["pro_json"]["status"] = "1"
        js = json.dumps(json_results["pro_json"])
        refile = open(report_enter,"w")
        refile.write(js)
        refile.close()
        scan_sutime=int(time.time())#扫描成功获取时间戳
    else:
        json_results["pro_json"]["status"] = "2"
        js = json.dumps(json_results["pro_json"])
        refile = open(report_enter, "w")
        refile.write(js)
        refile.close()

    #获取本地ｉｐ地址
    hostip = request.remote_addr
    try:
        _ip = request.headers["Host"]
        if _ip is not None:
            hostip = _ip
    except Exception as e:
        print(e)


    #调用php接口
    headers={
        "creatime":creatime,
        "scan_sutime":scan_sutime,
        "scan_name":"",
    }
    ip = str(hostip.split(':')[0])
    try:
        conn = httplib.HTTPConnection('%s' % ip)
        # params = urllib.urlencode({
        #     "creatime":creatime,
        #     "scan_sutime":scan_sutime,
        #     "scan_name":scan_sutime,
        #     "file_size":file_size,
        #     "report_data":report_data
        # })
        conn.request(method="GET",
                     url="/scan_suc.php?creatime=%s&scan_sutime=%s&user_name=%s&file_size=%s&report_data=%s" % (
                     creatime, scan_sutime, scan_name, file_size, report_data))
        # conn.request("POST","/scan_suc.php",params)

        res = conn.getresponse()
        # print res.status
        # print res.reason
        # print res.read()
        # print res.getheaders()
        conn.close()
    except Exception as e:
        print e

    log.logger.debug("Report created at %s" % (report_directory))
    log.logger.debug("Report created at %s" % (report_enter))

    return jsonify(json_results)

#exposed via nginx route
@app.route('/raptor/githook', methods=['POST'])
def gitHook():
    try:
    	filename = '%s.json' % (str(int(time.time())))
        parsed = json.loads(request.form['payload'])
        repo = parsed['repository']['full_name']
        head_commitId = parsed['head_commit']['id']
        user = parsed['repository']['owner']['name']
        url = parsed['repository']['html_url']

        if url.startswith(os.environ['ext_git_url']):
            internal = False
            r = requests.get('%s/repos/%s/git/commits/%s?access_token=%s' % (os.environ['ext_git_apiurl'], repo, head_commitId, os.environ['ext_git_token']))
        elif url.startswith(os.environ['int_git_url']):
            internal = True
            r = requests.get('%s/repos/%s/git/commits/%s?access_token=%s' % (os.environ['int_git_apiurl'], repo, head_commitId, os.environ['int_git_apiurl']))

        if r.json()['message'] == parsed['head_commit']['message']:
            report_directory = '%s/%s/commit-%s/%s/%s' % (os.environ['reportpath'], user, head_commitId, repo, filename)
            json_results = init.start(repo, report_directory, internal)
            
            if not os.path.exists(os.path.dirname(report_directory)):
                os.makedirs(os.path.dirname(report_directory), mode=0777)
            
            results = str(json.dumps(json_results))
            fhandle = open(report_directory, "w")
            content = fhandle.write(results)
            fhandle.close()

            log.logger.debug("Report created at %s" % (report_directory))
            return jsonify(json_results)
    except Exception as e:
        log.logger.error(str(e))
    return ""
app.wsgi_app = ProxyFix(app.wsgi_app)

if __name__ == "__main__":
    app.run(host='0.0.0.0',port=5000)

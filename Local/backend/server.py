#!/usr/bin/python
#-- coding:utf-8 --

from raptor import init as inits
from flask import Flask, request,make_response
from werkzeug.contrib.fixers import ProxyFix
from werkzeug import secure_filename
import os, json, threading, hashlib, shutil, zipfile, datetime
from raptor import log
from config import execute_db,fetchall_db
from functools import wraps
from raptor import re_move_file

from gevent import monkey
from gevent.pywsgi import WSGIServer
from geventwebsocket.handler import WebSocketHandler

import time

monkey.patch_all()
app = Flask(__name__)
app.config.update(
    DEBUG=True
)

app.debug = True

@app.errorhandler(500)
def server_error(e):
    return 'Internal Server Error', 500

@app.route('/', methods=['GET'])
def help():
    return '<h1>Usage</h1><h3>/scan/?r=&lt;git-repo-path&gt;&amp;p=&lt;report-save-directory&gt;</h3>'

@app.route('/heartbeat', methods=['GET'])
def heartbeat():
    return '{"status":"true", "time":%s}' % (str(int(time.time())))

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

def allow_cross_domain(fun):
	@wraps(fun)
	def wrapper_fun(*args, **kwargs):
		rst = make_response(fun(*args, **kwargs))
		rst.headers['Access-Control-Allow-Origin'] = '*'
		rst.headers['Access-Control-Allow-Methods'] = 'PUT,GET,POST,DELETE'
		allow_headers = "Referer,Accept,Origin,userid-Agent"
		rst.headers['Access-Control-Allow-Headers'] = allow_headers
		return rst
	return wrapper_fun

def unzip_thread(fname, path='.'):
    def unzip(fname, path='.'):
        try:
            if zipfile.is_zipfile(fname):
                z = os.popen('unzip %s -d %s' % (fname,path)).read()
                # if z == 0:
                re_move_file.Dirs(path)
                return True
            else:
                return False
        except Exception as e:
            log.logger.error(e)
            return False
    t = threading.Thread(target=unzip, args=(fname, path))
    t.start()

#删除zip文件
def move_file(username,scan_name):
    try:
        path = os.path.abspath(os.path.join(app.config['UPLOAD_FOLDER'], username, scan_name))  # 文件存放路径（用户/任务名称）
        shutil.rmtree(path)
    except Exception as e:
        log.logger.error(e)

#上传文件接口
@app.route("/raptor/upload", methods=['POST'])
@allow_cross_domain
def index():
    userid = request.form.get('user_id')
    result = {}
    sql = '''SELECT id,user_name,diction FROM obsec_user WHERE id="%s";''' %userid
    user = fetchall_db(sql)
    be_record = '代码扫描上传文件'
    username = user[0][1]
    userdiction = user[0][2]
    user_id = user[0][0]
    upload_date = time.strftime('%Y-%m-%d', time.localtime(time.time()))
    upload_time = time.strftime('%Y-%m-%d %H:%M:%S', time.localtime(time.time()))
    if int(user_id) == int(userid) and request.method == 'POST':
        upld_file = request.files['file']
        scan_name = request.form.get('scan_name')
        if not scan_name:
            scan_name = 'untitled-%s' % (str(datetime.datetime.now().strftime('%d_%m_%Y_%H_%m_%S')))
        else:
            sql = '''SELECT count(id) FROM obsec_upload_info WHERE user_id="%s" AND task_name="%s";'''%\
                  (userid,scan_name)
            s_name = fetchall_db(sql)
            if s_name[0][0] != 0:
                be_record = '上传文件'
                be_res = '任务名称重复'
                sql = '''INSERT INTO obsec_history_data(user_name,diction,be_record,be_res,jour_type,data_time) 
VALUES("%s","%s","%s","%s","1","%s");''' %(username, userdiction, be_record, be_res, upload_time)
                execute_db(sql)
                result['status'] = 5  # 任务名称重复
                return json.dumps(result)

        if upld_file and allowed_file(upld_file.filename):
            try:
                filename = secure_filename(upld_file.filename)
                path = os.path.abspath(os.path.join(app.config['UPLOAD_FOLDER'], username, scan_name))#文件存放路径（用户/任务名称）
                if not os.path.exists(path):
                    os.makedirs(path, mode=0777)
                    
                save_path = os.path.abspath(os.path.join(path, filename))#上传文件路径
                upld_file.save(save_path)#保存文件

                t = str(int(time.time()))
                new_fname = hashlib.sha224(open(save_path, 'rb').read()+t).hexdigest()+'.zip'
                new_path = os.path.abspath(os.path.join(path, new_fname))
                size = os.path.getsize(save_path);#字节数
                zip_size = float(size);

                if zip_size == 0:
                    move_file(username, scan_name)
                    be_res = 'zip包为空'
                    sql = '''INSERT INTO obsec_history_data(user_name,diction,be_record,be_res,jour_type,data_time) 
VALUES("%s","%s","%s","%s","1","%s");''' % (username, userdiction, be_record, be_res, upload_time)
                    execute_db(sql)
                    result['status'] = 4#zip包为空
                    return json.dumps(result)
                os.rename(save_path, new_path)#重命名

                unzip = unzip_thread(new_path, os.path.join(path, new_fname.rstrip('.zip')))
                if unzip == False:
                    move_file(username[0][0], scan_name)
                    result['status'] = 6# 未解压
                    be_res = 'zip包未解压'
                    sql = '''INSERT INTO obsec_history_data(user_name,diction,be_record,be_res,jour_type,data_time)
VALUES("%s","%s","%s","%s","1","%s");''' % (username, userdiction, be_record, be_res, upload_time)
                    execute_db(sql)
                    return json.dumps(result)

                sql = '''INSERT INTO obsec_upload_info (user_id,upload_orig_name,upload_file_name,
upload_file_path,upload_time,upload_date,task_name,upload_file_size)  VALUES ("%s","%s","%s","%s","%s","%s","%s","%s");''' % \
                      (int(userid),filename,new_fname,new_path,upload_time,upload_date,scan_name,zip_size)
                execute_db(sql)
                result['status'] = 1
                be_res = '操作成功'
                sql = '''INSERT INTO obsec_history_data(user_name,diction,be_record,be_res,jour_type,data_time)
                VALUES("%s","%s","%s","%s","1","%s");''' % (username, userdiction, be_record, be_res, upload_time)
                execute_db(sql)
                return json.dumps(result)
            except Exception as e:
                log.logger.error(e)
    else:
        be_res = '用户不存在'
        sql = '''INSERT INTO obsec_history_data(user_name,diction,be_record,be_res,jour_type,data_time)
VALUES("%s","%s","%s","%s","1","%s");''' % (username, userdiction, be_record, be_res, upload_time)
        execute_db(sql)
        result['status'] = 0#用户不存在
        return json.dumps(result)

#扫描接口
@app.route('/zip/scan', methods=['POST'])
@allow_cross_domain
def zip_scan():
    userid = request.form.get('user_id')#用户id
    scan_name = request.form.get('scan_name')#任务名称
    results = {}
    try:
        sql = '''SELECT id,user_name,diction FROM obsec_user WHERE id="%s";''' % userid
        user = fetchall_db(sql)
        be_record = '代码审计文件扫描'
        username = user[0][1]
        userdiction = user[0][2]
        user_id = user[0][0]
        if int(user_id) == int(userid):
            sql = '''SELECT s.scan_status FROM obsec_upload_info as u,obsec_scan_summary as s WHERE u.user_id="%s" AND 
        u.task_name="%s" AND u.id = s.upinfo_id;'''%(userid,scan_name)
            upload_user = fetchall_db(sql)
            if upload_user != 0 and upload_user:#防止同一条数据多次扫描
                now_time = time.strftime('%Y-%m-%d %H:%M:%S', time.localtime(time.time()))
                be_res = '同一条数据多次扫描'
                sql = '''INSERT INTO obsec_history_data(user_name,diction,be_record,be_res,jour_type,data_time) 
        VALUES("%s","%s","%s","%s","1","%s");''' % (username, userdiction, be_record, be_res, now_time)
                execute_db(sql)
                results["status"] = 3
                return json.dumps(results)
            else:
                sql = '''SELECT id,task_name,upload_orig_name,upload_file_path,upload_file_name FROM obsec_upload_info 
                WHERE user_id="%s" AND task_name="%s";''' % (userid, scan_name)
                upload_db = fetchall_db(sql)
                if not upload_user and upload_db:
                    upinfo_id = int(upload_db[0][0])
                    task_name = str(upload_db[0][1])#任务名称
                    upload_file = str(upload_db[0][2])#上传包的名称
                    file_path = str(upload_db[0][3].split('.zip')[0])#上传文件路径
                else:
                    now_time = time.strftime('%Y-%m-%d %H:%M:%S', time.localtime(time.time()))
                    be_res = '没有上传信息'
                    sql = '''INSERT INTO obsec_history_data(user_name,diction,be_record,be_res,jour_type,data_time) 
        VALUES("%s","%s","%s","%s","1","%s");''' % (username, userdiction, be_record, be_res, now_time)
                    execute_db(sql)
                    results["status"] = 2
                    results["remark"] = "No upload information"#没有上传信息
                    return json.dumps(results)

                start_time = time.strftime('%Y-%m-%d %H:%M:%S',time.localtime(time.time()))
                sql = '''INSERT INTO obsec_scan_summary (upinfo_id,scan_status,scan_start_time,scan_sped) \
            VALUES ("%s","%s","%s","%s");''' %(upinfo_id,3,start_time,"0%")
                sql_db = execute_db(sql)#插入一条进度条数据

                if sql_db == True:
                    sql = '''SELECT id FROM obsec_scan_summary WHERE upinfo_id="%s";'''% (upinfo_id)
                    result_db = fetchall_db(sql)
                    summary_id = int(result_db[0][0])
                    # import pdb
                    # pdb.set_trace()
                    json_results = inits.scan_zip(upload_file, task_name,file_path,upinfo_id,summary_id)

                    if json_results:
                        total_issues = json_results["scan_info"]["security_warnings"]#代码总行数
                        total_leak = json_results["leak_lines"]#漏洞总行数
                        total_file_num = len(json_results["file_type_total"]) #漏洞文件类型总个数
                        total_defect_num = json_results["warnings_type"]#漏洞缺陷种类个数
                        file_types = ",".join(json_results["file_type_total"])#漏洞文件类型集合

                        per = "100%"
                        end_time = time.strftime('%Y-%m-%d %H:%M:%S', time.localtime(time.time()))
                        sql = '''UPDATE obsec_scan_summary SET code_line_num="%s",leak_num="%s",leak_file_num="%s",
        leak_defect_num="%s",leak_file_type="%s",scan_end_time="%s",scan_sped="%s",scan_status="1" WHERE upinfo_id in ("%s");''' \
                              % (total_issues,total_leak,total_file_num,total_defect_num,str(file_types),end_time,per,upinfo_id)
                        execute_db(sql)
                        results['status'] = 1
                        now_time = time.strftime('%Y-%m-%d %H:%M:%S', time.localtime(time.time()))
                        be_res = '扫描成功'
                        sql = '''INSERT INTO obsec_history_data(user_name,diction,be_record,be_res,jour_type,data_time) 
                        VALUES("%s","%s","%s","%s","1","%s");''' % (username, userdiction, be_record, be_res, now_time)
                        execute_db(sql)
                        return json.dumps(results)
                    else:
                        sql = '''UPDATE obsec_scan_summary SET scan_status=2 WHERE user_id="%s" AND task_name="%s";''' \
                              % (userid, scan_name)
                        execute_db(sql)
                        results["status"] = 2  # 扫描失败
                        results["remark"] = "Update failure"  # 更新obsec_scan_data表失败
                        now_time = time.strftime('%Y-%m-%d %H:%M:%S', time.localtime(time.time()))
                        be_res = '扫描失败'
                        sql = '''INSERT INTO obsec_history_data(user_name,diction,be_record,be_res,jour_type,data_time) 
        VALUES("%s","%s","%s","%s","1","%s");''' % (username, userdiction, be_record, be_res, now_time)
                        execute_db(sql)
                        return json.dumps(results)
                else:
                    results["status"] = 2##录入obsec_scan_data表失败
                    results["remark"] = "Insertion failure"
                    now_time = time.strftime('%Y-%m-%d %H:%M:%S', time.localtime(time.time()))
                    be_res = '扫描失败'
                    sql = '''INSERT INTO obsec_history_data(user_name,diction,be_record,be_res,jour_type,data_time) 
        VALUES("%s","%s","%s","%s","1","%s");''' % (username, userdiction, be_record, be_res, now_time)
                    execute_db(sql)
                    return json.dumps(results)
        else:
            results["status"] = 0#用户不存在
            now_time = time.strftime('%Y-%m-%d %H:%M:%S', time.localtime(time.time()))
            be_res = '用户不存在'
            sql = '''INSERT INTO obsec_history_data(user_name,diction,be_record,be_res,jour_type,data_time)
        VALUES("%s","%s","%s","%s","1","%s");''' % (username, userdiction, be_record, be_res, now_time)
            execute_db(sql)
            return json.dumps(results)
    except Exception as e:
        log.logger.error(e)

app.wsgi_app = ProxyFix(app.wsgi_app)

if __name__ == "__main__":
    http_server = WSGIServer(('0.0.0.0', 5000), app, handler_class=WebSocketHandler)
    http_server.serve_forever()
    # app.run(host='0.0.0.0',port=5000)

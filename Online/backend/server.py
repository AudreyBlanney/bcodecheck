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

ALLOWED_EXTENSIONS = set(['zip'])

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
                z = os.system('unzip %s -d %s' % (fname,path))
                if z == 0:
                    re_move_file.Dirs(path)
                    return True
                # z = zipfile.ZipFile(fname, 'r')
                # for file in z.namelist():
                #     z.extract(file, path)
                # # z.extractall(path)
                # #re_move_file.Dirs(path)
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
        path = '/var/raptor/uploads/%s/%s/' % (username, scan_name)  # 文件存放路径（用户/任务名称）
        shutil.rmtree(path)
    except Exception as e:
        log.logger.error(e)

# 修改扫描状态
def update_scan(userid, scan_name):
    select_sql = '''SELECT id FROM obsec_upload_info WHERE user_id=%s AND task_name ="%s";''' % (userid, scan_name)
    upinfo = fetchall_db(select_sql)
    if upinfo:
        print upinfo
        update_sql = '''UPDATE obsec_scan_summary SET scan_status=2 WHERE upinfo_id=%s;''' \
                     % (upinfo[0])
        execute_db(update_sql)
        return True
    else:
        return False


#上传文件接口
@app.route("/raptor/upload", methods=['POST'])
@allow_cross_domain
def index():
    userid = request.form.get('user_id')
    result = {}
    sql = '''SELECT id FROM obsec_user WHERE id="%s";''' %userid
    user = fetchall_db(sql)
    if user[0][0] == int(userid) and request.method == 'POST':
        upld_file = request.files['file']
        scan_name = request.form.get('scan_name')
        if not scan_name:
            scan_name = 'untitled-%s' % (str(datetime.datetime.now().strftime('%d_%m_%Y_%H_%m_%S')))
        else:
            sql = '''SELECT count(id) FROM obsec_upload_info WHERE user_id="%s" AND task_name="%s";'''%\
                  (userid,scan_name)
            s_name = fetchall_db(sql)
            if s_name[0][0] != 0:
                result['status'] = 5#任务名称重复
                return json.dumps(result)
        sql = '''SELECT user_name FROM obsec_user WHERE id="%s";''' % userid
        username = fetchall_db(sql)
        if upld_file and allowed_file(upld_file.filename):
            try:
                filename = secure_filename(upld_file.filename)
                path = '/var/raptor/uploads/%s/%s/'% (username[0][0],scan_name)#文件存放路径（用户/任务名称）
                if not os.path.exists(os.path.dirname(path)):
                    os.makedirs(os.path.dirname(path), mode=0777)

                save_path = os.path.abspath(os.path.join(path, filename))#上传文件路径
                upld_file.save(save_path)#保存文件

                t = str(int(time.time()))
                new_fname = hashlib.sha224(open(save_path, 'rb').read()+t).hexdigest()+'.zip'
                new_path = os.path.abspath(os.path.join(path, new_fname))
                size = os.path.getsize(save_path);#字节数
                zip_size = float(size);

                if zip_size == 0:
                    move_file(username[0][0], scan_name)
                    result['status'] = 4#zip包为空
                    return json.dumps(result)
                os.rename(save_path, new_path)#重命名

                unzip = unzip_thread(new_path, os.path.join(path, new_fname.rstrip('.zip')))
                if unzip == False:
                    move_file(username[0][0], scan_name)
                    result['status'] = 6# 未解压
                    return json.dumps(result)

                sql = '''SELECT upload_size,upload_num FROM obsec_upload_data WHERE user_id="%s";''' % userid
                upload_result = fetchall_db(sql)#查看用户上传次数和大小

                upload_date = time.strftime('%Y-%m-%d', time.localtime(time.time()))
                upload_time = time.strftime('%Y-%m-%d %H:%M:%S', time.localtime(time.time()))

                sql = '''SELECT  count(upload_date) FROM obsec_upload_info WHERE user_id="%s" AND upload_date="%s";'''\
                              %(userid,upload_date)
                upload_num = fetchall_db(sql)#查看用户上传次数

                if upload_result != False:
                    sizes = round(zip_size/1024/1024,2)
                    if sizes > int(upload_result[0][0]):
                        move_file(username[0][0],scan_name)
                        result['status'] = 2#超出限制的大小
                        result['limit_size'] = int(upload_result[0][0])
                        result['zip_size'] = sizes
                        return json.dumps(result)
                    elif int(upload_num[0][0]) > int(upload_result[0][1]):
                        move_file(username[0][0], scan_name)
                        result['status'] = 3#超出限制的个数
                        result['limit_num'] = int(upload_result[0][1])
                        result['upload_num'] = int(upload_num[0][0])
                        return json.dumps(result)
                    else:
                        sql = '''INSERT INTO obsec_upload_info (user_id,upload_orig_name,upload_file_name,
    upload_file_path,upload_time,upload_date,task_name,upload_file_size)  VALUES ("%s","%s","%s","%s","%s","%s","%s","%s");''' % \
                              (int(userid),filename,new_fname,new_path,upload_time,upload_date,scan_name,sizes)
                        execute_db(sql)
                        result['status'] = 1
                        return json.dumps(result)

            except Exception as e:
                log.logger.error(e)
    else:
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
        sql = '''SELECT id FROM obsec_user WHERE id="%s";''' % userid
        user = fetchall_db(sql)
        if user[0][0] == int(userid):
            sql = '''SELECT s.scan_status FROM obsec_upload_info as u,obsec_scan_summary as s WHERE u.user_id="%s" AND 
    u.task_name="%s" AND u.id = s.upinfo_id;'''%(userid,scan_name)
            upload_user = fetchall_db(sql)
            if upload_user != 0 and upload_user:#防止同一条数据多次扫描
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
                        return json.dumps(results)
                    else:
                        update_scan(userid, scan_name)
                        execute_db(sql)
                        results["status"] = 2  # 扫描失败
                        results["remark"] = "Update failure"  # 更新obsec_scan_data表失败
                        return json.dumps(results)
                else:
                    results["status"] = 2##录入obsec_scan_data表失败
                    results["remark"] = "Insertion failure"
                    return json.dumps(results)
        else:
            results["status"] = 0#用户不存在
            return json.dumps(results)
    except Exception as e:
        update_scan(userid, scan_name)
        log.logger.error(e)

app.wsgi_app = ProxyFix(app.wsgi_app)

if __name__ == "__main__":
    http_server = WSGIServer(('0.0.0.0', 5000), app, handler_class=WebSocketHandler)
    http_server.serve_forever()
    # app.run(host='0.0.0.0',port=5000)

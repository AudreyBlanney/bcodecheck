# -*- coding:utf-8 -*-
 
import os,sys
import chardet
from init import *
import json



#进度条json
pro_json={
    "name":"",
    "progress":"",
    "status":"3"
}
def convert( filename, in_enc = "GBK", out_enc="UTF8" ):
    try:
        #print "convert " + filename,
        f = open('raptor/num.txt','r')
        con = os.popen('cat %s |grep -v \'^$\' |wc -l' % filename).read().split(' ')[0]
        num = f.read()
        num = int(num)+int(con)
        f.close()
        f = open('raptor/num.txt','w')
        f.write(str(num))
        f.close()
        content = open(filename).read()
        result = chardet.detect(content)#通过chardet.detect获取当前文件的编码格式串，返回类型为字典类型
        coding = result.get('encoding')#获取encoding的值[编码格式]
        tp =  os.path.splitext(filename)[1]
        tps = ['.java','.aspx','.asp','.c','.h','.cpp','.php','.py','rb','.pl','.jsp','.xml','.js']
        if (coding != 'utf-8') and (tp in tps):#文件格式如果不是utf-8的时候，才进行转码
            try:
               # print coding + "to utf-8!",
                new_content = content.decode(in_enc,'ignore').encode(out_enc)
                open(filename, 'w').write(new_content)
               # print " done"
            except:
                pass
        else:
            pass
            #print coding
    except IOError,e:
    # except:
        print " error"
 
 
def explore(dir,total,report_enter,report_dir):
    i = 0
    for root, dirs, files in os.walk(str(dir)):
        for file in files:
            path = os.path.join(root, file)
            convert(path)

            i = i+1
            if i <= total:
                per = (i * 40 / total)
                pro_json["progress"]=str(per)+"%"
                pro_json["name"] = str(report_dir)
                pro_json["status"] = "3"
                js = json.dumps(pro_json)
                f = open(report_enter, 'w')
                f.write(js)
                f.close()
            else:
                pass



def all_file(dir):
    i = 0
    for root, dirs, files in os.walk(str(dir)):
        for file in files:
            i = i+1
    f = open('/var/www/html/num.html','w')
    f.write(str(i))
    f.close()
    return i

def main(di):
    for path in sys.argv[1]:
        if os.path.isfile(path):
            convert(path)
        elif os.path.isdir(path):
            explore(path)
 
if __name__ == "__main__":
    explore("/home/code/Upload/")

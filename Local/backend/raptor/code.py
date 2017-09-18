# -*- coding:utf-8 -*-
import chardet
from init import *

import sys;
sys.path.append("../")
from config import execute_db

def convert( filename, in_enc = "GBK", out_enc="UTF8" ):
    try:
        #print "convert " + filename,
        content = open(filename).read()
        result = chardet.detect(content)#通过chardet.detect获取当前文件的编码格式串，返回类型为字典类型
        coding = result.get('encoding')#获取encoding的值[编码格式]
        tp = os.path.splitext(filename)[1]
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
    except IOError,e:
        print " error"

def explore(dir,total,upinfo_id,sumid):
    i = 0
    for root, dirs, files in os.walk(dir):
        for file in files:
            path = os.path.join(root, file)
            convert(path)
            i = i+1
            if i <= total:
                per = (i * 20 / total)
                scan_sped = str(per)+"%"
                sql = '''UPDATE obsec_scan_summary SET scan_sped="%s" WHERE id="%s" AND upinfo_id="%s"; ''' %\
                      (scan_sped, sumid, upinfo_id)
                execute_db(sql)
            else:
                pass

def file_all(dir):
    i = 0
    for root, dirs, files in os.walk(dir):
        for file in files:
            i = i+1
    return i

def main(path):
    for path in sys.argv[1]:
        if os.path.isfile(path):
            convert(path)
        elif os.path.isdir(path):
            explore(path)
 
if __name__ == "__main__":
    explore("/home/code/Upload/")

#! coding=utf-8

import os
import sys
import time
import re

def remove_file(_, path):
    file_types = ['.java','.aspx','.asp','.c','.h','.cpp','.php','.py','.rb','.pl','.xml','.jsp']
    types = os.path.splitext(_)[1]
    filename = os.path.basename(_)
    chinesefilename = re.search(r'[\u4e00-\u9fa5]', filename, re.U)
    print chinesefilename
    if (chinesefilename !=  None) and (types not in file_types):
	print 'yes'
	os.remove(path)
       #print chinesefilename
       #print "删除了文件：%s\n' % path"
    else :
        return

def Dirs(dir):
    # 切换到目的目录
    try:
        os.chdir(os.path.abspath(dir))
    except PermissionError as e:
        return

    # 遍历目录
    for _ in os.listdir(dir):
        # 如果是目录，获取绝对路径，重新调用dirs函数
        if os.path.isdir(_):
            path = os.path.abspath(_)
            Dirs(path)
            os.chdir(os.pardir)
        elif os.path.isfile(_):
            path = os.path.abspath(_)
            remove_file(_, path)
        else :
            path = os.path.abspath(_)
            os.remove(path)


#if __name__ == '__main__':
#     Dirs("/home/codecheck1/")











#os.path.basename(path)


















'''
主要的函数，就两个

dirs是获取所有的文件，把绝对路径存入到paths列表里

zip_file函数是把所有的文件打包，压缩

要改的是，加一个函数，功能是解压文件，然后把解压后的目录路径传给dirs函数，走之前的流程


https://pymotw.com/3/zipfile/

'''

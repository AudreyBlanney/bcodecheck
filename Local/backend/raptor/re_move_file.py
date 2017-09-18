#! coding=utf-8

import os
import re

def remove_file(_, path):
    file_types = ['.java','.aspx','.asp','.c','.h','.cpp','.php','.py','.rb','.pl','.xml','.jsp']
    types = os.path.splitext(_)[1]
    filename = str(os.path.splitext(_)[0])
    if types not in file_types:
        os.remove(path)
    elif types in file_types:
        chinesefilename = re.search(r'[\x80-\xff]', filename, re.I)
        if chinesefilename != None:
            os.remove(path)
    else:
        return

def Dirs(dir):
    # 切换到目的目录
    try:
        os.chdir(os.path.abspath(dir))
    except Exception as e:
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
            if not os.listdir(dir):
                os.removedirs(dir)
        else:
            path = os.path.abspath(_)
            os.remove(path)


# if __name__ == '__main__':
#     Dirs('/tmp')








#709db0b7d7bb3a1205342aae80f8a13dd46243abffcb0f0e8a5d86f1

#709db0b7d7bb3a1205342aae80f8a13dd46243abffcb0f0e8a5d86f1




























'''
主要的函数，就两个

dirs是获取所有的文件，把绝对路径存入到paths列表里

zip_file函数是把所有的文件打包，压缩

要改的是，加一个函数，功能是解压文件，然后把解压后的目录路径传给dirs函数，走之前的流程


https://pymotw.com/3/zipfile/

'''
#! coding=utf-8

import os
import sys
import time

def remove_file(_, path):
    file_types = ['.java','.aspx','.asp','.c','.h','.cpp','.php','.py','.rb','.pl','.xml','.jsp']
    types = os.path.splitext(_)[1]
    if types not in file_types:
        os.remove(path)
    else:
        return

def Dirs(dir):
    try:
        os.chdir(os.path.abspath(dir))
    except Exception as e:
        return

    for _ in os.listdir(dir):
        # 如果是目录，获取绝对路径，重新调用dirs函数
        if os.path.isdir(_):
            path = os.path.abspath(_)
            Dirs(path)
            os.chdir(os.pardir)
        elif os.path.isfile(_):
            path = os.path.abspath(_)
            remove_file(_, path)
        else:
            path = os.path.abspath(_)
            os.remove(path)


# if __name__ == '__main__':
#     Dirs('/tmp')
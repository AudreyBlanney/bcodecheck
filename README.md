# bcodecheck

---
title: 开发文档说明
---
## 项目开发后端， 主要使用python语言、Flask框架、pymsql数据库和前端交互完成！

× 请先看一下以下脚本,然后再运行；
## 安装python包：sh install.sh
## 建立数据库: sh mysql_con.sh (请先看checkout.sql文件，在运行)

## 启动：sh start.sh
## 配置：config.py
## 功能：Online/backend( 线上版本)、Local/backend(盒子版本)

### 'Online/backend/server.py'解读
> 主要功能是调用flask框架,，加入了2个路由

### 1.'/raptor/upload'

* 主要作用是接受前端发送的包信息,将解压包和未解压包到服务器指定目录和包信息存入数据库。

* 开始对用户信息的判断
    > 错误信息格式为json:{'status':0}

* 如果用户存在，获取包和任务名称(如果没有任务名称，则会用'untitled-'+ 当前时间产生任务名称)。

    * 并判断任务名称是否重复.
        > 错误信息格式为json:{'status':5}

    * 获取包的大小，判断是否为0.
         > 错误信息格式为json:{'status':4}

    * unzip_thread传入解压包名和路径，如果返回Flase,则删除上传文件.
        > 错误信息格式为json:{'status':6}

    * 根据userid获取，用户上传次数和包大小。

        * 如果上传次数大于，则删除上传文件。
            >错误信息格式为json:{'status':2}

        * 如果上传包大小大于，则删除上传文件。
            >错误信息格式为json:{'status':3}

### 2.'/zip/scan'

* 主要作用扫描接口，将扫描结果存入数据库;
* 用到了统计源码总行数的插件（cloc）；

* 开始对用户信息的判断。
    > 错误信息格式为json:{'status':0}

* 通过用户userid和任务名称scan_name获取扫描状态,如果不等于0，则同一条数据多次扫描.
    > 错误信息格式为json:{'status':3}

* 通过用户userid和任务名称scan_name获取上传包的信息,如果等于0，
    > 错误信息格式为json:{'status':2,'remark':'No upload information'}

* 插入一条进度条数据, 如果返回值为Flase，则插入失败。
    > 错误信息格式为json:{'status':2}

### 3.'unzip_thread'
* 主要功能解压文件的多线程,返回True，则解压成功；返回Flase，则解压失败；

### 4.'move_file'
* 主要功能删除文件，传入用户名username和任务名称scan_name，根据参数删除相应的文件；


## 'Online/backend/raptor'目录下脚本分析

### |-- __init__.py
	* 加载模块需要

### |-- re_move_file.py
	* 主要功能筛选文件名为中文命名文件和不符合文件类型的文件，并删除；

### |-- code.py
	*  主要功能将文件编码转成utf-8 编码,并产生一个百分比的进度条；


### |-- init.py
	* 主要功能扫描文件匹配规则，并返回部分json数据和百分比，则每个文件的漏洞信息存入数据库；

### |-- codescan.py
	* 主要功根据文件后缀判断并匹配规则， 返回json数据和规则的json文件并保存在解压文件同一目录；

### |-- externalscan.py
	* 主要用到（scanjs函数）外部扫描文件并匹配规则，产生json文件和json数据；

### |-- android.py
    * 主要功能访问（http://schemas.android.com/apk/res/android）,返回列表；


### |-- fsb.py

	* 主要匹配ignore_list规则， 返回json数据；

### |-- gitrob.py

	* 主要功能传入扫描路径和某规则进行扫描，返回列表；

### |-- log.py

	* 主要功能记录log日志，检测程序的错误信息，存放路径'/var/raptor/log/debug.log';



### 'Local/backend/server.py'解读
> 主要功能是调用flask框架,，加入了2个路由

### 1.'/raptor/upload'
    * 主要功能同上，去掉了上传的大小和次数，则每条状态信息存入日志表（数据库）；

### 2.'/zip/scan'
    * 主要功能同上，则每条状态信息存入日志表（数据库）；

### |-- re_move_file.py
	* 主要功能同上，不同是(36行)删除筛选后的空文件夹；（请留意和完善）;



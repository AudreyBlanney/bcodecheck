#!/bin/bash
# Installs pip, cffi (requires libffi-dev), 
# libgit2 with SSH support (requires cmake, cffi, libssh-4 and/or libssh-dev); 
# python modules: pygit2, keyring, flask; 
# nodejs (required by Mozilla's scanjs); 
# brakeman
# beautifulsoup for parsing php-rips html report
raptor_path="$(pwd)"
echo "gem: --no-rdoc --no-ri" >> ~/.gemrc
echo "gem: --no-document" >> ~/.gemrc
cp /etc/apt/sources.list /etc/apt/sources.list.bak
cp tsinghuasources.txt /etc/apt/sources.list
apt-get update
apt-get upgrade
apt-get install -y python
apt-get install -y python-pip
apt-get install mysql-server
apt-get install -y git
apt-get install -y nginx
apt-get install -y apache2
apt-get install -y php5
apt-get install -y php5-common
apt-get install -y php5-curl
apt-get install -y libapache2-mod-php5
apt-get install -y libapache2-mod-rpaf
apt-get install -y php5-mcrypt
apt-get install -y libffi-dev
apt-get install -y ruby
apt-get install -y php5-cli
apt-get install -y cmake
apt-get install -y libssh-4
apt-get install -y libssl-dev
apt-get install -y linux-headers-generic
apt-get install -y build-essential
apt-get install -y autoconf
apt-get install -y libtool
apt-get install -y pkg-config
apt-get install -y python-pip
apt-get install -y python-opengl
apt-get install -y python-imaging
apt-get install -y python-pyrex
apt-get install -y python-pyside.qtopengl
apt-get install -y idle-python2.7
apt-get install -y libgle3
apt-get install -y python-dev
apt-get install -y python-dateutil
apt-get install -y python-docutils
apt-get install -y python-feedparser
apt-get install -y python-gdata
apt-get install -y python-jinja2
apt-get install -y python-ldap
apt-get install -y python-libxslt1
apt-get install -y python-lxml
apt-get install -y python-mako
apt-get install -y python-mock
apt-get install -y python-openid
apt-get install -y python-psycopg2
apt-get install -y python-psutil
apt-get install -y python-pybabel
apt-get install -y python-pychart
apt-get install -y python-pydot
apt-get install -y python-pyparsing
apt-get install -y python-reportlab
apt-get install -y python-simplejson
apt-get install -y python-tz
apt-get install -y python-unittest2
apt-get install -y python-vatnumber
apt-get install -y python-vobject
apt-get install -y python-webdav
apt-get install -y python-werkzeug
apt-get install -y python-xlwt
apt-get install -y python-yaml
apt-get install -y python-zsi
apt-get install php5-mysql
apt-get install cloc
apt-get install Git git-core
pip install cffi
#wget https://github.com/libgit2/libgit2/archive/v0.25.0.tar.gz
tar -zxvf libgit2-0.26.0.tar.gz
cd libgit2-0.26.0
cmake .
make
sudo make install
cd /etc/ld.so.conf
cp ./libc.conf libgit2.conf
ldconfig
cd ~
pip install pygit2
pip install keyring
pip install flask
pip install gunicorn
pip install beautifulsoup
pip install Django
pip install hjson
pip install gevent
pip install gevent-websocket
pip install pymysql
curl -sL https://deb.nodesource.com/setup | sudo bash -
apt-get install -y nodejs
gem install brakeman
cd $raptor_path
#rm -rf v0.24.1.tar.gz
#rm -rf libgit2-0.24.1/
mkdir -p /var/raptor/scan_results
chmod -R 777 /var/raptor/scan_results #development purpose only
mkdir -p /var/raptor/log
chmod -R 777 /var/raptor/log #development purpose only
mkdir -p /var/raptor/uploads
chmod -R 777 /var/raptor/uploads #development purpose only
mkdir -p /var/raptor/clones
chmod -R 777 /var/raptor/clones #development purpose only
mkdir -p /var/www/html/raptor
#cp -r frontend/* /var/www/html/raptor
chmod -R 755 /var/www/html/raptor
cp index.html /var/www/html/index.html
cp num.html /var/www/html/raptor/num.html
chmod 777 /var/www/html/raptor/num.html
rm -rf /etc/apache2/sites-available/raptor.conf
rm -rf /etc/apache2/sites-enabled/raptor.conf
rm -rf /etc/nginx/sites-available/raptor
rm -rf /etc/nginx/sites-enabled/raptor
rm -rf /etc/nginx/sites-enabled/default
cp -r confs/apache2/* /etc/apache2
cp -r confs/nginx/* /etc/nginx
ln -s /etc/apache2/sites-available/raptor.conf /etc/apache2/sites-enabled/raptor.conf
ln -s /etc/nginx/sites-available/raptor /etc/nginx/sites-enabled/raptor
service apache2 restart
service nginx restart
while true
do
read -t 30 -p 'Attention: You have 30s to chose which version would you want to set up! ### Local or Online ### [L/O]' version 
case $version in
L | l )
echo '### Local version ### chosed ~ Waiting for copying files !'
cd $raptor_path
cp -r Local/frontend/* /var/www/html/raptor
echo 'Cpoy Files Successed !!!'
;;
O | o )
echo '### Online version ### chosed ~ Waiting for copying files !'
cd $raptor_path
cp -r Online/frontend/* /var/www/html/raptor
echo 'Cpoy Files Successed !!!'
;;
* )
echo 'Invalid Input!!! Please Retry!!!'
;;
esac
exit 0
done
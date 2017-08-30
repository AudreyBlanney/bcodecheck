#!/bin/bash
#####################################
#                           		#
#						   			#
#                                   #
# 									#
#	__Author__ = Audrey丶Blanney	#
#									#
#####################################

while true
do
	read -t 30 -p 'Attention: You have 30s to chose which version would you want to set up! ### Local or Online ### [L/O]' version 
	case $version in
		L | l )
			echo '### Local version ### chosed ~ Waiting for copying files !'
			cd CodeCheck/Local/
			cp -r frontend/* /home/audrey/test/1
			echo 'Cpoy Files Successed !!!'
				;;
		O | o )
			echo '### Online version ### chosed ~ Waiting for copying files !'
			cd CodeCheck/Online/
			cp -r frontend/* /home/audrey/test/2
			echo 'Cpoy Files Successed !!!'
				;;
		* )
			echo 'Invalid Input!!! Please Retry!!!'
				;;
	esac
	exit 0
done
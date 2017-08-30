#!/bin/bash
mysql -h localhost -uroot -pbcodecheck -e "create database codecheck"
mysql -h localhost -uroot -pbcodecheck -D codecheck -e "source /home/bcode/raptor-maste/codecheck.sql"

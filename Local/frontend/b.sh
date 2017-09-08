#!/bin/bash
#filename：monitor_interface.sh
function usage
{
   echo "use ./test_net.sh ethX time"
   echo "$1 is you network interface "
   echo "$2 is the last time!"
   echo "for example: ./test_net.sh eth0 2"
   exit 100
}
# if [ $# -lt 2 -o $# -gt 2 ];then
#   usage
# fi
eth=$1
time=$2
old_inbw=`cat /proc/net/dev | grep $eth | awk -F'[: ]+' '{print $3}'`
old_outbw=`cat /proc/net/dev | grep $eth | awk -F'[: ]+' '{print $11}'`
#while true
#do
  sleep $time
  new_inbw=`cat /proc/net/dev | grep $eth | awk -F'[: ]+' '{print $3}'`
  new_outbw=`cat /proc/net/dev | grep $eth | awk -F'[: ]+' '{print $11}'`
  inbw=`expr $((($new_inbw-$old_inbw)/$time))`
  outbw=`expr $((($new_outbw-$old_outbw)/$time))`
  echo "$eth: IN:$inbw bytes  OUT:$outbw bytes"
  old_inbw=${new_inbw}
  old_outbw=${new_outbw}
#done
exit 0

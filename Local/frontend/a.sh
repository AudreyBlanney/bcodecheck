#!/bin/bash
#filename：monitor_interface.sh
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

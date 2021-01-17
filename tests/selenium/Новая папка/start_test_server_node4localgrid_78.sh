#!/bin/bash
hub="http://`hostname -f`:4444/grid/register"
chromedriver='-Dwebdriver.chrome.driver=chromedriver_78.sh'
geckodriver='-Dwebdriver.gecko.driver=geckodriver.sh'
jar='selenium-server-standalone-3.141.59.jar'
host=`hostname -f`
hostport="-host ${host} -port 5556"
node='-role node -nodeConfig DefaultNodeMac.json'

cd "$( dirname ${BASH_SOURCE[0]})"
java ${chromedriver} ${geckodriver} -jar ${jar} ${node} -hub ${hub} ${hostport} -maxSession 3 -browserTimeout 240 -timeout 240
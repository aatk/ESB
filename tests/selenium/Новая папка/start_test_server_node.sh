#!/bin/bash
hub='-hub http://git.ztech:4444/grid/register'
chromedriver='-Dwebdriver.chrome.driver=chromedriver_75.sh'
geckodriver='-Dwebdriver.gecko.driver=geckodriver.sh'
jar='selenium-server-standalone-3.141.59.jar'
host=`hostname -f`
hostport="-host ${host} -port 5555"
limits='-maxSession 10 -browserTimeout 240 -timeout 440'
node='-role node -nodeConfig DefaultNodeMac.json'

cd "$( dirname ${BASH_SOURCE[0]})"
java ${chromedriver} ${geckodriver} -jar ${jar} ${node} ${hub} ${hostport} ${limits} -log ~/selenium_node.log
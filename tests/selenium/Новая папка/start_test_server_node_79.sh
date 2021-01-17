#!/bin/bash
hub='-hub http://git.ztech:4444/grid/register'
chromedriver='-Dwebdriver.chrome.driver=chromedriver_79.sh'
geckodriver='-Dwebdriver.gecko.driver=geckodriver.sh'
jar='selenium-server-standalone-3.141.59.jar'
host=`hostname -f`
hostport="-host ${host} -port 5555"
limits='-maxSession 10 -browserTimeout 240 -timeout 440'
node='-role node -nodeConfig DefaultNodeMac.json'
cd "$( dirname ${BASH_SOURCE[0]})"
cp -r . ~/Downloads/sephora-selenium-tests/ && cd ~/Downloads/sephora-selenium-tests/
killall -9 chromedriver_79.sh chromedriver_79
killall -9 chrome
java ${chromedriver} ${geckodriver} -jar ${jar} ${node} ${hub} ${hostport} ${limits} -log ~/selenium_node.log
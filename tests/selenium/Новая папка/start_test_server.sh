#!/bin/bash
chromedriver='-Dwebdriver.chrome.driver=chromedriver_83'
geckodriver='-Dwebdriver.gecko.driver=geckodriver.sh'
jar='selenium-server-standalone-3.141.59.jar'
cd "$( dirname ${BASH_SOURCE[0]})"

java ${chromedriver} ${geckodriver} -jar ${jar}
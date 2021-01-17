#!/bin/bash

jar='selenium-server-standalone-3.141.59.jar'
cd "$( dirname ${BASH_SOURCE[0]})"
java -jar ${jar} -role hub -port 4444
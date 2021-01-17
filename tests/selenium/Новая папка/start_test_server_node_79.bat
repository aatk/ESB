@echo off

REM reset current browsers
taskkill /IM chrome.exe /f
taskkill /IM chromedriver_79.exe /f
taskkill /IM java.exe /f
taskkill /IM firefox.exe /f
taskkill /IM geckodriver.exe /f

set java1="c:\Program Files (x86)\Java\jre1.8.0_231\bin\java.exe"
set java2="c:\Program Files\Java\jre1.8.0_231\bin\java.exe"
set log1=..\_output\win10.log
set log2=..\_output\win7.log
set chromedriver=-Dwebdriver.chrome.driver=chromedriver_79.bat
set geckodriver=-Dwebdriver.gecko.driver=geckodriver.bat
set jar=selenium-server-standalone-3.141.59.jar
set hub=-hub http://git.ztech:4444/grid/register
set node=-role node -nodeConfig DefaultNode.json -port 5555

set sescnt=10
set win10=ZT-WIN-10-GRID
if "%COMPUTERNAME%"=="%win10%" (set sescnt=15)
if "%COMPUTERNAME%"=="%win10%" (set log2=%log1%)
set sescnt=-maxSession %sescnt%

%java1% %chromedriver% %geckodriver% -jar %jar% %node% %hub% %sescnt% -browserTimeout 240 -timeout 440 -log %log1%
%java2% %chromedriver% %geckodriver% -jar %jar% %node% %hub% %sescnt% -browserTimeout 240 -timeout 440 -log %log2%
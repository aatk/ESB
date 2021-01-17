@echo off

REM reset current browsers
taskkill /IM chrome.exe /f
taskkill /IM chromedriver_75.exe /f
taskkill /IM java.exe /f
taskkill /IM firefox.exe /f
taskkill /IM geckodriver.exe /f

set java1="c:\Program Files (x86)\Java\jre1.8.0_221\bin\java.exe"
set java2="c:\Program Files\Java\jre1.8.0_221\bin\java.exe"
set log1=\\ic3\_sites\ru_zerostudio\sephora\tests\_output\win10.log
set log2=\\ic3\_sites\ru_zerostudio\sephora\tests\_output\win7.log
set chromedriver=-Dwebdriver.chrome.driver=\\ic3\_sites\ru_zerostudio\sephora\tests\selenium\chromedriver_76.bat
set jar=\\ic3\_sites\ru_zerostudio\sephora\tests\selenium\selenium-server-standalone-3.141.59.jar
set hub=http://git.ztech:4444/grid/register
set geckodriver="-Dwebdriver.gecko.driver=\\ic3\_sites\ru_zerostudio\sephora\tests\selenium\geckodriver.bat"
set config=\\ic3\_sites\ru_zerostudio\sephora\tests\selenium\DefaultNode.json

set sescnt=5
set win10=ZT-WIN-10-GRID
if "%COMPUTERNAME%"=="%win10%" (set sescnt=15)
if "%COMPUTERNAME%"=="%win10%" (set log2=%log1%)

%java1% %chromedriver% %geckodriver% -jar %jar% -role node -hub %hub% -maxSession %sescnt% -browserTimeout 240 -timeout 440 -nodeConfig %config% -log %log1%
%java2% %chromedriver% %geckodriver% -jar %jar% -role node -hub %hub% -maxSession %sescnt% -browserTimeout 240 -timeout 440 -nodeConfig %config% -log %log2%
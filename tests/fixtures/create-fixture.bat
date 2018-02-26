@ECHO OFF
whois %1 > %1.txt
git add %1.txt

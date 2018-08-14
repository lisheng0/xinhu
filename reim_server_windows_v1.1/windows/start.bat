@echo off
taskkill /F /IM php.exe
%cd%\php-5.4.14\php.exe -c %cd%\php-5.4.14\php.ini %cd%\server.php
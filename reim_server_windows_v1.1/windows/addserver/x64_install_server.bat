@echo off
D:\phpstudy\WWW\xinhu\reim_server_windows_v1.1\windows\addserver\nssm\win64\nssm.exe install rockreimserver D:\phpstudy\WWW\xinhu\reim_server_windows_v1.1\windows\php-5.4.14\php.exe "-c D:\phpstudy\WWW\xinhu\reim_server_windows_v1.1\windows\php-5.4.14\php.ini D:\phpstudy\WWW\xinhu\reim_server_windows_v1.1\windows\server.php"
D:\phpstudy\WWW\xinhu\reim_server_windows_v1.1\windows\addserver\nssm\win64\nssm.exe set rockreimserver DisplayName "REIM�����"
D:\phpstudy\WWW\xinhu\reim_server_windows_v1.1\windows\addserver\nssm\win64\nssm.exe set rockreimserver Description "REIM�����,����www.rockoa.com�����ṩ"
cmd

@echo off
schtasks /create /sc DAILY /mo 1 /sd "2017/09/01" /st "04:00:00"  /tn "ÿ��4���Զ�����REIM�����" /ru System /tr "D:\phpstudy\WWW\xinhu\reim_server_windows_v1.1\windows\addserver\x32_restart_server.bat"
cmd

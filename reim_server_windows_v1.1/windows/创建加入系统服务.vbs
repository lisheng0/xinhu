Dim appdata,ws,Startup,fso,nowpath,filename,filename1,nssmpath,servername
Set ws=Wscript.createobject("wscript.shell")
Set fso = CreateObject("Scripting.FileSystemObject")
Startup = ws.SpecialFolders("Startup")
nowpath	= fso.GetFolder(".").Path

servername = "rockreimserver" '�������Ʊ�����Ӣ��

Sub createFile(wss)
	
	nssmpath= ""&nowpath&"\addserver\nssm\win"&wss&"\nssm.exe"
	filename= "addserver/x"&wss&"_install_server.bat"
	Set myfile=fso.CreateTextFile(filename, true)
	myfile.WriteLine("@echo off"& vbCrlf &""&nssmpath&" install "&servername&" "&nowpath&"\php-5.4.14\php.exe ""-c "&nowpath&"\php-5.4.14\php.ini "&nowpath&"\server.php"""& vbCrlf &""&nssmpath&" set "&servername&" DisplayName ""REIM�����"""& vbCrlf &""&nssmpath&" set "&servername&" Description ""REIM�����,����www.rockoa.com�����ṩ"""& vbCrlf &"cmd")
	myfile.Close

	filename= "addserver/x"&wss&"_remove_server.bat"
	Set myfile=fso.CreateTextFile(filename, true)
	myfile.WriteLine("@echo off"& vbCrlf &""&nssmpath&" stop "&servername&""& vbCrlf &""&nssmpath&" remove "&servername&" "& vbCrlf &"cmd")
	myfile.Close

	filename1= "addserver\x"&wss&"_restart_server.bat"
	Set myfile=fso.CreateTextFile(filename1, true)
	myfile.WriteLine("@echo off"& vbCrlf &""&nssmpath&" restart "&servername&"")
	myfile.Close

	filename= "addserver/x"&wss&"_start_server.bat"
	Set myfile=fso.CreateTextFile(filename, true)
	myfile.WriteLine("@echo off"& vbCrlf &""&nssmpath&" start "&servername&" "& vbCrlf &"cmd")
	myfile.Close

	filename= "addserver/x"&wss&"_stop_server.bat"
	Set myfile=fso.CreateTextFile(filename, true)
	myfile.WriteLine("@echo off"& vbCrlf &""&nssmpath&" stop "&servername&" "& vbCrlf &"cmd")
	myfile.Close
	
	'����ÿ����������
	filename= "addserver/x"&wss&"_add_task.bat"
	stryun = "schtasks /create /sc DAILY /mo 1 /sd ""2017/09/01"" /st ""04:00:00""  /tn ""ÿ��4���Զ�����REIM�����"" /ru System /tr """&nowpath&"\"&filename1&""""
	Set myfile=fso.CreateTextFile(filename, true)
	myfile.WriteLine("@echo off"& vbCrlf &""&stryun&""& vbCrlf &"cmd")
	myfile.Close
	
End Sub

createFile "64" '�����ļ�64λ
createFile "32" '�����ļ�32λ


Set ws = Nothing
Set fso = Nothing
MsgBox "�ļ��Ѵ���������Ŀ¼addserver�²鿴�ļ���ʹ��˵��.txt��",0,"��ʾ"
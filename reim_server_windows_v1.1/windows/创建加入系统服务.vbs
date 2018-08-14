Dim appdata,ws,Startup,fso,nowpath,filename,filename1,nssmpath,servername
Set ws=Wscript.createobject("wscript.shell")
Set fso = CreateObject("Scripting.FileSystemObject")
Startup = ws.SpecialFolders("Startup")
nowpath	= fso.GetFolder(".").Path

servername = "rockreimserver" '服务名称必须用英文

Sub createFile(wss)
	
	nssmpath= ""&nowpath&"\addserver\nssm\win"&wss&"\nssm.exe"
	filename= "addserver/x"&wss&"_install_server.bat"
	Set myfile=fso.CreateTextFile(filename, true)
	myfile.WriteLine("@echo off"& vbCrlf &""&nssmpath&" install "&servername&" "&nowpath&"\php-5.4.14\php.exe ""-c "&nowpath&"\php-5.4.14\php.ini "&nowpath&"\server.php"""& vbCrlf &""&nssmpath&" set "&servername&" DisplayName ""REIM服务端"""& vbCrlf &""&nssmpath&" set "&servername&" Description ""REIM服务端,来自www.rockoa.com官网提供"""& vbCrlf &"cmd")
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
	
	'创建每天重启服务
	filename= "addserver/x"&wss&"_add_task.bat"
	stryun = "schtasks /create /sc DAILY /mo 1 /sd ""2017/09/01"" /st ""04:00:00""  /tn ""每天4点自动重启REIM服务端"" /ru System /tr """&nowpath&"\"&filename1&""""
	Set myfile=fso.CreateTextFile(filename, true)
	myfile.WriteLine("@echo off"& vbCrlf &""&stryun&""& vbCrlf &"cmd")
	myfile.Close
	
End Sub

createFile "64" '创建文件64位
createFile "32" '创建文件32位


Set ws = Nothing
Set fso = Nothing
MsgBox "文件已创建，请用目录addserver下查看文件【使用说明.txt】",0,"提示"
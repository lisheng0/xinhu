Set ws=Wscript.createobject("wscript.shell")
ws.run "start.bat",0
Set ws = Nothing
MsgBox "REIM服务端已启动",0,"提示"
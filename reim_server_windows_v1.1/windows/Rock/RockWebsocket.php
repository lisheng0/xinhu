<?php
class RockWebsocket {
	
	public 	$clientConn = array();
	public 	$clientObj 	= array();
	private $_clientConn= array();
	private $queuearrar	= array();
	private $taskurla	= array();
	private $sleepmiao	= 5;
	private $nowdate	= '';
	private $timerloader= 0;
	
	public function __construct(){
		$this->fromrecida 			= explode(',', REIMRECID);
	}

	private function printstr($str='', $lx=0)
	{
		if((!DEBUG && $lx!=3) || $str=='')return;
		static $msgxu=0;
		$msgxu++;
		echo $msgxu.'.';
		if($lx==0 || $lx==1 || $lx==3){
			$time = date('H:i:s');
			echo '['.$time.']';
		}
		echo $str;
		if($lx==0 || $lx==2 || $lx==3)echo "\n";
	}
	
	public function addmsg($str='', $conn=false)
	{
		if($conn)$str = "".$this->getclieninfor($conn).":".$str."";
		$this->printstr($str);
	}
	
	public function getclieninfor($conn)
	{
		$str = "" . $conn->getRemoteIp() . ":" . $conn->getRemotePort() . "";
		return $str;
	}
	
	/**
	*	有客户端连接时
	*/
	public function clientConnect($conn)
	{
		$linkstr = $this->getclieninfor($conn);
		$remoteip= $conn->getRemoteIp();
		$heiarr  = require(ROOT_PATH.'/Rock/Rockblacklist.php');
		$isclos  = false;
		if($heiarr)foreach($heiarr as $ips){
			if(strpos($remoteip, $ips)===0){
				$this->addmsg('ip['.$remoteip.'] in blacklist', $conn);
				$conn->close();
				$isclos = true;
				break;
			}
		}
		if($isclos)return;
		$this->_clientConn[$linkstr] = $conn;
	}
	
	public function clientClose($conn)
	{
		$this->_clientClose($conn);
	}

	private function getfromis($from)
	{
		if(!$from)return false;
		$arrs = $this->fromrecida;
		return in_array($from, $arrs);
	}
	
	public function clientMessage($conn, $data)
	{
		$this->addmsg($data, $conn);
		$aarr 	= json_decode($data, true);
		$from 	= $atype = $receid = $cont = $optdt = '';
		$adminid= '0';
		if(is_array($aarr))foreach($aarr as $k=>$v)$$k=$v;
		if($from==''||$atype==''||$adminid=='0'||$adminid==''){
			$conn->close();
			return;
		}
		if(!$this->getfromis($from)){
			$conn->close();
			return;
		}
		if($atype=='connect'){
			$this->_clientlinadd($from, $adminid, $conn);
		}
		if($atype=='send' && $receid!=''){
			$this->_sendmsg($from, $receid, $data);
		}
		if($atype=='getonline'){
			$ouid = $this->getonlineuid($from);
			$this->connectsendarr($conn,array(
				'type'	=> 'getonline',
				'online'=> $ouid,
			));
		}
	}
	
	public function connectsend($conn, $msg)
	{
		$conn->send($msg);
	}
	
	public function connectsendarr($conn, $arr=array())
	{
		$msg = json_encode($arr);
		$this->connectsend($conn,$msg);
	}
	
	//发送给客户端
	private function _sendmsg($from, $receid, $msg)
	{
		$arr = $this->getfromarr($from);
		if($receid=='all'){
			foreach($arr as $uid=>$rs){
				$this->connectsend($rs['conn'], $msg);
			}
		}else{
			$reces = explode(',', $receid);
			foreach($reces as $reid){
				if(isset($arr[$reid]))$this->connectsend($arr[$reid]['conn'], $msg);
			}
		}
	}
	
	private function getfromarr($from)
	{
		if(!isset($this->clientConn[$from]))$this->clientConn[$from]=array();
		$arr = $this->clientConn[$from];
		return $arr;
	}
	
	//客户端关闭
	private function _clientClose($conn)
	{
		$infor = $this->getclieninfor($conn);
		if(isset($this->clientObj[$infor])){
			$arr = $this->clientObj[$infor];
			$from= $arr['from'];
			$uid = $arr['uid'];
			$this->addmsg('close:'.$from.','.$uid.'', $conn);
			unset($this->clientObj[$infor]);
			unset($this->clientConn[$from][$uid]);
			$this->sendonoffline($from, $uid, 'offline');
		}
	}
	
	//群发我上线下线(已取消，影响性能)
	private function sendonoffline($from, $uid, $lx)
	{
		//$this->_sendmsg($from, 'all', '{"type":"onoffline","adminid":"'.$uid.'","cont":"'.$lx.'"}');
	}
	
	//连接用户添加
	private function _clientlinadd($from, $uid, $conn)
	{
		$arr = $this->getfromarr($from);
		if(isset($arr[$uid])){
			$this->connectsend($arr[$uid]['conn'],'{"type":"offoline","sendid":"'.$uid.'"}');
			$arr[$uid]['conn']->close();
		}
		$narr	= array(
			'uid' 	=> $uid,
			'from' 	=> $from,
			'ip'	=> $conn->getRemoteIp(),
			'port'	=> $conn->getRemotePort(),
			'conn' 	=> $conn,
			'time' 	=> time()
		);
		$this->clientConn[$from][$uid] = $narr;
		$this->clientObj[$this->getclieninfor($conn)] = $narr;
		$this->sendonoffline($from, $uid, 'online');
		return $this->getonline($from);
	}
	//统计在线人数
	public function getonline($from)
	{
		$oi 	= 0;
		$arr 	= $this->getfromarr($from);
		foreach($arr as $uid=>$rs)$oi++;
		return $oi;
	}
	//获取在线人员id
	private function getonlineuid($from)
	{
		$s 		= '';
		$arr 	= $this->getfromarr($from);
		foreach($arr as $uid=>$rs){
			$s .=','.$uid.'';
		}
		if($s!='')$s=substr($s,1);
		return $s;
	}
	
	/**
	*	推送过来的数据库
	*/
	public function pushUdpMessage($conn, $datamess)
	{
		$data = $this->timerrun($datamess, $conn);
		if($data=='' || substr($data,0,8)=='success|' || is_array($data)){
			if(!is_array($data))$data = substr($data, 8);
			$msg	='ok';
		}else{
			$msg 	= $data;
			$data	= '';
		}
		$barr['msg']  = $msg;
		$barr['code'] = $msg=='ok' ? 0 : 2;
		$barr['data'] = $data;
		$conn->send(json_encode($barr));
		$conn->close();
	}
	
	/**
	*	定时器定时运行 推送时运行的
	*/
	public function timer()
	{
		$ndt 	= date('Y-m-d');
		if($this->nowdate=='')$this->nowdate = $ndt;
		
		$path 	= PATHS;
		@$dir 	= opendir($path);
		$fpath	= '';
		$nyunf	= array('index.html', '.', '..', 'task.json', 'queue.json','access.log');
		while( false !== ($file = readdir($dir))){
			if(!in_array($file, $nyunf)){
				$filess = $path.'/'.$file;
				if(is_file($filess)){
					$fpath = $filess;
					break;
				}
			}
		}
		//定时读取
		if($fpath!=''){
			$this->addmsg($fpath);
			$fcont = file_get_contents($fpath);
			$this->timerrun($fcont, false, true);
			unlink($fpath);
		}
		
		//加载计划任务
		if($this->timerloader==2){
			$this->initstarttask();
		}
		
		//到第二天刷新计划任务
		if($ndt != $this->nowdate){
			$this->restartalltask();
		}
		$this->kickclient();
		$time = time();
		for($i=0;$i<$this->sleepmiao;$i++){
			$xu  = $time+$i;
			$this->_timeusens('a', $xu);
			foreach($this->fromrecida as $recid)$this->_timeusens($recid, $xu);
		}
		$this->nowdate = $ndt;
		$this->timerloader++;
	}
	private function _timeusens($k,$time)
	{
		$times = ''.$k.''.$time.'';
		if(isset($this->queuearrar[$times])){
			$str = $this->queuearrar[$times];
			unset($this->queuearrar[$times]);
			$this->timerrun($str);
		}
	}
	
	//推送过来运行
	public function timerrun($datastr='', $conn=false, $redbo=false)
	{
		if(!$datastr)return 'data is empty';
		$this->addmsg($datastr, $conn);
		$aarr 	= json_decode($datastr, true);
		if(!is_array($aarr))return 'data is error';;
		$from 	= $atype = $receid = $adminid = $url = '';
		$time 	= time();
		$runtime= 0;  //定时运行时间
		foreach($aarr as $k=>$v)$$k=$v;

		if($from==''||$atype==''){
			return 'from is empty';
		}
		
		if(!$this->getfromis($from)){
			return 'not found from';
		}
		
		$data = '';
		if($runtime-$this->sleepmiao > $time){
			$this->queuearrar['a'.$runtime] = $datastr;
			return $data;
		}
		
		if($atype=='send' && $receid!=''){
			$this->_sendmsg($from, $receid, $datastr);
		}
		
		if($atype=='runurl' && substr($url,0,4)=='http'){
			if(!$redbo){
				$path	= ''.PATHS.'/'.$from.'_'.time().'_'.rand(100,999).'.txt';
				file_put_contents($path, $datastr);
			}else{
				$jg  = (strpos($url, '?')===false) ? '?' : '&';
				$url.=''.$jg.'runtime='.$time.'';
				new Threadurl($url);
			}
		}
	
		if($atype=='starttask' && $url!=''){
			$this->starttask($from,$url);
		}
		
		//在线人数
		if($atype=='getonline'){
			$ouid = $this->getonlineuid($from);
			$data = 'success|'.$ouid;
		}
		
		return $data;
	}
	
	//踢非法连接的
	public function kickclient()
	{
		foreach($this->_clientConn as $cstr=>$conn){
			if(!isset($this->clientObj[$cstr])){
				$conn->close();
			}
			unset($this->_clientConn[$cstr]);
		}
	}
	
	//错误的，可能是浏览器直接访问的
	public function clientError($conn, $data)
	{
		$from = isset($_GET['reimrecid']) ? $_GET['reimrecid'] : '';
		if(!$this->getfromis($from)){
			
		}else{
			$txt 	= $GLOBALS['HTTP_RAW_POST_DATA'];
			if(strpos($txt,'"adminid"')){
				$this->timerrun($txt);
				return "ok";
			}
		}
		return "";
	}
	
	//读取计划任务
	private function starttask($from,$url)
	{
		$this->taskurla[$from] = $url;
		$dt 	= date('Y-m-d');
		$url 	= $url.'&dt='.$dt.'';
		$obj 	= new Threadurl($url,true);
		$result	= $obj->getresult();
		foreach($this->queuearrar as $key=>$str){
			if(strpos($key,$from)===0)unset($this->queuearrar[$key]);
		}
		$oi = 0;
		if($result!=''){
			$result = str_replace('[recid]', $from, $result);
			$bara 	= json_decode($result, true);
			if($bara)foreach($bara as $k=>$str){
				$this->queuearrar[$k]=$str;
				$oi++;
			}
			@file_put_contents(''.PATHS.'/task.json', json_encode($this->taskurla));
		}
		$this->addmsg('recid['.$from.']task('.$oi.');');
	}
	
	private function restartalltask()
	{
		$this->queuearrar	= array();//清空队列
		foreach($this->taskurla as $from=>$url){
			$this->starttask($from, $url);
		}
	}
	
	//初始化计划任务
	private function initstarttask()
	{
		$paths = ''.PATHS.'/task.json';
		if(file_exists($paths)){
			$str = file_get_contents($paths);
			if($str!='')$this->taskurla = json_decode($str, true);
		}
		$this->restartalltask();
	}
}

if(!class_exists('Thread')){
	abstract class Thread {
		public function run(){}
		public function start(){
			$this->run();
		}
		public function join(){return true;}
		public function isRunning(){return false;}
	}
}
class Threadurl extends Thread {
	private $urlstr 	= '';
	private $result 	= '';
	public function __construct($url, $isback=false){
		$this->urlstr 	= $url;
		$this->start();
		if($isback)while($this->isRunning())usleep(10); 
	}
	public function run(){
		@$this->result = file_get_contents($this->urlstr);
	}
	public function getresult()
	{
		return $this->result;
	}
}
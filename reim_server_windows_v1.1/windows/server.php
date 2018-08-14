<?php
use Workerman\Worker;
require_once('config.php');
require_once('Workerman/Autoloader.php');
require_once('Rock/Rockloader.php');

$worker = new Worker('websocket://'.WSIP.':'.WSPORT.'');
$worker->count 	= 1; 
$RockWebsocket	= new RockWebsocket();

$worker->onWorkerStart = function($task)
{
	if($task->id === 0){
		\Workerman\Lib\Timer::add(5, function(){
			$GLOBALS['RockWebsocket']->timer();
		});
		
		//内部开启一个UDP推送的
		$udpworker = new Worker('udp://'.PUSHIP.':'.PUSHPORT.'');
		$udpworker->onMessage = function($connection, $data)
		{
			$GLOBALS['RockWebsocket']->pushUdpMessage($connection, $data);
		};
		
		$udpworker->listen();
	}
	echo "----- start success from http://www.rockoa.com/ push(".PUSHIP.":".PUSHPORT.") -----\n";
};

$worker->onConnect = function($connection)
{
	$GLOBALS['RockWebsocket']->clientConnect($connection);
};

$worker->onClose = function($connection)
{
	$GLOBALS['RockWebsocket']->clientClose($connection);
};

$worker->onMessage = function($connection, $data)
{
	$GLOBALS['RockWebsocket']->clientMessage($connection, $data);
};


$worker->onWorkerStop = function($worker)
{
    echo "Worker stopping...\n";
};

Worker::runAll();
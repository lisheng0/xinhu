<?php
class loginClassAction extends ActionNot
{
	
	public function defaultAction()
	{
		if($this->adminid>0){
			$this->rock->location('?d=reim');
		}
		$this->title = getconfig('reimtitle','REIM');
		
		$this->assign('loginyzm', (int)getconfig('loginyzm','0'));
	}
	
}
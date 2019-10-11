<?php

if (!defined('IN_IA')) {
	exit('Access Denied');
}

class Task_SuperdeskShopV2Page extends WebPage
{
	public function main()
	{
		$this->runTasks();
	}
}



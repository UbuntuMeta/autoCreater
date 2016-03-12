<?php

/**
*  自动化生成代码框架的工具类
**/
class Creater 
{
	
	protected $controllers = array();  // 控制器数组

	protected $models = array();       // 模型数组

	protected $helpers = array();     // 组件数组


	/**
	* 根据名称和模块生成默认模板内容的控制器
	**/
	public function controller($controllerStrs)
	{
		$this->controllers = explode(',', $controllerStrs);
		if (empty($this->controllers)) exit("no controller's name enter, please check your input!");


	}

	/**
	* 根据名称和模块生成默认模板内容的model
	**/
	public function model()
	{
		
	}

	/**
	* 根据名称和模块生成默认模板内容的helper
	**/
	public function helper()
	{
		
	}

	private function _splitWithModule(array &$arr)
	{
		if (empty($arr)) exit('nothing to create!');



	}
}

/*if (count($argv) < 1) {
	return;
}

$date = isset($argv[1]) ? $argv[1] : date("Ymd");
$onceNum = isset($argv[2]) ? $argv[2] : 2;*/


/*
	如果采用脚本方式

	生成controller文件
	php creater.php controller game,news,product

	or php creater.php controller game2015/game,news,product
*/
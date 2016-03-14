<?php

/**
*  自动化生成代码框架的工具类
**/


require './config/template.php';

class Creater 
{
	
	protected $controllers = array();  // 控制器数组

    protected $template = null;

	protected $models = array();       // 模型数组

	protected $helpers = array();     // 组件数组

    protected $configs = array();

    public function __construct()
    {
        require './config/conf.php'; // 加载配置
        $this->configs = $config;
    }

    /**
	* 根据名称和模块生成默认模板内容的控制器
	**/
	public function controller($controllerStrs)
	{
        $this->_createFile('controller', $controllerStrs);
	}

	/**
	* 根据名称和模块生成默认模板内容的model
	**/
	public function model($modelStrs)
	{
        $this->_createFile('model', $modelStrs);
	}

	/**
	* 根据名称和模块生成默认模板内容的helper
	**/
	public function helper($helperStrs)
	{
        $this->_createFile('helper', $helperStrs);
    }

    /**
     * 创建文件
     *
     * @param string $typeName 文件类型
     * @param string $string 文件名字符串，多个以逗号分隔
     * @author freephp
     */
    private function _createFile($typeName, $string){
        $typeField = $typeName . 's';
        $this->$typeField = explode(',', $string);
        if (empty($this->$typeField)) exit("no $typeName's name enter, please check your input!");
        $template = new Template(array('type' => $typeName, 'isNormal' => true));
        foreach ($this->$typeField as $k => $v) {

            $template->className = $v;

            if (strstr($v, '/')) {
                $path = substr($v, 0, strripos(strtolower($v), '/'));
                $toCreatePath = $this->configs[$typeName . 'Path'] . '/' . $path;
                if (!is_dir($toCreatePath)) {
                    mkdir($toCreatePath, 0777, true);
                }
                $template->className = str_replace($path .'/', '', $v);
            }

            $contents = $template->loadFile();

            $filePath = $this->configs[$typeName . 'Path'] . '/' . strtolower($v) . '_model.php';
            if(!file_exists($filePath)) {
                $this->_writeFile($filePath, $contents);
                echo 'success write it!' . "\r\n";
            } else {
                $this->_writeFile($filePath, $contents);
                print_r('The  ' . $typeName . ' ' . $v . ' has existed,  created again!');
            }
        }
    }

	private function _splitWithModule(array &$arr)
	{
		if (empty($arr)) exit('nothing to create!');


	}

    /**
     * 写入文件
     *
     * @param string $filePath 文件地址
     * @param string $contents 文件内容
     * @author freephp
     */
    private function _writeFile($filePath, $contents)
    {
        $fp = fopen($filePath, 'w');
        fputs($fp, $contents);
        fclose($fp);

    }
}
if (count($argv) < 1) {
    return;
}

$action = $argv[1];
$params = $argv[2];
$creater = new Creater();

$creater->$action($params);

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
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
	 * 
	 * @param  string $controllerStrs
	 * @return string
	 */
	public function controller($controllerStrs)
	{
        $this->_createFile('controller', $controllerStrs);
	}

	/**
	 * 根据名称和模块生成默认模板内容的model
	 * 
	 * @param  string $modelStrs
	 * @return string
	 */
	public function model($modelStrs)
	{
        $this->_createFile('model', $modelStrs);
	}

	/**
	 * 根据名称和模块生成默认模板内容的helper
	 * 
	 * @param  string $helperStrs
	 * @return string
	 */
	public function helper($helperStrs)
	{
        $this->_createFile('helper', $helperStrs);
    }

    /**
     * 将缩写命令补全为完整命令并执行
     * 
     * @param  array $params
     * @return string
     */
    public function alia(array $params)
    {
    	$operation = '';
    	switch ($params['opt']) {
    		case 'c':
    			$operation = 'controller';
    			break;
    		case 'm':
    			$operation = 'model';
    			break;
    		case 'h':
    			$operation = 'helper';
    			break;
    		
    		default:
    			exit('error operation !');
    			break;
    	}

    	$this->$operation($params['contents']);
    } 

    /**
	 * 显示帮助信息
	 * @return string
	 */
	public function showTips()
	{

		// heredoc
		print <<<EOT
		usage: php creater.php [--help] [c ] [m ] [h][argv]

		The most commonly used  commands are:

		short name   complete name     description
		   c          controller       to create controller file with a template
		   m          model            to create model file with a template
		   h          helper           to create helper file with a template

EOT;

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
        if (empty($this->{$typeField})) exit("no $typeName's name enter, please check your input!");
        $template = new Template(array('type' => $typeName, 'isNormal' => true));
 

        foreach ($this->$typeField as $k => $v) {
            $template->className = $v;

            if (strstr($v, '/')) {
                $template->className = $this->__splitDirsAndFile($v, $typeName);
            }

            $contents = $template->loadFile();
            
            $filePath = $this->configs[$typeName . 'Path'] . '/' . strtolower($v) . $this->_getFileTail($typeName);

            if(!file_exists($filePath)) {
                $this->_writeFile($filePath, $contents);
                echo 'success write it!' , "\r\n";
            } else {
                $this->_writeFile($filePath, $contents);
                print_r('The  ' . $typeName . ' ' . $v . ' has existed,  created again!');
            }
        }
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

	/**
	 * 获取文件后缀
	 * 
	 * @param  string $typeName 文件类型
	 * @return string
	 */
	private function _getFileTail($typeName) {
    	$tail = '.php';
        if ($typeName == 'model') $tail = '_model.php';
        if ($typeName == 'helper') $tail = '_helper.php';

        return $tail;
    }

    private function __splitDirsAndFile($val, $typeName) {
		$path = substr($val, 0, strripos(strtolower($val), '/'));

        $toCreatePath = $this->configs[$typeName . 'Path'] . '/' . $path;
        if (!is_dir($toCreatePath)) {
            mkdir($toCreatePath, 0777, true);
        }

	    return str_replace($path .'/', '', $val);
	}
}

/*
	采用脚本方式

	生成controller文件
	php creater.php controller game,news,product or php creater.php c game.product

	or php creater.php controller game2015/game,news,product
*/
if (count($argv) < 1) {
    return;
}

$action = trim($argv[1]);
$param = trim($argv[2]);

$creater = new Creater();

if (strlen($action) == 1) {
	$creater->alia(['opt' => $action, 'contents' => $param]);
} elseif ($action == '--help')  {
	$creater->showTips();
} else {
	$creater->$action($params);
}




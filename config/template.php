<?php

class Template
{
    public $className = '';

    protected $type = null;

    protected $isNormal = false; // 是否自动生成curd或model常规


    public function  __construct(array $params)
    {
        if ($this->existParam($params, 'className')) {
            $this->className = $params['className'];
        } else {
            $this->className = 'Test';
        }

        if (!in_array($params['type'], ['controller', 'model', 'helper'])) {
            exit('error type file to create!');
        } else {
            $this->type = $params['type'];
        }

        if ($this->existParam($params, 'isNormal')) {
            $this->isNormal = $params['isNormal'];
        }

    }


    public function loadFile()
    {
        if($this->_isController()) {
            return $this->_createController();
        } elseif ($this->_isModel()) {
            return $this->_createModel();
        } elseif ($this->_isHelper()) {

        } else {
            exit('undefine type to create file.');
        }

    }

    private function _createController()
    {
        $modelName = strtolower($this->className);
        $className = ucfirst($this->className);
        $content = <<<EOT
<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class {$className} extends CI_Controller
{
     function __construct()
    {
        parent::__construct();
        \$this->load->model('{$modelName}_model');
    }

EOT;
        if ($this->isNormal) {
            $content .= <<<EOT

    public function index()
    {
    }

    public function add()
    {
    }

    public function edit()
    {
    }

    public function delete()
    {
    }

    public function _searchFields(array \$param)
    {
    }

}
EOT;

        }

        return $content;
    }

    private function _createModel()
    {
        $modelName = strtolower($this->className);
        $content = <<<EOT
<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class {$this->className}_Model extends CI_Model
{
	protected \$primary_table = "{$modelName}";
	protected \$primary_key = "{$modelName}_id";

	public function __construct() {
		parent::__construct();
	}

	public function findById() {

	}


	public function getList(array \$param,\$isCount = TRUE){

	}
}
EOT;

        return $content;
    }

    private function _isController() {
        return ($this->type == 'controller')? : false;
    }

    private function _isModel() {
        return ($this->type == 'model')? : false;
    }
    private function _isHelper() {
        return ($this->type == 'helper')? : false;
    }

    protected function existParam($var, $key)
    {
        return (isset($var[$key]) && $var[$key])? : false;
    }
}
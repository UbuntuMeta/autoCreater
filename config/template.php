<?php

class Template
{
    public $className = '';

    protected $type = null;

    protected $isNormal = false; // 是否自动生成curd或model常规

    protected $validType = ['controller', 'model', 'helper', 'view'];
    public function  __construct(array $params)
    {
        if ($this->existParam($params, 'className')) {
            $this->className = $params['className'];
        } else {
            $this->className = 'Test';
        }
        $this->type = $params['type'];

        if (!$this->isValidType()) {
            exit('error type file to create!');
        }

        if ($this->existParam($params, 'isNormal')) {
            $this->isNormal = $params['isNormal'];
        }

    }

    public function loadFile()
    {
        $operation = '_create' . ucfirst($this->type);
        return $this->$operation();
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

    private function _createView()
    {
        $content = $this->className . ' view';

        return $content;
    }

    protected function existParam($var, $key)
    {
        return (isset($var[$key]) && $var[$key])? : false;
    }

    protected function isValidType() {
        return (in_array($this->type, $this->validType))?:false;
    }
}
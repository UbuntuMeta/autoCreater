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
class {$className} extends MY_Controller
{
    /**
     * 前面是get过来的参数名，后面是处理后的参数名
     */
    public \$searchFiledOptions = array(
            'orderField' => array(
                'name' => 'orderField',
                'default' => 'date',
                'type' => 'string'
            ),
            'order' => array(
                'name' => 'order',
                'default' => 'asc',
                'type' => 'string'
            ),
            'pageSize' => array(
                'name' => 'pageSize',
                'default' => self::PAGE_SIZE,
                'type' => 'numeric'
            ),
            'page' => array(
                'name' => 'page',
                'default' => 1,
                'type' => 'numeric'
            ),
        );

    function __construct()
    {
        parent::__construct();
        \$this->load->model('{$modelName}_model');
    }

EOT;
        if ($this->isNormal) {
            $content .= <<<EOT

    public function list()
    {
        \$params = \$this->getSearchFileds(\$this->searchFiledOptions, \$_REQUEST);
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

     /**
     * 获取搜索字段
     *
     * @param array \$allowOptions
     * @param array \$formOptions
     * @return array
     * @author liuhui05 at 2013-5-20 created but tangwen change at 2015-12-24
     */
    private function getSearchFileds(array \$allowOptions, array \$formOptions)
    {
        \$parms = array();
        foreach (\$allowOptions as \$key => \$option) {
            if (isset(\$formOptions[\$key])) {
                switch (\$option['type']) {
                    case 'numeric':     //自然数
                        if (is_numeric(\$formOptions[\$key]) && \$formOptions[\$key] > 0) {
                            \$parms[\$option['name']] = \$formOptions[\$key];
                        }
                        break;
                    case 'int':     //整型
                        if (is_numeric(\$formOptions[\$key])) {
                            \$parms[\$option['name']] = \$formOptions[\$key];
                        }
                        break;
                    case 'string':              //字符串
                        \$dsAllowStrings = array('\'', '"');     //简单过滤掉单引号，双引号
                        \$value = trim(str_replace(\$dsAllowStrings, '’', \$formOptions[\$key]));   //转换单双引号为汉字状态下的单引号
                        if (\$value) {
                            \$parms[\$option['name']] = \$value;
                        }
                        break;
                    case 'array':
                        \$value = array_intersect(\$option['max'], \$formOptions[\$key]);
                        if (\$value) {
                            \$parms[\$option['name']] = \$value;
                        }
                        break;
                    case 'date':
                        if (\$formOptions[\$key]) \$parms[\$option['name']] = strtotime(\$formOptions[\$key]);
                        break;
                }
            } else if (isset(\$option['default'])) {
                \$parms[\$option['name']] = \$option['default'];
            }
        }

        return \$parms;
    }

}
EOT;

        }

        return $content;
    }

    private function _createModel()
    {
        $modelName = strtolower($this->className);
        $className = ucfirst($this->className);
        $content = <<<EOT
<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class {$className}_Model extends MY_Model
{
	protected \$primary_table = "{$modelName}";
	protected \$primary_key = "{$modelName}_id";

	public function __construct()
    {
        parent::__construct();
        // 切换到mysql
        \$this->db = \$this->load->database('mysqllog', TRUE);
    }

    /**
     * 获取列表
     *
     * @param array \$param 查询数组
     * @return array
     * @author tangwen
     */

    public function getList(array \$params) {
         \$this->_buildWhere(\$param);

        if (isset(\$param['select'])) {
            \$this->db->select(\$param['select']);
        }

        if (isset(\$param['sort_by']) && \$param['sort_direction']) {
            \$data['sort_by'] = \$param['sort_by'];
            \$data['sort_direction'] = \$param['sort_direction'];
        }

        \$data['page'] = isset(\$param['page']) ? \$param['page'] : 1;
        \$data['pageSize'] = isset(\$param['pageSize']) ? \$param['pageSize'] : 15;

        \$result = \$this->get(\$data, true);

        return \$result;
    }

	public function findById(\$id) {
        return \$this->db->get(\$this->\$primary_table)->where('id' => \$id)->row_array();
	}


	public function getList(array \$param,\$isCount = TRUE){

	}

     /**
     * 构造查询条件
     * @param array \$param 查询数组
     * @return void
     * @author tangwen
     */
    private function _buildWhere(array \$param) {
        // 选某日 就是查询某天内每个小时的数据
        if (isset(\$param['date'])) {
            \$this->db->where('date >=', strtotime(date('Ymd', \$param['date'])));
            \$this->db->where('date <=', strtotime(date('Ymd 23:59:59', \$param['date'])));
        }

        // 查询开始日期
        if (isset(\$param['minDate'])) {
            \$param['minDate'] = strtotime(date('Ymd', \$param['minDate']));
            \$this->db->where('date >=', \$param['minDate']);
        }

        // 查询结束日期
        if (isset(\$param['maxDate'])) {
            \$param['maxDate'] = strtotime(date('Ymd', \$param['maxDate'])) + 24 * 3600 -1;
            \$this->db->where('date <=', \$param['maxDate']);
        }

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
<?php 

/**
 * 针对某个controller\model\helper文件的分析.
 * 
 */

class TestAnalyzer 
{
	protected $tag = "function";  // 需要解析的关键字标签

	protected $funcs = array(); // 所有函数数组

	protected $filePath = ''; // 解析文件

	/**
	 * 获取所有函数
	 * @param  string $tag 关键标签
	 * @return string
	 */
	public static function getFuncs($filePath = '', $tag = $this->tag)
	{
		if (!$filePath) exit("filepath could not be empty,and nothing to create test file!");
		$this->filePath = trim($filePath);
		$this->_analyzerByTag($tag);
	}

	/**
	 * 根据标签解析
	 * @param  string $tag 关键标签
	 * @return void
	 */
	public function _analyzerByTag($tag = $this->tag)
	{

	}
	
	/**
	 * 读取文件
	 * @return [type] [description]
	 */
	public function _load()
	{

	}
}
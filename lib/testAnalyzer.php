<?php 

/**
 * 针对某个controller\model\helper文件的分析.
 * 
 */

class TestAnalyzer 
{
	protected static $filePath = ''; // 解析文件

	/**
	 * 获取所有函数
	 * @param  string $tag 关键标签
	 * @return string
	 */
	public static function getFuncs($filePath = '', $tag = "function")
	{
		if (!$filePath) exit("filepath could not be empty,and nothing to create test file!");
		self::$filePath = trim($filePath);

		return self::_analyzerByTag($tag);
	}

	/**
	 * 根据标签解析
	 * @param  string $tag 关键标签
	 * @return void
	 */
	public static function _analyzerByTag($tag = "function")
	{	
		$pattern = "/function.*(.*).*\(/";
		preg_match_all($pattern, $str, $res);
        foreach ($res[0] as &$row) {
        	$row = trim(str_replace("(", "",str_replace("function", "", $row)));
        }

        return $res[0];
	}
	
	/**
	 * 读取文件
	 * @return [type] [description]
	 */
	public function _load()
	{
		if (!file_exists($this->filePath)) exit("the file doesn't exists!");

		return file_get_contents($this->filePath);
	}
}

// TestAnalyzer::getFuncs("sss");


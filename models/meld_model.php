<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class meld_Model extends CI_Model
{
	protected $primary_table = "meld";
	protected $primary_key = "meld_id";

	public function __construct() {
		parent::__construct();
	}

	public function findById() {

	}


	public function getList(array $param,$isCount = TRUE){

	}
}
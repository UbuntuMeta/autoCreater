<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class Melds extends CI_Controller
{
     function __construct()
    {
        parent::__construct();
        $this->load->model('melds_model');
    }

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

    public function _searchFields(array $param)
    {
    }

}
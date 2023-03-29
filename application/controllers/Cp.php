<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cp extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/Cp
	 *	- or -
	 * 		http://example.com/index.php/Cp/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/Cp/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct()
    {
        parent::__construct();
        $this->load->model('cp_model');
        $this->load->model('perms_model');
        $this->load->helper('cookie');
		$this->load->library("pagination");
		//Model Requirements
		$this->tableName = 'users';
		$this->primaryKey = 'id';
		//Form Fields
		$this->fields = array(
			'1|text' => '{email|البريد الالكتروني}',
			'1|password' => '{password|كلمة السر}'
		);
		$this->fieldsValidation = array(
			array('email','البريد الالكتروني','required'), 
			array('password','كلمة السر','required')
		);
    }
	public function index()
	{
		if(!$this->cp_model->is_logged_in()){
			redirect(base_url('cp/login'));
		}
		$data = array();
		$filter = ' ORDER BY id DESC LIMIT 5';
		$data['from'] = '';
		$data['to'] = '';
		$data['store_id'] = '';
        //Show categorys
		$data['transactions'] = $this->cp_model->getByData('transactions',$filter);
		$this->load->view('holders/header');
		$this->load->view('cp_home',$data);
		$this->load->view('holders/footer');
	}
	public function login()
	{
		if($this->cp_model->is_logged_in()){
			redirect(base_url('cp/'));
		}
		$data = array();
		//Form Fields
		$data['fields'] = $this->fields;
		$this->load->view('login',$data);
	}
	public function checkLogin()
	{
		if($this->cp_model->is_logged_in()){
			redirect(base_url('cp/'));
		}
		$data = array();
		//Form Fields
		$data['fields'] = $this->fields;
		$data['fieldsValidation'] = $this->fieldsValidation;
		$loginData = array(
			'email' => strip_tags($this->input->post('email')),
			'password' => strip_tags($this->input->post('password'))
		);
		$this->cp_model->validate($data['fieldsValidation']);
		if ($this->form_validation->run()){
			if($this->cp_model->login($loginData)){
				redirect(base_url());
			}else{
				$data = array();
				//Form Fields
				$data['process'] = 1;
				$data['fields'] = $this->fields;
				$this->load->view('login',$data);
			}
		}else{
			$this->login();
		}
	}
	public function logout(){
		$this->session->sess_destroy();
		redirect(base_url());
	}
}

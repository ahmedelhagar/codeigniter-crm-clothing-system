<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminControllerUsers extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/AdminControllerUsers
	 *	- or -
	 * 		http://example.com/index.php/AdminControllerUsers/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/AdminControllerUsers/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('users_model');
        $this->load->model('perms_model');
        $this->load->helper('cookie');
		$this->load->library("pagination");
		//Model Requirements
		$this->tableName = 'users';
		$this->primaryKey = 'id';
		//Form Fields
		$this->fields = array(
			'1|file' => '{image|الصورة الشخصية}',
			'3|text' => '{name|اسم العضو}--{email|البريد الالكتروني}--{mobile|رقم الهاتف}',
			'1|password' => '{password|كلمة السر}',
			'10|checkbox' => '{products|التحكم الكامل في المنتجات}--{barcode|اصدار باركود}--{viewProducts|عرض احصائيات منتج}--{acceptProducts|قبول منتج}--{users|التحكم الكامل في الأعضاء}--{balance|الأرصدة سحب وايداع}--{cashier|الكاشير}--{cashierReports|تقارير الكاشير}--{stores|التحكم في المحلات}--{storages|التحكم في المخازن}'
		);
		$this->fieldsValidation = array(
			array('name','اسم العضو كاملاً','required'), 
			array('email','البريد الالكتروني','required'), 
			array('mobile','رقم الهاتف','required'), 
			array('password','كلمة السر','required')
		);
		if(!$this->users_model->is_logged_in()){
			redirect(base_url('cp/login'));
		}
    }
	public function show()
	{
		$this->perms_model->getPerms('users');
		$data = array();
		//Get All Users - More in Users_model
		$data['all_users'] = $this->users_model->getFullRequest(
			$this->tableName,
			'(state IS NULL)',
			'',
			'',
			''
		);
        //Show Users
		$this->load->view('holders/header');
		$this->load->view('AdminControllerUsers_Views/AdminControllerUsers_show',$data);
		$this->load->view('holders/footer');
	}
	public function messages()
	{
		$this->perms_model->getPerms('users');
		$data = array();
        //Show Users
		$this->load->view('holders/header');
		$this->load->view('AdminControllerUsers_Views/AdminControllerUsers_messages',$data);
		$this->load->view('holders/footer');
	}
	public function view()
	{
		$this->perms_model->getPerms('users');
		$data = array();
		//Get User - More in Users_model
		$id = (int) $this->uri->segment(3);
		$data['user'] = $this->users_model->getFullRequest(
			$this->tableName,
			'(id = '.$id.')',
			'',
			'',
			''
		);
        //Show Users
		$this->load->view('holders/header');
		$this->load->view('AdminControllerUsers_Views/AdminControllerUsers_view',$data);
		$this->load->view('holders/footer');
	}
	public function create()
	{
		$this->perms_model->getPerms('users');
		$data = array();
		//Form Fields
		$data['fields'] = $this->fields;
		//Add User Form
		$this->load->view('holders/header');
		$this->load->view('AdminControllerUsers_Views/AdminControllerUsers_form',$data);
		$this->load->view('holders/footer');
	}
	public function write()
	{
		$this->perms_model->getPerms('users');
		$data = array();
		//Form Fields
		$data['fields'] = $this->fields;
		//Form Fields Validation
		$this->users_model->validate($this->fieldsValidation);
		if ($this->form_validation->run()){
			$this->users_model->insertRequest($this->tableName,$this->fieldsValidation);
			$data['process'] = 1;
			$this->load->view('holders/header');
			$this->load->view('AdminControllerUsers_Views/AdminControllerUsers_form',$data);
			$this->load->view('holders/footer');
		}else{
			$this->create();
		}
	}
	public function edit()
	{
		$this->perms_model->getPerms('users');
		$data = array();
		//Form Fields
		$data['fields'] = $this->fields;
		//Get User - More in Users_model
		$id = (int) $this->uri->segment(3);
		$data['user'] = $this->users_model->getFullRequest(
			$this->tableName,
			'(id = '.$id.')',
			'',
			'',
			''
		);
		//Add User Form
		$this->load->view('holders/header');
		$this->load->view('AdminControllerUsers_Views/AdminControllerUsers_form',$data);
		$this->load->view('holders/footer');
	}
	public function update()
	{
		$this->perms_model->getPerms('users');
		$data = array();
		//Form Fields
		$data['fields'] = $this->fields;
		//Get User - More in Users_model
		$id = (int) $this->uri->segment(3);
		$data['user'] = $this->users_model->getFullRequest(
			$this->tableName,
			'(id = '.$id.')',
			'',
			'',
			''
		);
		//Form Fields Validation
		$this->users_model->validate($this->fieldsValidation,array('image','password'));
		if ($this->form_validation->run()){
			$this->users_model->updateRequest($this->tableName,$this->fieldsValidation,'id',$data['user'][0]);
			$data['user'] = $this->users_model->getFullRequest(
				$this->tableName,
				'(id = '.$id.')',
				'',
				'',
				''
			);
			$data['process'] = 1;
			$this->load->view('holders/header');
			$this->load->view('AdminControllerUsers_Views/AdminControllerUsers_view',$data);
			$this->load->view('holders/footer');
		}else{
			$this->create();
		}
	}
	public function delete(){
		$this->perms_model->getPerms('users');
		$id = (int) $this->uri->segment(3);
		$data['user'] = $this->users_model->getFullRequest(
			$this->tableName,
			'id = '.$id,
			'',
			'',
			''
		);
		if($data['user']){
			$this->users_model->deleteRequest($this->tableName,'id',$id);
			redirect(base_url('adminControllerUsers/show'));
		}else{
			redirect(base_url('adminControllerUsers/show'));
		}
	}
}

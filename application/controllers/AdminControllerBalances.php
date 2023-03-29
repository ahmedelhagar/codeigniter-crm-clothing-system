<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminControllerBalances extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/AdminControllerbalances
	 *	- or -
	 * 		http://example.com/index.php/AdminControllerbalances/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/AdminControllerbalances/<method_name>
	 * @see https://codeigniter.com/balance_guide/general/urls.html
	 */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('balances_model');
		$this->load->model('perms_model');
        $this->load->helper('cookie');
		$this->load->library("pagination");
		//Model Requirements
		$this->tableName = 'balances';
		$this->primaryKey = 'id';
		//Form Fields
		$this->fields = array(
			'1|text' => '{amount|المبلغ}',
			'1|textarea' => '{reason|السبب}',
			'2|dropdown' => '{state|الحالة}--{place_id|المكان}'
		);
		$this->fieldsValidation = array(
			array('amount','المبلغ','required'),
			array('reason','السبب','required'),
			array('place_id','الفرع','required'),
			array('state','السبب','required')
		);
		if(!$this->balances_model->is_logged_in()){
			redirect(base_url('cp/login'));
		}
    }
	public function show()
	{
		$this->perms_model->getPerms('balance');
		$data = array();
		$data['store_id'] = '';
		if($this->input->post('from') && $this->input->post('to')){
			if($this->input->post('store')) {
					$store = ' AND (place_id = '.$this->input->post('store').')';
					$data['store_id'] = $this->input->post('store');
			}else{
					$store = '';
			}
			$filter = " created_at
			between TIMESTAMP('".str_replace('/','-',$this->input->post('from'))." 00:00:00.000000')
			and TIMESTAMP('".str_replace('/','-',$this->input->post('to'))." 23:59:00.000000')".$store." ORDER BY id DESC";
			$data['from'] = $this->input->post('from');
			$data['to'] = $this->input->post('to');
		}else{
			$filter = ' (id IS NOT NULL) ORDER BY id DESC';
			$data['from'] = '';
			$data['to'] = '';
		}
		//Get All balances - More in balances_model
		$data['all_balances'] = $this->balances_model->getFullRequest(
			$this->tableName,
			$filter,
			'',
			'',
			''
		);
        //Show balances
		$this->load->view('holders/header');
		$this->load->view('AdminControllerBalances_Views/AdminControllerBalances_show',$data);
		$this->load->view('holders/footer');
	}
	public function create()
	{
		$this->perms_model->getPerms('balance');
		$data = array();
		//Form Fields
		$data['fields'] = $this->fields;
		//Add balance Form
		$this->load->view('holders/header');
		$this->load->view('AdminControllerBalances_Views/AdminControllerBalances_form',$data);
		$this->load->view('holders/footer');
	}
	public function write()
	{
		$this->perms_model->getPerms('balance');
		$data = array();
		//Form Fields
		$data['fields'] = $this->fields;
		//Form Fields Validation
		$this->balances_model->validate($this->fieldsValidation);
		if ($this->form_validation->run()){
			$this->balances_model->insertRequest($this->tableName,$this->fieldsValidation);
			$data['process'] = 1;
			$this->load->view('holders/header');
			$this->load->view('AdminControllerBalances_Views/AdminControllerBalances_form',$data);
			$this->load->view('holders/footer');
		}else{
			$this->create();
		}
	}
	public function edit()
	{
		$this->perms_model->getPerms('balance');
		$data = array();
		//Form Fields
		$data['fields'] = $this->fields;
		//Get balance - More in balances_model
		$id = (int) $this->uri->segment(3);
		$data['balance'] = $this->balances_model->getFullRequest(
			$this->tableName,
			'(id = '.$id.')',
			'',
			'',
			''
		);
		//Add balance Form
		$this->load->view('holders/header');
		$this->load->view('AdminControllerBalances_Views/AdminControllerBalances_form',$data);
		$this->load->view('holders/footer');
	}
}

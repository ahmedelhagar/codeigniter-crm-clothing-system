<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminControllerFactories extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/AdminControllerFactories
	 *	- or -
	 * 		http://example.com/index.php/AdminControllerFactories/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/AdminControllerFactories/<method_name>
	 * @see https://codeigniter.com/factory_guide/general/urls.html
	 */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('factories_model');
        $this->load->helper('cookie');
		$this->load->library("pagination");
		//Model Requirements
		$this->tableName = 'factories';
		$this->primaryKey = 'id';
		//Form Fields
		$this->fields = array(
			'2|text' => '{name|اسم الفرع}--{address|عنوان الفرع}'
		);
		$this->fieldsValidation = array(
			array('name','اسم الفرع','required'),
			array('address','عنوان الفرع','required')
		);
		if(!$this->factories_model->is_logged_in()){
			redirect(base_url('cp/login'));
		}
    }
	public function show()
	{
		$data = array();
		//Get All factories - More in factories_model
		$data['all_factories'] = $this->factories_model->getFullRequest(
			$this->tableName,
			'',
			'',
			'',
			''
		);
        //Show factories
		$this->load->view('holders/header');
		$this->load->view('AdminControllerFactories_Views/AdminControllerFactories_show',$data);
		$this->load->view('holders/footer');
	}
	public function products()
	{
		$data = array();
		$id = (int) $this->uri->segment(3);
		//Get Factory
		$data['factory'] = $this->factories_model->getByData('factories',' WHERE (id = '.$id.')');
		if(!$data['factory']){
			redirect(base_url('404'));
		}
		//Get All products - More in factories_model
		$data['all_moved_products'] = $this->factories_model->getFullRequest('movestock','(place = \'factory\') AND (place_id = '.$id.') AND (state = 0)');
		$data['all_done_moved_products'] = $this->factories_model->getByData('stock',' WHERE (place = \'factory\') AND (place_id = '.$id.')');
        //Show products
		$this->load->view('holders/header');
		$this->load->view('AdminControllerFactories_Views/AdminControllerFactories_products',$data);
		$this->load->view('holders/footer');
	}
	public function acceptStock(){
		$data = array();
		$id = (int) $this->uri->segment(3);
		//Get Factory
		$data['movestock'] = $this->factories_model->getByData('movestock',' WHERE (id = '.$id.')');
		if(!$data['movestock']){
			redirect(base_url('404'));
		}elseif($data['movestock'][0]['state']){
			redirect(base_url('404'));
		}else{
			$data['movestock'] = $data['movestock'][0];
		}
		$data['factory'] = $this->factories_model->getByData('factories',' WHERE (id = '.$data['movestock']['place_id'].')');
		if($data['factory']){
			$data['factory'] = $data['factory'][0];
		}
		if($data['movestock']['quantity'] > 0){
			//Print
			$this->load->view('AdminControllerFactories_Views/AdminControllerFactories_print',$data);
		}
		$data['movestock']['movestock_id'] = $data['movestock']['id'];
		unset($data['movestock']['from_place']);
		unset($data['movestock']['from_place_id']);
		unset($data['movestock']['with_u_id']);
		unset($data['movestock']['created_at']);
		unset($data['movestock']['edited_at']);
		unset($data['movestock']['state']);
		$data['movestock']['editors_u_id'] = NULL;
		//Accept Proccess
		$stock = $this->factories_model->getByData('stock',' WHERE (place = "factory") AND (place_id = '.$data['movestock']['place_id'].') AND (p_id = '.$data['movestock']['p_id'].')');
		if($stock){
			//Update
			$this->factories_model->updateOriginalRequest('stock',array(
				'quantity' => $data['movestock']['quantity']+$stock[0]['quantity']
			),'id',$stock[0]['id']);
		}else{
			unset($data['movestock']['id']);
			//Insert
			$this->factories_model->insertOriginalRequest('stock',$data['movestock']);
		}
		$this->factories_model->updateOriginalRequest('movestock',array(
			'state' => 1
		),'id',$id);
		if($data['movestock']['quantity'] < 1){
			redirect(base_url('AdminControllerFactories/products/'.$data['factory']['id']));
		}
	}
	public function addstock()
	{
		$data = array();
		//Get User - More in Users_model
		$id = (int) $this->uri->segment(3);
		$place_id = (int) $this->uri->segment(5);
		$data['product'] = $this->factories_model->getFullRequest(
			'products',
			'(id = '.$id.')',
			'',
			'',
			''
		);
		$data['factory'] = $this->factories_model->getByData('factories',' WHERE (id = '.$place_id.')');
		if($data['factory']){
			$data['factory'] = $data['factory'][0];
		}
		if(!$data['product']){
			redirect(base_url('404'));
		}
		//Form Fields
		$data['fields'] = array(
			'1|number' => '{number|الكمية}'
		);
        //Show Users
		$this->load->view('holders/header');
		$this->load->view('AdminControllerFactories_Views/AdminControllerFactories_addstock',$data);
		$this->load->view('holders/footer');
	}
	public function writestock()
	{
		$data = array();
		//Get User - More in Users_model
		$id = (int) $this->uri->segment(3);
		$factory_id = (int) $this->uri->segment(4);
		$data['product'] = $this->factories_model->getFullRequest(
			'products',
			'(id = '.$id.')',
			'',
			'1',
			''
		);
		$data['factory'] = $this->factories_model->getByData('factories',' WHERE (id = '.$factory_id.')');
		if(!$data['factory']){
			redirect(base_url('404'));
		}
		if(!$data['product']){
			redirect(base_url('404'));
		}
		$data['factory'] = $data['factory'][0];
		$tableData['place'] = 'factory';
		$tableData['place_id'] = $factory_id;
		$tableData['from_place'] = 'factory';
		$tableData['from_place_id'] = 0;
        $tableData['edited_at'] = $this->factories_model->dataTime();
		$tableData['p_id'] = $id;
		$tableData['quantity'] = (int) $this->input->post('number');
		$tableData['with_u_id'] = $this->session->userdata('id');
		$tableData['state'] = 1;
		//Insert Proccess
		$stock = $this->factories_model->getByData('stock',' WHERE (place = "factory") AND (place_id = '.$tableData['place_id'].') AND (p_id = '.$tableData['p_id'].')');
		if($stock){
			//Update
			$this->factories_model->updateOriginalRequest('stock',array(
				'quantity' => $tableData['quantity']+$stock[0]['quantity']
			),'id',$stock[0]['id']);
		}
		//Form Fields
		$data['fields'] = array(
			'1|number' => '{number|العدد}'
		);
		$data['process'] = 1;
        //Show Users
		$this->load->view('holders/header');
		$this->load->view('AdminControllerFactories_Views/AdminControllerFactories_addstock',$data);
		$this->load->view('holders/footer');
	}
	public function create()
	{
		$data = array();
		//Form Fields
		$data['fields'] = $this->fields;
		//Add factory Form
		$this->load->view('holders/header');
		$this->load->view('AdminControllerFactories_Views/AdminControllerFactories_form',$data);
		$this->load->view('holders/footer');
	}
	public function write()
	{
		$data = array();
		//Form Fields
		$data['fields'] = $this->fields;
		//Form Fields Validation
		$this->factories_model->validate($this->fieldsValidation);
		if ($this->form_validation->run()){
			$this->factories_model->insertRequest($this->tableName,$this->fieldsValidation);
			$data['process'] = 1;
			$this->load->view('holders/header');
			$this->load->view('AdminControllerFactories_Views/AdminControllerFactories_form',$data);
			$this->load->view('holders/footer');
		}else{
			$this->create();
		}
	}
	public function edit()
	{
		$data = array();
		//Form Fields
		$data['fields'] = $this->fields;
		//Get factory - More in factories_model
		$id = (int) $this->uri->segment(3);
		$data['factory'] = $this->factories_model->getFullRequest(
			$this->tableName,
			'(id = '.$id.')',
			'',
			'',
			''
		);
		//Add factory Form
		$this->load->view('holders/header');
		$this->load->view('AdminControllerFactories_Views/AdminControllerFactories_form',$data);
		$this->load->view('holders/footer');
	}
	public function update()
	{
		$data = array();
		//Form Fields
		$data['fields'] = $this->fields;
		//Get factory - More in factories_model
		$id = (int) $this->uri->segment(3);
		$data['factory'] = $this->factories_model->getFullRequest(
			$this->tableName,
			'(id = '.$id.')',
			'',
			'',
			''
		);
		//Form Fields Validation
		$this->factories_model->validate($this->fieldsValidation,array('image','password'));
		if ($this->form_validation->run()){
			$this->factories_model->updateRequest($this->tableName,$this->fieldsValidation,'id',$data['factory'][0]);
			$data['all_factories'] = $this->factories_model->getFullRequest(
				$this->tableName,
				'',
				'',
				'',
				''
			);
			$data['process'] = 1;
			$this->load->view('holders/header');
			$this->load->view('AdminControllerFactories_Views/AdminControllerFactories_show',$data);
			$this->load->view('holders/footer');
		}else{
			$this->create();
		}
	}
	public function delete(){
		$id = (int) $this->uri->segment(3);
		$data['factory'] = $this->factories_model->getFullRequest(
			$this->tableName,
			'id = '.$id,
			'',
			'',
			''
		);
		if($data['factory']){
			$this->factories_model->deleteRequest($this->tableName,'id',$id);
			redirect(base_url('adminControllerFactories/show'));
		}else{
			redirect(base_url('adminControllerFactories/show'));
		}
	}
}

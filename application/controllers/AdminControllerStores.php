<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminControllerStores extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/AdminControllerstores
	 *	- or -
	 * 		http://example.com/index.php/AdminControllerstores/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/AdminControllerstores/<method_name>
	 * @see https://codeigniter.com/store_guide/general/urls.html
	 */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('stores_model');
		$this->load->model('cashier_model');
        $this->load->helper('cookie');
		$this->load->library("pagination");
		$this->load->model('perms_model');
		//Model Requirements
		$this->tableName = 'stores';
		$this->primaryKey = 'id';
		//Form Fields
		$this->fields = array(
			'2|text' => '{name|اسم الفرع}--{address|عنوان الفرع}'
		);
		$this->fieldsValidation = array(
			array('name','اسم الفرع','required'),
			array('address','عنوان الفرع','required')
		);
		if(!$this->stores_model->is_logged_in()){
			redirect(base_url('cp/login'));
		}
    }
	public function show()
	{
		$this->perms_model->getPerms('stores');
		$data = array();
		//Get All stores - More in stores_model
		$data['all_stores'] = $this->stores_model->getFullRequest(
			$this->tableName,
			'',
			'',
			'',
			''
		);
        //Show stores
		$this->load->view('holders/header');
		$this->load->view('AdminControllerStores_Views/AdminControllerStores_show',$data);
		$this->load->view('holders/footer');
	}
	public function create()
	{
		$this->perms_model->getPerms('stores');
		$data = array();
		//Form Fields
		$data['fields'] = $this->fields;
		//Add store Form
		$this->load->view('holders/header');
		$this->load->view('AdminControllerStores_Views/AdminControllerStores_form',$data);
		$this->load->view('holders/footer');
	}
	public function write()
	{
		$this->perms_model->getPerms('stores');
		$data = array();
		//Form Fields
		$data['fields'] = $this->fields;
		//Form Fields Validation
		$this->stores_model->validate($this->fieldsValidation);
		if ($this->form_validation->run()){
			$this->stores_model->insertRequest($this->tableName,$this->fieldsValidation);
			$data['process'] = 1;
			$this->load->view('holders/header');
			$this->load->view('AdminControllerStores_Views/AdminControllerStores_form',$data);
			$this->load->view('holders/footer');
		}else{
			$this->create();
		}
	}
	public function edit()
	{
		$this->perms_model->getPerms('stores');
		$data = array();
		//Form Fields
		$data['fields'] = $this->fields;
		//Get store - More in stores_model
		$id = (int) $this->uri->segment(3);
		$data['store'] = $this->stores_model->getFullRequest(
			$this->tableName,
			'(id = '.$id.')',
			'',
			'',
			''
		);
		//Add store Form
		$this->load->view('holders/header');
		$this->load->view('AdminControllerStores_Views/AdminControllerStores_form',$data);
		$this->load->view('holders/footer');
	}
	public function update()
	{
		$this->perms_model->getPerms('stores');
		$data = array();
		//Form Fields
		$data['fields'] = $this->fields;
		//Get store - More in stores_model
		$id = (int) $this->uri->segment(3);
		$data['store'] = $this->stores_model->getFullRequest(
			$this->tableName,
			'(id = '.$id.')',
			'',
			'',
			''
		);
		//Form Fields Validation
		$this->stores_model->validate($this->fieldsValidation,array('image','password'));
		if ($this->form_validation->run()){
			$this->stores_model->updateRequest($this->tableName,$this->fieldsValidation,'id',$data['store'][0]);
			$data['all_stores'] = $this->stores_model->getFullRequest(
				$this->tableName,
				'',
				'',
				'',
				''
			);
			$data['process'] = 1;
			$this->load->view('holders/header');
			$this->load->view('AdminControllerStores_Views/AdminControllerStores_show',$data);
			$this->load->view('holders/footer');
		}else{
			$this->create();
		}
	}
	public function delete(){
		$this->perms_model->getPerms('stores');
		$id = (int) $this->uri->segment(3);
		$data['store'] = $this->stores_model->getFullRequest(
			$this->tableName,
			'id = '.$id,
			'',
			'',
			''
		);
		if($data['store']){
			$this->stores_model->deleteRequest($this->tableName,'id',$id);
			redirect(base_url('adminControllerStores/show'));
		}else{
			redirect(base_url('adminControllerStores/show'));
		}
	}
	public function products()
	{
		$this->perms_model->getPerms('stores');
		$data = array();
		$id = (int) $this->uri->segment(3);
		//Get store
		$data['store'] = $this->stores_model->getByData('stores',' WHERE (id = '.$id.')');
		if(!$data['store']){
			redirect(base_url('404'));
		}
		//Get All products - More in stores_model
		$data['all_moved_products'] = $this->stores_model->getFullRequest('movestock','(place = \'store\') AND (place_id = '.$id.') AND (state = 0)');
		$data['all_done_moved_products'] = $this->stores_model->getByData('stock',' WHERE (place = \'store\') AND (place_id = '.$id.')');
        //Show products
		$this->load->view('holders/header');
		$this->load->view('AdminControllerStores_Views/AdminControllerStores_products',$data);
		$this->load->view('holders/footer');
	}
	public function acceptStock(){
		$this->perms_model->getPerms('acceptProducts');
		$data = array();
		$id = (int) $this->uri->segment(3);
		//Get store
		$data['movestock'] = $this->stores_model->getByData('movestock',' WHERE (id = '.$id.')');
		if(!$data['movestock']){
			redirect(base_url('404'));
		}elseif($data['movestock'][0]['state']){
			redirect(base_url('404'));
		}else{
			$data['movestock'] = $data['movestock'][0];
		}
		$data['store'] = $this->stores_model->getByData('stores',' WHERE (id = '.$data['movestock']['place_id'].')');
		if($data['store']){
			$data['store'] = $data['store'][0];
		}
		if($data['movestock']['quantity'] > 0){
			//Print
			$this->load->view('AdminControllerStores_Views/AdminControllerStores_print',$data);
		}
		$moveData = $data['movestock'];
		$data['movestock']['movestock_id'] = $data['movestock']['id'];
		unset($data['movestock']['from_place']);
		unset($data['movestock']['from_place_id']);
		unset($data['movestock']['with_u_id']);
		unset($data['movestock']['created_at']);
		unset($data['movestock']['edited_at']);
		unset($data['movestock']['state']);
		$data['movestock']['editors_u_id'] = NULL;
		//Accept Proccess
		$stock = $this->stores_model->getByData('stock',' WHERE (place = "store") AND (place_id = '.$data['movestock']['place_id'].') AND (p_created_at = \''.$data['movestock']['p_created_at'].'\')');
		if($stock){
			//Update
			$this->stores_model->updateOriginalRequest('stock',array(
				'quantity' => $data['movestock']['quantity']+$stock[0]['quantity']
			),'id',$stock[0]['id']);
		}else{
			unset($data['movestock']['id']);
			//Insert
			$this->stores_model->insertOriginalRequest('stock',$data['movestock']);
		}
		//Insert Move For Analatics
		unset($moveData['from_place']);
		unset($moveData['from_place_id']);
		unset($moveData['with_u_id']);
		unset($moveData['movestock_id']);
		unset($moveData['editors_u_id']);
		unset($moveData['id']);
		unset($moveData['state']);
		$product = $this->stores_model->getByData('products',' WHERE (created_at = \''.$moveData['p_created_at'].'\')');
		unset($moveData['p_id']);
		$product = (array) $product[0];
		$moveData['p_created_at'] = $product['created_at'];
		$moveData['method'] = 'add';
		$moveData['state'] = 'move';
		$this->stores_model->insertOriginalRequest('moves',$moveData);
		
		$this->stores_model->updateOriginalRequest('movestock',array(
			'state' => 1
		),'id',$id);
		if($data['movestock']['quantity'] < 1){
			redirect(base_url('AdminControllerStores/products/'.$data['store']['id']));
		}
	}
}

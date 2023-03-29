<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminControllerStorages extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/AdminControllerStorages
	 *	- or -
	 * 		http://example.com/index.php/AdminControllerStorages/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/AdminControllerStorages/<method_name>
	 * @see https://codeigniter.com/storage_guide/general/urls.html
	 */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('storages_model');
		$this->load->model('cashier_model');
        $this->load->helper('cookie');
		$this->load->library("pagination");
		$this->load->model('perms_model');
		//Model Requirements
		$this->tableName = 'storages';
		$this->primaryKey = 'id';
		//Form Fields
		$this->fields = array(
			'2|text' => '{name|اسم الفرع}--{address|عنوان الفرع}'
		);
		$this->fieldsValidation = array(
			array('name','اسم الفرع','required'),
			array('address','عنوان الفرع','required')
		);
		if(!$this->storages_model->is_logged_in()){
			redirect(base_url('cp/login'));
		}
    }
	public function show()
	{
		$this->perms_model->getPerms('storages');
		$data = array();
		//Get All factories - More in storages_model
		$data['all_storages'] = $this->storages_model->getFullRequest(
			$this->tableName,
			'',
			'',
			'',
			''
		);
        //Show factories
		$this->load->view('holders/header');
		$this->load->view('AdminControllerStorages_Views/AdminControllerStorages_show',$data);
		$this->load->view('holders/footer');
	}
	public function products()
	{
		$this->perms_model->getPerms('storages');
		$data = array();
		$id = (int) $this->uri->segment(3);
		//Get Factory
		$data['storage'] = $this->storages_model->getByData('storages',' WHERE (id = '.$id.')');
		if(!$data['storage']){
			redirect(base_url('404'));
		}
		//Get All products - More in storages_model
		$data['all_moved_products'] = $this->storages_model->getFullRequest('movestock','(place = \'storage\') AND (place_id = '.$id.') AND (state = 0)');
		$data['all_done_moved_products'] = $this->storages_model->getByData('stock',' WHERE (place = \'storage\') AND (place_id = '.$id.')');
        //Show products
		$this->load->view('holders/header');
		$this->load->view('AdminControllerStorages_Views/AdminControllerStorages_products',$data);
		$this->load->view('holders/footer');
	}
	public function acceptStock(){
		$this->perms_model->getPerms('acceptProducts');
		$data = array();
		$id = (int) $this->uri->segment(3);
		//Get Factory
		$data['movestock'] = $this->storages_model->getByData('movestock',' WHERE (id = '.$id.')');
		if(!$data['movestock']){
			redirect(base_url('404'));
		}elseif($data['movestock'][0]['state']){
			redirect(base_url('404'));
		}else{
			$data['movestock'] = $data['movestock'][0];
		}
		$data['storage'] = $this->storages_model->getByData('storages',' WHERE (id = '.$data['movestock']['place_id'].')');
		if($data['storage']){
			$data['storage'] = $data['storage'][0];
		}
		if($data['movestock']['quantity'] > 0){
			//Print
			$this->load->view('AdminControllerStorages_Views/AdminControllerStorages_print',$data);
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
		$stock = $this->storages_model->getByData('stock',' WHERE (place = "storage") AND (place_id = '.$data['movestock']['place_id'].') AND (p_created_at = \''.$data['movestock']['p_created_at'].'\')');
		if($stock){
			//Update
			$this->storages_model->updateOriginalRequest('stock',array(
				'quantity' => $data['movestock']['quantity']+$stock[0]['quantity']
			),'id',$stock[0]['id']);
		}else{
			unset($data['movestock']['id']);
			//Insert
			$this->storages_model->insertOriginalRequest('stock',$data['movestock']);
		}
		//Insert Move For Analatics
		unset($moveData['from_place']);
		unset($moveData['from_place_id']);
		unset($moveData['with_u_id']);
		unset($moveData['state']);
		$product = $this->storages_model->getByData('products',' WHERE (created_at = \''.$moveData['p_created_at'].'\')');
		unset($moveData['p_id']);
		unset($moveData['id']);
		$product = (array) $product[0];
		$moveData['p_created_at'] = $product['created_at'];
		$moveData['quantity'] = $data['movestock']['quantity'];
		$moveData['method'] = 'add';
		$moveData['state'] = 'move';
		$this->storages_model->insertOriginalRequest('moves',$moveData);
		$this->storages_model->updateOriginalRequest('movestock',array(
			'state' => 1
		),'id',$id);
		if($data['movestock']['quantity'] < 1){
			redirect(base_url('AdminControllerStorages/products/'.$data['factory']['id']));
		}
	}
	public function create()
	{
		$this->perms_model->getPerms('storages');
		$data = array();
		//Form Fields
		$data['fields'] = $this->fields;
		//Add storage Form
		$this->load->view('holders/header');
		$this->load->view('AdminControllerStorages_Views/AdminControllerStorages_form',$data);
		$this->load->view('holders/footer');
	}
	public function write()
	{
		$this->perms_model->getPerms('storages');
		$data = array();
		//Form Fields
		$data['fields'] = $this->fields;
		//Form Fields Validation
		$this->storages_model->validate($this->fieldsValidation);
		if ($this->form_validation->run()){
			$this->storages_model->insertRequest($this->tableName,$this->fieldsValidation);
			$data['process'] = 1;
			$this->load->view('holders/header');
			$this->load->view('AdminControllerStorages_Views/AdminControllerStorages_form',$data);
			$this->load->view('holders/footer');
		}else{
			$this->create();
		}
	}
	public function edit()
	{
		$this->perms_model->getPerms('storages');
		$data = array();
		//Form Fields
		$data['fields'] = $this->fields;
		//Get storage - More in storages_model
		$id = (int) $this->uri->segment(3);
		$data['storage'] = $this->storages_model->getFullRequest(
			$this->tableName,
			'(id = '.$id.')',
			'',
			'',
			''
		);
		//Add storage Form
		$this->load->view('holders/header');
		$this->load->view('AdminControllerStorages_Views/AdminControllerStorages_form',$data);
		$this->load->view('holders/footer');
	}
	public function update()
	{
		$this->perms_model->getPerms('storages');
		$data = array();
		//Form Fields
		$data['fields'] = $this->fields;
		//Get storage - More in storages_model
		$id = (int) $this->uri->segment(3);
		$data['storage'] = $this->storages_model->getFullRequest(
			$this->tableName,
			'(id = '.$id.')',
			'',
			'',
			''
		);
		//Form Fields Validation
		$this->storages_model->validate($this->fieldsValidation,array('image','password'));
		if ($this->form_validation->run()){
			$this->storages_model->updateRequest($this->tableName,$this->fieldsValidation,'id',$data['storage'][0]);
			$data['all_storages'] = $this->storages_model->getFullRequest(
				$this->tableName,
				'',
				'',
				'',
				''
			);
			$data['process'] = 1;
			$this->load->view('holders/header');
			$this->load->view('AdminControllerStorages_Views/AdminControllerStorages_show',$data);
			$this->load->view('holders/footer');
		}else{
			$this->create();
		}
	}
	public function delete(){
		$this->perms_model->getPerms('storages');
		$id = (int) $this->uri->segment(3);
		$data['storage'] = $this->storages_model->getFullRequest(
			$this->tableName,
			'id = '.$id,
			'',
			'',
			''
		);
		if($data['storage']){
			$this->storages_model->deleteRequest($this->tableName,'id',$id);
			redirect(base_url('AdminControllerStorages/show'));
		}else{
			redirect(base_url('AdminControllerStorages/show'));
		}
	}
}

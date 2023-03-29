<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminControllerOffices extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/AdminControllerOffices
	 *	- or -
	 * 		http://example.com/index.php/AdminControllerOffices/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/AdminControllerOffices/<method_name>
	 * @see https://codeigniter.com/office_guide/general/urls.html
	 */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('offices_model');
        $this->load->helper('cookie');
		$this->load->library("pagination");
		//Model Requirements
		$this->tableName = 'offices';
		$this->primaryKey = 'id';
		//Form Fields
		$this->fields = array(
			'2|text' => '{name|اسم الفرع}--{address|عنوان الفرع}'
		);
		$this->fieldsValidation = array(
			array('name','اسم الفرع','required'),
			array('address','عنوان الفرع','required')
		);
		if(!$this->offices_model->is_logged_in()){
			redirect(base_url('cp/login'));
		}
    }
	public function show()
	{
		$data = array();
		//Get All factories - More in offices_model
		$data['all_offices'] = $this->offices_model->getFullRequest(
			$this->tableName,
			'',
			'',
			'',
			''
		);
        //Show factories
		$this->load->view('holders/header');
		$this->load->view('AdminControllerOffices_Views/AdminControllerOffices_show',$data);
		$this->load->view('holders/footer');
	}
	public function products()
	{
		$data = array();
		$id = (int) $this->uri->segment(3);
		//Get Factory
		$data['office'] = $this->offices_model->getByData('offices',' WHERE (id = '.$id.')');
		if(!$data['office']){
			redirect(base_url('404'));
		}
		//Get All products - More in offices_model
		$data['all_moved_products'] = $this->offices_model->getFullRequest('movestock','(place = \'office\') AND (place_id = '.$id.') AND (state = 0)');
		$data['all_done_moved_products'] = $this->offices_model->getByData('stock',' WHERE (place = \'office\') AND (place_id = '.$id.')');
        //Show products
		$this->load->view('holders/header');
		$this->load->view('AdminControllerOffices_Views/AdminControllerOffices_products',$data);
		$this->load->view('holders/footer');
	}
	public function acceptStock(){
		$data = array();
		$id = (int) $this->uri->segment(3);
		//Get Factory
		$data['movestock'] = $this->offices_model->getByData('movestock',' WHERE (id = '.$id.')');
		if(!$data['movestock']){
			redirect(base_url('404'));
		}elseif($data['movestock'][0]['state']){
			redirect(base_url('404'));
		}else{
			$data['movestock'] = $data['movestock'][0];
		}
		$data['office'] = $this->offices_model->getByData('offices',' WHERE (id = '.$data['movestock']['place_id'].')');
		if($data['office']){
			$data['office'] = $data['office'][0];
		}
		if($data['movestock']['quantity'] > 0){
			//Print
			$this->load->view('AdminControllerOffices_Views/AdminControllerOffices_print',$data);
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
		$stock = $this->offices_model->getByData('stock',' WHERE (place = "office") AND (place_id = '.$data['movestock']['place_id'].') AND (p_id = '.$data['movestock']['p_id'].')');
		if($stock){
			//Update
			$this->offices_model->updateOriginalRequest('stock',array(
				'quantity' => $data['movestock']['quantity']+$stock[0]['quantity']
			),'id',$stock[0]['id']);
		}else{
			unset($data['movestock']['id']);
			//Insert
			$this->offices_model->insertOriginalRequest('stock',$data['movestock']);
		}
		$this->offices_model->updateOriginalRequest('movestock',array(
			'state' => 1
		),'id',$id);
		if($data['movestock']['quantity'] < 1){
			redirect(base_url('AdminControllerOffices/products/'.$data['factory']['id']));
		}
	}
	public function create()
	{
		$data = array();
		//Form Fields
		$data['fields'] = $this->fields;
		//Add office Form
		$this->load->view('holders/header');
		$this->load->view('AdminControllerOffices_Views/AdminControllerOffices_form',$data);
		$this->load->view('holders/footer');
	}
	public function write()
	{
		$data = array();
		//Form Fields
		$data['fields'] = $this->fields;
		//Form Fields Validation
		$this->offices_model->validate($this->fieldsValidation);
		if ($this->form_validation->run()){
			$this->offices_model->insertRequest($this->tableName,$this->fieldsValidation);
			$data['process'] = 1;
			$this->load->view('holders/header');
			$this->load->view('AdminControllerOffices_Views/AdminControllerOffices_form',$data);
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
		//Get office - More in offices_model
		$id = (int) $this->uri->segment(3);
		$data['office'] = $this->offices_model->getFullRequest(
			$this->tableName,
			'(id = '.$id.')',
			'',
			'',
			''
		);
		//Add office Form
		$this->load->view('holders/header');
		$this->load->view('AdminControllerOffices_Views/AdminControllerOffices_form',$data);
		$this->load->view('holders/footer');
	}
	public function update()
	{
		$data = array();
		//Form Fields
		$data['fields'] = $this->fields;
		//Get office - More in offices_model
		$id = (int) $this->uri->segment(3);
		$data['office'] = $this->offices_model->getFullRequest(
			$this->tableName,
			'(id = '.$id.')',
			'',
			'',
			''
		);
		//Form Fields Validation
		$this->offices_model->validate($this->fieldsValidation,array('image','password'));
		if ($this->form_validation->run()){
			$this->offices_model->updateRequest($this->tableName,$this->fieldsValidation,'id',$data['office'][0]);
			$data['all_offices'] = $this->offices_model->getFullRequest(
				$this->tableName,
				'',
				'',
				'',
				''
			);
			$data['process'] = 1;
			$this->load->view('holders/header');
			$this->load->view('AdminControllerOffices_Views/AdminControllerOffices_show',$data);
			$this->load->view('holders/footer');
		}else{
			$this->create();
		}
	}
	public function delete(){
		$id = (int) $this->uri->segment(3);
		$data['office'] = $this->offices_model->getFullRequest(
			$this->tableName,
			'id = '.$id,
			'',
			'',
			''
		);
		if($data['office']){
			$this->offices_model->deleteRequest($this->tableName,'id',$id);
			redirect(base_url('AdminControllerOffices/show'));
		}else{
			redirect(base_url('AdminControllerOffices/show'));
		}
	}
}

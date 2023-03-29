<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminControllerProducts extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/AdminControllerProducts
	 *	- or -
	 * 		http://example.com/index.php/AdminControllerProducts/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/AdminControllerProducts/<method_name>
	 * @see https://codeigniter.com/product_guide/general/urls.html
	 */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('products_model');
        $this->load->model('perms_model');
        $this->load->helper('cookie');
		$this->load->library("pagination");
		//Model Requirements
		$this->tableName = 'products';
		$this->primaryKey = 'id';
		//Form Fields
		$this->fields = array(
			'1|file' => '{upload|صورة المنتج لو متوفرة}',
			'5|text' => '{barcode|الباركود السابق}--{name|اسم المنتج}--{image|صورة المنتج - لاتعدل فيها}--{size|المقاس}--{color|اللون}',
			'1|number' => '{wholesale_price|سعر المنتج ج.م}',
			'1|dropdown' => '{source|مصدر المنتج}'
		);
		$this->fieldsValidation = array(
			array('source','المصدر',''),
			array('image','صورة',''),
			array('barcode','الباركود السابق',''),
			array('name','اسم المنتج','required'),
			array('size','المقاس','required'),
			array('color','اللون','required'),
			array('wholesale_price','سعر المنتج في الجملة','required')
		);
		if(!$this->products_model->is_logged_in()){
			redirect(base_url('cp/login'));
		}
    }
	public function show()
	{
		$this->perms_model->getPerms('products');
		$data = array();
		//Get All products - More in products_model
		$data['all_products'] = $this->products_model->getFullRequest(
			$this->tableName,
			' (id IS NOT NULL) ORDER BY id DESC',
			'',
			'',
			''
		);
        //Show products
		$this->load->view('holders/header');
		$this->load->view('AdminControllerProducts_Views/AdminControllerProducts_show',$data);
		$this->load->view('holders/footer');
	}
	public function view()
	{
		$this->perms_model->getPerms('viewProducts');
		$data = array();
		//Get User - More in Users_model
		$id = (int) $this->uri->segment(3);
		$data['product'] = $this->products_model->getFullRequest(
			$this->tableName,
			'(id = '.$id.')',
			'',
			'',
			''
		);
        //Show Users
		$this->load->view('holders/header');
		$this->load->view('AdminControllerProducts_Views/AdminControllerProducts_view',$data);
		$this->load->view('holders/footer');
	}
	public function saveImage(){
		$this->perms_model->getPerms('products');
		$img = $this->input->post('data_uri');
		$folderPath = "public/uploads/";
	
		$image_parts = explode(";base64,", $img);
		$image_type_aux = explode("image/", $image_parts[0]);
		$image_type = $image_type_aux[1];
	
		$image_base64 = base64_decode($image_parts[1]);
		$fileName = uniqid() . '.png';
	
		$file = $folderPath . $fileName;
		file_put_contents($file, $image_base64);
		$response = array(
			'image' => $fileName,
			'done' => 1
		);
		echo json_encode($response);
	}
	public function addstock()
	{
		$this->perms_model->getPerms('products');
		$data = array();
		//Get User - More in Users_model
		$id = (int) $this->uri->segment(3);
		$data['product'] = $this->products_model->getFullRequest(
			$this->tableName,
			'(id = '.$id.')',
			'',
			'',
			''
		);
		if(!$data['product']){
			redirect(base_url('404'));
		}
		//Form Fields
		$data['fields'] = array(
			'1|number' => '{number|العدد}',
			'3|dropdown' => '{from_place|المكان المرسل منه}--{place|المكان المرسل له}--{with|نقل مع}'
		);
        //Show Users
		$this->load->view('holders/header');
		$this->load->view('AdminControllerProducts_Views/AdminControllerProducts_addstock',$data);
		$this->load->view('holders/footer');
	}
	public function writestock()
	{
		$this->perms_model->getPerms('products');
		$data = array();
		//Get User - More in Users_model
		$id = (int) $this->uri->segment(3);
		$data['product'] = $this->products_model->getFullRequest(
			$this->tableName,
			'(id = '.$id.')',
			'',
			'',
			''
		);
		if(!$data['product']){
			redirect(base_url('404'));
		}
		$placeData = explode('-',$this->input->post('place'));
		$tableData['place'] = $placeData[0];
		$tableData['place_id'] = $placeData[1];
		$fromplaceData = explode('-',$this->input->post('from_place'));
		$tableData['from_place'] = $fromplaceData[0];
		$tableData['from_place_id'] = $fromplaceData[1];
		$tableData['p_created_at'] = $data['product'][0]->created_at;
		$tableData['quantity'] = $this->input->post('number');
		$tableData['with_u_id'] = $this->input->post('with');
		$tableData['state'] = 0;
		//If not from place
		if(in_array($tableData['place'],array('imported','bought','made'))){
			$tableData['state'] = 1;
			$this->products_model->insertOriginalRequest('movestock',$tableData);
			$moveData = $tableData;
			//Insert Move For Analatics
			unset($moveData['from_place']);
			unset($moveData['from_place_id']);
			unset($moveData['with_u_id']);
			unset($moveData['state']);
			$product = $this->products_model->getByData('products',' WHERE (created_at = \''.$moveData['p_created_at'].'\')');
			$product = (array) $product[0];
			$moveData['p_created_at'] = $product['created_at'];
			$moveData['method'] = 'add';
			$moveData['state'] = 'move';
			$this->products_model->insertOriginalRequest('moves',$moveData);
		}else{
			//If from place
			$this->products_model->insertOriginalRequest('movestock',$tableData);
			$moveData = $tableData;
			//Insert Move For Analatics
			$moveData['place'] = $moveData['from_place'];
			$moveData['place_id'] = $moveData['from_place_id'];
			unset($moveData['from_place']);
			unset($moveData['from_place_id']);
			unset($moveData['with_u_id']);
			unset($moveData['state']);
			$product = $this->products_model->getByData('products',' WHERE (created_at = \''.$moveData['p_created_at'].'\')');
			$product = (array) $product[0];
			$moveData['p_created_at'] = $product['created_at'];
			$moveData['method'] = 'minus';
			$moveData['state'] = 'move';
			$this->products_model->insertOriginalRequest('moves',$moveData);
		}

		//Main response view
		//Form Fields
		$data['fields'] = array(
			'1|number' => '{number|العدد}',
			'3|dropdown' => '{from_place|المكان المرسل منه}--{place|المكان المرسل له}--{with|نقل مع}'
		);
		$data['process'] = 1;
		//Show Users
		$this->load->view('holders/header');
		$this->load->view('AdminControllerProducts_Views/AdminControllerProducts_addstock',$data);
		$this->load->view('holders/footer');
	}
	public function barcode()
	{
		$data = array();
		//Get User - More in Users_model
		$id = (int) $this->uri->segment(3);
		$data['product'] = $this->products_model->getFullRequest(
			$this->tableName,
			'(id = '.$id.')',
			'',
			'',
			''
		);
		//Form Fields
		$data['fields'] = array(
			'1|number' => '{number|العدد}'
		);
		$data['products'] = $this->products_model->getByData('products','');
        //Show Users
		$this->load->view('holders/header');
		$this->load->view('AdminControllerProducts_Views/AdminControllerProducts_barcode',$data);
		$this->load->view('holders/footer');
	}
	public function barcodeValue(){
		$this->perms_model->getPerms('barcode');
		$data = array();
		//Get User - More in Users_model
		$id = $this->uri->segment(3);
		$this->products_model->barcode($id);
	}
	public function print()
	{
		$this->perms_model->getPerms('barcode');
		$data = array();
		//Get User - More in Users_model
		$data['inputs'] = array();
		foreach ($_POST as $key => $value) {
			$data['inputs'][] = htmlspecialchars($key);
		}
        //Show Users
		$this->load->view('AdminControllerProducts_Views/AdminControllerProducts_print',$data);
	}
	public function create()
	{
		$this->perms_model->getPerms('products');
		$data = array();
		//Form Fields
		$data['fields'] = $this->fields;
		//Add product Form
		$this->load->view('holders/header');
		$this->load->view('AdminControllerProducts_Views/AdminControllerProducts_form',$data);
		$this->load->view('holders/footer');
	}
	public function addmore()
	{
		$this->perms_model->getPerms('products');
		$data = array();
		//Form Fields
		$data['fields'] = $this->fields;
		//Get product - More in products_model
		$id = (int) $this->uri->segment(3);
		$data['product'] = $this->products_model->getFullRequest(
			$this->tableName,
			'(id = '.$id.')',
			'',
			'',
			''
		);
		//Add product Form
		$this->load->view('holders/header');
		$this->load->view('AdminControllerProducts_Views/AdminControllerProducts_moreform',$data);
		$this->load->view('holders/footer');
	}
	public function write()
	{
		$this->perms_model->getPerms('products');
		$data = array();
		//Form Fields
		$data['fields'] = $this->fields;
		$id = (int) $this->uri->segment(3);
		//Form Fields Validation
		$this->products_model->validate($this->fieldsValidation);
		if ($this->form_validation->run()){
			$this->products_model->insertRequest($this->tableName,$this->fieldsValidation);
			$data['process'] = 1;
			$this->load->view('holders/header');
			if($id){
				$data['product'] = $this->products_model->getFullRequest(
					$this->tableName,
					'(id = '.$id.')',
					'',
					'',
					''
				);
				$this->load->view('AdminControllerProducts_Views/AdminControllerProducts_moreform',$data);
			}else{
				$this->load->view('AdminControllerProducts_Views/AdminControllerProducts_form',$data);
			}
			$this->load->view('holders/footer');
		}else{
			if($id){
				$this->addmore();
			}else{
				$this->create();
			}
		}
	}
	public function edit()
	{
		$this->perms_model->getPerms('products');
		$data = array();
		//Form Fields
		$data['fields'] = $this->fields;
		//Get product - More in products_model
		$id = (int) $this->uri->segment(3);
		$data['product'] = $this->products_model->getFullRequest(
			$this->tableName,
			'(id = '.$id.')',
			'',
			'',
			''
		);
		//Add product Form
		$this->load->view('holders/header');
		$this->load->view('AdminControllerProducts_Views/AdminControllerProducts_form',$data);
		$this->load->view('holders/footer');
	}
	public function update()
	{
		$this->perms_model->getPerms('products');
		$data = array();
		//Form Fields
		$data['fields'] = $this->fields;
		//Get product - More in products_model
		$id = (int) $this->uri->segment(3);
		$data['product'] = $this->products_model->getFullRequest(
			$this->tableName,
			'(id = '.$id.')',
			'',
			'',
			''
		);
		//Form Fields Validation
		$this->products_model->validate($this->fieldsValidation,array('image','password'));
		if ($this->form_validation->run()){
			$this->products_model->updateRequest($this->tableName,$this->fieldsValidation,'id',$data['product'][0]);
			$data['all_products'] = $this->products_model->getFullRequest(
				$this->tableName,
				'',
				'',
				'',
				''
			);
			$data['process'] = 1;
			$this->load->view('holders/header');
			$this->load->view('AdminControllerProducts_Views/AdminControllerProducts_show',$data);
			$this->load->view('holders/footer');
		}else{
			$this->create();
		}
	}
	/*public function delete(){
		$this->perms_model->getPerms('products');
		$id = (int) $this->uri->segment(3);
		$data['product'] = $this->products_model->getFullRequest(
			$this->tableName,
			'id = '.$id,
			'',
			'',
			''
		);
		if($data['product']){
			$this->products_model->deleteRequest($this->tableName,'id',$id);
			redirect(base_url('AdminControllerProducts/show'));
		}else{
			redirect(base_url('AdminControllerProducts/show'));
		}
	}*/
	public function acceptAllStock(){
		$this->perms_model->getPerms('acceptProducts');
		$data = array();
		$data['dplace_id'] = $place_id = (int) $this->uri->segment(4);
		$data['dplace'] = $place = (string) $this->uri->segment(3);
		$data['moveIds'] = '';
		//Get All Moved
		$data['movestocks'] = $this->products_model->getByData('movestock',' WHERE (place = \''.$place.'\') AND (place_id = '.$place_id.') AND (state = 0)');
		foreach($data['movestocks'] as $data['movestock']){
			$data['moveIds'] .= $data['movestock']['id'].',';
			//Update State
			$this->products_model->updateOriginalRequest('movestock',array(
				'state' => 1
			),'id',$data['movestock']['id']);
			$moveData = $data['movestock'];
			//Add Stock
			unset($data['movestock']['from_place']);
			unset($data['movestock']['from_place_id']);
			unset($data['movestock']['with_u_id']);
			unset($data['movestock']['created_at']);
			unset($data['movestock']['edited_at']);
			unset($data['movestock']['state']);
			$data['movestock']['editors_u_id'] = NULL;
			//Accept Proccess
			$stock = $this->products_model->getByData('stock',' WHERE (place = "office") AND (place_id = '.$data['movestock']['place_id'].') AND (p_id = '.$data['movestock']['p_id'].')');
			if($stock){
				//Update
				$this->products_model->updateOriginalRequest('stock',array(
					'quantity' => $data['movestock']['quantity']+$stock[0]['quantity']
				),'id',$stock[0]['id']);
			}else{
				unset($data['movestock']['id']);
				//Insert
				$this->products_model->insertOriginalRequest('stock',$data['movestock']);
			}
			//Insert Move For Analatics
			unset($moveData['from_place']);
			unset($moveData['from_place_id']);
			unset($moveData['with_u_id']);
			unset($moveData['state']);
			$product = $this->products_model->getByData('products',' WHERE (id = '.$moveData['p_id'].')');
			unset($moveData['p_id']);
			$product = (array) $product[0];
			$moveData['p_created_at'] = $product['created_at'];
			$moveData['quantity'] = $data['movestock']['quantity'];
			$moveData['method'] = 'add';
			$moveData['state'] = 'move';
			$this->products_model->insertOriginalRequest('moves',$moveData);
		}
		$data['place'] = $this->products_model->getByData($place.'s',' WHERE (id = '.$place_id.')');
			if($data['place']){
				$data['place'] = $data['place'][0];
			}
		//Print
		$this->load->view('AdminControllerProducts_Views/AdminControllerProducts_printAll',$data);
	}
}

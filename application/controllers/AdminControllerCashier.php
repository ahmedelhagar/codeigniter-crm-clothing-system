<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminControllerCashier extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/AdminControllercategorys
	 *	- or -
	 * 		http://example.com/index.php/AdminControllercategorys/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/AdminControllercategorys/<method_name>
	 * @see https://codeigniter.com/category_guide/general/urls.html
	 */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('cashier_model');
        $this->load->helper('cookie');
		$this->load->library("pagination");
		$this->load->model('perms_model');
		//Model Requirements
		$this->tableName = 'cashier';
		$this->primaryKey = 'id';
		/*/Form Fields
		$this->fields = array(
			'1|text' => '{category|اسم القسم}'
		);
		$this->fieldsValidation = array(
			array('category','اسم القسم','required')
		);*/
		if(!$this->cashier_model->is_logged_in()){
			redirect(base_url('cp/login'));
		}
    }
	public function index()
	{
		$this->perms_model->getPerms('cashier');
		$data = array();
        //Show categorys
		$this->load->view('holders/header');
		$this->load->view('AdminControllerCashier_Views/AdminControllerCashier_index',$data);
		$this->load->view('holders/footer');
	}
	public function showAll()
	{
		$this->perms_model->getPerms('cashierReports');
		$data = array();
		$data['store_id'] = '';
		if($this->input->post('from') && $this->input->post('to')){
			if($this->input->post('store')) {
				 $store = ' AND (place_id = '.$this->input->post('store').')';
				 $data['store_id'] = $this->input->post('store');
			}else{
				 $store = '';
			}
			$filter = "where created_at
			between TIMESTAMP('".str_replace('/','-',$this->input->post('from'))." 00:00:00.000000')
			and TIMESTAMP('".str_replace('/','-',$this->input->post('to'))." 23:59:00.000000')".$store." ORDER BY id DESC";
			$data['from'] = $this->input->post('from');
			$data['to'] = $this->input->post('to');
		}else{
			$filter = ' ORDER BY id DESC';
			$data['from'] = '';
			$data['to'] = '';
		}
        //Show categorys
		$data['transactions'] = $this->cashier_model->getByData('transactions',$filter);
		$this->load->view('holders/header');
		$this->load->view('AdminControllerCashier_Views/AdminControllerCashier_showAll',$data);
		$this->load->view('holders/footer');
	}
	public function wholesale()
	{
		$this->perms_model->getPerms('cashier');
		$data = array();
		$id = (int) $this->uri->segment(3);
		$data['store'] = $this->cashier_model->getFullRequest('stores',
			'(id = '.$id.')',
			'',
			'',
			''
		);
		if(!$data['store']){
			redirect(base_url('AdminControllerCashier/wholesale_choose'));
		}
		$data['transactions'] = $this->cashier_model->getByData('transactions','ORDER BY id DESC');
		$data['store'] = $data['store'][0];
        //Show categorys
		$this->load->view('holders/header');
		$this->load->view('AdminControllerCashier_Views/AdminControllerCashier_wholesale',$data);
		$this->load->view('holders/footer');
	}
	public function wholesale_choose()
	{
		$this->perms_model->getPerms('cashier');
		$data = array();
		$data['stores'] = $this->cashier_model->getFullRequest('stores',
			'',
			'',
			'',
			''
		);
		if(!$data['stores']){
			redirect(base_url('AdminControllerStores/show'));
		}
        //Show categorys
		$this->load->view('holders/header');
		$this->load->view('AdminControllerCashier_Views/AdminControllerCashier_wholesale_choose',$data);
		$this->load->view('holders/footer');
	}
	public function searchRequest(){
		$this->perms_model->getPerms('cashier');
		$id = $this->input->post('barcode');
		if ( urlencode(urldecode($id)) === $id){
			$id = urldecode($this->input->post('barcode'));
		} else {
			$id = $this->input->post('barcode');
		}
		$oldBar = $this->cashier_model->getOldBar($id);
		$p_created_atpcs = str_split($oldBar[1],2);
		$data['product'] = $this->cashier_model->getByData('products',' WHERE (barcode = \''.$oldBar[0].'\') AND (created_at LIKE \'%'.$p_created_atpcs[0].'.'.$p_created_atpcs[1].'%\')');
		if($data['product']){
			$response = array(
				'product' => $data['product'][0],
				'done' => 1
			);
			echo json_encode($response);
		}else{
			$response = array(
				'done' => 0
			);
			echo json_encode($response);
		}
	}
	public function transaction(){
		$this->perms_model->getPerms('cashier');
		$id = (int) $this->uri->segment(3);
		$data['transaction'] = $this->cashier_model->getByData('transactions',' WHERE (id = '.$id.')');
		if(!$data['transaction']){
			redirect(base_url('404'));
		}
		$data['store'] = $this->cashier_model->getByData('stores',' WHERE (id = '.$data['transaction'][0]['place_id'].')');
		$data['store'] = $data['store'][0];
		$this->load->view('AdminControllerCashier_Views/AdminControllerCashier_print',$data);
	}
	public function barcodeValue(){
		$this->perms_model->getPerms('cashier');
		$data = array();
		//Get User - More in Users_model
		$id = (int) $this->uri->segment(3);
		$data['transaction'] = $this->cashier_model->getFullRequest(
			'transactions',
			'(id = '.$id.')',
			'',
			'',
			''
		);
		if(!$data['transaction']){
			redirect(base_url('404'));
		}
		$this->cashier_model->barcode(str_replace(array('-',':','.',' '),array('','','',''),$data['transaction'][0]->created_at));
	}
	public function refund(){
		$this->perms_model->getPerms('cashier');
		$id = $this->input->post('transaction');
		$place_id = $this->input->post('place_id');
		$data['transaction'] = $this->cashier_model->getByData('transactions',' WHERE (id = '.$id.')');
		if(!$data['transaction']){
			redirect(base_url('404'));
		}
		$data['transaction'] = $data['transaction'][0];
		$place = 'store';
		$itemsData = explode(',',$data['transaction']['items']);
		foreach($itemsData as $itemData){
			if($itemData){
				$item = explode('NxId',$itemData);
				$data['product'] = $this->cashier_model->getByData('products',' WHERE (created_at = \''.$this->cashier_model->p_created_at($item[1]).'\')');
				//Insert Move For Analatics
				$product = (array) $data['product'][0];
				$moveData['p_created_at'] = $product['created_at'];
				$moveData['quantity'] = (int)$item[0];
				$moveData['place'] = $place;
				$moveData['place_id'] = $place_id;
				$moveData['method'] = 'add';
				$moveData['state'] = 'refund';
				$this->cashier_model->insertOriginalRequest('moves',$moveData);
			}
		}
		$this->cashier_model->updateOriginalRequest('transactions',array(
			'state' => 1,
			'refund_u_id' => $this->session->userdata('id')
		),'id',$id);
		$response = array(
			'done' => 1
		);
		echo json_encode($response);
	}
	public function getPrevious(){
		$this->perms_model->getPerms('cashier');
		$data = $this->input->post('data');
		$productTag = '';
		foreach($data as $pData){
			$pData = (array) $pData;
			foreach($pData as $rowData){
				$num = $rowData[0];
				$p_id = $rowData[1];
				$productData = $this->cashier_model->getByData('products',' WHERE (id = '.$p_id.')');
				$product = (array) $productData[0];
				$productTag .= '<div class="product-item" id="pi-'.$product['id'].'" data-price="'.$product['wholesale_price'].'"><div class="btn btn-danger float-left" id="delete-re" onclick="return removeItem('.$product['id'].');"><span class="fa fa-times"></span></div><p>'.$product['name'].' - '.$product['size'].' - '.$product['color'].' - '.$product['wholesale_price'].'ج.م </p><input id="pinput-'.$product['id'].'" data-price="'.$product['wholesale_price'].'" onchange="return reCalc('.$product['id'].');" onkeyup="return reCalc('.$product['id'].');" onpast="return reCalc('.$product['id'].');" type="number" class="form-control" min="1" value="'.$num.'"><input id="dinput-'.$product['id'].'" data-price="'.$product['wholesale_price'].'" type="number" onchange="return reCalc('.$product['id'].');" onkeyup="return reCalc('.$product['id'].');" onpast="return reCalc('.$product['id'].');" class="form-control" min="0" max="100" placeholder="نسبة الخصم %"></div>';
			}
		}
		$response = array(
			'data' => $productTag,
			'done' => 1
		);
		echo json_encode($response);
	}
	public function pay(){
		$this->perms_model->getPerms('cashier');
		$method = $this->input->post('method');
		$byVisa = $this->input->post('byVisa');
		$data = $this->input->post('data');
		$discount = $this->input->post('discount');
		$c_cookie = (int) get_cookie('client');
		if($c_cookie){
			$c_data['client'] = $this->cashier_model->getByData('clients',' WHERE (id = '.$c_cookie.')');
			if($c_data['client']){
				$c_id = $c_data['client'][0]['created_at'];
				$responseClient = $c_data['client'][0];
			}else{
				$c_id = NULL;
				$responseClient = NULL;
			}
		}else{
			$c_id = NULL;
			$responseClient = NULL;
		}
		$price = 0;
		$items = '';
		$i = 0;
		$place = 'store';
		$pDiscVal = array();
		$place_id = (int) $this->input->post('place_id');
		$discounts = '';
		foreach($data as $req){
			$data['product'] = $this->cashier_model->getByData('products',' WHERE (id = '.$req[$i][1].')');
			$p_created_at = str_replace(array(':','-','.',' '),array('','','',''),$data['product'][0]['created_at']);
			$price += ($data['product'][0]['wholesale_price']*$req[$i][0])-($data['product'][0]['wholesale_price']*$req[$i][0]*0.01*$req[$i][2]);
			$items .= $req[$i][0].'NxId'.$p_created_at.',';
			//Insert Move For Analatics
			$moveData['p_created_at'] = $data['product'][0]['created_at'];
			$moveData['quantity'] = (int)$req[$i][0];
			$moveData['place'] = $place;
			$moveData['place_id'] = $place_id;
			$moveData['method'] = 'minus';
			$moveData['state'] = 'sell';
			$discounts .= $req[$i][2].',';
			$this->cashier_model->insertOriginalRequest('moves',$moveData);
		$i++;}
		
		$total = $price - $discount;
		$transaction = $this->cashier_model->insertOriginalRequest('transactions',array(
			'price' => $price,
			'discount' => $discount,
			'items' => $items,
			'c_id' => $c_id,
			'discounts' => $discounts,
			'place' => $place,
			'place_id' => $place_id,
			'byvisa' => $byVisa,
			'method' => $method,
			'refund_u_id' => 0,
			'state' => 0
		));
		$data['transaction'] = $this->cashier_model->getByData('transactions',' WHERE (id = '.$transaction.')');
		$data['transaction'] = $data['transaction'][0];
		if($method == 'cash' OR $method == 'visa' OR $method == 'cash+visa'){
			$response = array(
				'transaction' => $data['transaction'],
				'client' => $responseClient,
				'done' => 1
			);
			echo json_encode($response);
		}
	}
	public function getClient(){
		$this->perms_model->getPerms('cashier');
		$method = $this->input->post('method');
		$mobile = $this->input->post('mobile');
		$data['client'] = $this->cashier_model->getByData('clients',' WHERE (mobile = '.$mobile.')');
		if($method == 'search'){
			if($data['client']){
				$response = array(
					'client' => $data['client'][0],
					'done' => 1
				);
				echo json_encode($response);
			}else{
				$response = array(
					'done' => 0
				);
				echo json_encode($response);
			}
		}elseif($method == 'add'){
			$name = $this->input->post('name');
			$data['client'] = $this->cashier_model->getByData('clients',' WHERE (mobile = '.$mobile.')');
			if(!$data['client']){
				$client = $this->cashier_model->insertOriginalRequest('clients',array(
					'name' => $name,
					'mobile' => $mobile
				));
				$data['client'] = $this->cashier_model->getByData('clients',' WHERE (id = '.$client.')');
			}
			$response = array(
				'client' => $data['client'][0],
				'done' => 1
			);
			echo json_encode($response);
		}
	}
	public function searchRec(){
		$this->perms_model->getPerms('cashier');
		$barcode = urldecode($this->input->post('barcode'));
		if ( urlencode(urldecode($barcode)) === $barcode){
			$barcode = urldecode($this->input->post('barcode'));
		} else {
			$barcode = $this->input->post('barcode');
		}
		$data['client'] = $this->cashier_model->getByData('clients',' WHERE (mobile = '.$barcode.')');
		if($data['client'][0]['sent_at'] == NULL){
			$data['transaction'] = $this->cashier_model->getByData('transactions',' WHERE (c_id = '.$data['client'][0]['id'].')');
		}else{
			$data['transaction'] = $this->cashier_model->getByData('transactions',' WHERE (c_id = '.$data['client'][0]['indevice_id'].')');
		}
		$response = array(

			'client' => $data['client'][0]['name'],

			'transaction' => $data['transaction'],

			'done' => 1

		);

		echo json_encode($response);

	}
}

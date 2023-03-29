<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Api extends CI_Controller {
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
    public $token;
    public $reciver;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('cashier_model');
        $this->load->helper('cookie');
		$this->load->library("pagination");
        $this->token = $this->config->item('api_token');
        $this->reciver = 'https://kokykidswear.com/koky/api/reciver/';
        //$this->reciver = 'http://localhost/koky/api/reciver/';
    }
    public function updateData(){
        //Request from server
        $params = array (
            'token' => $this->token,
            'table' => 'allData'
            );
        $url = 'https://kokykidswear.com/koky/api/getData/';
        $ch = curl_init( $url );
        # Setup request to send json via POST.
        $payload = json_encode( array( "customer"=> $params ) );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        # Return response instead of printing.
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        # Send request.
        $result = curl_exec($ch);
        curl_close($ch);
        # Print response.
        $response = (array) json_decode($result);
        $tokenData = $this->cashier_model->getByData('tokens',' WHERE token = \''.$this->token.'\'');
        if(isset($response['products'])){
            //products
            foreach($response['products'] as $product){
                $product = (array) $product;
                if($product){
                    $now = new DateTime();
                    $now->setTimezone(new DateTimezone('Africa/Cairo'));
                    $dateNow = (array) $now;
                    if($tokenData[0]['id'] == $product['t_id']){
                        //Same Device
                        $t_idOp = 'IS NULL';
                        $indevice_idOp = 'IS NULL';
                        $product['t_id'] = NULL;
                        $product['indevice_id'] = NULL;
                    }elseif($product['t_id'] == NULL){
                        //From Server
                        $t_idOp = '= 0';
                        $indevice_idOp = '= '.$product['id'];
                        $product['t_id'] = 0;
                        $product['indevice_id'] = $product['id'];
                    }else{
                        //Other Devices
                        $t_idOp = '= '.$product['t_id'];
                        $indevice_idOp = '= '.$product['indevice_id'];
                    }
                    unset($product['id']);
                    $product['sent_at'] = $dateNow['date'];
                    $item = $this->cashier_model->getByData('products',' WHERE (source = \''.$product['source'].'\') AND (created_at = \''.$product['created_at'].'\')');
                    if(!$item){
                        $this->cashier_model->insertApiRequest('products',$product);
                    }else{
                        if($item[0]['upload'] !== $product['upload']){
                            $newItemData = array(
                                'upload' => $product['upload']
                            );
                            $this->cashier_model->updateOriginalRequest('products',$newItemData,'created_at',$product['created_at']);
                        }
                    }
                }
            }
        }
        if(isset($response['transactions'])){
            foreach($response['transactions'] as $transaction){
                $transaction = (array) $transaction;
                if($transaction){
                    $now = new DateTime();
                    $now->setTimezone(new DateTimezone('Africa/Cairo'));
                    $dateNow = (array) $now;
                    if($tokenData[0]['id'] == $transaction['t_id']){
                        //Same Device
                        $t_idOp = 'IS NULL';
                        $indevice_idOp = 'IS NULL';
                        $transaction['t_id'] = NULL;
                        $transaction['indevice_id'] = NULL;
                    }elseif($transaction['t_id'] == NULL){
                        //From Server
                        $t_idOp = '= 0';
                        $indevice_idOp = '= '.$transaction['id'];
                        $transaction['t_id'] = 0;
                        $transaction['indevice_id'] = $transaction['id'];
                    }else{
                        //Other Devices
                        $t_idOp = '= '.$transaction['t_id'];
                        $indevice_idOp = '= '.$transaction['indevice_id'];
                    }
                    unset($transaction['id']);
                    $transaction['sent_at'] = $dateNow['date'];
                    $item = $this->cashier_model->getByData('transactions',' WHERE (created_at = \''.$transaction['created_at'].'\')');
                    if(!$item){
                        $this->cashier_model->insertApiRequest('transactions',$transaction);
                    }else{
                        $newItemData = (array) $transaction;
                        $row = (array) $item[0];
                        //Update Row With new state
                        unset($row['id']);
                        if($newItemData['state']){
                            unset($newItemData['id']);
                            unset($newItemData['t_id']);
                            unset($newItemData['sent_at']);
                            unset($newItemData['indevice_id']);
                            $this->cashier_model->updateOriginalRequest('transactions',$newItemData,'created_at',$row['created_at']);
                        }
                    }
                }
            }
        }
        if(isset($response['clients'])){
            foreach($response['clients'] as $client){
                $client = (array) $client;
                if($client){
                    $now = new DateTime();
                    $now->setTimezone(new DateTimezone('Africa/Cairo'));
                    $dateNow = (array) $now;
                    if($tokenData[0]['id'] == $client['t_id']){
                        //Same Device
                        $t_idOp = 'IS NULL';
                        $indevice_idOp = 'IS NULL';
                        $client['t_id'] = NULL;
                        $client['indevice_id'] = NULL;
                    }elseif($client['t_id'] == NULL){
                        //From Server
                        $t_idOp = '= 0';
                        $indevice_idOp = '= '.$client['id'];
                        $client['t_id'] = 0;
                        $client['indevice_id'] = $client['id'];
                    }else{
                        //Other Devices
                        $t_idOp = '= '.$client['t_id'];
                        $indevice_idOp = '= '.$client['indevice_id'];
                    }
                    unset($client['id']);
                    $client['sent_at'] = $dateNow['date'];
                    $item = $this->cashier_model->getByData('clients',' WHERE (mobile = \''.$client['mobile'].'\')');
                    if(!$item){
                        $this->cashier_model->insertApiRequest('clients',$client);
                    }
                }
            }
        }
        if(isset($response['movestock'])){
            foreach($response['movestock'] as $movestock){
                $movestock = (array) $movestock;
                if($movestock){
                    $now = new DateTime();
                    $now->setTimezone(new DateTimezone('Africa/Cairo'));
                    $dateNow = (array) $now;
                    if($tokenData[0]['id'] == $movestock['t_id']){
                        //Same Device
                        $t_idOp = 'IS NULL';
                        $indevice_idOp = 'IS NULL';
                        $movestock['t_id'] = NULL;
                        $movestock['indevice_id'] = NULL;
                    }elseif($movestock['t_id'] == NULL){
                        //From Server
                        $t_idOp = '= 0';
                        $indevice_idOp = '= '.$movestock['id'];
                        $movestock['t_id'] = 0;
                        $movestock['indevice_id'] = $movestock['id'];
                    }else{
                        //Other Devices
                        $t_idOp = '= '.$movestock['t_id'];
                        $indevice_idOp = '= '.$movestock['indevice_id'];
                    }
                    unset($movestock['id']);
                    $movestock['sent_at'] = $dateNow['date'];
                    $item = $this->cashier_model->getByData('movestock',' WHERE (created_at = \''.$movestock['created_at'].'\')');
                    if(!$item){
                        $this->cashier_model->insertApiRequest('movestock',$movestock);
                    }else{
                        $newItemData = (array) $movestock;
                        $row = (array) $item[0];
                        //Update Row With new state
                        unset($row['id']);
                        if($newItemData['state']){
                            unset($newItemData['t_id']);
                            unset($newItemData['id']);
                            unset($newItemData['sent_at']);
                            unset($newItemData['indevice_id']);
                            $this->cashier_model->updateOriginalRequest('movestock',$newItemData,'created_at',$row['created_at']);
                        }
                    }
                }
            }
        }
        if(isset($response['stock'])){
            foreach($response['stock'] as $stock){
                $stock = (array) $stock;
                if($stock){
                    $now = new DateTime();
                    $now->setTimezone(new DateTimezone('Africa/Cairo'));
                    $dateNow = (array) $now;
                    if($tokenData[0]['id'] == $stock['t_id']){
                        //Same Device
                        $t_idOp = 'IS NULL';
                        $indevice_idOp = 'IS NULL';
                        $stock['t_id'] = NULL;
                        $stock['indevice_id'] = NULL;
                    }elseif($stock['t_id'] == NULL){
                        //From Server
                        $t_idOp = '= 0';
                        $indevice_idOp = '= '.$stock['id'];
                        $stock['t_id'] = 0;
                        $stock['indevice_id'] = $stock['id'];
                    }else{
                        //Other Devices
                        $t_idOp = '= '.$stock['t_id'];
                        $indevice_idOp = '= '.$stock['indevice_id'];
                    }
                    unset($stock['id']);
                    $stock['sent_at'] = $dateNow['date'];
                    $item = $this->cashier_model->getByData('stock',' WHERE (place = \''.$stock['place'].'\') AND (created_at = \''.$stock['created_at'].'\') AND (place_id = '.$stock['place_id'].')');
                    if(!$item){
                        $this->cashier_model->insertApiRequest('stock',$stock);
                    }
                }
            }
        }
        if(isset($response['moves'])){
            foreach($response['moves'] as $move){
                $move = (array) $move;
                if($move){
                    $now = new DateTime();
                    $now->setTimezone(new DateTimezone('Africa/Cairo'));
                    $dateNow = (array) $now;
                    if($tokenData[0]['id'] == $move['t_id']){
                        //Same Device
                        $t_idOp = 'IS NULL';
                        $indevice_idOp = 'IS NULL';
                        $move['t_id'] = NULL;
                        $move['indevice_id'] = NULL;
                    }elseif($move['t_id'] == NULL){
                        //From Server
                        $t_idOp = '= 0';
                        $indevice_idOp = '= '.$move['id'];
                        $move['t_id'] = 0;
                        $move['indevice_id'] = $move['id'];
                    }else{
                        //Other Devices
                        $t_idOp = '= '.$move['t_id'];
                        $indevice_idOp = '= '.$move['indevice_id'];
                    }
                    unset($move['id']);
                    $move['sent_at'] = $dateNow['date'];
                    $item = $this->cashier_model->getByData('moves',' WHERE (created_at = \''.$move['created_at'].'\')');
                    if(!$item){
                        $this->cashier_model->insertApiRequest('moves',$move);
                    }
                }
            }
        }
        if(isset($response['balances'])){
            foreach($response['balances'] as $balance){
                $balance = (array) $balance;
                if($balance){
                    $now = new DateTime();
                    $now->setTimezone(new DateTimezone('Africa/Cairo'));
                    $dateNow = (array) $now;
                    if($tokenData[0]['id'] == $balance['t_id']){
                        //Same Device
                        $t_idOp = 'IS NULL';
                        $indevice_idOp = 'IS NULL';
                        $balance['t_id'] = NULL;
                        $balance['indevice_id'] = NULL;
                    }elseif($balance['t_id'] == NULL){
                        //From Server
                        $t_idOp = '= 0';
                        $indevice_idOp = '= '.$balance['id'];
                        $balance['t_id'] = 0;
                        $balance['indevice_id'] = $balance['id'];
                    }else{
                        //Other Devices
                        $t_idOp = '= '.$balance['t_id'];
                        $indevice_idOp = '= '.$balance['indevice_id'];
                    }
                    unset($balance['id']);
                    $balance['sent_at'] = $dateNow['date'];
                    $item = $this->cashier_model->getByData('balances',' WHERE (created_at = \''.$balance['created_at'].'\')');
                    if(!$item){
                        $this->cashier_model->insertApiRequest('balances',$balance);
                    }
                }
            }
        }
        if(isset($response['stores'])){
            foreach($response['stores'] as $store){
                $store = (array) $store;
                if($store){
                    $now = new DateTime();
                    $now->setTimezone(new DateTimezone('Africa/Cairo'));
                    $dateNow = (array) $now;
                    if($tokenData[0]['id'] == $store['t_id']){
                        //Same Device
                        $t_idOp = 'IS NULL';
                        $indevice_idOp = 'IS NULL';
                        $store['t_id'] = NULL;
                        $store['indevice_id'] = NULL;
                    }elseif($store['t_id'] == NULL){
                        //From Server
                        $t_idOp = '= 0';
                        $indevice_idOp = '= '.$store['id'];
                        $store['t_id'] = 0;
                        $store['indevice_id'] = $store['id'];
                    }else{
                        //Other Devices
                        $t_idOp = '= '.$store['t_id'];
                        $indevice_idOp = '= '.$store['indevice_id'];
                    }
                    $store['sent_at'] = $dateNow['date'];
                    $item = $this->cashier_model->getByData('stores',' WHERE (created_at = \''.$store['created_at'].'\')');
                    if(!$item){
                        $this->cashier_model->insertApiRequest('stores',$store);
                    }
                }
            }
        }
        if(isset($response['storages'])){
            foreach($response['storages'] as $storage){
                $storage = (array) $storage;
                if($storage){
                    $now = new DateTime();
                    $now->setTimezone(new DateTimezone('Africa/Cairo'));
                    $dateNow = (array) $now;
                    if($tokenData[0]['id'] == $storage['t_id']){
                        //Same Device
                        $t_idOp = 'IS NULL';
                        $indevice_idOp = 'IS NULL';
                        $storage['t_id'] = NULL;
                        $storage['indevice_id'] = NULL;
                    }elseif($storage['t_id'] == NULL){
                        //From Server
                        $t_idOp = '= 0';
                        $indevice_idOp = '= '.$storage['id'];
                        $storage['t_id'] = 0;
                        $storage['indevice_id'] = $storage['id'];
                    }else{
                        //Other Devices
                        $t_idOp = '= '.$storage['t_id'];
                        $indevice_idOp = '= '.$storage['indevice_id'];
                    }
                    $storage['sent_at'] = $dateNow['date'];
                    $item = $this->cashier_model->getByData('storages',' WHERE (created_at = \''.$storage['created_at'].'\')');
                    if(!$item){
                        $this->cashier_model->insertApiRequest('storages',$storage);
                    }
                }
            }
        }
        if(isset($response['users'])){
            foreach($response['users'] as $user){
                $user = (array) $user;
                if($user){
                    $now = new DateTime();
                    $now->setTimezone(new DateTimezone('Africa/Cairo'));
                    $dateNow = (array) $now;
                    if($tokenData[0]['id'] == $user['t_id']){
                        //Same Device
                        $t_idOp = 'IS NULL';
                        $indevice_idOp = 'IS NULL';
                        $user['t_id'] = NULL;
                        $user['indevice_id'] = NULL;
                    }elseif($user['t_id'] == NULL){
                        //From Server
                        $t_idOp = '= 0';
                        $indevice_idOp = '= '.$user['id'];
                        $user['t_id'] = 0;
                        $user['indevice_id'] = $user['id'];
                    }else{
                        //Other Devices
                        $t_idOp = '= '.$user['t_id'];
                        $indevice_idOp = '= '.$user['indevice_id'];
                    }
                    $user['sent_at'] = $dateNow['date'];
                    $item = $this->cashier_model->getByData('users',' WHERE (created_at = \''.$user['created_at'].'\') AND (password = \''.$user['password'].'\')');
                    if(!$item){
                        $this->cashier_model->insertApiRequest('users',$user);
                    }
                }
            }
        }
        echo json_encode($response);
    }
    public function getMessages()
	{
        $json = file_get_contents('php://input');
        // Converts it into a PHP object
        $data = json_decode($json);
        $data = $data->customer;
        if(isset($data->token)){
            $tokenData = $this->cashier_model->getByData('tokens',' WHERE token = \''.$this->token.'\'');
            $user = $this->cashier_model->getByData('users',' WHERE (email = \''.$data->email.'\') AND (password = \''.$data->password.'\')');
            if($tokenData){
                if($user){
                    if($data->limit){
                        $limit = ' LIMIT '.$data->limit;
                        $whereReq = '(to_id = '.$user[0]['id'].')';
                    }else{
                        $limit = '';
                        $whereReq = '(from_id = '.$user[0]['id'].') OR (to_id = '.$user[0]['id'].')';
                    }
                    $messages = $this->cashier_model->getByData('messages',' WHERE '.$whereReq.' ORDER BY id DESC'.$limit);
                    $i=0;foreach($messages as $message){
                        $fromUser = $this->cashier_model->getByData('users',' WHERE (id = \''.$message['from_id'].'\')');
                        $toUser = $this->cashier_model->getByData('users',' WHERE (id = \''.$message['to_id'].'\')');
                        $messages[$i]['from'] = $fromUser[0]['name'];
                        $messages[$i]['to'] = $toUser[0]['name'];
                        $now = new DateTime();
                        $now->setTimezone(new DateTimezone('Africa/Cairo'));
                        $dateNow = (array) $now;
                        $currentDate = $dateNow['date'];
                        if($user[0]['id'] == $messages[$i]['to_id'] && $messages[$i]['seen'] == 0){
                            $this->cashier_model->updateOriginalRequest('messages',array(
                                'edited_at' => $currentDate,
                                'seen' => 1
                            ),'id',$messages[$i]['id']);
                        }
                    $i++;}
                    $allData = array(
                        'messages' => $messages,
                        'done' => 1
                    );
                    echo json_encode($allData);
                }else{
                    $allData = array(
                        'done' => 0
                    );
                    echo json_encode($allData);
                }
            }
        }
    }
    public function mapMessages(){
        $user = $this->cashier_model->getByData('users',' WHERE (email = \''.$this->session->userdata('email').'\') AND (password = \''.$this->session->userdata('password').'\')');
        $limit = (int) $this->uri->segment(3);
        //Request
        $params = array (
            'token' => $this->token,
            'email' => $user[0]['email'],
            'password' => $user[0]['password'],
            'limit' => $limit,
            'table' => 'messages'
            );
        $url = 'https://kokykidswear.com/koky/api/getMessages/';
        $ch = curl_init( $url );
        # Setup request to send json via POST.
        $payload = json_encode( array( "customer"=> $params ) );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        # Return response instead of printing.
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        # Send request.
        $result = curl_exec($ch);
        curl_close($ch);
        # Print response.
        $response = (array) json_decode($result);
        echo json_encode($response);
    }
    public function writeMessage()
	{
        $json = file_get_contents('php://input');
        // Converts it into a PHP object
        $data = json_decode($json);
        $data = $data->customer;
        if(isset($data->token)){
            $tokenData = $this->cashier_model->getByData('tokens',' WHERE token = \''.$this->token.'\'');
            $user = $this->cashier_model->getByData('users',' WHERE (email = \''.$data->email.'\') AND (password = \''.$data->password.'\')');
            if($tokenData){
                if($user){
                    $messageData = array(
                        'message' => $data->message,
                        'to_id' => $data->to_id,
                        'from_id' => $user[0]['id'],
                        'seen' => 0
                    );
                    $this->cashier_model->insertApiRequest2('messages',$messageData);
                    $allData = array(
                        'done' => 1
                    );
                    echo json_encode($allData);
                }else{
                    $allData = array(
                        'done' => 0
                    );
                    echo json_encode($allData);
                }
            }
        }
    }
    public function insertMessage(){
        $user = $this->cashier_model->getByData('users',' WHERE (email = \''.$this->session->userdata('email').'\') AND (password = \''.$this->session->userdata('password').'\')');
        $message = $this->input->post('message');
        $to_id = $this->input->post('users');
        //Request
        $params = array (
            'token' => $this->token,
            'email' => $user[0]['email'],
            'password' => $user[0]['password'],
            'message' => $message,
            'to_id' => $to_id,
            'table' => 'messages'
            );
        $url = 'https://kokykidswear.com/koky/api/writeMessage/';
        $ch = curl_init( $url );
        # Setup request to send json via POST.
        $payload = json_encode( array( "customer"=> $params ) );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        # Return response instead of printing.
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        # Send request.
        $result = curl_exec($ch);
        curl_close($ch);
        # Print response.
        $response = (array) json_decode($result);
        echo json_encode($response);
        if($response['done']){
            echo '<meta http-equiv="refresh" content="0; url='.base_url('adminControllerUsers/messages').'" />';
        }
    }
    public function getData()
	{
        $json = file_get_contents('php://input');
        // Converts it into a PHP object
        $data = json_decode($json);
        $data = $data->customer;
        if(isset($data->token)){
            $tokenData = $this->cashier_model->getByData('tokens',' WHERE token = \''.$this->token.'\'');
            if($tokenData){
                $products = $this->cashier_model->getByData('products','');
                $movestock = $this->cashier_model->getByData('movestock','');
                $stock = $this->cashier_model->getByData('stock','');
                $moves = $this->cashier_model->getByData('moves','');
                $transactions = $this->cashier_model->getByData('transactions','');
                $clients = $this->cashier_model->getByData('clients','');
                $balances = $this->cashier_model->getByData('balances','');
                $stores = $this->cashier_model->getByData('stores','');
                $storages = $this->cashier_model->getByData('storages','');
                $users = $this->cashier_model->getByData('users','');
                $allData = array(
                    'products' => $products,
                    'movestock' => $movestock,
                    'moves' => $moves,
                    'stock' => $stock,
                    'transactions' => $transactions,
                    'clients' => $clients,
                    'balances' => $balances,
                    'stores' => $stores,
                    'storages' => $storages,
                    'users' => $users,
                    'done' => 1
                );
                echo json_encode($allData);
            }
        }
    }
	public function reciver()
	{
        // Takes raw data from the request
        $json = file_get_contents('php://input');
        // Converts it into a PHP object
        $data = json_decode($json);
        $data = $data->customer;
        if(isset($data->token)){
            $tokenData = $this->cashier_model->getByData('tokens',' WHERE token = \''.$data->token.'\'');
            if($tokenData){
                $tables = array(
                    'movestock',
                    'stock',
                    'moves',
                    'clients',
                    'balances',
                    'stores',
                    'storages',
                    'users',
                    'products',
                    'transactions'
                );
                if($data->table == 'movestock' OR $data->table == 'stock'){
                    $tableData = $data->{$data->table.'s'};
                }else{
                    $tableData = $data->{$data->table};
                }
                //Have Token
                if(in_array($data->table,$tables)){
                    $i=0;
                    foreach($tableData as $row){
                        //Check if exist
                        if($data->table == 'clients'){
                            $rowData = $this->cashier_model->getByData($data->table,' WHERE (mobile = \''.$row->mobile.'\')');
                        }elseif($data->table == 'products'){
                            $rowData = $this->cashier_model->getByData($data->table,' WHERE (created_at = \''.$row->created_at.'\') AND (source = \''.$row->source.'\')');
                        }elseif($data->table == 'balances' OR $data->table == 'stores' OR $data->table == 'storages'){
                            $rowData = $this->cashier_model->getByData($data->table,' WHERE (created_at = \''.$row->created_at.'\')');
                        }elseif($data->table == 'users'){
                            $rowData = $this->cashier_model->getByData($data->table,' WHERE (password = \''.$row->password.'\') AND (created_at = \''.$row->created_at.'\')');
                        }elseif($data->table == 'moves'){
                            $rowData = $this->cashier_model->getByData($data->table,' WHERE (p_created_at = \''.$row->p_created_at.'\') AND (created_at = \''.$row->created_at.'\')');
                        }else{
                            $rowData = $this->cashier_model->getByData($data->table,' WHERE (place = \''.$row->place.'\') AND (place_id = \''.$row->place_id.'\') AND (created_at = \''.$row->created_at.'\')');
                        }
                        if(!$rowData){
                                //No Item
                                $row = (array) $row;
                                $row['indevice_id'] = $row['id'];
                                $tables2 = array(
                                    'movestock',
                                    'stock',
                                    'moves',
                                    'clients',
                                    'balances',
                                    'products',
                                    'transactions'
                                );
                                if(in_array($data->table,$tables2)){
                                    unset($row['id']);
                                }
                                $row['t_id'] = $tokenData[0]['id'];
                                $newItem = $this->cashier_model->insertApiRequest($data->table,$row);
                                $i++;
                        }
                        //Item Founded but needs to check state for movestock {1 IS Done} and transactions {1 IS Refunded}
                        if($rowData){
                            //check state
                            if($data->table == 'movestock' OR $data->table == 'transactions'){
                                $newItemData = (array) $rowData[0];
                                $row = (array) $row;
                                //Update Row With new state
                                unset($row['id']);
                                if($row['state']){
                                    $this->cashier_model->updateOriginalRequest($data->table,$row,'created_at',$newItemData['created_at']);
                                }
                            }
                        }
                    }
                    //Valid Table
                    $response = array(
                        $data->table.'_added' => $i,
                        'done' => 1
                    );
                    echo json_encode($response);
                }else{
                    $response = array(
                        'error' => 'Unvalid Table Name.',
                        'done' => 0
                    );
                    echo json_encode($response);
                }
            }else{
                $response = array(
                    'error' => 'Unvalid Token.',
                    'done' => 0
                );
                echo json_encode($response);
            }
        }else{
            $response = array(
                'error' => 'Please Send Your Device Token.',
                'done' => 0
            );
            echo json_encode($response);
        }
    }
    public function movestock_update(){
        $movestocks = $this->cashier_model->getByData('movestock','');
        //Request
        $params = array (
            'token' => $this->token,
            'table' => 'movestock',
            'movestocks' => $movestocks
            );
        $url = $this->reciver;
        $ch = curl_init( $url );
        # Setup request to send json via POST.
        $payload = json_encode( array( "customer"=> $params ) );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        # Return response instead of printing.
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        # Send request.
        $result = curl_exec($ch);
        curl_close($ch);
        //Update
        $now = new DateTime();
        $now->setTimezone(new DateTimezone('Africa/Cairo'));
        $dateNow = (array) $now;
        foreach($movestocks as $movestock){
            $this->cashier_model->updateByData('movestock','`sent_at` = \''.$dateNow['date'].'\' WHERE (id = '.$movestock['id'].')');
        }
        # Print response.
        $response = (array) json_decode($result);
        echo json_encode($response);
    }
    public function stock_update(){
        $stocks = $this->cashier_model->getByData('stock','');
        //Request
        $params = array (
            'token' => $this->token,
            'table' => 'stock',
            'stocks' => $stocks
            );
        $url = $this->reciver;
        $ch = curl_init( $url );
        # Setup request to send json via POST.
        $payload = json_encode( array( "customer"=> $params ) );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        # Return response instead of printing.
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        # Send request.
        $result = curl_exec($ch);
        curl_close($ch);
        //Update
        $now = new DateTime();
        $now->setTimezone(new DateTimezone('Africa/Cairo'));
        $dateNow = (array) $now;
        foreach($stocks as $stock){
            $this->cashier_model->updateByData('stock','`sent_at` = \''.$dateNow['date'].'\' WHERE (id = '.$stock['id'].')');
        }
        # Print response.
        $response = (array) json_decode($result);
        echo json_encode($response);
    }
    public function product_update(){
        $products = $this->cashier_model->getByData('products','');
        //Request
        $params = array (
            'token' => $this->token,
            'table' => 'products',
            'products' => $products
            );
        $url = $this->reciver;
        $ch = curl_init( $url );
        # Setup request to send json via POST.
        $payload = json_encode( array( "customer"=> $params ) );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        # Return response instead of printing.
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        # Send request.
        $result = curl_exec($ch);
        curl_close($ch);
        //Update
        $now = new DateTime();
        $now->setTimezone(new DateTimezone('Africa/Cairo'));
        $dateNow = (array) $now;
        foreach($products as $product){
            $this->cashier_model->updateByData('products','`sent_at` = \''.$dateNow['date'].'\' WHERE (id = '.$product['id'].')');
        }
        # Print response.
        $response = (array) json_decode($result);
        echo json_encode($response);
    }
    public function transaction_update(){
        $transactions = $this->cashier_model->getByData('transactions','');
        //Request
        $params = array (
            'token' => $this->token,
            'table' => 'transactions',
            'transactions' => $transactions
            );
        $url = $this->reciver;
        $ch = curl_init( $url );
        # Setup request to send json via POST.
        $payload = json_encode( array( "customer"=> $params ) );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        # Return response instead of printing.
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        # Send request.
        $result = curl_exec($ch);
        curl_close($ch);
        //Update
        $now = new DateTime();
        $now->setTimezone(new DateTimezone('Africa/Cairo'));
        $dateNow = (array) $now;
        foreach($transactions as $transaction){
            $this->cashier_model->updateByData('transactions','`sent_at` = \''.$dateNow['date'].'\' WHERE (id = '.$transaction['id'].')');
        }
        # Print response.
        $response = (array) json_decode($result);
        echo json_encode($response);
    }
    public function move_update(){
        $moves = $this->cashier_model->getByData('moves','');
        //Request
        $params = array (
            'token' => $this->token,
            'table' => 'moves',
            'moves' => $moves
            );
        $url = $this->reciver;
        $ch = curl_init( $url );
        # Setup request to send json via POST.
        $payload = json_encode( array( "customer"=> $params ) );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        # Return response instead of printing.
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        # Send request.
        $result = curl_exec($ch);
        curl_close($ch);
        //Update
        $now = new DateTime();
        $now->setTimezone(new DateTimezone('Africa/Cairo'));
        $dateNow = (array) $now;
        foreach($moves as $move){
            $this->cashier_model->updateByData('moves','`sent_at` = \''.$dateNow['date'].'\' WHERE (id = '.$move['id'].')');
        }
        # Print response.
        $response = (array) json_decode($result);
        echo json_encode($response);
    }
    public function client_update(){
        $clients = $this->cashier_model->getByData('clients','');
        //Request
        $params = array (
            'token' => $this->token,
            'table' => 'clients',
            'clients' => $clients
            );
        $url = $this->reciver;
        $ch = curl_init( $url );
        # Setup request to send json via POST.
        $payload = json_encode( array( "customer"=> $params ) );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        # Return response instead of printing.
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        # Send request.
        $result = curl_exec($ch);
        curl_close($ch);
        //Update
        $now = new DateTime();
        $now->setTimezone(new DateTimezone('Africa/Cairo'));
        $dateNow = (array) $now;
        foreach($clients as $client){
            $this->cashier_model->updateByData('clients','`sent_at` = \''.$dateNow['date'].'\' WHERE (id = '.$client['id'].')');
        }
        # Print response.
        $response = (array) json_decode($result);
        echo json_encode($response);
    }
    public function storage_update(){
        $storages = $this->cashier_model->getByData('storages','');
        //Request
        $params = array (
            'token' => $this->token,
            'table' => 'storages',
            'storages' => $storages
            );
        $url = $this->reciver;
        $ch = curl_init( $url );
        # Setup request to send json via POST.
        $payload = json_encode( array( "customer"=> $params ) );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        # Return response instead of printing.
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        # Send request.
        $result = curl_exec($ch);
        curl_close($ch);
        //Update
        $now = new DateTime();
        $now->setTimezone(new DateTimezone('Africa/Cairo'));
        $dateNow = (array) $now;
        foreach($storages as $storage){
            $this->cashier_model->updateByData('storages','`sent_at` = \''.$dateNow['date'].'\' WHERE (id = '.$storage['id'].')');
        }
        # Print response.
        $response = (array) json_decode($result);
        echo json_encode($response);
    }
    public function balance_update(){
        $balances = $this->cashier_model->getByData('balances','');
        //Request
        $params = array (
            'token' => $this->token,
            'table' => 'balances',
            'balances' => $balances
            );
        $url = $this->reciver;
        $ch = curl_init( $url );
        # Setup request to send json via POST.
        $payload = json_encode( array( "customer"=> $params ) );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        # Return response instead of printing.
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        # Send request.
        $result = curl_exec($ch);
        curl_close($ch);
        //Update
        $now = new DateTime();
        $now->setTimezone(new DateTimezone('Africa/Cairo'));
        $dateNow = (array) $now;
        foreach($balances as $balance){
            $this->cashier_model->updateByData('balances','`sent_at` = \''.$dateNow['date'].'\' WHERE (id = '.$balance['id'].')');
        }
        # Print response.
        $response = (array) json_decode($result);
        echo json_encode($response);
    }
    public function store_update(){
        $stores = $this->cashier_model->getByData('stores','');
        //Request
        $params = array (
            'token' => $this->token,
            'table' => 'stores',
            'stores' => $stores
            );
        $url = $this->reciver;
        $ch = curl_init( $url );
        # Setup request to send json via POST.
        $payload = json_encode( array( "customer"=> $params ) );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        # Return response instead of printing.
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        # Send request.
        $result = curl_exec($ch);
        curl_close($ch);
        //Update
        $now = new DateTime();
        $now->setTimezone(new DateTimezone('Africa/Cairo'));
        $dateNow = (array) $now;
        foreach($stores as $store){
            $this->cashier_model->updateByData('stores','`sent_at` = \''.$dateNow['date'].'\' WHERE (id = '.$store['id'].')');
        }
        # Print response.
        $response = (array) json_decode($result);
        echo json_encode($response);
    }
    public function user_update(){
        $users = $this->cashier_model->getByData('users','');
        //Request
        $params = array (
            'token' => $this->token,
            'table' => 'users',
            'users' => $users
            );
        $url = $this->reciver;
        $ch = curl_init( $url );
        # Setup request to send json via POST.
        $payload = json_encode( array( "customer"=> $params ) );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        # Return response instead of printing.
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        # Send request.
        $result = curl_exec($ch);
        curl_close($ch);
        //Update
        $now = new DateTime();
        $now->setTimezone(new DateTimezone('Africa/Cairo'));
        $dateNow = (array) $now;
        foreach($users as $user){
            $this->cashier_model->updateByData('users','`sent_at` = \''.$dateNow['date'].'\' WHERE (id = '.$user['id'].')');
        }
        # Print response.
        $response = (array) json_decode($result);
        echo json_encode($response);
    }
}
?>
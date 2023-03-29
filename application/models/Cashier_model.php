<?php

class cashier_model extends CI_Model{

    //Reading Data From DB
    function getFullRequest($table,$con_col,$count='',$limit = null,$start = null){

        /*
        Usage:
            $this->products_model->getFullRequest(
                'Table',
                '# Leave Empty To Get Data -OR- (Your Statements) #',
                '# Leave Empty To Get Data -OR- Type Anything To Count Rows #',
                'Limit',
                'Start'
            );
        */
        if($con_col == ''){
            $q=$this->db->get($table);
        }else{
            $q=$this->db->get_where($table,$con_col);
        }
        
        if ($limit !== null && $start !== null) {
            $this->db->limit($limit, $start);
         }
        if($q->num_rows() > 0){

            if($count==''){

                return $q->result();

            }else{

                return $q->num_rows();

            }

        }else{return false;}

    }
    function findConnectedRecord($id,$connectedId,$table,$connectedTable){
        $tableData = $this->cashier_model->getByData($table,' WHERE (id = '.$id.')');
        if($tableData[0]['t_id'] > 0 AND $tableData[0]['t_id'] !== NULL){
            //Sent From Device
            $connectedTableData = $this->cashier_model->getByData($connectedTable,' WHERE (indevice_id = '.$connectedId.')');
            return $connectedTableData[0]['id'];
        }else{
            return $connectedId;
        }
    }
    //Get By Data
    function getByData($table,$statment){
        $sql="Select * from `$table`$statment";    
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    function getpid($created_at,$barcode){
        $explodedDate = explode('.',$created_at);
        $explodedDate1 = explode(':',$explodedDate[0]);
        return $barcode.$explodedDate1[2].substr($explodedDate[1],0,2);
    }
    function getOldBar($id){
        $oldBarcodeData = str_split($id);
		$letters = count($oldBarcodeData);
		$created_atpcs = '';
		for($i=($letters-1);$i >= ($letters-4);$i--){
			$created_atpcs .= $oldBarcodeData[$i];
		}
		$created_atpcs = strrev($created_atpcs);
		$oldBarcode = explode($created_atpcs,$id);
		return array($oldBarcode[0],$created_atpcs);
    }
    //Get By Data
    function updateByData($table,$statment){
        $sql='UPDATE `'.$table.'` SET '.$statment;    
        $query = $this->db->query($sql);
        return $query;
    }
    function p_created_at($id){
        $fullId = str_split($id, 4);
        $md = str_split($fullId[1],2);
        $hm = str_split($fullId[2],2);
        $s_ms = str_split($fullId[3],2);
        $p_created_at = $fullId[0].'-'.$md[0].'-'.$md[1].' '.$hm[0].':'.$hm[1].':'.$s_ms[0].'.'.$s_ms[1].$fullId[4];
        return $p_created_at;
    }
    function getQuantity($created_at,$place,$place_id){
        $allRows = $this->getByData('moves',' WHERE (p_created_at = \''.$created_at.'\') AND (place = \''.$place.'\') AND (place_id = \''.$place_id.'\')');
        if($allRows){
            $quantity = 0;
            foreach($allRows as $row){
                $row = (array) $row;
                if($row['method'] == 'minus'){
                    $row['quantity'] = (-1)*$row['quantity'];
                }
                $quantity += $row['quantity'];
            }
            return $quantity;
        }else{
            return 0;
        }
    }
    //Insert Data
    function insertRequest($table,$validations){
        $data = array();
        foreach($validations as $validation){
            if($validation[0] == 'password'){
                $data[$validation[0]] = $this->encryption->encrypt($this->input->post($validation[0]));
            }else{
                $data[$validation[0]] = $this->input->post($validation[0]);
            }
        }
        $data['image'] = $this->upload('image','jpg|png|gif|jpeg');
        $data['created_at'] = $this->dataTime();
        $data['edited_at'] = $this->dataTime();
        $data['u_id'] = $this->session->userdata('id');
        $this->db->insert($table,$data);
        return $this->db->insert_id();
    }
    //Insert Data
    function insertOriginalRequest($table,$data){
        $data['created_at'] = $this->dataTime();
        $data['u_id'] = $this->session->userdata('id');
        $this->db->insert($table,$data);
        return $this->db->insert_id();
    }
    //Insert Data
    function insertApiRequest($table,$data){
        $data['sent_at'] = $this->dataTime();
        $this->db->insert($table,$data);
        return $this->db->insert_id();
    }
    //Insert Data
    function insertApiRequest2($table,$data){
        $data['created_at'] = $this->dataTime();
        $this->db->insert($table,$data);
        return $this->db->insert_id();
    }

    //Update Data
    function updateRequest($table,$validations,$column,$userData){
        $data = array();
        foreach($validations as $validation){
            if($validation[0] == 'password' && $this->input->post($validation[0]) !== ''){
                $data[$validation[0]] = $this->encryption->encrypt($this->input->post($validation[0]));
            }elseif($validation[0] == 'password' && $this->input->post($validation[0]) == ''){}else{
                $data[$validation[0]] = $this->input->post($validation[0]);
            }
        }
        $image = $this->upload('image','jpg|png|gif|jpeg');
        if($image !== ''){
            unlink("public/uploads/".$userData->image);
            $data['image'] = $image;
        }
        $data['edited_at'] = $this->dataTime();
        $id = $userData->id;
        $this->db->where($column,$id);
        $this->db->limit(1);
        $update = $this->db->update($table,$data);

        return $update;
    }

    //Full Update Data
    function updateOriginalRequest($table,$data = array(),$column,$userData){
        $data['edited_at'] = $this->dataTime();
        $id = $userData;
        $this->db->where($column,$id);
        $this->db->limit(1);
        $update = $this->db->update($table,$data);

        return $update;
    }

    //Get Date And Time
    function dataTime(){
        $now = new DateTime();

        $now->setTimezone(new DateTimezone('Africa/Cairo'));

        $dateNow = (array) $now;
        
        return $dateNow['date'];
    }

    //Get Permissions
    function getPermissions($permissions){
        $search = array(
            'cashier',
            'barcode',
            'products',
            'store',
            'office',
            'factory'
        );
        $replace = array(
            'كاشير',
            'اصدار باركود',
            'المنتجات',
            'المخزون',
            'المكتب',
            'المصنع'
        );
        return str_replace($search,$replace,$permissions);
    }

    //Delete Request
    function deleteRequest($table,$col,$value){

        $this->db->where($col,$value);
        $this->db->limit(1);
        $this->db->delete($table);
        
    }
    //Create Form Fields
    function formFields($fields,$classPrefix,$userData = ''){
        /*
        Usage:
                $fields = array(
                    '3|text' => '{name|placeholder}--{email|placeholder}--{mobile|placeholder}',
                    '1|password' => '{password|placeholder}',
                    '1|textarea' => '{permissions|placeholder}'
                );
                $this->products_model->formFields($fields,'class&id Prefix',$userData);
        */
        $returnedFields = '';
        foreach($fields as $fieldNumOrder => $field){
            $fieldNum = explode('|',$fieldNumOrder);
            $counter = 1;
            while($counter <= $fieldNum[0]){
                $labelHolder = explode('--',$field);
                $tableHolder = explode('|',str_replace(array('{','}'),array(''),$labelHolder[$counter-1]));
                $returnedFields .= $this->getFormField($fieldNum[1],$tableHolder[1],$tableHolder[0],$classPrefix.'-'.$counter,$userData);
            $counter++;}
        }
        echo $returnedFields;
    }

    //Get Form Field
    function getFormField($order,$labelHolder,$table,$classPrefix,$userData = ''){
        /*
            Usage:
            $this->getFormField($order,$labelHolder,$table,$classPrefix);
        */
        $label = '<label for="'.$classPrefix.'-'.$table.'">'.$labelHolder.'</label>';
        $inputs = array('text','password','hidden','number','checkbox','file');
        if(in_array($order,$inputs)){
            //Start Inputs
            $data = array(
                    'type'  => $order,
                    'id'    => $classPrefix.'-'.$table
                    
            );
            if($order == 'number'){
                $data['min'] = 0;
            }
            if($order == 'checkbox'){
                $inputClass = ' mt-2 ml-2 float-right';
                $data['name']  = 'permissions[]';
                $data['value'] = $table;
                if($userData !== ''){
                    $userPermissions = explode(',',$userData->permissions);
                    if(in_array($table,$userPermissions)){
                        $data['checked'] = 'checked';
                    }
                }
            }else{
                $inputClass = ' form-control';
                $data['name']  = $table;
                $data['placeholder'] = $labelHolder;
                if($userData !== '' && $table !== 'password'){
                    $data['value'] = $userData->{$table};
                }
            }
            $data['class']  = $classPrefix.'-'.$table.$inputClass;
            if($order == 'checkbox'){
                return '<div class="col-12 float-right">'.$label.form_input($data).'</div>';
            }else{
                return $label.form_input($data);
            }
            //End Inputs
        }elseif($order == 'textarea'){
            //Start Textarea
                $data = array(
                    'name'  => $table,
                    'id'    => $classPrefix.'-'.$table,
                    'placeholder' => $labelHolder,
                    'class' => $classPrefix.'-'.$table.' form-control'
                );
                return $label.form_textarea($data);
            //End Textarea
        }elseif($order == 'dropdown'){
            //Start Dropdown
            $attr = array(
                'name'  => $table,
                'id'    => $classPrefix.'-'.$table,
                'class' => $classPrefix.'-'.$table.' form-control'
            );
            if($table == 'source'){
                $values = array(
                    'imported'=>'مستوردة',
                    'bought'=>'تم شراؤها',
                    'made'=>'تم تصنيعها'
                );
                if($userData !== ''){
                    if($userData->{$table}){
                        $selected = $userData->{$table};
                    }else{
                        $selected = 'imported';
                    }
                }else{
                    $selected = 'imported';
                }
            }elseif($table == 'place' OR $table == 'from_place'){
                $storages = array();
                $values = array();
                $untabled = array(
                    'imported-0'=>'مستوردة',
                    'bought-0'=>'تم شراؤها',
                    'made-0'=>'تم تصنيعها'
                );
                $sql="Select * from `storages`";    
                $query = $this->db->query($sql);
                $data['all_storages'] = $query->result_array();
                if($data['all_storages']){
                    foreach($data['all_storages'] as $storage){
                        $storages['storage-'.$storage['id']] = 'مخزن '.$storage['name'];
                    }
                }
                $factories = array();
                $sql="Select * from `factories`";    
                $query = $this->db->query($sql);
                $data['all_factories'] = $query->result_array();
                if($data['all_factories']){
                    foreach($data['all_factories'] as $factory){
                        $factories['factory-'.$factory['id']] = 'مصنع '.$factory['name'];
                    }
                }
                $offices = array();
                $sql="Select * from `offices`";    
                $query = $this->db->query($sql);
                $data['all_offices'] = $query->result_array();
                if($data['all_offices']){
                    foreach($data['all_offices'] as $office){
                        $offices['office-'.$office['id']] = 'مكتب '.$office['name'];
                    }
                }
                $stores = array();
                $sql="Select * from `stores`";    
                $query = $this->db->query($sql);
                $data['all_stores'] = $query->result_array();
                if($data['all_stores']){
                    foreach($data['all_stores'] as $store){
                        $stores['store-'.$store['id']] = 'فرع '.$store['name'];
                    }
                }
                $values = array_merge($untabled,$storages,$factories,$storages,$offices,$stores);
                if($userData !== ''){
                    if($userData->{$table}){
                        $selected = $userData->{$table};
                    }else{
                        $selected = array_key_first($values);
                    }
                }else{
                    $selected = array_key_first($values);
                }
            }elseif($table == 'with'){
                $users = array();
                $values = array();
                $sql="Select * from `users`";    
                $query = $this->db->query($sql);
                $data['all_users'] = $query->result_array();
                if($data['all_users']){
                    foreach($data['all_users'] as $user){
                        $users[$user['id']] = $user['name'];
                    }
                }
                $values = $users;
                $selected = array_key_first($values);
            }
            return $label.form_dropdown($table,$values,$selected,$attr);
        //End Dropdown
        }else{
            return false;
        }
    }
    function processAlert($type,$validation,$msg = ''){
        //Alerting styles
        $alertStart = '<div class="alert alert-'.$type.' alert-dismissible fade show" role="alert">';

        $alertEnd = '</div>';
        if($type == 'danger' && $validation == 'validation'){
            return validation_errors($alertStart,$alertEnd);
        }elseif($type == 'success' && $validation == 'validation'){
            return validation_errors($alertStart,$alertEnd);
        }elseif($type == 'success' && $validation == 'alert'){
            return $alertStart.$msg.$alertEnd;
        }elseif($type == 'danger' && $validation == 'alert'){
            return $alertStart.$msg.$alertEnd;
        }
    }
    function rules(){
        return $rules = array(
            'required' => 'يجب عليك إدخال %s .',
            'is_unique' => '%s مسجل لدينا بالفعل',
            'matches' => 'يجب عليك إدخال %s .',
            'integer' => 'يجب عليك إدخال %s .',
            'valid_email' => 'يجب عليك إدخال %s صحيح.',
        );
    }
    function upload($userfile,$types = 'pdf|docx|rar|zip'){
        $config['upload_path']          = './public/uploads/';
        $config['allowed_types']        = $types;
        $config['max_size']             = 50000;
        $config['max_width']            = 999999;
        $config['max_height']           = 999999;
        $config['encrypt_name']           = TRUE;

        $this->load->library('upload', $config);
        
        
        if ( ! $this->upload->do_upload($userfile))
        {
            return '';
        }else{
            $data['upload'] = array('upload_data' => $this->upload->data());
            $filename = $data['upload']['upload_data']['file_name'];
            return $filename;
        }
    }
    function validate($fields,$filter = array()){
        foreach($fields as $field){
            if(in_array($field[0],$filter)){}else{
                $this->form_validation->set_rules($field[0], $field[1], $field[2],$this->rules());
            }
        }
    }
    public function is_logged_in($accessUser = '')
    {
        $sql="Select * from `users` where (`email` = '".$this->session->userdata('email')."')";    
        $query = $this->db->query($sql);
        $userData = $query->result_array();
        if($userData){
            $userData = $userData[0];
            $userPassword = $userData['password'];
            if($userPassword == $this->session->userdata('password')){
                return True;
            }else{
                return False;
            }
        }else{
            return False;
        }
    }

    function barcode($value)
    {
        $this->load->library('zend');
        $this->zend->load('Zend/Barcode');

        Zend_Barcode::render('code128', 'image', array('text' => $value, 'drawText' => false), array());
    } 

}
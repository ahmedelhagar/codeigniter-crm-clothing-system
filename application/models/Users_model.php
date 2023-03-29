<?php

class Users_model extends CI_Model{

    //Reading Data From DB
    function getFullRequest($table,$con_col,$count='',$limit = null,$start = null){

        /*
        Usage:
            $this->users_model->getFullRequest(
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
        $data['permissions'] = implode(',',$this->input->post('permissions'));
        $data['image'] = $this->upload('image','jpg|png|gif|jpeg');
        $data['created_at'] = $this->dataTime();
        $data['edited_at'] = $this->dataTime();
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
        $data['permissions'] = implode(',',$this->input->post('permissions'));
        $image = $this->upload('image','jpg|png|gif|jpeg');
        if($image !== ''){
            unlink("public/uploads/".$userData->image);
            $data['image'] = $image;
        }
        $data['created_at'] = $this->dataTime();
        $data['edited_at'] = $this->dataTime();
        $id = $userData->id;
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
    //Get By Data
    function getByData($table,$statment){
        $sql="Select * from `$table`$statment";    
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    //Get Permissions
    function getPermissions($permissions){
        $values = '{products|التحكم الكامل في المنتجات}--{barcode|اصدار باركود}--{viewProducts|عرض احصائيات منتج}--{acceptProducts|قبول منتج}--{users|التحكم الكامل في الأعضاء}--{balance|الأرصدة سحب وايداع}--{cashier|الكاشير}--{cashierReports|تقارير الكاشير}--{stores|التحكم في المحلات}--{storages|التحكم في المخازن}';
        $searchEx = explode('}--{',$values);
        $search = array();
        $replace = array();
        foreach($searchEx as $searchData){
            $sr = explode('|',str_replace(array('{','}'),array('',''),$searchData));
            $search[] = $sr[0];
            $replace[] = $sr[1];
        }
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
                $this->users_model->formFields($fields,'class&id Prefix',$userData);
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

}
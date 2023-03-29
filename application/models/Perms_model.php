<?php

class Perms_model extends CI_Model{
    //Get By Data
    function getByData($table,$statment){
        $sql="Select * from `$table`$statment";    
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    function getPerms($perm,$redirect=''){
        $id = (int) $this->session->userdata('id');
        $userData = $this->getByData('users',' WHERE (id = '.$id.')');
        $perms = explode(',',$userData[0]['permissions']);
        if(in_array($perm,$perms)){
            return true;
        }else{
            if(!$redirect){
                redirect(base_url('cp/notPerms'));
            }else{
                return false;
            }
        }
    }
    function is_connected()
    {
        $connected = @fsockopen("www.google.com", 80); 
                                            //website, port  (try 80 or 443)
        if ($connected){
            $is_conn = true; //action when connected
            fclose($connected);
        }else{
            $is_conn = false; //action in connection failure
        }
        return $is_conn;

    }
}
?>
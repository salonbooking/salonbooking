<?php

class SLN_Action_Ajax_UpdateUser extends SLN_Action_Ajax_Abstract
{
    public function execute()
    {
       $result = $this->getResult($_POST['s']);
       if(!$result){
           $ret = array(
               'success' => 0,
               'errors' => array(__('User not found','sln'))
           );
       }else{
           $ret = array(
               'success' => 1,
               'result' => $result,
               'message' => __('User updated','sln')
           );
       }
       return $ret;
    }
    private function getResult($search)
    {
        $number = 1;
        $user_query = new WP_User_Query( compact('search','number') );
        if(!$user_query->results) return;
        $u = $user_query->results[0];
        $values = array(
            'id' => $u->ID,
            'firstname' => $u->user_firstname,
            'lastname'  => $u->user_lastname,
            'email'     => $u->user_email,
            'phone'     => get_user_meta($u->ID, '_sln_phone', true),
            'address'     => get_user_meta($u->ID, '_sln_address', true)
        );
        return $values;
    }
}

<?php
namespace app\controller;

class Admin extends \system\mvc\Controller
{
    public function say()
    {
        // $this->res->code = '404';
        // $this->res->message = '找不到您的资源';
        // $this->res->status(404)->json();
        $this->db->trans(function(){
            var_dump(\app\model\Account::migrate());
            var_dump(\app\model\User::migrate());
        });
    }
}

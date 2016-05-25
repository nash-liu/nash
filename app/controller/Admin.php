<?php
namespace app\controller;

class Admin extends \system\mvc\Controller
{
    public function say()
    {
        // $this->pdo = 123;
        // echo gettype($this->pdo);
        // $this->res->code = '404';
        // $this->res->message = '找不到您的资源';
        // $this->res->status(404)->json();
        // $this->db->trans(function(){
        //     var_dump(\app\model\Account::depot()->migrate());
        //     var_dump(\app\model\User::depot()->migrate());
        // });
        $r = new \app\model\Account(6);
        var_dump($r->_id);
        die;
    }
}

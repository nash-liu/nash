<?php
namespace app\controller;

class Admin extends \system\mvc\Controller
{
    public function say()
    {
        $this->res->code = '404';
        $this->res->message = '找不到您的资源';
        $this->res->json(404);
    }
}

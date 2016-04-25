<?php
namespace app\controller;

class Admin extends \system\mvc\Controller
{
    public function say($p1, $p2)
    {
        echo $p1, '-', $p2;
    }
}

<?php
namespace app\controller;

class Admin extends \system\mvc\Controller
{
    public function say()
    {
        // var_dump($this->db);
        // $re = $this->db->trans(function($db){
        //     $db->exec('CREATE TABLE `account` (`id` INT(11) NOT NULL AUTO_INCREMENT, `username` VARCHAR(16) NOT NULL, `password` VARCHAR(32) NOT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB');
        //     $db->exec('CREATE TABLE `user` (`id` INT(11) NOT NULL AUTO_INCREMENT, `account_id` INT(11) NOT NULL, `name` VARCHAR(50) NULL DEFAULT NULL, `age` INT(32) NULL DEFAULT NULL, PRIMARY KEY (`id`), INDEX `account_user` (`account_id`), CONSTRAINT `account_user` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`)) COLLATE=\'utf8mb4_general_ci\' ENGINE=InnoDB');
        // });
        // var_dump($this->db);
        // var_dump($re);
        // $this->res->code = '404';
        // $this->res->message = '找不到您的资源';
        // $this->res->status(404)->json();
        var_dump($this->db->trans(function() {
            \app\model\Account::migrate();
            \app\model\User::migrate();
        }));
    }
}

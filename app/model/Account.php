<?php
namespace app\model;
use \system\mvc\Model;
class Account extends Model
{
    protected static $table = array(
        Model::COLLATE => 'utf8mb4_general_ci',
        Model::ENGINE => 'InnoDB',
        Model::TABLE => 'account',
        Model::FIELDS => array(
            'id' => array(
                Model::TYPE => Model::TYPE_INT,
                Model::NOT_NULL,
                Model::AUTO_INCREMENT,
                Model::COMMENT => '主键id'
            ),
            'username' => array(
                Model::TYPE => array(Model::TYPE_VARCHAR, 16),
                Model::NOT_NULL
            ),
            'password' => array(
                Model::TYPE => array(Model::TYPE_VARCHAR, 32),
                Model::NOT_NULL
            )
        ),
        Model::PRIMARY => array(
            'id'
        )
    );
}
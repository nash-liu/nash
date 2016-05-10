<?php
namespace app\model;
use \system\mvc\Model;
class User extends Model
{
    protected static $table = array(
        Model::COLLATE => 'utf8mb4_general_ci',
        Model::ENGINE => 'InnoDB',
        Model::TABLE => 'user',
        Model::FIELDS => array(
            'id' => array(
                Model::TYPE => Model::TYPE_INT,
                Model::NOT_NULL,
                Model::AUTO_INCREMENT,
                Model::COMMENT => '主键id'
            ),
            'account_id' => array(
                Model::TYPE => Model::TYPE_INT,
                Model::NOT_NULL,
                Model::COMMENT => '外键关联--account.id'
            ),
            'name' => array(
                Model::TYPE => array(Model::TYPE_VARCHAR, 50),
                Model::DEFAULT_VALUE => Model::DEFAULT_NULL
            ),
            'age' => array(
                Model::TYPE => Model::TYPE_INT,
                Model::DEFAULT_VALUE => Model::DEFAULT_NULL
            )
        ),
        Model::PRIMARY => array(
            'id'
        ),
        Model::FOREIGN => array(
            'account' => array('account_id', 'id')
        ),
        Model::INDEX => array(
            array('account_id')
        )
    );
}
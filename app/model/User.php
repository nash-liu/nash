<?php
namespace app\model;

use \system\mvc\Model;

class User extends Model
{
    static $table = array(
        Model::TABLE => 'user',
        Model::COLLATE => 'utf8mb4_general_ci',
        Model::ENGINE => 'InnoDB',
        Model::COMMENT => '用户信息表',
        Model::KEYS => array(
            Model::PRIMARY => array('id'),
            Model::FOREIGN => array(
                'account' => array('account_id', 'id')
            )
        ),
        Model::FIELDS => array(
            'id' => array(
                Model::TYPE => array(Model::TYPE_INT, 11),
                Model::NOT_NULL,
                Model::AUTO_INCREMENT,
                Model::COMMENT => '主键id'
            ),
            'account_id' => array(
                Model::TYPE => array(Model::TYPE_INT, 11),
                Model::COMMENT => '关联Account.id'
            ),
            'name' => array(
                Model::TYPE => array(Model::TYPE_VARCHAR, 16),
                Model::COMMENT => '名称'
            ),
            'age' => array(
                Model::TYPE => array(Model::TYPE_VARCHAR, 32),
                Model::COMMENT => '年龄'
            ),
            '_data_status' => array(
                Model::TYPE => array(Model::TYPE_ENUM, array('nomal', 'deleted')),
                Model::NOT_NULL,
                Model::DEFAULT_VALUE => 'nomal',
                Model::COMMENT => '数据状态'
            ),
            '_create_datetime' => array(
                Model::TYPE => Model::TYPE_DATETIME,
                Model::NOT_NULL,
                Model::DEFAULT_VALUE => Model::DEFAULT_NOW,
                Model::COMMENT => '创建时间'
            ),
            '_update_datetime' => array(
                Model::TYPE => Model::TYPE_DATETIME,
                Model::DEFAULT_VALUE => Model::DEFAULT_NULL,
                Model::ON_UPDATE_NOW,
                Model::COMMENT => '更新时间'
            )
        )
    );
}
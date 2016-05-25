<?php
namespace app\model;

use \system\mvc\Model;
use \system\inc\database\mysql\DbTypeVarchar;

class Account extends Model
{
    protected static function _fileds()
    {
        return array(
            'username' => new DbTypeVarchar(16) , // 设置属性格式 varchar(16)
            'password' => new DbTypeVarchar(32)   // 设置属性格式 varchar(32)
        );
    }
}
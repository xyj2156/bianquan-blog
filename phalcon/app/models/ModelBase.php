<?php
/**
 * Created by PhpStorm.
 * User: jief2
 * Date: 2018/7/1
 * Time: 1:29
 */

namespace App\Model;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\{
    Exception as ModelError
};

class ModelBase extends Model
{
    protected $prefix = '';
    protected $tableName = '';

    public function initialize()
    {
        if (!empty($this->tableName))
            $this->setSource($this->prefix . $this->tableName);
    }

    public function getSource()
    {
        if (empty($this->tableName)) {
            return $this->prefix . parent::getSource();
        }
        return parent::getSource();
    }

    /**
     * 尝试调用 未知静态方法的时候 先尝试调用 父级方法，如果抛出异常，调用 query 之后 的方法
     * @param $method
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($method, $arguments)
    {
        try{
            return parent::__callStatic($method, $arguments); // TODO: Change the autogenerated stub
        }catch (\Error | \Exception $error){
            return static::query() -> $method(...$arguments);
        }
    }

    /**
     * @param $field
     * @param string $id
     * @return mixed | bool
     * todo 静态调用 测试通过 测试对象中调用的问题
     */
    static public function setInc ($field, $id = 0)
    {
        if(!is_numeric($id)){
            return static::_setInc($field);
        }
        $tmpObj = static::findFirst($id);
        if(isset($tmpObj -> $field)){
            $tmpObj -> $field += 1;
            return $tmpObj -> update();
        }
        return false;
    }

//    todo 测试 写自增函数 添加到所有 继承这个模型的 calss 没有测试
    protected function _setInc($field)
    {
        if(isset($this -> $field)){
            $this -> $field += 1;
            return $this -> update();
        }
        return false;
    }
}
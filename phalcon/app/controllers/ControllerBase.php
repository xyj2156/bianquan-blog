<?php

namespace App\Controller;
use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
    /**
     * 公共返回json的方法
     * @param int $code         返回状态码
     * @param mixed $data       返回的数据
     * @param string $msg       返回的信息 默认为空
     * @param mixed ...$other   其他数据
     */
    protected function responseJson ($errorCode, $data, array $msg = [], ...$other)
    {
        empty($msg) && $msg = ['get ok', 'get fail'];
        $flag = $data === null;
        $key = (int)$flag;
        $key !== 0 && $key = 1;
        $arr = [
            'status' => $flag ? $errorCode : 0,
            'retcode' => !$flag,
            'message' => $msg[$key],
            'msg' => $msg[$key],
            'result' => $data,
        ];
        empty($other) or $arr['detail'] = $other;
        return $this -> response -> setJsonContent($arr);
    }

    protected function selfActionToUrl(array $arr):array
    {
        $class = strtolower(static::class);
        if(false !== strpos($class, '\\')){
            $tmp = explode('\\', $class);
            $class = array_pop($tmp);
            $class = substr($class, 0, -10);
        }
        foreach ($arr as $k => $v){
            if(is_numeric($k)){
                $k = $v;
                unset($arr[$k]);
            }
            $arr[$k] = HOST.'/'.$class.'/'.$v;
        }
        return $arr;
    }
}

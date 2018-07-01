<?php
/**
 * 2018年6月28日 19:23:22
 */
namespace App\Controller;

class IndexController extends ControllerBase
{

    public function __call($method, $arg)
    {
        $data = $this -> getUrlArr([
            'test' => '/index.php',
            'page' => '/page/id/',
            'get_webdata' => '/config/get_webdata',
            'get_tags' => '/tags/getTags',
            'get_neighbors' => '/neighbors/getNeighbors',
            'searchfor' => '/article/search',
            'getPageCount' => '/article/getPageCount',
            'getPage' => '/article/getPage',
            'getComment' => '/comment/getComment',
            'getOne' => '/article/getOne',
            'add_praise' => '/article/add_praise',
            'addComment' => '/comment/addComment',
            'register' => '/login/register',
            'check_login' => '/login/check_login',
            'get_about' => '/config/get_about',
            'get_all_article' => '/article/get_all_article'
        ]);
        return $this -> responseJson(10000, $data);
    }

    protected function getUrlArr(array $arr)
    {
        $host = HOST;
        foreach ($arr as $k => &$v)
            $v = "{$host}$v";
        return $arr;
    }
}
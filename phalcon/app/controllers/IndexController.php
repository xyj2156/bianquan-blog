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
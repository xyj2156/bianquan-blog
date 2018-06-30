<?php
/**
 * Created by PhpStorm.
 * User: jief2
 * Date: 2018/7/1
 * Time: 2:59
 */

namespace App\Controller;


use App\Model\Article;

class ArticleController extends ControllerBase
{
    protected $pageSize = 6;
    public function searchAction()
    {
        $key = $this -> request -> getJsonRawBody(true) ['key'];
        if(!$key) {
            return $this -> responseJson(10005, null, ['', '传过来的值为空']);
        }

        return $this -> responseJson(10004, ['key' => $key]);
    }

    public function getPageCountAction()
    {
        $tag = $this -> request -> getJsonRawBody(true) ['tag'];
        $where = 'a_published=1';
        if($tag){
            $where .= " AND a_tag=$tag";
        }
        $data = Article::count($where);
        if ($data > $this -> pageSize && $data%$this -> pageSize === 0){
            $data = floor($data/$this -> pageSize);
        } else {
            $data = ceil($data/$this -> pageSize);
        }
        return $this -> responseJson(10006, $data);
    }

    public function getPageAction ()
    {
        $json = $this -> request -> getJsonRawBody(true);
        $page = $json['page'];
        $tag = $json['tag'];
        $pageSize = $this -> pageSize;
        $where = 'a_published=1';
        if($tag){
            $where .= "AND a_tag = {$tag}";
        }
        $condtions = [
            'order' => 'a_id desc',
            'conditions' => $where,
            'offset' => $page && $page != 1 ? 0 : $page * $pageSize,
            'limit' => $pageSize,
        ];
        $data = Article::find($condtions);
        return $this -> responseJson(10007, $data);
    }
}
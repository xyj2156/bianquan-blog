<?php
/**
 * Created by PhpStorm.
 * User: jief2
 * Date: 2018/7/1
 * Time: 2:59
 */

namespace App\Controller;


use App\Model\Article;
use App\Model\WebData;

class ArticleController extends ControllerBase
{
    protected $pageSize = 6;

    public function searchAction()
    {
        $key = $this->request->getJsonRawBody(true) ['key'];
        if (!$key) {
            return $this->responseJson(10005, null, ['', '传过来的值为空']);
        }

        return $this->responseJson(10004, ['key' => $key]);
    }

    public function getPageCountAction()
    {
        $tag = $this->request->getJsonRawBody(true) ['tag'];
        $where = 'a_published=1';
        if ($tag) {
            $where .= " AND a_tag='{$tag}'";
        }
        $data = Article::count($where);
        if ($data > $this->pageSize && $data % $this->pageSize === 0) {
            $data = floor($data / $this->pageSize);
        } else {
            $data = ceil($data / $this->pageSize);
        }
        return $this->responseJson(10006, $data);
    }

    public function getPageAction()
    {
        $json = $this->request->getJsonRawBody(true);
        $page = $json['page'];
        $tag = $json['tag'];
        $pageSize = $this->pageSize;
        $where = 'a_published=1';
        if ($tag) {
            $where .= " AND a_tag = '{$tag}'";
        }
        $condtions = [
            'order' => 'a_id desc',
            'conditions' => $where,
            'offset' => $page && $page != 1 ? 0 : $page * $pageSize,
            'limit' => $pageSize,
        ];
        $data = Article::find($condtions);
        return $this->responseJson(10007, $data);
    }

    public function getOneAction()
    {
        $json = $this->request->getJsonRawBody(true);
        if (empty($json) || empty($json['id'])) {
            return $this->responseJson(10009, null);
        }
        $id = $json['id'];
        $data = Article::findFirst([
            'conditions' => 'a_id=:id: AND a_published=1',
            'bind' => [
                'id' => $id,
            ],
        ]);
        return $this->responseJson(10010, $data);
    }

    public function add_praise()
    {
        $json = $this->request->getJsonRawBody(true);
        $web_res = WebData::setInc('total_praise', 1);
        if (empty($json) || empty($json['id'])) {
            return $this->responseJson(10014, $web_res, ['webdata ok', 'webdata fail']);
        }
        $id = $json['id'];
        $article = Article::findFirst($id);
        $article->a_praise += 1;
        $_res = $article->save();
        $msg = ['all ok'];
        if ($web_res) {
            if ($_res) {
                $errcode = 10011;
                $res = true;
                $msg [] = 'all fail';
            } else {
                $errcode = 10011;
                $res = null;
                $msg [] = 'article fail, webdata ok';
            }
        } else {
            if ($_res) {
                $errcode = 10012;
                $res = null;
                $msg [] = 'article ok webdata fail';
            } else {
                $errcode = 10013;
                $res = null;
                $msg[] = 'all fail';
            }
        }
        return $this->responseJson($errcode, $res, $msg);
    }

//    todo 时间轴
    public function get_all_article()
    {}
}
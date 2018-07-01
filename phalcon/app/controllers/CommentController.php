<?php
/**
 * Created by PhpStorm.
 * User: jief2
 * Date: 2018/7/1
 * Time: 13:17
 */

namespace App\Controller;


use App\Model\Comment;

class CommentController extends ControllerBase
{
    public function getComment()
    {
        $json = $this -> request -> getJsonRawBody(true);
        $post_id = $json['id'];
        if(empty($post_id)){
            return $this -> responseJson(10008, null, ['', '未知的 文章ID']);
        }
        $where = "c_article_id = {$post_id}";
//        $where = ['c_article_id' => $post_id];
        $data = Comment::find([
            'conditions' => $where,
            'columns' => '*,if(c_published=1,c_content,0) as c_content'
        ]);
        $data = $data -> toArray();
//        处理查询出来的数据
        foreach ($data as &$v){
            $v = $v -> toArray();
            $v_tmp = array_shift($v);
            $v_tmp = $v_tmp -> toArray();
            $v = array_merge($v_tmp,$v);
        }
        return $this -> responseJson(10008, $data);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: jief2
 * Date: 2018/7/1
 * Time: 13:17
 */

namespace App\Controller;


use App\Model\Comment;
use App\Model\WebData;

class CommentController extends ControllerBase
{
    public function getComment()
    {
        $json = $this -> request -> getJsonRawBody(true);
        $post_id = empty($json['id']) ? 0 : $json['id'];
//      if(empty($post_id)) return $this -> responseJson(10008, null, ['', '未知的 文章ID']);
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

    public function addComment ()
    {
        $json = $this -> request -> getJsonRawBody(true);
        if(!is_array($json)){
            return $this -> responseJson(10014, null, ['', '非法参数']);
        }
        if(empty($json['id'])){
            $json['id'] = 0;
            $webdata = WebData::setInc('total_comment', 1);
        }
        $comment = new Comment;
        $comment -> c_article_id = $json['id'];
        $comment -> c_content = $json['commentText'];
        $comment -> c_user = $json['commenter'];
//        $comment -> c_email = $json['commenterEmail'];
        $comment -> c_time = $json['time'];
        $comment -> c_type = $json['type'];
        $comment -> c_old_comment = $json['oldComment'];
        $comment -> c_index = $json['index'];
        $comment -> c_published = 1;
        $res = $comment -> create();
        empty($res) && $res = null;
        return $this -> responseJson(10015, $res, ['add comment ok'. 'add comment fail']);
    }
}
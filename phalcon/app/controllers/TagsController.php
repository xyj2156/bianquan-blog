<?php
/**
 * Created by PhpStorm.
 * User: jief2
 * Date: 2018/7/1
 * Time: 2:41
 */

namespace App\Controller;

use App\Model\Tags;

class TagsController extends ControllerBase
{
    public function getTagsAction ()
    {
        $data = Tags::find([
            'columns' => 'tag_name as tag'
        ]);
        $data = $data -> toArray();
        return $this -> responseJson(10002, $data);
    }
}
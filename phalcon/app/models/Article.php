<?php
/**
 * Created by PhpStorm.
 * User: jief2
 * Date: 2018/7/1
 * Time: 3:14
 */

namespace App\Model;


class Article extends ModelBase
{
    protected $tableName = 'article';
    public $a_tag;
    public $a_published;
}
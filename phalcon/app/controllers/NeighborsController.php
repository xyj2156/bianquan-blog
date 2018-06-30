<?php
/**
 * Created by PhpStorm.
 * User: jief2
 * Date: 2018/7/1
 * Time: 2:43
 */

namespace App\Controller;


use App\Model\Neighbors;

class NeighborsController extends ControllerBase
{
    public function getNeighborsAction ()
    {
        $data = Neighbors::find() -> toArray();
        return $this -> responseJson(10003, $data);
    }
}
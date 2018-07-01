<?php
/**
 * 2018年6月28日 19:23:22
 */
namespace App\Controller;

use App\Model\WebData;

class ConfigController extends ControllerBase
{

    public function get_webdataAction()
    {
//        $data = $this -> db -> fetchAll('select * from webdata');
        $data = (WebData::find()) -> toArray();
        return $this -> responseJson(10001, $data);
    }

    public function get_about()
    {}
}
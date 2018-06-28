<?php
/**
 * Created by PhpStorm.
 * User: jief2
 * Date: 2018/6/28
 * Time: 18:43
 */

/**
 * 获取主机名（域名）
 * @return string
 */
function getHost():string
{
    $https = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];
    return "{$https}{$host}";
}
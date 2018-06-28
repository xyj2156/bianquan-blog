<?php

$router = $di->getRouter();

$di -> set('router', function () use ($router) {
//    设置 url 源 从哪里 解析 控制器 和 方法
    $router -> setUriSource(1);
    return $router;
});

// Define your routes here

$router->handle();

<?php

$loader = new \Phalcon\Loader();

//注册命名空间
$loader->registerNamespaces([
    'App\Model' => $config->application->modelsDir,
    'App\Library' => $config->application->libraryDir,
    'App\Controller' => $config->application->controllersDir,
])->register();
<?php

/*
 * This file is part of the jimchen/hashids.
 *
 * (c) JimChen <18219111672@163.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

define('YII_DEBUG', true);
$_SERVER['SCRIPT_NAME'] = '/'.__DIR__;
$_SERVER['SCRIPT_FILENAME'] = __FILE__;
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../vendor/yiisoft/yii2/Yii.php';

Yii::setAlias('@tests', __DIR__);
$config = require __DIR__.'/app/config/main.php';
$app = new \yii\console\Application($config);

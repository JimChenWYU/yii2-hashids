<?php

/*
 * This file is part of the jimchen/hashids.
 *
 * (c) JimChen <18219111672@163.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

return [
    'id'          => 'yii2-hashids-app',
    'basePath'    => dirname(__DIR__),
    'vendorPath'  => dirname(dirname(__DIR__)) . '/vendor',
    'runtimePath' => dirname(dirname(__DIR__)) . '/runtime',
    'bootstrap'   => [
    ],
    'components'  => [
        'hashids' => [
            'class' => 'jimchen\hashids\HashidsComponent',
            'salt'  => '5b862dfe120ec',
        ],
    ],
];

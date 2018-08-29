<?php

/*
 * This file is part of the jimchen/hashids.
 *
 * (c) JimChen <18219111672@163.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace jimchen\hashids;

use Hashids\Hashids;
use yii\helpers\ArrayHelper;

class HashidsFactory
{
    /**
     * Make a new Hashids client.
     *
     * @author JimChen <1047004324@qq.com>
     *
     * @param array $config
     *
     * @return Hashids
     *
     * @throws \Exception
     */
    public function make(array $config)
    {
        $config = $this->getConfig($config);

        return $this->getClient($config);
    }

    /**
     * Get the configuration data.
     *
     * @author JimChen <1047004324@qq.com>
     *
     * @param array $config
     *
     * @return array
     */
    protected function getConfig(array $config)
    {
        return [
            'salt' => ArrayHelper::getValue($config, 'salt', ''),
            'length' => ArrayHelper::getValue($config, 'length', 0),
            'alphabet' => ArrayHelper::getValue($config, 'alphabet', 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'),
        ];
    }

    /**
     * Get the hashids client.
     *
     * @author JimChen <1047004324@qq.com>
     *
     * @param array $config
     *
     * @return Hashids
     *
     * @throws \Exception
     */
    protected function getClient(array $config)
    {
        return new Hashids($config['salt'], $config['length'], $config['alphabet']);
    }
}

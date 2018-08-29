<?php

/*
 * This file is part of the jimchen/hashids.
 *
 * (c) JimChen <18219111672@163.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace jimchen\hashids;

use jimchen\hashids\contracts\AbstractManager;

class HashidsManager extends AbstractManager
{
    /**
     * The factory instance.
     *
     * @var HashidsFactory
     */
    protected $factory;

    /**
     * Create a new Hashids manager instance.
     *
     * @author JimChen <1047004324@qq.com>
     *
     * @param array          $config
     * @param HashidsFactory $factory
     */
    public function __construct(array $config, HashidsFactory $factory)
    {
        parent::__construct($config);

        $this->factory = $factory;
    }

    /**
     * Create the connection instance.
     *
     * @author JimChen <1047004324@qq.com>
     *
     * @param array $config
     *
     * @return \Hashids\Hashids
     *
     * @throws \Exception
     */
    protected function createConnection(array $config)
    {
        return $this->factory->make($config);
    }

    /**
     * Get the configuration name.
     *
     * @author JimChen <1047004324@qq.com>
     *
     * @return string
     */
    protected function getConfigName()
    {
        return 'hashids';
    }

    /**
     * Get the factory instance.
     *
     * @author JimChen <1047004324@qq.com>
     *
     * @return HashidsFactory
     */
    public function getFactory()
    {
        return $this->factory;
    }
}

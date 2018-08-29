<?php

/*
 * This file is part of the jimchen/hashids.
 *
 * (c) JimChen <18219111672@163.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace jimchen\hashids;

use yii\base\Component;
use yii\base\InvalidArgumentException;

/**
 * @property HashidsFactory $factory
 * @property HashidsManager $manager
 * @property object         $connection
 *
 * @method bool|string encode(...$params)
 * @method array decode(...$params)
 * @method mixed|string encode_hex(string $str)
 * @method string decode_hex(string $hash)
 * @method int get_max_int_value()
 */
class HashidsComponent extends Component
{
    /*
    |--------------------------------------------------------------------------
    | Default Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the connections below you wish to use as
    | your default connection for all work. Of course, you may use many
    | connections at once using the manager class.
    |
    */
    public $default;

    /**
     * @var string
     */
    public $salt;

    /**
     * @var int
     */
    public $length;

    /*
    |--------------------------------------------------------------------------
    | Hashids Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the connections setup for your application. Example
    | configuration has been included, but you may add as many connections as
    | you would like.
    |
    */
    private $connections;

    /**
     * Initialize component.
     *
     * @author JimChen <1047004324@qq.com>
     */
    public function init()
    {
        $this->setUpConfig();
        $this->registerFactory();
        $this->registerManager();
        $this->registerBindings();
    }

    /**
     * Setup the config.
     *
     * @author JimChen <1047004324@qq.com>
     */
    protected function setUpConfig()
    {
        if (is_null($this->default)) {
            $this->default = 'main';
        }

        if (is_null($this->length)) {
            $this->length = 16;
        }

        if (is_null($this->salt)) {
            throw new InvalidArgumentException('HashidsComponent require salt value.');
        }

        $this->connections = [
            'main' => [
                'salt'   => $this->salt,
                'length' => $this->length,
            ],

            'alternative' => [
                'salt'   => $this->salt,
                'length' => $this->length,
            ],
        ];
    }

    /**
     * Register the factory class.
     *
     * @author JimChen <1047004324@qq.com>
     */
    protected function registerFactory()
    {
        \Yii::$container->setSingleton('hashids.factory', function () {
            return new HashidsFactory();
        });
    }

    /**
     * Register the manager class.
     *
     * @author JimChen <1047004324@qq.com>
     */
    protected function registerManager()
    {
        \Yii::$container->setSingleton('hashids.manager', function () {
            return new HashidsManager(
                $this->getConfig(),
                $this->getFactory()
            );
        });
    }

    /**
     * Register the bindings.
     *
     * @author JimChen <1047004324@qq.com>
     */
    protected function registerBindings()
    {
        \Yii::$container->set('hashids.connection', function () {
            $manager = $this->getManager();

            return $manager->connection();
        });
    }

    /**
     * Get a configuration.
     *
     * @author JimChen <1047004324@qq.com>
     *
     * @return array
     */
    public function getConfig()
    {
        return [
            'hashids' => [
                'default'     => $this->default,
                'connections' => $this->connections,
            ],
        ];
    }

    /**
     * Get factory class.
     *
     * @author JimChen <1047004324@qq.com>
     *
     * @return HashidsFactory|object
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function getFactory()
    {
        return \Yii::$container->get('hashids.factory');
    }

    /**
     * Get the manager class.
     *
     * @author JimChen <1047004324@qq.com>
     *
     * @return HashidsManager|object
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function getManager()
    {
        return \Yii::$container->get('hashids.manager');
    }

    /**
     * Get the connection.
     *
     * @author JimChen <1047004324@qq.com>
     *
     * @return object
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function getConnection()
    {
        return \Yii::$container->get('hashids.connection');
    }

    /**
     * Call static methods.
     *
     * @author JimChen <1047004324@qq.com>
     *
     * @param $method
     * @param $arguments
     *
     * @return mixed
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function __call($method, $arguments)
    {
        return call_user_func_array([$this->getManager(), $method], $arguments);
    }
}

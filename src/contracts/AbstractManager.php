<?php

/*
 * This file is part of the jimchen/hashids.
 *
 * (c) JimChen <18219111672@163.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace jimchen\hashids\contracts;

use Closure;
use yii\base\InvalidArgumentException;
use yii\helpers\ArrayHelper;

abstract class AbstractManager implements ManagerInterface
{
    /**
     * The config instance.
     *
     * @var array
     */
    protected $config;

    /**
     * The active connection instances.
     *
     * @var object[]
     */
    protected $connections = [];

    /**
     * The custom connection resolvers.
     *
     * @var callable[]
     */
    protected $extensions = [];

    /**
     * Create a new manager instance.
     *
     * @author JimChen <1047004324@qq.com>
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Get a connection instance.
     *
     * @author JimChen <1047004324@qq.com>
     *
     * @param string|null $name
     *
     * @return object
     */
    public function connection($name = null)
    {
        $name = $name ?: $this->getDefaultConnection();

        if (!isset($this->connections[$name])) {
            $this->connections[$name] = $this->makeConnection($name);
        }

        return $this->connections[$name];
    }

    /**
     * Reconnect to the given connection.
     *
     * @author JimChen <1047004324@qq.com>
     *
     * @param string|null $name
     *
     * @return object
     */
    public function reconnect($name = null)
    {
        $name = $name ?: $this->getDefaultConnection();

        $this->disconnect($name);

        return $this->connection($name);
    }

    /**
     * Disconnect from the given connection.
     *
     * @author JimChen <1047004324@qq.com>
     *
     * @param string|null $name
     */
    public function disconnect($name = null)
    {
        $name = $name ?: $this->getDefaultConnection();

        unset($this->connections[$name]);
    }

    /**
     * Create the connection instance.
     *
     * @author JimChen <1047004324@qq.com>
     *
     * @param array $config
     *
     * @return object
     */
    abstract protected function createConnection(array $config);

    /**
     * Make the connection instance.
     *
     * @author JimChen <1047004324@qq.com>
     *
     * @param $name
     *
     * @return object
     */
    protected function makeConnection($name)
    {
        $config = $this->getConnectionConfig($name);

        if (isset($this->extensions[$name])) {
            return $this->extensions[$name]($config);
        }

        if ($driver = ArrayHelper::getValue($config, 'driver')) {
            if (isset($this->extensions[$driver])) {
                return $this->extensions[$driver]($config);
            }
        }

        return $this->createConnection($config);
    }

    /**
     * Get the configuration name.
     *
     * @author JimChen <1047004324@qq.com>
     *
     * @return string
     */
    abstract protected function getConfigName();

    /**
     * Get the configuration for a connection.
     *
     * @author JimChen <1047004324@qq.com>
     *
     * @param string|null $name
     *
     * @return array
     */
    public function getConnectionConfig($name = null)
    {
        $name = $name ?: $this->getDefaultConnection();

        $connections = ArrayHelper::getValue($this->config, $this->getConfigName().'.connections');
        if (!is_array($config = ArrayHelper::getValue($connections, $name)) && !$config) {
            throw new InvalidArgumentException("Connection [$name] not configured.");
        }

        $config['name'] = $name;

        return $config;
    }

    /**
     * Get the default connection name.
     *
     * @author JimChen <1047004324@qq.com>
     *
     * @return string
     */
    public function getDefaultConnection()
    {
        return ArrayHelper::getValue($this->config, $this->getConfigName().'.default');
    }

    /**
     * Set the default connection name.
     *
     * @author JimChen <1047004324@qq.com>
     *
     * @param string $name
     */
    public function setDefaultConnection($name)
    {
        ArrayHelper::setValue($this->config, $this->getConfigName().'.default', $name);
    }

    /**
     * Register an extension connection resolver.
     *
     * @author JimChen <1047004324@qq.com>
     *
     * @param string   $name
     * @param callable $resolver
     */
    public function extend($name, callable $resolver)
    {
        if ($resolver instanceof Closure) {
            $this->extensions[$name] = $resolver->bindTo($this, $this);
        } else {
            $this->extensions[$name] = $resolver;
        }
    }

    /**
     * Return all of the created connections.
     *
     * @author JimChen <1047004324@qq.com>
     *
     * @return object[]
     */
    public function getConnections()
    {
        return $this->connections;
    }

    /**
     * Get the config instance.
     *
     * @author JimChen <1047004324@qq.com>
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Dynamically pass methods to the default connection.
     *
     * @author JimChen <1047004324@qq.com>
     *
     * @param $method
     * @param $arguments
     *
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return call_user_func_array([$this->connection(), $method], $arguments);
    }
}

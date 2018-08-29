<?php

/*
 * This file is part of the jimchen/hashids.
 *
 * (c) JimChen <18219111672@163.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace jimchen\hashids\contracts;

interface ManagerInterface
{
    /**
     * Get a connection instance.
     *
     * @param string|null $name
     *
     * @return object
     */
    public function connection($name = null);

    /**
     * Reconnect to the given connection.
     *
     * @param string|null $name
     *
     * @return object
     */
    public function reconnect($name = null);

    /**
     * Disconnect from the given connection.
     *
     * @param string|null $name
     */
    public function disconnect($name = null);

    /**
     * Get the configuration for a connection.
     *
     * @param string|null $name
     *
     * @return array
     */
    public function getConnectionConfig($name = null);

    /**
     * Get the default connection name.
     *
     * @return string
     */
    public function getDefaultConnection();

    /**
     * Set the default connection name.
     *
     * @param string $name
     */
    public function setDefaultConnection($name);

    /**
     * Register an extension connection resolver.
     *
     * @param string   $name
     * @param callable $resolver
     */
    public function extend($name, callable $resolver);

    /**
     * Return all of the created connections.
     *
     * @return object[]
     */
    public function getConnections();
}

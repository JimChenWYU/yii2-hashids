<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 8/29/2018
 * Time: 1:30 PM
 */

namespace tests\contracts;

use jimchen\hashids\contracts\AbstractManager;
use tests\AbstractTestCase;

class AbstractManagerTest extends AbstractTestCase
{
    public function testGetConfig()
    {
        $manager = new Manager($this->getConfig());
        $this->assertSame($this->getConfig(), $manager->getConfig());
    }

    public function testGetDefaultConnection()
    {
        $manager = new Manager($this->getConfig());
        $this->assertSame('main', $manager->getDefaultConnection());
    }

    public function testSetDefaultConnection()
    {
        $manager = new Manager($this->getConfig());
        $manager->setDefaultConnection('alternative');
        $this->assertSame('alternative', $manager->getDefaultConnection());
    }

    public function testGetConnectionsConfig()
    {
        $manager = new Manager($this->getConfig());

        $this->assertSame([
            'salt'   => 'your-main-salt',
            'length' => 16,
            'name'   => 'main'
        ], $manager->getConnectionConfig());

        $this->assertSame([
            'salt'   => 'your-alternative-salt',
            'length' => 16,
            'name'   => 'alternative'
        ], $manager->getConnectionConfig('alternative'));
    }

    /**
     * @expectedException \yii\base\InvalidArgumentException
     * @expectedExceptionMessage Connection [foo] not configured.
     */
    public function testGetConnectionConfigThrowException()
    {
        $manager = new Manager($this->getConfig());
        $manager->getConnectionConfig('foo');
    }

    public function testConnection()
    {
        $manager = new Manager($this->getConfig());
        $this->assertInstanceOf(\stdClass::class, $manager->connection());
    }

    public function testGetConnections()
    {
        $manager = new Manager($this->getConfig());
        $manager->connection();
        $this->assertCount(1, $manager->getConnections());

        $manager = new Manager($this->getConfig());
        $manager->connection('main');
        $manager->connection('main');
        $this->assertCount(1, $manager->getConnections());

        $manager = new Manager($this->getConfig());
        $manager->connection('main');
        $manager->connection('alternative');
        $this->assertCount(2, $manager->getConnections());
    }

    public function testReconnect()
    {
        $manager = new Manager($this->getConfig());
        $originConnection = $manager->connection();
        $reconnection = $manager->reconnect();
        $this->assertCount(1, $manager->getConnections());
        $this->assertInstanceOf(\stdClass::class, $reconnection);
        $this->assertNotSame($originConnection, $reconnection);
    }

    public function testDisconnection()
    {
        $manager = new Manager($this->getConfig());
        $manager->connection();
        $manager->disconnect();
        $this->assertCount(0, $manager->getConnections());

        $manager->connection();
        $manager->disconnect('alternative');
        $this->assertCount(1, $manager->getConnections());
    }

    public function testExtendWithClosureArgument()
    {
        $manager = new Manager($this->getConfig());

        $manager->extend('main', function () {
            return new \DateTime();
        });

        $this->assertInstanceOf(\DateTime::class, $manager->connection());
    }

    public function testExtendWithArrayArgument()
    {
        $manager = new Manager($this->getConfig());

        $manager->extend('main', [$this, 'alternative']);

        $this->assertInstanceOf(\DateTime::class, $manager->connection());
    }

    public function alternative()
    {
        return new \DateTime();
    }

    public function testExtendWithDriver()
    {
        $manager = new Manager($this->getConfig());

        $manager->extend('main', function () {
            return new \DateTime();
        });

        $this->assertInstanceOf(\DateTime::class, $manager->connection('test-extend'));
    }

    public function testMagicCall()
    {
        $manager = new Manager($this->getConfig());

        $manager->extend('main', function () {
            return new \DateTime();
        });

        $this->assertFalse(method_exists($manager, 'getTimezone'));
        $this->assertInstanceOf(\DateTimeZone::class, $manager->getTimezone());
    }
    
    protected function getConfig()
    {
        return [
            'hashids' => [
                'default'     => 'main',
                'connections' => [
                    'main' => [
                        'salt'   => 'your-main-salt',
                        'length' => 16,
                    ],

                    'alternative' => [
                        'salt'   => 'your-alternative-salt',
                        'length' => 16,
                    ],

                    'test-extend' => [
                        'driver' => 'main'
                    ]
                ],
            ],
        ];
    }
}

class Manager extends AbstractManager
{

    /**
     * Create the connection instance.
     *
     * @author JimChen <1047004324@qq.com>
     *
     * @param array $config
     *
     * @return object
     */
    protected function createConnection(array $config)
    {
        return new \stdClass();
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
}
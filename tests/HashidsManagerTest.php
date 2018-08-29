<?php

/*
 * This file is part of the jimchen/hashids.
 *
 * (c) JimChen <18219111672@163.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace tests;

use Hashids\Hashids;
use jimchen\hashids\HashidsFactory;
use jimchen\hashids\HashidsManager;

class HashidsManagerTest extends AbstractTestCase
{
    public function testCreateConnection()
    {
        $config = $this->getConfig();
        $manager = $this->getManager($config);
        $this->assertSame([], $manager->getConnections());

        $return = $manager->connection();
        $this->assertInstanceOf(Hashids::class, $return);
    }

    public function testGetFactory()
    {
        $config = $this->getConfig();
        $manager = $this->getManager($config);

        $this->assertInstanceOf(HashidsFactory::class, $manager->getFactory());
    }

    protected function getManager(array $config)
    {
        /**
         * @var HashidsFactory|\PHPUnit_Framework_MockObject_MockObject
         */
        $factory = $this->getMock(HashidsFactory::class);
        $factory->method('make')->willReturn($this->getMock(Hashids::class));
        $manager = new HashidsManager($config, $factory);
        $config['name'] = 'hashids';

        return $manager;
    }

    protected function getConfig()
    {
        return [
            'hashids' => [
                'default' => 'main',
                'connections' => [
                    'main' => [
                        'salt' => 'your-salt-string',
                        'length' => 'your-length-integer',
                        'alphabet' => 'your-alphabet-string',
                    ],
                ],
            ],
        ];
    }
}

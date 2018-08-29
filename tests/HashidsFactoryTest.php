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

class HashidsFactoryTest extends AbstractTestCase
{
    public function testMakeStandard()
    {
        $factory = $this->getHashidsFactory();
        $return = $factory->make([
            'salt' => 'your-salt-string',
            'length' => 'your-length-integer',
            'alphabet' => 'your-alphabet-string',
        ]);

        $this->assertInstanceOf(Hashids::class, $return);
    }

    protected function getHashidsFactory()
    {
        return new HashidsFactory();
    }
}

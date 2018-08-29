<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 8/29/2018
 * Time: 2:30 PM
 */

namespace tests;

use Hashids\Hashids;
use jimchen\hashids\HashidsComponent;
use jimchen\hashids\HashidsFactory;
use jimchen\hashids\HashidsManager;
use Yii;

class HashidsComponentTest extends AbstractTestCase
{
    /**
     * @var HashidsComponent
     */
    protected $hashids;

    protected function setUp()
    {
        $this->hashids = \Yii::$app->hashids;
    }

    /**
     * @expectedException \yii\base\InvalidArgumentException
     * @expectedExceptionMessage HashidsComponent require salt value.
     */
    public function testGetHashidsComponentWithoutSaltThrowException()
    {
        Yii::createObject(HashidsComponent::class, [
            'salt' => null
        ]);
    }

    public function testHasRegisterFactory()
    {
        $this->assertInstanceOf(HashidsFactory::class, Yii::$container->get('hashids.factory'));
    }

    public function testHasRegisterManager()
    {
        $this->assertInstanceOf(HashidsManager::class, Yii::$container->get('hashids.manager'));
    }

    public function testHasRegisterBinding()
    {
        $this->assertInstanceOf(Hashids::class, Yii::$container->get('hashids.connection'));
    }

    public function testGetFactory()
    {
        $this->assertSame(Yii::$container->get('hashids.factory'), $this->hashids->getFactory());
    }

    public function testGetManager()
    {
        $this->assertSame(Yii::$container->get('hashids.manager'), $this->hashids->getManager());
    }

    public function testGetConnection()
    {
        $this->assertSame(Yii::$container->get('hashids.connection'), $this->hashids->getConnection());
    }

    public function testMagicCall()
    {
        $this->assertFalse(method_exists($this->hashids, 'encode'));
        $this->assertEquals((int)$this->hashids->length, strlen($this->hashids->encode(1234)));
    }
}
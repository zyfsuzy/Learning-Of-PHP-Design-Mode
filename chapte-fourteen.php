<?php 
/*
1、模式定义
注册模式（Registry）也叫做注册树模式，注册器模式。注册模式为应用中经常使用的对象创建一个中央存储器来存放这些对象 —— 通常通过一个只包含静态方法的抽象类来实现（或者通过单例模式）。
*/
namespace DesignPatterns\Structural\Registry;

/**
 * class Registry
 */
abstract class Registry
{
    const LOGGER = 'logger';

    /**
     * @var array
     */
    protected static $storedValues = array();
    private   static $allowedKeys = ['logger'];
    /**
     * sets a value
     *
     * @param string $key
     * @param mixed  $value
     *
     * @static
     * @return void
     */
    public static function set($key, $value)
    {
        if (!in_array($key, self::$allowedKeys)) {
            throw new \InvalidArgumentException('Invalid key given');
        }
        self::$storedValues[$key] = $value;
        
    }

    /**
     * gets a value from the registry
     *
     * @param string $key
     *
     * @static
     * @return mixed
     */
    public static function get($key)
    {
        return self::$storedValues[$key];
    }

    // typically there would be methods to check if a key has already been registered and so on ...
}
//for test
namespace DesignPatterns\Structural\Registry\Tests;

use DesignPatterns\Structural\Registry\Registry;

class RegistryTest extends \PHPUnit_Framework_TestCase
{

    public function testSetAndGetLogger()
    {
        Registry::set(Registry::LOGGER, new \StdClass());

        $logger = Registry::get(Registry::LOGGER);
        $this->assertInstanceOf('StdClass', $logger);
    }
}

//总结：目标是将经常使用的类的对象存放。

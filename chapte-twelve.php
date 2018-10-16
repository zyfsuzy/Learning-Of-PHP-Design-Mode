<?php 
// php design mode - fluent interface
/*
1、模式定义
在软件工程中，流接口（Fluent Interface）是指实现一种面向对象的、能提高代码可读性的 API 的方法，其目的就是可以编写具有自然语言一样可读性的代码，我们对这种代码编写方式还有一个通俗的称呼 —— 方法链。

Laravel 中流接口模式有着广泛使用，比如查询构建器，邮件等等。
*/


namespace DesignPatterns\Structural\FluentInterface;

/**
 * SQL 类
 */
class Sql
{
    /**
     * @var array
     */
    protected $fields = array();

    /**
     * @var array
     */
    protected $from = array();

    /**
     * @var array
     */
    protected $where = array();

    /**
     * 添加 select 字段
     *
     * @param array $fields
     *
     * @return SQL
     */
    public function select(array $fields = array())
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * 添加 FROM 子句
     *
     * @param string $table
     * @param string $alias
     *
     * @return SQL
     */
    public function from($table, $alias)
    {
        $this->from[] = $table . ' AS ' . $alias;

        return $this;
    }

    /**
     * 添加 WHERE 条件
     *
     * @param string $condition
     *
     * @return SQL
     */
    public function where($condition)
    {
        $this->where[] = $condition;

        return $this;
    }

    /**
     * 生成查询语句
     *
     * @return string
     */
    public function getQuery()
    {
        return 'SELECT ' . implode(',', $this->fields)
                . ' FROM ' . implode(',', $this->from)
                . ' WHERE ' . implode(' AND ', $this->where);
    }
}



namespace DesignPatterns\Structural\FluentInterface\Tests;

use DesignPatterns\Structural\FluentInterface\Sql;

/**
 * FluentInterfaceTest 测试流接口SQL
 */
class FluentInterfaceTest extends \PHPUnit_Framework_TestCase
{

    public function testBuildSQL()
    {
        $instance = new Sql();
        $query = $instance->select(array('foo', 'bar'))
                ->from('foobar', 'f')
                ->where('f.bar = ?')
                ->getQuery();

        $this->assertEquals('SELECT foo,bar FROM foobar AS f WHERE f.bar = ?', $query);
    }
}

/*
流接口模式如同工厂作业一样，对产品各个部件进行组装，最后生成完整的产品,调用类的各个接口，每一个接口负责语句的一部分，最后得到完整的语句结构，提供方便的表达式生成类。
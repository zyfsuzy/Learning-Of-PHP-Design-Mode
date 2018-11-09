# PHP--DesignPatterns

Abstract Factory
====
Purpose
-----
为一系列相互关联类创建对象，接口或者抽象类只关心如何将互相关联的类进行抽象组合,并不关心如何进行怎么去创建。而是让工厂去处理对象创建工作。</br>

Code
---
```Parser.php.php```

```php
<?php

namespace DesignPatterns\Creational\AbstractFactory;
interface Parser
{
    public function parse(string $input): array;
}

```

```
CsvParser.php
```
```php
<?php
namespace DesignPatterns\Creational\AbstractFactory;
class CsvParser implements Parser
{
    const OPTION_CONTAINS_HEADER = true;
    const OPTION_CONTAINS_NO_HEADER = false;
    /**
     * @var bool
     */
    private $skipHeaderLine;
    public function __construct(bool $skipHeaderLine)
    {
        $this->skipHeaderLine = $skipHeaderLine;
    }
    public function parse(string $input): array
    {
        $headerWasParsed = false;
        $parsedLines = [];
        foreach (explode(PHP_EOL, $input) as $line) {
            if (!$headerWasParsed && $this->skipHeaderLine === self::OPTION_CONTAINS_HEADER) {
                continue;
            }
            $parsedLines[] = str_getcsv($line);
        }
        return $parsedLines;
    }
}
 
```

```
JsonParser.php
```

```php 
<?php
namespace DesignPatterns\Creational\AbstractFactory;
class JsonParser implements Parser
{
    public function parse(string $input): array
    {
        return json_decode($input, true);
    }
}
```


```
ParseFactory.php
```
```php
<?php
namespace DesignPatterns\Creational\AbstractFactory;
class ParserFactory
{
    public function createCsvParser(bool $skipHeaderLine): CsvParser
    {
        return new CsvParser($skipHeaderLine);
    }
    public function createJsonParser(): JsonParser
    {
        return new JsonParser();
    }
}
```

```
test.php
```
```php
<?php
namespace DesignPatterns\Creational\AbstractFactory\Tests;
use DesignPatterns\Creational\AbstractFactory\CsvParser;
use DesignPatterns\Creational\AbstractFactory\JsonParser;
use DesignPatterns\Creational\AbstractFactory\ParserFactory;
use PHPUnit\Framework\TestCase;
class AbstractFactoryTest extends TestCase
{
    public function testCanCreateCsvParser()
    {
        $factory = new ParserFactory();
        $parser = $factory->createCsvParser(CsvParser::OPTION_CONTAINS_HEADER);
        $this->assertInstanceOf(CsvParser::class, $parser);
    }
    public function testCanCreateJsonParser()
    {
        $factory = new ParserFactory();
        $parser = $factory->createJsonParser();
        $this->assertInstanceOf(JsonParser::class, $parser);
    }
}
```

抽象工厂模式就是我们的抽象工厂约定了可以生产的产品规格，生成产品交给工厂。




FactoryMethod
===
Purpose
---
这种设计模式更好符合面向对象的依赖注入原则，依赖注入（Dependency Injection）是控制反转（Inversion of Control）的一种实现方式，</br>
当调用者需要被调用者的协助时，在传统的程序设计过程中，通常由调用者来创建被调用者的实例，但在这里，创建被调用者的工作不再由调用者来完成，而是将被调用者的创建移到调用者的外部，从而反转被调用者的创建，消除了调用者对被调用者创建的控制，因此称为控制反转。

这意味着工厂方法依赖的抽象而不是具体的实体类，这比其他设计模式更具优势。

```
Logger.php
```
```php
<?php
namespace DesignPatterns\Creational\FactoryMethod;
interface Logger
{
    public function log(string $message);
}
```

```
FileLogger.php
```
```php
<?php
namespace DesignPatterns\Creational\FactoryMethod;
class FileLogger implements Logger
{
    /**
     * @var string
     */
    private $filePath;
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }
    public function log(string $message)
    {
        file_put_contents($this->filePath, $message . PHP_EOL, FILE_APPEND);
    }
}
```
该类继承接口并实现接口方法。

```
LoggerFactory.php

```
```php
<?php
namespace DesignPatterns\Creational\FactoryMethod;
interface LoggerFactory
{
    public function createLogger(): Logger;
}
```
定义工厂的抽象接口，上面就说明了工厂方法依赖的是抽象，并不是具体的实体类。

```
FileLoggerFactory.php
```
```php
<?php
namespace DesignPatterns\Creational\FactoryMethod;
class FileLoggerFactory implements LoggerFactory
{
    /**
     * @var string
     */
    private $filePath;
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }
    public function createLogger(): Logger
    {
        return new FileLogger($this->filePath);
    }
}
```
工厂类继承工厂抽象接口，用于创建不同log类对象,由于每一种log类对应日志的存放顺序不一样，构造方法指定日志的存放路径。

```
StdoutLogger.php
```
```php
<?php
namespace DesignPatterns\Creational\FactoryMethod;
class StdoutLogger implements Logger
{
    public function log(string $message)
    {
        echo $message;
    }
}
```

```
StdoutLoggerFactory.php
```

```PHP
<?php
namespace DesignPatterns\Creational\FactoryMethod;
class StdoutLoggerFactory implements LoggerFactory
{
    public function createLogger(): Logger
    {
        return new StdoutLogger();
    }
}
```
定义另一种工厂类，该工厂与上一个工厂的区别在于用于创建的对象类型的类型不同。既然都是工厂那都必须继承```LoggerFactory```接口。

工厂方法之所以都实现依赖注入面向对象原则，因为几乎有每一个实体类都依赖的抽象而不是具体的实体类。真正实现```SOLID```原则种的D。


Strategy
===
Purpose
---
区分不同策略，并能快速选择一种策略模式。</br>

Code
---

```ComparatorInterface.php```

```php
<?php
namespace DesignPatterns\Behavioral\Strategy;
interface ComparatorInterface
{
    /**
     * @param mixed $a
     * @param mixed $b
     *
     * @return int
     */
    public function compare($a, $b): int;
}
```
约定策略的接口

```IdComparator.php```
```php
<?php
namespace DesignPatterns\Behavioral\Strategy;
class IdComparator implements ComparatorInterface
{
    /**
     * @param mixed $a
     * @param mixed $b
     *
     * @return int
     */
    public function compare($a, $b): int
    {
        return $a['id'] <=> $b['id'];
    }
}
```
```DateComparator.php```
```
<?php
namespace DesignPatterns\Behavioral\Strategy;
class DateComparator implements ComparatorInterface
{
    /**
     * @param mixed $a
     * @param mixed $b
     *
     * @return int
     */
    public function compare($a, $b): int
    {
        $aDate = new \DateTime($a['date']);
        $bDate = new \DateTime($b['date']);
        return $aDate <=> $bDate;
    }
}
```

```context.php```
```php
<?php
namespace DesignPatterns\Behavioral\Strategy;
class Context
{
    /**
     * @var ComparatorInterface
     */
    private $comparator;
    public function __construct(ComparatorInterface $comparator)
    {
        $this->comparator = $comparator;
    }
    public function executeStrategy(array $elements) : array
    {
        uasort($elements, [$this->comparator, 'compare']);
        return $elements;
    }
}
```

创建一个类对象时指定一种策略类对象，每一种策略对应处理方法内部逻辑调用者并不需要关注。




State
===
Purpose
---
很明确的指出了对象状态改变的情况。

```state.php```
```php
<?php
namespace DesignPatterns\Behavioral\State;
interface State
{
    public function proceedToNext(OrderContext $context);
    public function toString(): string;
}
```
指明了各个状态需要需要的接口。


```createstate.php```
```php
<?php
namespace DesignPatterns\Behavioral\State;
class StateCreated implements State
{
    public function proceedToNext(OrderContext $context)
    {
        $context->setState(new StateShipped());
    }
    public function toString(): string
    {
        return 'created';
    }
}
```
初始化状态类，并为对象指定一个状态。

```ship.php```
```php
<?php
namespace DesignPatterns\Behavioral\State;
class StateShipped implements State
{
    public function proceedToNext(OrderContext $context)
    {
        $context->setState(new StateDone());
    }
    public function toString(): string
    {
        return 'shipped';
    }
}
```
中间状态

```done.php```
```php
<?php
namespace DesignPatterns\Behavioral\State;
class StateDone implements State
{
    public function proceedToNext(OrderContext $context)
    {
        // there is nothing more to do
    }
    public function toString(): string
    {
        return 'done';
    }
}
```


```context.php```
```php
<?php
namespace DesignPatterns\Behavioral\State;
class OrderContext
{
    /**
     * @var State
     */
    private $state;
    public static function create(): OrderContext
    {
        $order = new self();
        $order->state = new StateCreated();
        return $order;
    }
    public function setState(State $state)
    {
        $this->state = $state;
    }
    public function proceedToNext()
    {
        $this->state->proceedToNext($this);
    }
    public function toString()
    {
        return $this->state->toString();
    }
}
```

创建类的初始化一个状态类，类的对象可以向下一个状态进行。

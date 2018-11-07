# Learning-Of-PHP-Design-Mode

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

<?php 
namespace DesignPatterns\Structural\DependencyInjection;
class DatabaseConfiguration
{
    /**
     * @var string
     */
    private $host;
    /**
     * @var int
     */
    private $port;
    /**
     * @var string
     */
    private $username;
    /**
     * @var string
     */
    private $password;
    public function __construct(string $host, int $port, string $username, string $password)
    {
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
    }
    public function getHost(): string
    {
        return $this->host;
    }
    public function getPort(): int
    {
        return $this->port;
    }
    public function getUsername(): string
    {
        return $this->username;
    }
    public function getPassword(): string
    {
        return $this->password;
    }
}

namespace DesignPatterns\Structural\DependencyInjection;
class DatabaseConnection
{
    /**
     * @var DatabaseConfiguration
     */
    private $configuration;
    /**
     * @param DatabaseConfiguration $config
     */
    public function __construct(DatabaseConfiguration $config)
    {
        $this->configuration = $config;
    }
    public function getDsn(): string
    {
        // this is just for the sake of demonstration, not a real DSN
        // notice that only the injected config is used here, so there is
        // a real separation of concerns here
        return sprintf(
            '%s:%s@%s:%d',
            $this->configuration->getUsername(),
            $this->configuration->getPassword(),
            $this->configuration->getHost(),
            $this->configuration->getPort()
        );
    }
}

//提供一种松耦合的设计结构
//依赖注入（Dependency Injection）是控制反转（Inversion of Control）的一种实现方式。

/*
当调用者需要被调用者的协助时，在传统的程序设计过程中，通常由调用者来创建被调用者的实例，但在这里，创建被调用者的工作不再由调用者来完成，而是将被调用者的创建移到调用者的外部，从而反转被调用者的创建，消除了调用者对被调用者创建的控制，因此称为控制反转。

要实现控制反转，通常的解决方案是将创建被调用者实例的工作交由 IoC 容器来完成，然后在调用者中注入被调用者（通过构造器/方法注入实现），这样我们就实现了调用者与被调用者的解耦，该过程被称为依赖注入。

依赖注入不是目的，它是一系列工具和手段，最终的目的是帮助我们开发出松散耦合（loosecoupled）、可维护、可测试的代码和程序。这条原则的做法是大家熟知的面向接口，或者说是面向抽象编程。*/
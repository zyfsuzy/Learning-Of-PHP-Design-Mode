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
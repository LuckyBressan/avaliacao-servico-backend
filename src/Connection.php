<?php

namespace App;

class Connection
{

    private string $host;
    private string $port;
    private string $dbname;
    private string $user;
    private string $password;

    public function __construct(
        string $host = "localhost",
        string $port = "5432",
        string $dbname = "avaliacao-servico",
        string $user = "postgres",
        string $password = "postgres"
    ) {
        $this->host = $host;
        $this->port = $port;
        $this->dbname = $dbname;
        $this->user = $user;
        $this->password = $password;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): string
    {
        return $this->port;
    }

    public function getDbname(): string
    {
        return $this->dbname;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setHost(string $host): void
    {
        $this->host = $host;
    }

    public function setPort(string $port): void
    {
        $this->port = $port;
    }

    public function setDbname(string $dbname): void
    {
        $this->dbname = $dbname;
    }

    public function setUser(string $user): void
    {
        $this->user = $user;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * Build and return a PostgreSQL DSN string for PDO.
     * Example: pgsql:host=localhost;port=5432;dbname=avaliacao-servico
     */
    public function getDsn(): string
    {
        $host = $this->host;
        $port = $this->port;
        $dbname = $this->dbname;

        return "pgsql:host={$host};port={$port};dbname={$dbname}";
    }

    /**
     * Return a connection string compatible with pg_connect
     * Example: host=localhost port=5432 dbname=avaliacao-servico user=postgres password=postgres
     */
    public function getStringConnection(): string
    {
        $host = $this->host;
        $port = $this->port;
        $dbname = $this->dbname;
        $user = $this->user;
        $password = $this->password;

        return "host={$host} port={$port} dbname={$dbname} user={$user} password={$password}";
    }

    // keep single getStringConnection implementation above

}
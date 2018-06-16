<?php
namespace Tunacan\Database;

class DataSource implements DataSourceInterface
{
    protected $type;
    protected $host;
    protected $port;
    protected $dbname;
    protected $user;
    protected $password;
    protected $option;

    public function __construct(
        string $type,
        string $host,
        string $port,
        string $dbname,
        string $user,
        string $password,
        array $option = []
    ) {
        $this->type = $type;
        $this->host = $host;
        $this->port = $port;
        $this->dbname = $dbname;
        $this->user = $user;
        $this->password = $password;
        $this->option = $option;
    }

    public function getConnection(): \PDO
    {
        try {
            $connection = new \PDO(
                sprintf(
                    '%s:host=%s;port=%s;dbname=%s',
                    $this->type,
                    $this->host,
                    $this->port,
                    $this->dbname
                ),
                $this->user,
                $this->password,
                $this->option
            );
            return $connection;
        } catch (\PDOException $e) {
            throw $e;
        }
    }
}

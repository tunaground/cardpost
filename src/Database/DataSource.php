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
    protected static $isTransaction;
    /**
     * @var \PDO $transactionInstance
     */
    protected static $transactionInstance;

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
        static::$isTransaction = false;
    }

    public function getConnection(): \PDO
    {
        try {
            if (static::$isTransaction === true) {
                if (is_null(static::$transactionInstance)) {
                    static::$transactionInstance = new \PDO(
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
                    static::$transactionInstance->beginTransaction();
                }
                return static::$transactionInstance;
            }
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

    public static function beginTransaction()
    {
        static::$isTransaction = true;
    }

    public static function commit()
    {
        static::$transactionInstance->commit();
        static::clear();
    }

    public static function rollBack()
    {
        static::$transactionInstance->rollBack();
        static::clear();
    }

    public static function clear()
    {
        static::$transactionInstance = null;
    }
}

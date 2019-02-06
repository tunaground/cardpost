<?php
namespace Tunacan\Database;

interface DataSourceInterface
{
    public function getConnection(): \PDO;
}

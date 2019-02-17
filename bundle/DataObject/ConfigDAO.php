<?php
namespace Tunacan\Bundle\DataObject;

use Tunacan\Database\DataSourceInterface;
use Tunacan\Util\LoaderInterface;

class ConfigDAO
{
    /** @var DataSourceInterface */
    private $dataSource;
    /**
     * @Inject("database.query.loader")
     * @var LoaderInterface
     */
    private $queryLoader;

    public function __construct(DataSourceInterface $dataSource)
    {
        $this->dataSource = $dataSource;
    }

    /**
     * @param string $key
     * @return string
     * @throws \Exception
     */
    public function getConfigByKey(string $key): string
    {
        $conn = $this->dataSource->getConnection();
        $stmt = $conn->prepare($this->queryLoader->load('getConfigByKey'));
        $stmt->bindValue(':key', $key, \PDO::PARAM_STR);
        $stmt->execute();
        $conn = null;
        $error = $stmt->errorInfo();
        if ($error[0] !== '00000') {
            $error = "[{$error[0]}][{$error[1]}] {$error[2]}";
            throw new \PDOException($error);
        }
        return $stmt->fetchColumn(0);
    }
}

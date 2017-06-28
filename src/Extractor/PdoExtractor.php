<?php

namespace BiSight\Etl\Extractor;

use BiSight\Etl\RowInterface;
use BiSight\Etl\Column;
use LinkORB\Component\DatabaseManager\DatabaseManager;
use PDO;
use RuntimeException;

class PdoExtractor implements ExtractorInterface
{
    private $pdo;
    private $sql;
    private $count;
    private $dbm;
    private $dbname;

    /**
     * @param string $dbname
     * @param string $sql
     */
    public function __construct($dbname, $sql)
    {
        $this->dbm = new DatabaseManager();
        $this->dbname = $dbname;
        $this->sql = $sql;
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->pdo = $this->dbm->getPdo($this->dbname);
        $this->stmt = $this->pdo->prepare($this->sql);
        $res = $this->stmt->execute();

        if (!$res) {
            $arr = $this->stmt->errorInfo();
            throw new RuntimeException(sprintf(
                "%s\n%s",
                $arr[2], $this->sql
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCount()
    {
        return $this->stmt->rowCount();
    }

    /**
     * {@inheritdoc}
     */
    public function getColumns()
    {
        $columns = array();
        $i = 0;
        while ($i < $this->stmt->columnCount()) {
            $meta = $this->stmt->getColumnMeta($i++);

            $columns[] = Column::createNew()
                ->setName((string) $meta['name'])
                ->setLength((string) $meta['len'])
                ->setType((string) $meta['native_type'])
                ->setPrecision((string) $meta['precision'])
            ;
        }

        return Column::reassignAliases($columns);
    }

    /**
     * {@inheritdoc}
     */
    public function extract(RowInterface $row)
    {
        $data = $this->stmt->fetch(PDO::FETCH_ASSOC);
        foreach ($data as $key => $value) {
            $row->set($key, $value);
        }
    }
}

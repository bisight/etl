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
    
    public function __construct($dbname, $sql)
    {
        $dbm = new DatabaseManager();
        $pdo = $dbm->getPdo($dbname);
        $this->pdo = $pdo;
        $this->sql = $sql;
    }
    
    public function init()
    {
        $this->stmt = $this->pdo->prepare($this->sql);
        $res = $this->stmt->execute();
        if (!$res) {
            $arr = $this->stmt->errorInfo();
            throw new RuntimeException($arr[2] . "\n" . $this->sql);
        }
    }

    public function getCount()
    {
        return $this->stmt->rowCount();
    }
    
    public function getColumns()
    {
        $columns = array();
        $i = 0;
        while ($i < $this->stmt->columnCount()) {
            $column = new Column();
            $meta = $this->stmt->getColumnMeta($i);
            //print_r($meta);
            $column->setName((string)$meta['name']);
            $column->setLength((string)$meta['len']);
            $column->setType((string)$meta['native_type']);
            $column->setPrecision((string)$meta['precision']);
            // optional: flags and tablename
            $columns[] = $column;
            $i++;
        }
        return $columns;
    }
    
    public function extract(RowInterface $row)
    {
        $data = $this->stmt->fetch(PDO::FETCH_ASSOC);
        foreach ($data as $key => $value) {
            $row->set($key, $value);
        }
    }
}

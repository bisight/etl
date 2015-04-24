<?php

namespace BiSight\Etl\Loader;

use BiSight\Etl\RowInterface;
use RuntimeException;

class PdoLoader implements LoaderInterface
{
    private $pdo;
    private $tablename;
    private $columns = array();
    
    public function __construct($pdo, $tablename)
    {
        $this->pdo = $pdo;
        $this->tablename = $tablename;
    }
    
    public function load(RowInterface $row)
    {
        $sql = "INSERT INTO " . $this->tablename;
        $sql .= " (";
        foreach ($this->columns as $column) {
            $sql .= $column->getName() . ", ";
        }
        $sql = rtrim($sql, ", ");
        $sql .= ") VALUES (";
        $values = array();
        
        foreach ($this->columns as $column) {
            $values[] = $row->get($column->getName());
            $sql .= "?, ";
        }
        $sql = rtrim($sql, ", ");
        $sql .= ");";
        
        //echo $sql;
        
        $this->stmt = $this->pdo->prepare($sql);
        $this->stmt->execute($values);
    }
    
    public function init($columns)
    {
        $this->columns = $columns;
        
        $sql = "DROP TABLE " . $this->tablename;
        $this->stmt = $this->pdo->prepare($sql);
        $this->stmt->execute();

        
        $sql = "CREATE TABLE " . $this->tablename;
        $sql .= "(";
        foreach ($columns as $column) {
            
            switch ($column->getType()) {
                case "LONG":
                    $type = "int(" . $column->getLength() . ")";
                    break;
                case "DOUBLE":
                    $type = "double";
                    break;
                case "VAR_STRING":
                    $type = "varchar(" . $column->getLength() . ")";
                    break;
                default:
                    throw new RuntimeException("Unsupported type: " . $column->getType());
            }
            $sql .= $column->getName() . " " . $type . ", ";
        }
        $sql = rtrim($sql, ", ");
        $sql .= ");";
        //echo $sql;
        
        $this->stmt = $this->pdo->prepare($sql);
        $this->stmt->execute();

    }
}

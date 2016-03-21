<?php

namespace BiSight\Etl\Loader;

use BiSight\Etl\RowInterface;
use LinkORB\Component\DatabaseManager\DatabaseManager;
use RuntimeException;

/**
 * @todo Replace echo to $output->writeln
 */
class PdoViewLoader implements LoaderInterface
{
    private $pdo;
    private $tablename;
    private $columns = array();
    private $sql;

    public function __construct($dbname, $tablename, $sql)
    {
        $dbm = new DatabaseManager();

        $this->pdo = $dbm->getPdo($dbname);
        $this->tablename = $tablename;
        $this->sql = $sql;
    }

    /**
     * {@inheritdoc}
     */
    public function getTablename()
    {
        return $this->tablename;
    }

    /**
     * {@inheritdoc}
     */
    public function load(RowInterface $row)
    {
        return;
    }

    /**
     * {@inheritdoc}
     *
     * @todo Refactor
     */
    public function init($columns)
    {
        $this->columns = $columns;

        $sql = sprintf('DROP TABLE IF EXISTS `%s`', $this->tablename);
        $this->stmt = $this->pdo->prepare($sql);
        $this->stmt->execute();

        // Get columns
        $sql = str_replace('**', '*', $this->sql);

        $this->stmt = $this->pdo->prepare($sql);
        if (!$this->stmt->execute()) {
            throw new \Exception(sprintf(
                "Query '%s' failed.",
                $sql
            ));
        }
        
        $columnNames = '';
        $c = 0;
        while ($c<$this->stmt->columnCount()) {
            $meta = $this->stmt->getColumnMeta($c);
            //print_r($meta);
            if ($columnNames!='') {
                $columnNames .= ', ';
            }
            $columnNames .= $meta['table'] . '.' . $meta['name'] . ' AS ' . $meta['table'] . '_' . $meta['name'];
            $c++;
        }
        //exit($columnNames);
        $sql = str_replace('**', $columnNames, $this->sql);
        
        // Create the view
        $sql = sprintf('CREATE OR REPLACE VIEW `%s` AS ' . $sql, $this->tablename);
        
        $sql .= ';';

        $this->stmt = $this->pdo->prepare($sql);
        if (!$this->stmt->execute()) {
            throw new \Exception(sprintf(
                "Query '%s' failed.",
                $sql
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function cleanup()
    {
        return;
    }
}

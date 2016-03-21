<?php

namespace BiSight\Etl\Loader;

use BiSight\Etl\RowInterface;
use LinkORB\Component\DatabaseManager\DatabaseManager;
use RuntimeException;
use SqlFormatter;

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
        $this->sql = SqlFormatter::format($sql, false);
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
                "Query failed:\n%s\nError: %s",
                $sql,
                $this->stmt->errorCode() . ': ' . $this->stmt->errorInfo()[2]
            ));
        }
        
        $columnNames = '';
        $c = 0;
        while ($c<$this->stmt->columnCount()) {
            $meta = $this->stmt->getColumnMeta($c);
            if ($columnNames!='') {
                $columnNames .= ', ';
            }
            $columnNames .= $meta['table'] . '.' . $meta['name'] . ' AS ' . $meta['table'] . '_' . $meta['name'];
            $c++;
        }
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

<?php

namespace BiSight\Etl\Loader;

use BiSight\Etl\RowInterface;
use LinkORB\Component\DatabaseManager\DatabaseManager;
use RuntimeException;

/**
 * @todo Replace echo to $output->writeln
 */
class PdoLoader implements LoaderInterface
{
    private $pdo;
    private $tablename;
    private $indexes;
    private $columns = array();
    private $skipdrop = false;

    public function __construct($dbname, $tablename, $indexes = null, $skipdrop = false)
    {
        $this->dbname = $dbname;
        $this->tablename = $tablename;
        $this->indexes = $indexes;

        if ($skipdrop == 'true') {
            $this->skipdrop = true;
        }
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
        $sql = 'INSERT INTO ' . $this->tablename;
        $sql .= ' (';
        foreach ($this->columns as $column) {
            $sql .= $column->getAlias() . ', ';
        }
        $sql = rtrim($sql, ', ');
        $sql .= ') VALUES (';
        $values = array();

        foreach ($this->columns as $column) {
            $values[] = $row->get($column->getAlias());
            $sql .= '?, ';
        }
        $sql = rtrim($sql, ', ');
        $sql .= ');';

        $this->stmt = $this->pdo->prepare($sql);
        if (!$this->stmt->execute($values)) {
            throw new \Exception(sprintf(
                "Query '%s' failed with values %s.",
                $sql,
                implode(', ', $values)
            ));
        }
    }

    /**
     * {@inheritdoc}
     *
     * @todo Refactor
     */
    public function init($columns)
    {
        $dbm = new DatabaseManager();
        $this->pdo = $dbm->getPdo($this->dbname);

        $this->columns = $columns;

        if (!$this->skipdrop) {
            $sql = sprintf('DROP TABLE `%s`', $this->tablename);
            $this->stmt = $this->pdo->prepare($sql);
            $this->stmt->execute();
        }

        $sql = sprintf('CREATE TABLE `%s`', $this->tablename);
        $sql .= '(';
        foreach ($columns as $column) {
            switch ($column->getType()) {
                case 'TINY':
                case 'TINYINT':
                    $type = 'tinyint';
                    break;

                case 'integer':
                case 'LONG':
                    $type = 'int';
                    break;
                case 'LONGLONG':
                    $type = 'bigint';
                    break;
                case 'numeric':
                case 'decimal':
                    if ($column->getLength() && $column->getPrecision()) {
                        $type = $column->getType() . '(' . $column->getLength() . ',' . $column->getPrecision() . ')';
                    } elseif ($column->getLength()) {
                        $type = $column->getType() . '(' . $column->getLength() . ')';
                    } else {
                        $type = $column->getType();
                    }
                    break;
                case 'NEWDECIMAL':
                    $type = 'decimal(13,3)';
                    break;
                case 'double':
                case 'DOUBLE':
                    $type = 'double';
                    break;

                case 'string':
                case 'STRING':
                case 'VAR_STRING':
                    $type = 'varchar(' . $column->getLength() . ')';
                    break;

                case 'text':
                    $type = 'text';
                    break;

                case 'datetime':
                case 'DATETIME':
                    $type = 'DATETIME';
                    break;
                case 'DATE':
                    $type = 'DATETIME';
                    break;

                case 'BLOB':
                    $type = 'text';
                    break;

                default:
                    throw new RuntimeException(sprintf(
                        'Unsupported type "%s"',
                        $column->getType()
                    ));
            }
            $sql .= $column->getAlias() . ' ' . $type . ', ';
        }
        $sql = rtrim($sql, ', ');
        $sql .= ');';

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
        $indexes = $this->indexes;
        $indexes = str_replace("\n", ';', $indexes);
        $lines = explode(';', $indexes);

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line) {
                $part = explode(':', $line);
                $indexname = $part[0];
                if (count($part) != 2) {
                    throw new RuntimeException(sprintf(
                        'Failed parsing indexline: %s',
                        $line
                    ));
                }
                $columnnames = explode(',', $part[1]);

                $sql = 'ALTER TABLE ' . $this->tablename;
                $sql .= ' ADD INDEX ' . $indexname;
                $sql .= '(';

                foreach ($columnnames as $columnname) {
                    $sql .= $columnname . ', ';
                }

                $sql = rtrim($sql, ' ,');
                $sql .= ');';
                echo "\n" . $sql . "\n";

                $this->stmt = $this->pdo->prepare($sql);
                $res = $this->stmt->execute();
            }
        }
    }
}

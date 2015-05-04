<?php

namespace BiSight\Etl\Extractor;

use BiSight\Etl\RowInterface;
use BiSight\Etl\Column;
use PDO;
use RuntimeException;
use DateInterval;
use DateTime;

class DateExtractor implements ExtractorInterface
{
    private $start;
    private $end;
    private $day;
    private $interval;
    
    public function __construct($start, $end, $interval = 1)
    {
        $this->start = new DateTime($start);
        $this->end = new DateTime($end);
        
        $this->interval = $interval;
    }
    
    public function init()
    {
        $this->day = $this->start;
    }

    public function getCount()
    {
        $diff = $this->start->diff($this->end);
        return (int)$diff->days;
    }
    
    public function getColumns()
    {
        $columns = array();

        $column = new Column();
        $column->setName('date');
        $column->setLength(8);
        $column->setType('LONG');
        //$column->setPrecision();
        $columns[] = $column;

        return $columns;
    }
    
    public function extract(RowInterface $row)
    {
        $this->day->add(new DateInterval('P1D'));
        $row->set('date', (int)$this->day->format('Ymd'));
    }
}

<?php

namespace BiSight\Etl\Extractor;

use BiSight\Etl\RowInterface;
use BiSight\Etl\Column;
use DateInterval;
use DateTime;

class DateExtractor implements ExtractorInterface
{
    private $start;
    private $end;
    private $day;
    private $interval;

    /**
     * @param string  $start
     * @param string  $end
     * @param integer $interval
     */
    public function __construct($start, $end, $interval = 1)
    {
        $this->start = new DateTime($start);
        $this->end = new DateTime($end);

        $this->interval = $interval;
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->day = $this->start;
    }

    /**
     * {@inheritdoc}
     */
    public function getCount()
    {
        $diff = $this->start->diff($this->end);

        return (int) $diff->days;
    }

    /**
     * {@inheritdoc}
     */
    public function getColumns()
    {
        return Column::reassignAliases(array(
            Column::createNew()
                ->setType('long')
                ->setName('date')
                ->setLength(8)
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function extract(RowInterface $row)
    {
        $interval = sprintf('P%sD', $this->interval);

        $this->day->add(new DateInterval($interval));
        $row->set('date', (int) $this->day->format('Ymd'));
    }
}

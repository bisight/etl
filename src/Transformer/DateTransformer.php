<?php

namespace BiSight\Etl\Transformer;

use BiSight\Etl\Column;
use BiSight\Etl\RowInterface;
use DateTime;

class DateTransformer implements TransformerInterface
{
    private $dateColumnName;

    /**
     * @param string $dateColumnName
     */
    public function __construct($dateColumnName = 'date')
    {
        $this->dateColumnName = $dateColumnName;
    }

    /**
     * {@inheritdoc}
     */
    public function getColumns()
    {
        return Column::reassignAliases(array(
            Column::createNew()
                ->setName('year')
                ->setType('integer')
                ->setLength(4),
            Column::createNew()
                ->setName('month')
                ->setType('integer')
                ->setLength(2),
            Column::createNew()
                ->setName('weeknumber')
                ->setType('integer')
                ->setLength(3),
            Column::createNew()
                ->setName('yearmonth')
                ->setType('integer')
                ->setLength(6),
            Column::createNew()
                ->setName('quarter')
                ->setType('integer')
                ->setLength(1),
            Column::createNew()
                ->setName('yearquarter')
                ->setType('integer')
                ->setLength(5),
            Column::createNew()
                ->setName('yearquartermonth')
                ->setType('integer')
                ->setLength(7),
            Column::createNew()
                ->setName('weekday')
                ->setType('integer')
                ->setLength(1),
            Column::createNew()
                ->setName('weekdayname')
                ->setType('string')
                ->setLength(16),
            Column::createNew()
                ->setName('weekdayflag')
                ->setType('string')
                ->setLength(1)
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function transform(RowInterface $row)
    {
        $datestring = $row->get($this->dateColumnName);
        $date = new DateTime($datestring);

        $quarter = ceil($date->format('n') / 3);

        $weekday = 'Y';
        if (($date->format('N') == 6) || ($date->format('N') == 7)) {
            $weekday = 'N';
        }

        $row->set('year', (int) $date->format('Y'));
        $row->set('month', (int) $date->format('m'));
        $row->set('weekday', (int) $date->format('N'));
        $row->set('weekdayname', $date->format('l'));
        $row->set('weekdayflag', $weekday);
        $row->set('weeknumber', (int) $date->format('W'));
        $row->set('yearmonth', (int) $date->format('Ym'));
        $row->set('quarter', (int) $quarter);

        $row->set('yearquarter', sprintf(
            "%s%s",
            (int) $date->format('Y'),
            $quarter
        ));

        $row->set('yearquartermonth', sprintf(
            "%s%s%s",
            (int) $date->format('Y'),
            $quarter,
            $date->format('m')
        ));
    }
}

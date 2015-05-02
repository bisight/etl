<?php

namespace BiSight\Etl\Transformer;

use BiSight\Etl\Column;
use BiSight\Etl\RowInterface;
use DateTime;

class DateTransformer implements TransformerInterface
{
    public function __construct()
    {
    }
    
    public function getColumns()
    {
        $columns = array();
        
        $column = new Column();
        $column->setName('year');
        $column->setType('LONG');
        $column->setLength(11);
        $column->setPrecision('');
        $columns[] = $column;


        $column = new Column();
        $column->setName('month');
        $column->setType('LONG');
        $column->setLength(11);
        $column->setPrecision('');
        $columns[] = $column;

        $column = new Column();
        $column->setName('yearmonth');
        $column->setType('LONG');
        $column->setLength(11);
        $column->setPrecision('');
        $columns[] = $column;

        $column = new Column();
        $column->setName('quarter');
        $column->setType('LONG');
        $column->setLength(11);
        $column->setPrecision('');
        $columns[] = $column;
        
        $column = new Column();
        $column->setName('yearquarter');
        $column->setType('LONG');
        $column->setLength(11);
        $column->setPrecision('');
        $columns[] = $column;
        
        $column = new Column();
        $column->setName('yearquartermonth');
        $column->setType('LONG');
        $column->setLength(11);
        $column->setPrecision('');
        $columns[] = $column;

        $column = new Column();
        $column->setName('weekday');
        $column->setType('LONG');
        $column->setLength(11);
        $column->setPrecision('');
        $columns[] = $column;

        $column = new Column();
        $column->setName('weekdayname');
        $column->setType('VAR_STRING');
        $column->setLength(16);
        $column->setPrecision('');
        $columns[] = $column;
        
        $column = new Column();
        $column->setName('weekdayflag');
        $column->setType('VAR_STRING');
        $column->setLength(1);
        $column->setPrecision('');
        $columns[] = $column;
        
        return $columns;
    }
    
    public function transform(RowInterface $row)
    {
        $datestring = $row->get('date');
        $date = new DateTime($datestring);
        $quarter = ceil($date->format('n')/3);
        $weekday = 'Y';
        
        if (($date->format('N')==6) || ($date->format('N')==7)) {
            $weekday = 'N';
        }

        $row->set('year', (int)$date->format('Y'));
        $row->set('month', (int)$date->format('m'));
        $row->set('weekday', (int)$date->format('N'));
        $row->set('weekdayname', $date->format('l'));
        $row->set('weekdayflag', $weekday);
        $row->set('yearmonth', (int)$date->format('Ym'));
        $row->set('quarter', (int)$quarter);
        $row->set('yearquarter', (int)$date->format('Y') . $quarter);
        $row->set('yearquartermonth', (int)$date->format('Y') . $quarter . $date->format('m'));
    }
}

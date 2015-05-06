<?php

namespace BiSight\Etl\Transformer;

class ExpressionUtils
{
    public function safeDivide($a, $b)
    {
        if ($b == 0) {
            return null;
        }
        return $a / $b;
    }
    
    public function round($value, $precision = 0)
    {
        return round($value, $precision);
    }
    
    public function stamp2date($stamp)
    {
        return date('Ymd', $stamp);
    }
}

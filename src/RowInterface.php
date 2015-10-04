<?php

namespace BiSight\Etl;

interface RowInterface
{
    /**
     * @param string $key
     * @param mixed  $value
     * @return RowInterface
     */
    public function set($key, $value);

    /**
     * @param  string $key
     * @return mixed
     */
    public function get($key);

    /**
     * @return array
     */
    public function getArray();
}

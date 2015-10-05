<?php

namespace BiSight\Etl\Loader;

use BiSight\Etl\RowInterface;

interface LoaderInterface
{
    /**
     * @return string
     */
    public function getTablename();

    /**
     * Initialize environment.
     * For example, create table for load data to.
     *
     * @param array $columns
     */
    public function init($columns);

    /**
     * Loads data.
     *
     * @param RowInterface $row
     */
    public function load(RowInterface $row);

    /**
     * Clean environment after loading.
     */
    public function cleanup();
}

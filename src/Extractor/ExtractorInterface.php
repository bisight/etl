<?php

namespace BiSight\Etl\Extractor;

use BiSight\Etl\RowInterface;

interface ExtractorInterface
{
    /**
     * Initialize extractor.
     */
    public function init();

    /**
     * @return integer
     */
    public function getCount();

    /**
     * @return array
     */
    public function getColumns();

    /**
     * Extract data.
     *
     * @param RowInterface $row
     */
    public function extract(RowInterface $row);
}

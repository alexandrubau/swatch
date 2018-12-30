<?php

namespace Swatch\Collector;

/**
 * Interface CollectorInterface
 *
 * @author Alexandru Bau <alexandru.bau@gmail.com>
 */
interface CollectorInterface
{
    /**
     * Collects information and returns it.
     *
     * @return mixed
     */
    public function collect();
}
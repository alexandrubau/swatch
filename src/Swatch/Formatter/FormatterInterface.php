<?php

namespace Swatch\Formatter;

/**
 * Interface FormatterInterface
 *
 * @author Alexandru Bau <alexandru.bau@gmail.com>
 */
interface FormatterInterface
{
    /**
     * Formats information.
     *
     * @param array $record
     * @return mixed
     */
    public function format(array $record);
}
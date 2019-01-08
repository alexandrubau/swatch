<?php

namespace Swatch\Formatter;

use Swatch\Builder\ComponentInterface;

/**
 * Interface FormatterInterface
 *
 * @author Alexandru Bau <alexandru.bau@gmail.com>
 */
interface FormatterInterface extends ComponentInterface
{
    /**
     * Formats information.
     *
     * @param array $record
     * @return mixed
     */
    public function format(array $record);
}
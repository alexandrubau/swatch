<?php

namespace Swatch\Handler;

use Swatch\Formatter\FormatterInterface;

/**
 * Interface HandlerInterface
 *
 * @author Alexandru Bau <alexandru.bau@gmail.com>
 */
interface HandlerInterface
{
    /**
     * @return FormatterInterface
     */
    public function getFormatter(): FormatterInterface;

    /**
     * @param FormatterInterface $formatter
     * @return mixed
     */
    public function setFormatter(FormatterInterface $formatter);

    /**
     * Handles information gathered from collectors.
     *
     * @param mixed $record
     * @return mixed
     */
    public function handle($record);
}
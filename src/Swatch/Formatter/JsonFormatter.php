<?php

namespace Swatch\Formatter;

/**
 * Class JsonFormatter
 *
 * @author Alexandru Bau <alexandru.bau@gmail.com>
 */
class JsonFormatter implements FormatterInterface
{

    /**
     * @var int
     */
    protected $jsonOptions = 0;

    /**
     * @var int
     */
    protected $jsonDepth;

    /**
     * JsonFormatter constructor.
     *
     * @param bool $pretty
     * @param int $depth
     */
    public function __construct(bool $pretty = false, int $depth = 512)
    {
        if ($pretty) {

            $this->jsonOptions |= JSON_PRETTY_PRINT;
        }

        $this->jsonDepth = $depth;
    }

    /**
     * @inheritdoc
     */
    public function format(array $record): string
    {
        return json_encode($record, $this->jsonOptions, $this->jsonDepth);
    }
}
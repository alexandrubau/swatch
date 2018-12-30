<?php

namespace Swatch\Handler;

use Swatch\Formatter\FormatterInterface;
use Swatch\Formatter\JsonFormatter;

/**
 * Class StreamHandler
 *
 * @author Alexandru Bau <alexandru.bau@gmail.com>
 */
class StreamHandler implements HandlerInterface
{
    /**
     * @var FormatterInterface
     */
    protected $formatter;

    /**
     * @var resource
     */
    protected $stream;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var bool
     */
    private $created;

    /**
     * @var string
     */
    private $error;

    /**
     * StreamHandler constructor.
     *
     * @param resource|string|mixed $stream
     */
    public function __construct($stream)
    {
        if (is_resource($stream)) {

            $this->stream = $stream;

        } else {

            $this->url = $stream;
        }
    }

    /**
     * @inheritdoc
     */
    public function getFormatter(): FormatterInterface
    {
        return $this->formatter ?? $this->formatter = new JsonFormatter();
    }

    /**
     * @inheritdoc
     */
    public function setFormatter(FormatterInterface $formatter)
    {
        $this->formatter = $formatter;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function handle($record)
    {
        if (!is_resource($this->stream)) {

            $this->createDir();

            $this->handleError(function () {

                $this->stream = fopen($this->url, 'a');
            });

            if (!is_resource($this->stream)) {

                throw new \UnexpectedValueException(sprintf('The stream or file "%s" could not be opened: ' . $this->error, $this->url));
            }
        }

        fwrite($this->stream, (string)$record);
    }

    /**
     * Creates directory path for the stream url.
     */
    private function createDir(): void
    {
        if ($this->created) {

            return;
        }

        $dir = $this->getDir();

        if (is_null($dir)) {

            $this->created = true;

            return;
        }

        if (!is_dir($dir)) {

            $this->handleError(function () use ($dir) {

                mkdir($dir, 0777, true);
            });

            if (!is_dir($dir)) {

                throw new \UnexpectedValueException(sprintf('There is no existing directory at "%s" and its not buildable: ' . $this->error, $dir));
            }
        }

        $this->created = true;
    }

    /**
     * Retrieves the directory path from the stream url.
     *
     * @return string|null
     */
    private function getDir(): ?string
    {
        $pos = strpos($this->url, '://');

        if ($pos === false) {

            return dirname($this->url);
        }

        if ('file://' === substr($this->url, 0, 7)) {

            return dirname(substr($this->url, 7));
        }

        return null;
    }

    /**
     * Executes the supplied callback and stores last error, if any.
     *
     * @param callable $callback
     */
    private function handleError(callable $callback): void
    {
        $this->error = null;

        set_error_handler([$this, 'handleErrorCustom']);

        $callback();

        restore_error_handler();
    }

    /**
     * Custom error handler.
     *
     * @param int $errno
     * @param string $errstr
     */
    private function handleErrorCustom($errno, $errstr): void
    {
        $result = preg_replace('{^(fopen|mkdir)\(.*?\): }', '', $errstr);

        if (!is_string($result)) {

            return;
        }

        $this->error = $result;
    }
}
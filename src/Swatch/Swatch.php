<?php

namespace Swatch;

use Swatch\Collector\CollectorInterface;
use Swatch\Handler\HandlerInterface;

/**
 * Class Swatch
 *
 * @author Alexandru Bau <alexandru.bau@gmail.com>
 */
class Swatch
{
    const NAME = 'Swatch';
    const VERSION = 'v0.0.2';

    /**
     * @var CollectorInterface[]|array
     */
    protected $collectors;

    /**
     * @var HandlerInterface[]|array
     */
    protected $handlers;

    /**
     * @var \DateTimeZone|null
     */
    protected $timezone;

    /**
     * @var string
     */
    private $name;

    /**
     * Swatch constructor.
     *
     * @param string $name
     * @param array $collectors
     * @param array $handlers
     * @param \DateTimeZone|null
     */
    public function __construct(string $name, array $collectors = [], array $handlers = [], ?\DateTimeZone $timezone = null)
    {
        $this->name = $name;
        $this->collectors = $collectors;
        $this->handlers = $handlers;
        $this->timezone = $timezone ?? new \DateTimeZone(date_default_timezone_get() ?? 'UTC');
    }

    /**
     * @return CollectorInterface[]|array
     */
    public function getCollectors(): array
    {
        return $this->collectors;
    }

    /**
     * @param CollectorInterface[]|array $collectors
     * @return self
     */
    public function setCollectors(array $collectors): self
    {
        $this->collectors = $collectors;
        return $this;
    }

    /**
     * @param CollectorInterface $collector
     * @return Swatch
     */
    public function addCollector(CollectorInterface $collector): self
    {
        $this->collectors[] = $collector;
        return $this;
    }

    /**
     * @return HandlerInterface[]|array
     */
    public function getHandlers(): array
    {
        return $this->handlers;
    }

    /**
     * @param HandlerInterface[]|array $handlers
     * @return self
     */
    public function setHandlers(array $handlers): self
    {
        $this->handlers = $handlers;
        return $this;
    }

    /**
     * @param HandlerInterface $handler
     * @return Swatch
     */
    public function addHandler(HandlerInterface $handler): self
    {
        $this->collectors[] = $handler;
        return $this;
    }

    /**
     * Gathers information from all collectors, formats it
     * and sends it to the handlers.
     *
     * @throws \Exception
     */
    public function report(): void
    {
        $payload = $this->buildPayload();

        foreach ($this->collectors as $index => $collector) {

            $name = $collector->getName() ?? $index;

            $payload[$name] = $collector->collect();
        }

        foreach ($this->handlers as $handler) {

            $data = $handler->getFormatter()->format($payload);

            $result = $handler->handle($data);

            if ($result === false) {
                break;
            }
        }
    }

    /**
     * Creates payload containing default information.
     *
     * @return array
     *
     * @throws \Exception
     */
    protected function buildPayload(): array
    {
        $payload = [
            'name' => $this->name,
            'datetime' => new \DateTimeImmutable('now', $this->timezone)
        ];

        return $payload;
    }
}
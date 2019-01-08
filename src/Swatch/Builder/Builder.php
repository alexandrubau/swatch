<?php

namespace Swatch\Builder;

use Swatch\Collector\CollectorInterface;
use Swatch\Config;
use Swatch\Formatter\FormatterInterface;
use Swatch\Handler\HandlerInterface;
use Swatch\Swatch;

/**
 * Class Builder
 *
 * @author Alexandru Bau <alexandru.bau@gmail.com>
 */
class Builder
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * Builder constructor.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Builds an instance based on supplied configuration.
     *
     * @return Swatch
     *
     * @throws \ReflectionException
     */
    public function build(): Swatch
    {
        $config = $this->config->read();

        $collectors = $this->buildCollectors($config);

        $handlers = $this->buildHandlers($config);

        return new Swatch($config['name'], $collectors, $handlers);
    }

    /**
     * @param array $config
     * @return CollectorInterface[]|array
     *
     * @throws \ReflectionException
     */
    protected function buildCollectors(array $config): array
    {
        if (!array_key_exists('collectors', $config)) {

            return [];
        }

        $collectors = [];

        foreach ($config['collectors'] as $id => $definition) {

            $collector = $this->buildFromDefinition($definition);

            $collectors[$id] = $collector;

            $collector->setName($id);
        }

        return $collectors;
    }

    /**
     * @param array $config
     * @return HandlerInterface[]|array
     *
     * @throws \ReflectionException
     */
    protected function buildHandlers(array $config): array
    {
        if (!array_key_exists('handlers', $config)) {

            return [];
        }

        $formatters = [];

        foreach ($config['formatters'] as $id => $definition) {

            $formatters[$id] = $this->buildFromDefinition($definition);
        }

        $handlers = [];

        foreach ($config['handlers'] as $id => $definition) {

            $handler = $this->buildFromDefinition($definition);

            $handlers[$id] = $handler;

            if (!is_array($definition)) {

                continue;
            }

            if (!array_key_exists('formatter', $definition)) {

                continue;
            }

            if (!array_key_exists($definition['formatter'], $formatters)) {

                $error = sprintf('The formatter with id "%s" does not exist', $definition['formatter']);

                throw new \InvalidArgumentException($error);
            }

            $handler->setFormatter($formatters[$definition['formatter']]);
        }

        return $handlers;
    }

    /**
     * Builds an object instance using the supplied definition.
     *
     * @param string|array $definition
     * @return ComponentInterface|object
     *
     * @throws \ReflectionException
     */
    private function buildFromDefinition($definition): ComponentInterface
    {
        if (is_string($definition)) {

            return $this->buildClassFromPath($definition)->newInstance();
        }

        $name = $definition['class'];

        unset($definition['class']);

        $class = $this->buildClassFromPath($name);

        $args = $this->getConstructorArgs($class, $definition);

        return $class->newInstanceArgs($args);
    }

    /**
     * Builds a new object instance from the supplied class name.
     *
     * @param string $class
     * @return \ReflectionClass
     *
     * @throws \ReflectionException
     */
    private function buildClassFromPath(string $class): \ReflectionClass
    {
        if (!class_exists($class)) {

            $error = sprintf('Class "%s" does not exist', $class);

            throw new \InvalidArgumentException($error);
        }

        return new \ReflectionClass($class);
    }

    /**
     * Retrieves a list with constructor arguments or null if the constructor is missing.
     *
     * @param \ReflectionClass $class
     * @param array $definition
     * @return array
     *
     * @throws \ReflectionException
     */
    private function getConstructorArgs(\ReflectionClass $class, array $definition): array
    {
        $args = [];

        $constructor = $class->getConstructor();

        if (is_null($constructor)) {

            return $args;
        }

        foreach ($constructor->getParameters() as $param) {

            if (array_key_exists($param->getName(), $definition)) {

                $args[] = $definition[$param->getName()];

                continue;
            }

            if ($param->isDefaultValueAvailable()) {

                $args[] = $param->getDefaultValue();

                continue;
            }

            $error = sprintf('Missing value for constructor argument "%s" from class "%s"', $param->getName(), $class->getName());

            throw new \InvalidArgumentException($error);
        }

        return $args;
    }
}
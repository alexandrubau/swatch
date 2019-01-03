<?php

namespace Swatch;

use Swatch\Collector\CollectorInterface;
use Swatch\Handler\HandlerInterface;

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
     * Builds an Inspector instance based on supplied configuration.
     *
     * @return Inspector
     *
     * @throws \ReflectionException
     */
    public function build(): Inspector
    {
        $config = $this->config->read();

        $inspector = new Inspector($config['name']);

        if (array_key_exists('collectors', $config)) {

            $collectors = $this->buildFromList($config['collectors'], function ($id, $def, CollectorInterface $collector) {

                $collector->setName($id);
            });

            $inspector->setCollectors($collectors);
        }

        if (array_key_exists('formatters', $config)) {

            $formatters = $this->buildFromList($config['formatters']);
        }

        if (array_key_exists('handlers', $config)) {

            $formatters = $formatters ?? [];

            $handlers = $this->buildFromList($config['handlers'], function ($id, $def, HandlerInterface $handler) use ($formatters) {

                if (!is_array($def)) {

                    return;
                }

                if (!array_key_exists('formatter', $def)) {

                    return;
                }

                if (!array_key_exists($def['formatter'], $formatters)) {

                    throw new \UnexpectedValueException(sprintf('The formatter with id "%s" does not exist', $def['formatter']));
                }

                $handler->setFormatter($formatters[$def['formatter']]);
            });


            $inspector->setHandlers($handlers);
        }

        return $inspector;
    }

    /**
     * Builds object instances using the supplied list of definitions.
     *
     * @param array $definitions
     * @param callable $callback
     * @return array
     *
     * @throws \ReflectionException
     */
    private function buildFromList(array $definitions, callable $callback = null)
    {
        $list = [];

        foreach ($definitions as $id => $definition) {

            $list[$id] = $this->buildFromDefinition($definition);

            if (!is_callable($callback)) {

                continue;
            }

            $callback($id, $definition, $list[$id]);
        }

        return $list;
    }

    /**
     * Builds an object instance using the supplied definition.
     *
     * @param string|array $definition
     * @return mixed
     *
     * @throws \ReflectionException
     */
    private function buildFromDefinition($definition)
    {
        // Case 1: the definition contains only the string
        if (is_string($definition)) {

            return $this->buildClassFromPath($definition)->newInstance();
        }

        // Case 2: The definition contains an object with only the "class" property
        $name = $definition['class'];

        unset($definition['class']);

        if (empty($definition)) {

            return $this->buildClassFromPath($name)->newInstance();
        }

        // Case 3: The definition contains an object with multiple properties
        $class = $this->buildClassFromPath($name);

        $args = $this->getConstructorArgs($definition, $class);

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
    private function buildClassFromPath(string $class)
    {
        if (!class_exists($class)) {

            throw new \UnexpectedValueException(sprintf('Class "%s" does not exist', $class));
        }

        return new \ReflectionClass($class);
    }

    /**
     * Retrieves a list with constructor arguments or null if the constructor is missing.
     *
     * @param array $definition
     * @param \ReflectionClass $class
     * @return array|null
     * @throws \ReflectionException
     */
    private function getConstructorArgs(array $definition, \ReflectionClass $class): array
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

            throw new \UnexpectedValueException(sprintf('Missing value for constructor argument "%s" from class "%s"', $param->getName(), $class->getName()));
        }

        return $args;
    }
}
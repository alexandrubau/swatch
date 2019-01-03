<?php

namespace Swatch\Collector;

/**
 * Class PhpCollector
 *
 * Gathers information regarding PHP such as version, modules, etc.
 *
 * @author Alexandru Bau <alexandru.bau@gmail.com>
 */
class PhpCollector implements CollectorInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @inheritdoc
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function setName(?string $name)
    {
        $this->setName($name);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function collect()
    {
        return [
            'version' => PHP_VERSION,
            'version_major' => PHP_MAJOR_VERSION,
            'version_minor' => PHP_MINOR_VERSION,
            'version_release' => PHP_RELEASE_VERSION,
        ];
    }
}
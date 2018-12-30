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
     * @inheritdoc
     */
    public function collect()
    {
        return [
            'version' => phpversion()
        ];
    }
}
<?php

namespace Swatch\Collector;

use Swatch\Builder\ComponentInterface;

/**
 * Interface CollectorInterface
 *
 * @author Alexandru Bau <alexandru.bau@gmail.com>
 */
interface CollectorInterface extends ComponentInterface
{
    /**
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * @param string|null $name
     * @return mixed
     */
    public function setName(?string $name);

    /**
     * Collects information and returns it.
     *
     * @return mixed
     */
    public function collect();
}
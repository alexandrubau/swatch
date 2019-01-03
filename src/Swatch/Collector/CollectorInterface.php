<?php

namespace Swatch\Collector;

/**
 * Interface CollectorInterface
 *
 * @author Alexandru Bau <alexandru.bau@gmail.com>
 */
interface CollectorInterface
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
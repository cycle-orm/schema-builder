<?php

declare(strict_types=1);

namespace Cycle\Schema;

interface GeneratorInterface
{
    /**
     * Run generator over given registry.
     *
     */
    public function run(Registry $registry): Registry;
}

<?php

declare(strict_types=1);

namespace Garethnic\EloquentCipher\Mapping;

/**
 * Interface IndexMappingInterface
 * @package ParagonIE\EloquentCipherSweet\Mapping
 */
interface IndexMappingInterface
{
    /**
     * @param array $indexes
     */
    public function __invoke(array $indexes);
}

<?php

declare(strict_types=1);

namespace Garethnic\EloquentCipher\Mapping;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CompoundIndexMapping
 * @package ParagonIE\EloquentCipherSweet\Mapping
 */
class CompoundIndexMapping implements IndexMappingInterface
{
    private string $table;

    private string $index;

    private string|null|Model $target;

    private string $property;

    /**
     * BlindIndexMapping constructor.
     */
    public function __construct(
        string $table = '',
        string $index = '',
        Model $model = null,
        string $property = ''
    ) {
        $this->table = $table;
        $this->index = $index;
        $this->target = $model;
        $this->property = $property;
    }

    /**
     * @param array $indexes
     * @throws \TypeError
     */
    public function __invoke(array $indexes)
    {
        if (! isset($indexes[$this->table][$this->index]['value'])) {
            throw new \TypeError('Missing indexes on input array');
        }
        if (! ($this->target instanceof Model)) {
            throw new \TypeError('Target must be an Eloquent Model');
        }
        $this->target->{$this->property} = $indexes[$this->table][$this->index]['value'];
    }
}

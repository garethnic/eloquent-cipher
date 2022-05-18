<?php

declare(strict_types=1);

namespace Garethnic\EloquentCipher\Mapping;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BlindIndexMapping
 * @package ParagonIE\EloquentCipherSweet\Mapping
 */
class BlindIndexMapping implements IndexMappingInterface
{
    /**
     * @var string
     */
    private $table = '';

    /**
     * @var string
     */
    private $column = '';

    /**
     * @var string
     */
    private $index = '';

    /**
     * @var string
     */
    private $target = '';

    /**
     * @var string
     */
    private $property = '';

    /**
     * BlindIndexMapping constructor.
     *
     * @param string $table
     * @param string $column
     * @param string $index
     * @param Model|null $model
     * @param string $property
     */
    public function __construct(
        string $table = '',
        string $column = '',
        string $index = '',
        Model $model = null,
        string $property = ''
    ) {
        $this->table = $table;
        $this->column = $column;
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

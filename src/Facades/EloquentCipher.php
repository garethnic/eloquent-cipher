<?php

namespace Garethnic\EloquentCipher\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Garethnic\EloquentCipher\EloquentCipher
 */
class EloquentCipher extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'eloquent-cipher';
    }
}

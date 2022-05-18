<?php

namespace Garethnic\EloquentCipher;

use Garethnic\EloquentCipher\Commands\GenerateKey;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class EloquentCipherServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('eloquent-cipher')
            ->hasConfigFile()
            ->hasMigration('create_blind_indexes_table')
            ->hasCommand(GenerateKey::class);
    }
}

<?php

namespace Garethnic\EloquentCipher;

use Garethnic\EloquentCipher\Commands\GenerateKey;
use ParagonIE\CipherSweet\Backend\FIPSCrypto;
use ParagonIE\CipherSweet\Backend\ModernCrypto;
use ParagonIE\CipherSweet\CipherSweet;
use ParagonIE\CipherSweet\Contract\BackendInterface;
use ParagonIE\CipherSweet\Contract\KeyProviderInterface;
use ParagonIE\CipherSweet\Exception\CryptoOperationException;
use ParagonIE\CipherSweet\KeyProvider\FileProvider;
use ParagonIE\CipherSweet\KeyProvider\RandomProvider;
use ParagonIE\CipherSweet\KeyProvider\StringProvider;
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

    public function registeringPackage()
    {
        parent::registeringPackage();

        $this->app->singleton(CipherSweet::class, function ($app) {
            $backend = $this->buildBackend();

            return new CipherSweet($this->buildKeyProvider($backend), $backend);
        });
    }

    /**
     * @return BackendInterface
     */
    protected function buildBackend(): BackendInterface
    {
        return match (config('eloquent-cipher.backend')) {
            'fips' => new FIPSCrypto(),
            default => new ModernCrypto(),
        };
    }

    /**
     * @param BackendInterface $backend
     * @return KeyProviderInterface
     * @throws CryptoOperationException
     */
    protected function buildKeyProvider(BackendInterface $backend): KeyProviderInterface
    {
        return match (config('eloquent-cipher.provider')) {
            'custom' => $this->buildCustomKeyProvider(),
            'file' => new FileProvider(config('eloquent-cipher.providers.file.path')),
            'string' => new StringProvider(config('eloquent-cipher.providers.string.key')),
            default => new RandomProvider($backend),
        };
    }

    /**
     * @return KeyProviderInterface
     */
    protected function buildCustomKeyProvider(): KeyProviderInterface
    {
        $factory = app(config('eloquent-cipher.providers.custom.via'));

        return $factory();
    }
}

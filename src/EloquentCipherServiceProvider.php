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

    /**
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CipherSweet::class, function () {
            $backend = $this->buildBackend();

            return new CipherSweet($this->buildKeyProvider($backend), $backend);
        });
    }

    /**
     * @return BackendInterface
     */
    protected function buildBackend(): BackendInterface
    {
        switch (config('eloquent-cipher.backend')) {
            case 'fips':
                return new FIPSCrypto;
            case 'nacl':
            default:
                return new ModernCrypto;
        }
    }

    /**
     * @param BackendInterface $backend
     * @return KeyProviderInterface
     * @throws CryptoOperationException
     */
    protected function buildKeyProvider(BackendInterface $backend): KeyProviderInterface
    {
        switch (config('eloquent-cipher.provider')) {
            case 'custom':
                return $this->buildCustomKeyProvider();
            case 'file':
                return new FileProvider(config('eloquent-cipher.file.path'));
            case 'string':
                return new StringProvider(config('eloquent-cipher.string.key'));
            case 'random':
            default:
                return new RandomProvider($backend);
        }
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

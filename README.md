
# Laravel and CipherSweet integration

[![Latest Version on Packagist](https://img.shields.io/packagist/v/garethnic/eloquent-cipher.svg?style=flat-square)](https://packagist.org/packages/garethnic/eloquent-cipher)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/garethnic/eloquent-cipher/run-tests?label=tests)](https://github.com/garethnic/eloquent-cipher/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/garethnic/eloquent-cipher/Check%20&%20fix%20styling?label=code%20style)](https://github.com/garethnic/eloquent-cipher/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/garethnic/eloquent-cipher.svg?style=flat-square)](https://packagist.org/packages/garethnic/eloquent-cipher)

Foundation provided by:
- [Paragonie - CipherSweet](https://github.com/paragonie/ciphersweet)
- [Paragonie - Eloquent CipherSweeet](https://github.com/paragonie/eloquent-ciphersweet)

Resources:
- [CipherSweet Docs](https://ciphersweet.paragonie.com/)
- [Building Searchable Encrypted Databases with PHP and SQL](https://paragonie.com/blog/2017/05/building-searchable-encrypted-databases-with-php-and-sql)
- [CipherSweet: Searchable Encryption Doesn't Have to be Bitter](https://paragonie.com/blog/2019/01/ciphersweet-searchable-encryption-doesn-t-have-be-bitter)

## Installation

You can install the package via composer:

```bash
composer require garethnic/eloquent-cipher
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="eloquent-cipher-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="eloquent-cipher-config"
```

This is the contents of the published config file:

```php
return [
    /*
    |--------------------------------------------------------------------------
    | Cryptographic Backend
    |--------------------------------------------------------------------------
    |
    | This controls which cryptographic backend will be used by CipherSweet.
    | Unless you have specific compliance requirements, you should choose
    | "nacl".
    |
    | Supported: "fips", "nacl"
    |
    */

    'backend' => env('CIPHERSWEET_BACKEND', 'nacl'),

    /*
    |--------------------------------------------------------------------------
    | Key Provider
    |--------------------------------------------------------------------------
    |
    | Select which key provider your application will use. The default option
    | is to read a string literal out of .env, but it's also possible to
    | provide the key in a file, use a custom key provider, or use random keys
    | for testing.
    |
    | "string" is selected by default to read a key directly from your .env
    | file. Use `artisan ciphersweet:generate:key` to securely generate that
    | key.
    |
    | Supported: "custom", "file", "random", "string"
    |
    */

    'provider' => env('CIPHERSWEET_PROVIDER', 'string'),

    /*
    |--------------------------------------------------------------------------
    | Key Providers
    |--------------------------------------------------------------------------
    |
    | Set provider-specific options here. "string" will read the key directly
    | from your .env file. "file" will read the contents of the specified file
    | to use as your key. "custom" points to a factory class that returns a
    | provider from its `__invoke` method. Please see the docs for more details.
    |
    */

    'providers' => [
        'custom' => [
            //'via' => \App\CipherSweetKey\CreateKeyProvider::class,
        ],
        'file' => [
            'path' => env('CIPHERSWEET_FILE_PATH'),
        ],
        'string' => [
            'key' => env('CIPHERSWEET_KEY'),
        ],
    ],
];
```

## Usage

```php
$eloquentCipher = new Garethnic\EloquentCipher();
echo $eloquentCipher->echoPhrase('Hello, Garethnic!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Gareth Nicholson](https://github.com/garethnic)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

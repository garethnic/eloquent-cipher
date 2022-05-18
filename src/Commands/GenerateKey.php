<?php

namespace Garethnic\EloquentCipher\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use ParagonIE\CipherSweet\Util;
use ParagonIE\ConstantTime\Hex;

class GenerateKey extends Command
{
    use ConfirmableTrait;

    protected $signature = 'ciphersweet:generate:key
        {--show : Display key instead of modifying .env}
        {--force : For operation to run when in production}';

    public $description = 'Generates a key for the CipherSweet engine.';

    public function handle(): int
    {
        $key = Hex::encode(random_bytes(32));

        if ($this->option('show')) {
            $this->comment('<comment>' . $key . '</comment>');
        } elseif ($this->confirmToProceed()) {
            $pattern = "/^CIPHERSWEET_KEY=.*$/m";
            $filePath = $this->laravel->basePath() . '/.env';
            $contents = file_get_contents($filePath);

            file_put_contents(
                $filePath,
                preg_match($pattern, $contents) ?
                    preg_replace($pattern, 'CIPHERSWEET_KEY=' . $key, $contents) :
                    $contents . "\n" . 'CIPHERSWEET_KEY=' . $key
            );
        }

        Util::memzero($key);

        return self::SUCCESS;
    }
}

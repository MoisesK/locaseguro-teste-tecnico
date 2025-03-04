<?php

declare(strict_types=1);

use Illuminate\Contracts\Console\Kernel;

error_reporting(E_ALL & ~E_DEPRECATED);

$composerAutoload = __DIR__ . '/../vendor/autoload.php';

if (is_file($composerAutoload)) {
    require_once $composerAutoload;
}

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();


DG\BypassFinals::enable();

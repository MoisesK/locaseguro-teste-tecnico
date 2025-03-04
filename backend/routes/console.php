<?php

declare(strict_types=1);

$mountPath = fn (string $path) => __DIR__ . "/../app/{$path}";

$this->load($mountPath('Console'));

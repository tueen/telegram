<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Tueen\Telegram\Generator\CodeGenerator;

$specFile = __DIR__ . '/../resources/api.json';
$srcDir = __DIR__ . '/../src';

if (!file_exists($specFile)) {
    echo "Error: Specification file not found at: {$specFile}\n";
    exit(1);
}

echo "Starting Telegram Bot API generator...\n";
$generator = new CodeGenerator($specFile, $srcDir);
$generator->run();
echo "All classes generated successfully!\n";

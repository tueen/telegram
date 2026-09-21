<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Tueen\Telegram\Generator\CodeGenerator;

$specFile = 'C:/Users/Elsiom/.gemini/antigravity/brain/a244ad9a-6e9c-41d6-976d-5f19a35fe655/scratch/api.json';
$srcDir = __DIR__ . '/../src';

echo "Starting Telegram Bot API generator...\n";
$generator = new CodeGenerator($specFile, $srcDir);
$generator->run();
echo "All classes generated successfully!\n";

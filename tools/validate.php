<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

$errors = [];
$checked = 0;

$srcDir = realpath(__DIR__ . '/../src');
$iterator = new \RecursiveIteratorIterator(
    new \RecursiveDirectoryIterator($srcDir, \FilesystemIterator::SKIP_DOTS)
);

foreach ($iterator as $file) {
    if (!$file->isFile() || $file->getExtension() !== 'php') {
        continue;
    }

    $filePath = $file->getRealPath();
    $code = file_get_contents($filePath);

    try {
        $tokens = token_get_all($code, TOKEN_PARSE);
        $checked++;
    } catch (\ParseError $e) {
        $errors[] = $file->getFilename() . ": " . $e->getMessage();
    }
}

echo "Checked $checked files.\n";
if (empty($errors)) {
    echo "SUCCESS: All $checked PHP files parsed cleanly with zero syntax errors!\n";
} else {
    echo "Found " . count($errors) . " errors:\n" . implode("\n", $errors) . "\n";
    exit(1);
}

<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

$errors = [];
$checked = 0;

$dirs = [
    __DIR__ . '/../src/Types',
    __DIR__ . '/../src/Methods',
    __DIR__ . '/../src/Contracts',
    __DIR__ . '/../src/Properties',
    __DIR__ . '/../src/Enums',
    __DIR__ . '/../src/Client',
    __DIR__ . '/../src/Pipeline',
    __DIR__ . '/../src/Exceptions',
    __DIR__ . '/../src/Attributes',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) continue;
    $files = scandir($dir);
    foreach ($files as $file) {
        if (!str_ends_with($file, '.php')) continue;
        $filePath = $dir . '/' . $file;
        $code = file_get_contents($filePath);

        try {
            // Check syntax with php_check_syntax if available or tokens
            $tokens = token_get_all($code, TOKEN_PARSE);
            $checked++;
        } catch (\ParseError $e) {
            $errors[] = "$file: " . $e->getMessage();
        }
    }
}

echo "Checked $checked files.\n";
if (empty($errors)) {
    echo "SUCCESS: All $checked PHP files parsed cleanly with zero syntax errors!\n";
} else {
    echo "Found " . count($errors) . " errors:\n" . implode("\n", $errors) . "\n";
}

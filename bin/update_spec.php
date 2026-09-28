<?php

declare(strict_types=1);

/**
 * Tueen Telegram Client - Specification Updater
 *
 * Scrapes official Telegram Bot API documentation directly from
 * https://core.telegram.org/bots/api using the internal Python scraper
 * located in tools/scraper/scrape.py.
 */

echo "=== Telegram Bot API Specification Updater ===\n";
echo "Scraping latest Bot API specification from core.telegram.org...\n\n";

$projectRoot = dirname(__DIR__);
$targetFile = $projectRoot . '/resources/api.json';
$scratchFile = $projectRoot . '/scratch/api.json';
$scraperScript = $projectRoot . '/tools/scraper/scrape.py';
$requirementsFile = $projectRoot . '/tools/scraper/requirements.txt';

if (!file_exists($scraperScript)) {
    fwrite(STDERR, "Error: Scraper script not found at {$scraperScript}\n");
    exit(1);
}

// 1. Detect available Python command
$pythonCandidates = ['python3', 'python', 'py -3', 'py'];
$pythonBin = null;

foreach ($pythonCandidates as $cmd) {
    $checkCmd = (stripos(PHP_OS, 'WIN') === 0)
        ? "where {$cmd} 2>nul"
        : "command -v {$cmd} 2>/dev/null";

    // Test running version command
    $output = [];
    $ret = 1;
    @exec("{$cmd} --version 2>&1", $output, $ret);

    if ($ret === 0 && !empty($output)) {
        $pythonBin = $cmd;
        echo "Found Python runtime: " . trim($output[0]) . " ({$cmd})\n";
        break;
    }
}

if ($pythonBin === null) {
    fwrite(STDERR, "Error: Python 3 was not detected on your system.\n");
    fwrite(STDERR, "Please install Python 3.10+ to scrape the Telegram Bot API specification.\n");
    exit(1);
}

// 2. Check if Python scraper dependencies are installed
$depCheckOutput = [];
$depCheckRet = 1;
exec("{$pythonBin} -c \"import requests, bs4, html5lib\" 2>&1", $depCheckOutput, $depCheckRet);

if ($depCheckRet !== 0) {
    echo "Notice: Required Python packages (requests, beautifulsoup4, html5lib) missing.\n";
    echo "Attempting automatic installation via pip...\n";

    $pipCmd = "{$pythonBin} -m pip install -r " . escapeshellarg($requirementsFile);
    passthru($pipCmd, $pipRet);

    if ($pipRet !== 0) {
        fwrite(STDERR, "\nError: Failed to install Python scraper dependencies.\n");
        fwrite(STDERR, "Please run manually: pip install -r tools/scraper/requirements.txt\n");
        exit(1);
    }
}

// 3. Execute local Python scraper
$scrapeCmd = sprintf(
    '%s %s --output %s',
    $pythonBin,
    escapeshellarg($scraperScript),
    escapeshellarg($targetFile)
);

echo "\nExecuting local scraper: {$scrapeCmd}\n";
$exitCode = 0;
passthru($scrapeCmd, $exitCode);

if ($exitCode !== 0 || !file_exists($targetFile)) {
    fwrite(STDERR, "\nError: Python scraper failed with exit code {$exitCode}.\n");
    exit(1);
}

// 4. Verify and sync generated specification
$rawJson = file_get_contents($targetFile);
$data = json_decode($rawJson, true);

if (!is_array($data) || !isset($data['methods'], $data['types'])) {
    fwrite(STDERR, "\nError: Generated api.json is corrupted or invalid.\n");
    exit(1);
}

// Sync to scratch/api.json if scratch directory exists
if (is_dir(dirname($scratchFile))) {
    copy($targetFile, $scratchFile);
}

echo "\nSpecification updated successfully!\n";
echo "Path        : {$targetFile}\n";
echo "API Version : " . ($data['version'] ?? 'N/A') . " (" . ($data['release_date'] ?? 'N/A') . ")\n";
echo "Methods     : " . count($data['methods']) . "\n";
echo "Types       : " . count($data['types']) . "\n";
echo "==============================================\n";

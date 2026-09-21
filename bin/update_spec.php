<?php

declare(strict_types=1);

echo "Fetching latest Telegram Bot API specification...\n";

$targetFile = __DIR__ . '/../resources/api.json';
$url = 'https://raw.githubusercontent.com/PaulSonOfLars/telegram-bot-api-spec/master/api.json';

$proxies = [
    null, // Try direct first
    getenv('HTTP_PROXY') ?: null,
    getenv('HTTPS_PROXY') ?: null,
    'tcp://127.0.0.1:10809', // Common local proxy
];

$proxies = array_unique(array_filter($proxies));
array_unshift($proxies, null); // ensure direct is attempted first

$content = false;

foreach ($proxies as $proxy) {
    $proxyLabel = $proxy ? "proxy {$proxy}" : "direct connection";
    echo "Attempting download via {$proxyLabel}...\n";

    $opts = [
        'http' => [
            'timeout' => 15,
            'follow_location' => 1,
            'user_agent' => 'Tueen-Telegram-Client-Updater/1.0',
        ],
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
        ],
    ];

    if ($proxy !== null) {
        $opts['http']['proxy'] = $proxy;
        $opts['http']['request_fulluri'] = true;
    }

    $ctx = stream_context_create($opts);
    $data = @file_get_contents($url, false, $ctx);

    if ($data !== false && strlen($data) > 100000) {
        $json = json_decode($data, true);
        if (isset($json['methods'], $json['types'])) {
            $content = $data;
            echo "Successfully downloaded specification (" . strlen($data) . " bytes)!\n";
            echo "API Version: " . ($json['version'] ?? 'N/A') . " (" . ($json['release_date'] ?? 'N/A') . ")\n";
            echo "Methods: " . count($json['methods']) . ", Types: " . count($json['types']) . "\n";
            break;
        }
    }
}

if ($content === false) {
    echo "Warning: Could not fetch updated spec online. Keeping existing resources/api.json.\n";
    exit(0);
}

if (!is_dir(dirname($targetFile))) {
    mkdir(dirname($targetFile), 0777, true);
}

file_put_contents($targetFile, $content);
echo "Updated resources/api.json successfully.\n";

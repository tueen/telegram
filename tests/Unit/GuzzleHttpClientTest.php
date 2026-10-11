<?php

declare(strict_types=1);

namespace Tueen\Telegram\Tests\Unit;

use GuzzleHttp\Client as GuzzleClient;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Tueen\Telegram\Client\GuzzleHttpClient;
use Tueen\Telegram\Config;

class GuzzleHttpClientTest extends TestCase
{
    public function testGetClientConfiguresOptionsWithDefaultVerifySslFalse(): void
    {
        $config = new Config(
            botToken: 'TEST_TOKEN',
            timeout: 25.0,
            connectTimeout: 8.0,
            proxy: 'http://127.0.0.1:8080'
        );

        $client = new GuzzleHttpClient();

        $refMethod = new ReflectionMethod($client, 'getClient');
        $guzzle = $refMethod->invoke($client, $config);

        $this->assertInstanceOf(GuzzleClient::class, $guzzle);
        $configProp = $guzzle->getConfig();

        $this->assertSame(25.0, $configProp['timeout']);
        $this->assertSame(8.0, $configProp['connect_timeout']);
        $this->assertFalse($configProp['http_errors']);
        $this->assertSame('http://127.0.0.1:8080', $configProp['proxy']);
        $this->assertFalse($configProp['verify']);
    }

    public function testGetClientHonorsVerifySslTrueWhenConfigured(): void
    {
        $config = new Config(
            botToken: 'TEST_TOKEN',
            verifySsl: true
        );

        $client = new GuzzleHttpClient();

        $refMethod = new ReflectionMethod($client, 'getClient');
        $guzzle = $refMethod->invoke($client, $config);

        $this->assertInstanceOf(GuzzleClient::class, $guzzle);
        $configProp = $guzzle->getConfig();

        $this->assertTrue($configProp['verify']);
    }

    public function testGetClientHonorsVerifySslStringWhenCustomCaPathConfigured(): void
    {
        $caPath = '/etc/ssl/certs/custom-ca.pem';
        $config = new Config(
            botToken: 'TEST_TOKEN',
            verifySsl: $caPath
        );

        $client = new GuzzleHttpClient();

        $refMethod = new ReflectionMethod($client, 'getClient');
        $guzzle = $refMethod->invoke($client, $config);

        $this->assertInstanceOf(GuzzleClient::class, $guzzle);
        $configProp = $guzzle->getConfig();

        $this->assertSame($caPath, $configProp['verify']);
    }

    public function testGetClientReturnsExistingGuzzleInstance(): void
    {
        $mockGuzzle = new GuzzleClient();
        $client = new GuzzleHttpClient($mockGuzzle);

        $config = new Config(botToken: 'TEST_TOKEN');

        $refMethod = new ReflectionMethod($client, 'getClient');
        $guzzle = $refMethod->invoke($client, $config);

        $this->assertSame($mockGuzzle, $guzzle);
    }
}

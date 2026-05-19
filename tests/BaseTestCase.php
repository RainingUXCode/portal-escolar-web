<?php

declare(strict_types=1);

// If PHPUnit is not installed (no composer), include a small stub so static analysis and runtime still work.
if (!class_exists('PHPUnit\\Framework\\TestCase')) {
  require_once __DIR__ . '/phpunit_stub.php';
}

use PHPUnit\Framework\TestCase;

/**
 * BaseTestCase provides simple HTTP helpers using cURL and maintains cookies.
 *
 * @coversNothing
 * @extends \PHPUnit\Framework\TestCase
 */
class BaseTestCase extends TestCase
{
  /**
   * Base URL for the app under test.
   * @var string
   */
  protected $baseUrl;

  /**
   * Path to a temporary cookie jar file.
   * @var string
   */
  protected $cookieFile;

  protected function setUp(): void
  {
    $this->baseUrl = getenv('BASE_URL') ?: 'http://localhost/portal-simple';
    $this->cookieFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'portal_test_cookies_' . uniqid();
    // ensure file exists
    @file_put_contents($this->cookieFile, "");
  }

  protected function tearDown(): void
  {
    if (file_exists($this->cookieFile)) {
      @unlink($this->cookieFile);
    }
  }

  /**
   * Perform an HTTP request and return structured response.
   *
   * @param string $method GET|POST
   * @param string $path relative path to base
   * @param array|string $data POST data
   * @return array{status:int,body:string,headers:string,info:array}
   */
  protected function httpRequest($method, $path, $data = [])
  {
    $url = rtrim($this->baseUrl, '/') . '/' . ltrim($path, '/');

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookieFile);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookieFile);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    if (strtoupper($method) === 'POST') {
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }

    $response = curl_exec($ch);
    $info = curl_getinfo($ch) ?: [];
    $err = curl_error($ch);
    curl_close($ch);

    if ($response === false) {
      return [
        'status' => 0,
        'body' => '',
        'headers' => '',
        'info' => $info,
        'error' => $err,
      ];
    }

    // separate headers and body
    $header_size = $info['header_size'] ?? 0;
    $raw_headers = substr($response, 0, $header_size);
    $body = substr($response, $header_size);

    return [
      'status' => (int)($info['http_code'] ?? 0),
      'body' => (string)$body,
      'headers' => (string)$raw_headers,
      'info' => $info,
    ];
  }

  /**
   * @param string $path
   * @return array{status:int,body:string,headers:string,info:array}
   */
  protected function httpGet($path)
  {
    return $this->httpRequest('GET', $path);
  }

  /**
   * @param string $path
   * @param array|string $data
   * @return array{status:int,body:string,headers:string,info:array}
   */
  protected function httpPost($path, $data = [])
  {
    return $this->httpRequest('POST', $path, $data);
  }
}

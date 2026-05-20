<?php

require_once __DIR__ . '/bootstrap.php';

class PerformanceTest extends \BaseTestCase
{
  /**
   * Test a list of endpoints to ensure response time is below 3000ms
   */
  public function testEndpointsRespondWithinThreeSeconds()
  {
    $endpoints = [
      ['method' => 'GET', 'path' => 'login.php'],
      ['method' => 'GET', 'path' => 'cadastro.php'],
      ['method' => 'GET', 'path' => 'area_aluno.php'],
      ['method' => 'GET', 'path' => 'painel_admin.php'],
      // lightweight POSTs for processa endpoints (measure time, not correctness)
      ['method' => 'POST', 'path' => 'app/processa/processa_login.php', 'data' => ['email' => 'admin@escola.com', 'senha' => 'admin123']],
      ['method' => 'POST', 'path' => 'app/processa/processa_cadastro.php', 'data' => ['nome' => 'Perf Test', 'email' => 'perf' . time() . '@escola.com', 'matricula' => (string)time(), 'data_nascimento' => '2000-01-01', 'endereco' => 'Rua', 'senha' => '123456']]
    ];

    $thresholdMs = 3000; // 3 seconds

    foreach ($endpoints as $ep) {
      $method = strtoupper($ep['method'] ?? 'GET');
      $path = $ep['path'];
      $data = $ep['data'] ?? [];

      $t0 = microtime(true);
      if ($method === 'POST') {
        $resp = $this->httpPost($path, $data);
      } else {
        $resp = $this->httpGet($path);
      }
      $t1 = microtime(true);

      $elapsedMs = null;
      if (!empty($resp['info']['total_time'])) {
        $elapsedMs = $resp['info']['total_time'] * 1000;
      } else {
        $elapsedMs = ($t1 - $t0) * 1000;
      }

      if ($elapsedMs === null) {
        throw new \RuntimeException("Could not determine response time for {$path}");
      }
      if ($elapsedMs >= $thresholdMs) {
        throw new \RuntimeException(sprintf('%s responded in %.0f ms, exceeding %d ms', $path, $elapsedMs, $thresholdMs));
      }
    }

    // register at least one assertion to avoid PHPUnit marking the test as risky
    if (method_exists(
      '\\PHPUnit\\Framework\\TestCase',
      'addToAssertionCount'
    )) {
      // when running under PHPUnit, increase assertion count
      $this->addToAssertionCount(1);
    }
  }
}


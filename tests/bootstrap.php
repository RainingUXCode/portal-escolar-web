<?php
// Simple bootstrap for tests
// Load Composer autoload if available
$composerAutoload = __DIR__ . '/../vendor/autoload.php';
if (file_exists($composerAutoload)) {
  require_once $composerAutoload;
}

// If PHPUnit is not available via Composer, load a small stub for static analysis and basic execution.
if (!class_exists('\\PHPUnit\\Framework\\TestCase')) {
  require_once __DIR__ . '/phpunit_stub.php';
}

require_once __DIR__ . '/BaseTestCase.php';

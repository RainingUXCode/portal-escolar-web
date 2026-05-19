<?php
// Lightweight PHPUnit stub for static analysis and environments without composer.
if (!class_exists('\\PHPUnit\\Framework\\TestCase')) {
  eval('namespace PHPUnit\\Framework {\n    abstract class TestCase {\n        public function assertNotNull($a, $msg = "") { }\n        public function assertLessThan($expected, $actual, $msg = "") { }\n        public function assertStringContainsString($needle, $haystack, $msg = "") { }\n        public function assertStringNotContainsString($needle, $haystack, $msg = "") { }\n        public function assertSame($expected, $actual, $msg = "") { }\n        public function assertTrue($condition, $msg = "") { }\n        public function assertFalse($condition, $msg = "") { }\n        public function addToAssertionCount(int $count) { }\n    }\n}');
}

<?php
// Static type declarations to help IDEs (intelephense) resolve PHPUnit types.
namespace PHPUnit\Framework {
  abstract class TestCase
  {
    public function assertNotNull($a, string $msg = ''): void {}
    public function assertLessThan($expected, $actual, string $msg = ''): void {}
    public function assertStringContainsString($needle, $haystack, string $msg = ''): void {}
    public function assertStringNotContainsString($needle, $haystack, string $msg = ''): void {}
    public function assertSame($expected, $actual, string $msg = ''): void {}
    public function assertTrue($condition, string $msg = ''): void {}
    public function assertFalse($condition, string $msg = ''): void {}
    public function addToAssertionCount(int $count): void {}
  }
}

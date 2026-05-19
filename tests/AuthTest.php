<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseTestCase.php';

use BaseTestCase;

/**
 * @extends \BaseTestCase
 * @coversNothing
 */
class AuthTest extends \BaseTestCase
{
  private string $testEmail;
  private string $testPass;

  protected function setUp(): void
  {
    parent::setUp();
    // unique test user per run
    $uniq = bin2hex(random_bytes(4));
    $this->testEmail = "test_user_{$uniq}@escola.com";
    $this->testPass = 'Senha123';
    $this->createTestUser($this->testEmail, $this->testPass);
  }

  protected function tearDown(): void
  {
    $this->deleteTestUser($this->testEmail);
    parent::tearDown();
  }

  private function createTestUser(string $email, string $senha): int
  {
    require __DIR__ . '/../includes/conexao.php';
    $nome = $conn->real_escape_string('Teste Automacao');
    $emailEsc = $conn->real_escape_string($email);
    $senhaEsc = $conn->real_escape_string($senha);
    $matricula = 'MAT' . substr(md5($email), 0, 8);
    $sql = "INSERT INTO usuarios (nome, email, senha, matricula, data_nascimento, endereco, nivel_acesso) VALUES ('{$nome}', '{$emailEsc}', '{$senhaEsc}', '{$matricula}', '2000-01-01', 'Rua Teste', 'aluno')";
    $conn->query($sql);
    return (int)$conn->insert_id;
  }

  private function deleteTestUser(string $email): void
  {
    require __DIR__ . '/../includes/conexao.php';
    $emailEsc = $conn->real_escape_string($email);
    $conn->query("DELETE FROM usuarios WHERE email = '{$emailEsc}'");
  }

  public function testLoginValid(): void
  {
    $res = $this->httpPost('processa/processa_login.php', ['email' => $this->testEmail, 'senha' => $this->testPass]);
    self::assertSame(200, $res['status']);
    self::assertStringContainsString('Olá, bem-vindo', $res['body']);
  }

  public function testLoginInvalid(): void
  {
    $res = $this->httpPost('processa/processa_login.php', ['email' => 'no-such-user@escola.com', 'senha' => 'x']);
    self::assertSame(200, $res['status']);
    self::assertStringContainsString('Login falhou', $res['body']);
  }

  public function testIncorrectPassword(): void
  {
    $res = $this->httpPost('processa/processa_login.php', ['email' => $this->testEmail, 'senha' => 'wrongpass']);
    self::assertSame(200, $res['status']);
    self::assertStringContainsString('Login falhou', $res['body']);
  }

  public function testEmailInvalidFormat(): void
  {
    $res = $this->httpPost('processa/processa_login.php', ['email' => 'not-an-email', 'senha' => 'whatever']);
    self::assertSame(200, $res['status']);
    self::assertStringContainsString('Login falhou', $res['body']);
  }

  public function testEmailWithoutEscolaDomain(): void
  {
    $res = $this->httpPost('processa/processa_login.php', ['email' => 'user@otherdomain.com', 'senha' => 'whatever']);
    self::assertSame(200, $res['status']);
    self::assertStringContainsString('Login falhou', $res['body']);
  }

  public function testAuthenticatedSessionAllowsAccess(): void
  {
    // login
    $this->httpPost('processa/processa_login.php', ['email' => $this->testEmail, 'senha' => $this->testPass]);
    // access protected page
    $res = $this->httpGet('area_aluno.php');
    self::assertSame(200, $res['status']);
    self::assertStringContainsString('Portal do Estudante', $res['body']);
  }

  public function testProtectionAgainstUnauthorizedAccess(): void
  {
    // new client (no cookies) - create fresh instance by creating new BaseTestCase cookie file
    $cookieBackup = $this->cookieFile;
    $this->cookieFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'portal_test_cookies_' . uniqid();
    @file_put_contents($this->cookieFile, "");

    $res = $this->httpGet('area_admin.php');
    // should redirect to login page
    self::assertSame(200, $res['status']);
    self::assertTrue(
      (strpos($res['body'], 'Login do Portal') !== false) || (strpos($res['body'], 'Você precisa fazer login') !== false),
      'Expected login page or login message when accessing protected page unauthenticated.'
    );

    // restore cookie file
    $this->cookieFile = $cookieBackup;
  }
}

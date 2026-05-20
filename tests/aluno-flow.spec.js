const { test, expect } = require("@playwright/test");

const BASE_URL = process.env.BASE_URL || "http://localhost/portal-simple";

test("Cadastro de usuário", async ({ page }) => {
  await page.goto(`${BASE_URL}/cadastro.php`);

  await page.fill('input[name="nome"]', "Usuário Teste");

  await page.fill('input[name="matricula"]', "2025001");

  await page.fill('input[name="email"]', "teste@email.com");

  await page.fill('input[name="data_nascimento"]', "2000-01-01");

  await page.fill('input[name="endereco"]', "Rua Teste");

  await page.fill('input[name="senha"]', "123456");

  await page.click('button[type="submit"]');

  await expect(page.locator("body")).toContainText(/sucesso|cadastrado|login/i);
});

test("Aluno consegue se cadastrar e fazer login", async ({ page }) => {
  const timestamp = Date.now();

  const email = `teste${timestamp}@escola.com`;

  const senha = "123456";

  // CADASTRO

  await page.goto(`${BASE_URL}/cadastro.php`);

  await page.fill('input[name="nome"]', "Aluno Teste");

  await page.fill('input[name="matricula"]', `${timestamp}`);

  await page.fill('input[name="email"]', email);

  await page.fill('input[name="data_nascimento"]', "2000-01-01");

  await page.fill('input[name="endereco"]', "Rua Teste");

  await page.fill('input[name="senha"]', senha);

  await page.click('button[type="submit"]');

  await expect(page).not.toHaveURL(/cadastro.php/);

  // LOGIN

  await page.goto(`${BASE_URL}/login.php`);

  await page.fill('input[name="email"]', email);

  await page.fill('input[name="senha"]', senha);

  await page.click('button[type="submit"]');

  await expect(page).not.toHaveURL(/login.php/);

  // VALIDA ÁREA DO ALUNO

  await expect(page.locator("body")).toContainText(/perfil|aluno|bem-vindo/i);
});

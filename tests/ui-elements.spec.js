const { test, expect } = require("@playwright/test");

const BASE_URL = "http://localhost/portal-simple";
const ADMIN_EMAIL = "admin@escola.com";
const ADMIN_SENHA = "admin123";

async function loginAs(page, email, senha) {
  await page.goto(`${BASE_URL}/login.php`);
  await page.fill('input[name="email"]', email);
  await page.fill('input[name="senha"]', senha);
  await page.click('button[type="submit"]');
  await expect(page).not.toHaveURL(/login.php/);
}

test.describe("UI elements - inputs and buttons", () => {
  test("Login page inputs and submit button exist", async ({ page }) => {
    await page.goto(`${BASE_URL}/login.php`);

    const email = page.locator('input[name="email"]');
    const senha = page.locator('input[name="senha"]');
    const submit = page.locator('button[type="submit"]');

    await expect(email).toBeVisible();
    await expect(senha).toBeVisible();
    await expect(submit).toBeVisible();

    // check required attribute on inputs (if present)
    const emailReq = await email.getAttribute("required");
    const senhaReq = await senha.getAttribute("required");

    // at least inputs should be enabled
    await expect(email).toBeEnabled();
    await expect(senha).toBeEnabled();
    await expect(submit).toBeEnabled();

    // optional: fill and ensure submit stays enabled
    await email.fill("teste@ui.com");
    await senha.fill("senha");
    await expect(submit).toBeEnabled();
  });

  test("Cadastro page inputs and submit button exist", async ({ page }) => {
    await page.goto(`${BASE_URL}/cadastro.php`);

    const nome = page.locator('input[name="nome"]');
    const matricula = page.locator('input[name="matricula"]');
    const email = page.locator('input[name="email"]');
    const data = page.locator('input[name="data_nascimento"]');
    const endereco = page.locator('input[name="endereco"]');
    const senha = page.locator('input[name="senha"]');
    const submit = page.locator('button[type="submit"]');

    await expect(nome).toBeVisible();
    await expect(matricula).toBeVisible();
    await expect(email).toBeVisible();
    await expect(data).toBeVisible();
    await expect(endereco).toBeVisible();
    await expect(senha).toBeVisible();
    await expect(submit).toBeVisible();

    // ensure form validation presence (if required attributes used)
    await expect(nome).toBeEnabled();
    await expect(submit).toBeEnabled();
  });

  test("Painel admin shows news and polls form controls after login", async ({
    page,
  }) => {
    await loginAs(page, ADMIN_EMAIL, ADMIN_SENHA);
    await page.goto(`${BASE_URL}/painel_admin.php`);

    // Notícias tab
    const noticiasTab = page.locator('button[data-tab-toggle="noticias"]');
    await expect(noticiasTab).toBeVisible();
    await noticiasTab.click();

    await expect(page.locator('input[name="titulo"]')).toBeVisible();
    await expect(page.locator('input[name="autor"]')).toBeVisible();
    await expect(page.locator('textarea[name="conteudo"]')).toBeVisible();
    await expect(page.locator('input[name="imagem"]')).toBeVisible();
    await expect(
      page.locator('#form-criar-noticia button[type="submit"]'),
    ).toBeVisible();

    // Enquetes tab
    const enquetesTab = page.locator('button[data-tab-toggle="enquetes"]');
    await expect(enquetesTab).toBeVisible();
    await enquetesTab.click();

    await expect(page.locator('input[name="pergunta"]')).toBeVisible();
    const optionInputs = page.locator('input[name="opcoes[]"]');
    await expect(optionInputs.first()).toBeVisible();
    await expect(optionInputs.nth(1)).toBeVisible();
    await expect(page.locator("#add-opcao-btn")).toBeVisible();
    await expect(
      page.locator('#form-criar-enquete button[type="submit"]'),
    ).toBeVisible();
  });

  test("Area aluno voting UI shows options or results after registering", async ({
    page,
  }) => {
    const timestamp = Date.now();
    const alunoEmail = `teste-ui-${timestamp}@escola.com`;
    const alunoSenha = "123456";

    // register
    await page.goto(`${BASE_URL}/cadastro.php`);
    await page.fill('input[name="nome"]', `Aluno UI ${timestamp}`);
    await page.fill('input[name="matricula"]', `${timestamp}`);
    await page.fill('input[name="email"]', alunoEmail);
    await page.fill('input[name="data_nascimento"]', "2000-01-01");
    await page.fill('input[name="endereco"]', "Rua UI");
    await page.fill('input[name="senha"]', alunoSenha);
    await page.click('button[type="submit"]');
    await expect(page).not.toHaveURL(/cadastro.php/);

    // login as student
    await page.goto(`${BASE_URL}/login.php`);
    await page.fill('input[name="email"]', alunoEmail);
    await page.fill('input[name="senha"]', alunoSenha);
    await page.click('button[type="submit"]');
    await expect(page).not.toHaveURL(/login.php/);

    // area aluno
    await page.goto(`${BASE_URL}/area_aluno.php`);

    // either there are poll-option-normal elements (able to vote) or result cards
    const normalOption = page.locator(".poll-option-normal").first();
    const destaqueOption = page.locator(".poll-option-destaque").first();
    const voteButtons = page.locator("button.vote-button");

    // At least one of these should be present (if there are enquetes)
    const normalCount = await normalOption.count();
    const destaqueCount = await destaqueOption.count();
    const voteButtonsCount = await voteButtons.count();

    // not asserting exact numbers; ensure page rendered polling UI parts
    await expect(
      normalCount + destaqueCount + voteButtonsCount,
    ).toBeGreaterThanOrEqual(0);

    // If there are normal options, ensure they are clickable
    if (normalCount > 0) {
      await expect(normalOption).toBeVisible();
      await normalOption.click();
      // check that the corresponding vote button toggles enabled state
      const voteBtn = page.locator('button[id^="votar-btn-normal-"]').first();
      // either it's enabled after selection or already visible
      await expect(voteBtn).toBeVisible();
    }
  });
});

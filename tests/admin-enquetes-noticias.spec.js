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

async function logout(page) {
  await page.goto(`${BASE_URL}/processa/processa_logout.php`);
  await expect(page).toHaveURL(/index.php/);
}

async function createNews(page, title, author, content) {
  await page.goto(`${BASE_URL}/painel_admin.php`);
  await page.click('button[data-tab-toggle="noticias"]');
  await page.fill('input[name="titulo"]', title);
  await page.fill('input[name="autor"]', author);
  await page.fill('textarea[name="conteudo"]', content);
  await page.click('#form-criar-noticia button[type="submit"]');
  await expect(page.locator('div[role="alert"]')).toContainText(
    "Notícia criada com sucesso",
  );

  const noticeCard = page
    .locator("#noticias-existentes-container .bg-gray-50", { hasText: title })
    .first();
  await expect(noticeCard).toBeVisible();
  return noticeCard;
}

async function deleteNews(page, title) {
  await page.goto(`${BASE_URL}/painel_admin.php`);
  await page.click('button[data-tab-toggle="noticias"]');
  const noticeCard = page
    .locator("#noticias-existentes-container .bg-gray-50", { hasText: title })
    .first();
  await expect(noticeCard).toBeVisible();
  await noticeCard.locator('button:has(img[alt="Excluir"])').click();
  await expect(page.locator('div[role="alert"]')).toContainText(
    "Notícia excluída com sucesso",
  );
  await expect(
    page.locator("#noticias-existentes-container .bg-gray-50", {
      hasText: title,
    }),
  ).toHaveCount(0);
}

async function createPoll(page, pergunta, opcoes) {
  await page.goto(`${BASE_URL}/painel_admin.php`);
  await page.click('button[data-tab-toggle="enquetes"]');
  await page.fill('input[name="pergunta"]', pergunta);
  const optionInputs = page.locator('input[name="opcoes[]"]');
  await optionInputs.nth(0).fill(opcoes[0]);
  await optionInputs.nth(1).fill(opcoes[1]);

  for (let i = 2; i < opcoes.length; i += 1) {
    await page.click("#add-opcao-btn");
    await optionInputs.nth(i).fill(opcoes[i]);
  }

  await page.click('#form-criar-enquete button[type="submit"]');
  await expect(page.locator('div[role="alert"]')).toContainText(
    "Enquete criada com sucesso",
  );

  await page.click('button[data-tab-toggle="enquetes"]');

  const pollCard = page
    .locator("#enquetes-existentes-container .bg-gray-50", {
      hasText: pergunta,
    })
    .first();
  await expect(pollCard).toBeVisible();
  return pollCard;
}

async function deletePoll(page, pergunta) {
  await page.goto(`${BASE_URL}/painel_admin.php`);
  await page.click('button[data-tab-toggle="enquetes"]');
  const pollCard = page
    .locator("#enquetes-existentes-container .bg-gray-50", {
      hasText: pergunta,
    })
    .first();
  await expect(pollCard).toBeVisible();
  await pollCard.locator('button:has(img[alt="Excluir"])').click();
  await expect(page.locator('div[role="alert"]')).toContainText(
    "Enquete excluída com sucesso",
  );
  await expect(
    page.locator("#enquetes-existentes-container .bg-gray-50", {
      hasText: pergunta,
    }),
  ).toHaveCount(0);
}

async function createAndLoginStudent(page, timestamp) {
  const alunoEmail = `teste-aluno-${timestamp}@escola.com`;
  const alunoSenha = "123456";
  await page.goto(`${BASE_URL}/cadastro.php`);
  await page.fill('input[name="nome"]', `Aluno Teste ${timestamp}`);
  await page.fill('input[name="matricula"]', `${timestamp}`);
  await page.fill('input[name="email"]', alunoEmail);
  await page.fill('input[name="data_nascimento"]', "2000-01-01");
  await page.fill('input[name="endereco"]', "Rua Teste");
  await page.fill('input[name="senha"]', alunoSenha);
  await page.click('button[type="submit"]');
  await expect(page).not.toHaveURL(/cadastro.php/);
  await loginAs(page, alunoEmail, alunoSenha);
  return { email: alunoEmail, senha: alunoSenha };
}

test.describe.serial("Admin painel e enquetes", () => {
  test("Admin cria e exclui enquete e notícia", async ({ page }) => {
    const timestamp = Date.now();
    const noticiaTitulo = `Teste Notícias ${timestamp}`;
    const noticiaAutor = "QA Automação";
    const noticiaConteudo =
      "Conteúdo de teste para validar criação e exclusão de notícias.";

    await loginAs(page, ADMIN_EMAIL, ADMIN_SENHA);
    await createNews(page, noticiaTitulo, noticiaAutor, noticiaConteudo);

    const enquetePergunta = `Qual sua matéria favorita? ${timestamp}`;
    const enqueteOpcoes = ["Matemática", "História", "Ciências"];
    await createPoll(page, enquetePergunta, enqueteOpcoes);

    await deleteNews(page, noticiaTitulo);
    await deletePoll(page, enquetePergunta);
  });

  test("Aluno realiza voto único em enquete normal", async ({ page }) => {
    const timestamp = Date.now();
    const enquetePergunta = `Qual lanche você prefere? ${timestamp}`;
    const enqueteOpcoes = ["Pizza", "Salada", "Sanduíche"];

    await loginAs(page, ADMIN_EMAIL, ADMIN_SENHA);
    await createPoll(page, enquetePergunta, enqueteOpcoes);
    await logout(page);

    await createAndLoginStudent(page, timestamp);
    await page.goto(`${BASE_URL}/area_aluno.php`);

    const pollCard = page
      .locator(".bg-white.rounded-xl", { hasText: enquetePergunta })
      .first();
    await expect(pollCard).toBeVisible();

    const firstOption = pollCard.locator(".poll-option-normal").first();
    await firstOption.click();
    await pollCard.locator('button:has-text("Votar")').click();

    await expect(page).toHaveURL(/area_aluno.php/);
    await expect(
      pollCard.locator('button:has-text("Voto Registrado")'),
    ).toBeVisible();
    await expect(
      pollCard.locator('button:has-text("Voto Registrado")'),
    ).toBeDisabled();

    await logout(page);
    await loginAs(page, ADMIN_EMAIL, ADMIN_SENHA);
    await deletePoll(page, enquetePergunta);
  });
});

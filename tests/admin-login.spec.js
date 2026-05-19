const { test, expect } = require("@playwright/test");

test("Administrador consegue fazer login", async ({ page }) => {
  await page.goto("http://localhost/portal-simple/login.php");

  await page.fill('input[name="email"]', "admin@escola.com");

  await page.fill('input[name="senha"]', "admin123");

  await page.click('button[type="submit"]');

  // valida que saiu da página de login
  await expect(page).not.toHaveURL(/login.php/);

  // valida área administrativa
  await expect(page.locator("body")).toContainText(/admin|painel|dashboard/i);
});

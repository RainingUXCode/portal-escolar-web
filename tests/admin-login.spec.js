const { test, expect } = require("@playwright/test");

const BASE_URL = process.env.BASE_URL || "http://localhost/portal-simple";

test("Administrador consegue fazer login", async ({ page }) => {
  await page.goto(`${BASE_URL}/login.php`);

  await page.fill('input[name="email"]', "admin@escola.com");

  await page.fill('input[name="senha"]', "admin123");

  await page.click('button[type="submit"]');

  // valida que saiu da página de login
  await expect(page).not.toHaveURL(/login.php/);

  // valida área administrativa
  await expect(page.locator("body")).toContainText(/admin|painel|dashboard/i);
});

const { test, expect } = require("@playwright/test");

const BASE_URL = process.env.BASE_URL || "http://localhost/portal-simple";

test("Página admin bloqueia usuário não logado", async ({ page }) => {
  await page.goto(`${BASE_URL}/painel_admin.php`);

  await expect(page).toHaveURL(/login/i);
});

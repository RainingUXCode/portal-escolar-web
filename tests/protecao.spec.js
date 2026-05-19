const { test, expect } = require("@playwright/test");

test("Página admin bloqueia usuário não logado", async ({ page }) => {
  await page.goto("http://localhost/portal-simple/painel_admin.php");

  await expect(page).toHaveURL(/login/i);
});

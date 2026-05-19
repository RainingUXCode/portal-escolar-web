const { test, expect } = require("@playwright/test");

test("Página inicial carrega", async ({ page }) => {
  await page.goto("http://localhost/portal-simple");

  await expect(page.locator("body")).toBeVisible();
});

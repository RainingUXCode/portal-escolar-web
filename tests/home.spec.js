const { test, expect } = require("@playwright/test");

const BASE_URL = process.env.BASE_URL || "http://localhost/portal-simple";

test("Página inicial carrega", async ({ page }) => {
  await page.goto(BASE_URL);

  await expect(page.locator("body")).toBeVisible();
});

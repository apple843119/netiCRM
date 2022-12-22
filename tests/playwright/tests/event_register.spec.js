const { test, expect, chromium } = require('@playwright/test');

/** @type {import('@playwright/test').Page} */
let page;

var item = {
  event_name_1: '無名額限制，填表完成送出',
  event_name_2: '有名額限制，不開放候補',
  event_name_3: '有名額限制，開放候補',
  event_name_4: '有名額限制，需事先審核',
  event_name_5: '無名額限制，需事先審核',
  site_name: 'netiCRM'
}

function getPageTitle(title){
  return title + " | "+item.site_name;
}

test.beforeAll(async () => {
  const browser = await chromium.launch({"headless": false});
  page = await browser.newPage();
});

test.afterAll(async () => {
  console.log('After tests');
  await page.close();
});

test.describe.serial('Event register page test', () => {
  test.use({ storageState: 'storageState.json' });
  test('Normal registration', async () => {
    await page.goto('/civicrm/event/register?reset=1&action=preview&id=1&cid=0');
    await page.locator('input[name="email-5"]').click();
    await page.locator('input[name="email-5"]').fill('test@aipvo.com');
    await page.locator('text=/.*Continue \\>\\>.*/').click();
    await expect(page).toHaveTitle('無名額限制，填表完成送出 | netiCRM');
  });

  test.describe('limit participants. Not fot waiting', () => {
    test('Check can register', async () => {
      await page.goto('/civicrm/event/register?reset=1&action=preview&id=2&cid=0');
      await page.locator('input[name="email-5"]').click();
      await page.locator('input[name="email-5"]').fill('test@aipvo.com');
      await page.locator('text=/.*Continue \\>\\>.*/').click();
      await expect(page).toHaveTitle('有名額限制，不開放候補 | netiCRM');
    });
  });

  test('Event register page test :limit participants. Not fot waiting.', async () => {
    await page.goto('/civicrm/event/register?reset=1&action=preview&id=3&cid=0');
    await page.locator('input[name="email-5"]').click();
    await page.locator('input[name="email-5"]').fill('test@aipvo.com');
    await page.locator('text=/.*Continue \\>\\>.*/').click();
    await expect(page).toHaveTitle('有名額限制，開放候補 | netiCRM');
  });

  test('limit participants. Need approval.', async () => {
    await page.goto('/civicrm/event/register?reset=1&action=preview&id=4&cid=0');
    await page.locator('input[name="email-5"]').click();
    await page.locator('input[name="email-5"]').fill('test@aipvo.com');
    await page.locator('text=/.*Continue \\>\\>.*/').click();
    await expect(page).toHaveTitle('有名額限制，需事先審核 | netiCRM');
  });

  test('No limit participants. Need approval.', async () => {
    await page.goto('/civicrm/event/register?reset=1&action=preview&id=5&cid=0');
    await page.locator('input[name="email-5"]').click();
    await page.locator('input[name="email-5"]').fill('test@aipvo.com');
    await page.locator('text=/.*Continue \\>\\>.*/').click();
    var page_title = getPageTitle(item.event_name_5);
    await expect(page).toHaveTitle(page_title);
    await expect(page).toHaveURL(/_qf_ThankYou_display/);
    await expect(page.locator('.bold')).toContainText([/審核|reviewed/i]);
  });
});



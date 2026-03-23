const { scrapeNewsList } = require("./listScraper");
const { scrapeNewsDetail } = require("./detailScraper");
const {
  mergeUniqueArticles,
  readArticles,
  sleep,
  writeArticles,
} = require("../utils/helpers");

/**
 * Main scraping flow:
 * 1) collect list from portal
 * 2) enrich each item with detail page data
 * 3) merge and persist to JSON without duplicates
 */
async function runScraper() {
  const delayMs = Number(process.env.REQUEST_DELAY_MS || 1200);

  const list = await scrapeNewsList();
  const detailedArticles = [];

  for (let index = 0; index < list.length; index += 1) {
    const item = list[index];

    try {
      const detail = await scrapeNewsDetail(item);
      detailedArticles.push(detail);
    } catch (error) {
      detailedArticles.push(item);
      // eslint-disable-next-line no-console
      console.error(
        `[SCRAPER] Failed detail scrape for ${item.url}: ${error.message}`,
      );
    }

    if (index < list.length - 1) {
      await sleep(delayMs);
    }
  }

  const existing = await readArticles();
  const merged = mergeUniqueArticles(existing, detailedArticles);
  await writeArticles(merged);

  return {
    fetched: list.length,
    stored: merged.length,
    addedOrUpdated: detailedArticles.length,
  };
}

module.exports = {
  runScraper,
};

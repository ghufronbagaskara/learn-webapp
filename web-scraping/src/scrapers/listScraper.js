const cheerio = require("cheerio");
const { v4: uuidv4 } = require("uuid");

const {
  cleanText,
  parseDate,
  requestWithRetry,
  safeGet,
  toAbsoluteUrl,
} = require("../utils/helpers");

function extractCategoryFromUrl(url) {
  if (!url) {
    return "Umum";
  }

  try {
    const parsed = new URL(url);
    const subdomain = parsed.hostname.split(".")[0];
    return cleanText(subdomain || "Umum");
  } catch (error) {
    return "Umum";
  }
}

/** Scrape article list from Kompas homepage-like pages. */
async function scrapeNewsList() {
  const baseUrl = process.env.BASE_URL || "https://www.kompas.com";
  const limit = Number(process.env.SCRAPE_ARTICLE_LIMIT || 12);

  const response = await requestWithRetry(baseUrl);
  const $ = cheerio.load(response.data);

  const seen = new Set();
  const articles = [];

  $("a").each((_, anchor) => {
    if (articles.length >= limit) {
      return;
    }

    const href = safeGet(() => $(anchor).attr("href"), "");
    const absoluteUrl = toAbsoluteUrl(baseUrl, href);

    if (!absoluteUrl || !absoluteUrl.includes("/read/")) {
      return;
    }

    if (!absoluteUrl.includes("kompas.com") || seen.has(absoluteUrl)) {
      return;
    }

    const card = $(anchor).closest(
      "article, .articleItem, .mostItem, .latest, li, div",
    );

    const title = cleanText(
      safeGet(
        () =>
          $(anchor).attr("title") ||
          $(anchor)
            .find("h1, h2, h3, .title, .article__title")
            .first()
            .text() ||
          $(anchor).text(),
        "",
      ),
    );

    if (!title || title.length < 8) {
      return;
    }

    const thumbnail = toAbsoluteUrl(
      baseUrl,
      cleanText(
        safeGet(
          () =>
            card.find("img").first().attr("src") ||
            card.find("img").first().attr("data-src") ||
            card.find("img").first().attr("data-original"),
          "",
        ),
      ),
    );

    const summary = cleanText(
      safeGet(
        () =>
          card
            .find("p, .article__lead, .description, .short-desc")
            .first()
            .text() || $(anchor).attr("aria-label"),
        "",
      ),
    );

    const publishedRaw = cleanText(
      safeGet(
        () =>
          card.find("time").first().attr("datetime") ||
          card.find("time").first().text() ||
          card.find(".date, .article__date").first().text(),
        "",
      ),
    );

    const item = {
      id: uuidv4(),
      title,
      url: absoluteUrl,
      category: extractCategoryFromUrl(absoluteUrl),
      thumbnail,
      summary,
      content: "",
      author: "",
      publishedAt: parseDate(publishedRaw),
      tags: [],
      commentCount: null,
      scrapedAt: new Date().toISOString(),
    };

    seen.add(absoluteUrl);
    articles.push(item);
  });

  return articles;
}

module.exports = {
  scrapeNewsList,
};

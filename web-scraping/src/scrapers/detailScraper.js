const cheerio = require("cheerio");

const {
  cleanText,
  parseDate,
  requestWithRetry,
  safeGet,
  toAbsoluteUrl,
} = require("../utils/helpers");

function parseCommentCount(raw) {
  if (!raw) {
    return null;
  }

  const match = raw.replace(/\./g, "").match(/\d+/);
  if (!match) {
    return null;
  }

  return Number(match[0]);
}

/** Scrape a single article page to enrich list data with full details. */
async function scrapeNewsDetail(article) {
  if (!article || !article.url) {
    return article;
  }

  const response = await requestWithRetry(article.url);
  const $ = cheerio.load(response.data);

  const content = cleanText(
    safeGet(() => {
      const paragraphs = $(
        ".read__content p, .article__body p, .content-text p, .content p",
      )
        .map((_, p) => cleanText($(p).text()))
        .get()
        .filter(Boolean);

      if (paragraphs.length > 0) {
        return paragraphs.join("\n\n");
      }

      return $('.read__content, .article__body, [itemprop="articleBody"]')
        .first()
        .text();
    }, ""),
  );

  const author = cleanText(
    safeGet(
      () =>
        $('[itemprop="author"]').first().text() ||
        $(".read__credit__item").first().text() ||
        $(".author, .writer").first().text(),
      "",
    ),
  );

  const publishedAt = parseDate(
    cleanText(
      safeGet(
        () =>
          $('meta[property="article:published_time"]').attr("content") ||
          $("time").first().attr("datetime") ||
          $("time").first().text(),
        "",
      ),
    ),
  );

  const tags = safeGet(
    () =>
      $('a.tag__item, .article__tag a, a[href*="/tag/"]')
        .map((_, tag) => cleanText($(tag).text()))
        .get()
        .filter(Boolean),
    [],
  );

  const commentText = cleanText(
    safeGet(
      () =>
        $(".comment__count").first().text() ||
        $('[class*="comment"]').first().text() ||
        "",
      "",
    ),
  );

  const category = cleanText(
    safeGet(
      () =>
        $(".breadcrumb a").eq(1).text() ||
        $(".article__subtitle").first().text() ||
        article.category,
      article.category,
    ),
  );

  return {
    ...article,
    thumbnail: toAbsoluteUrl(
      process.env.BASE_URL || "https://www.kompas.com",
      article.thumbnail,
    ),
    content: content || article.summary || "",
    author,
    category: category || article.category,
    tags: Array.from(new Set(tags)),
    commentCount: parseCommentCount(commentText),
    publishedAt: publishedAt || article.publishedAt || null,
    scrapedAt: new Date().toISOString(),
  };
}

module.exports = {
  scrapeNewsDetail,
};

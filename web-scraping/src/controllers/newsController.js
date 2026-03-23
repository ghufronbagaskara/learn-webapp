const { runScraper } = require("../scrapers");
const { paginate, readArticles } = require("../utils/helpers");

function includeKeyword(article, keyword) {
  const haystack = [
    article.title,
    article.summary,
    article.content,
    article.author,
    article.category,
    ...(Array.isArray(article.tags) ? article.tags : []),
  ]
    .join(" ")
    .toLowerCase();

  return haystack.includes(keyword.toLowerCase());
}

/** Get all news with optional pagination and keyword search via query string. */
async function getAllNews(req, res) {
  try {
    const { page = 1, perPage = 10, q = "" } = req.query;
    const articles = await readArticles();

    const filtered = q
      ? articles.filter((article) => includeKeyword(article, String(q)))
      : articles;

    const result = paginate(filtered, Number(page), Number(perPage));

    return res.json({
      articles: result.data,
      total: result.total,
      page: result.page,
      perPage: result.perPage,
      totalPages: result.totalPages,
    });
  } catch (error) {
    return res
      .status(500)
      .json({ message: "Failed to load articles", error: error.message });
  }
}

async function getNewsById(req, res) {
  try {
    const { id } = req.params;
    const articles = await readArticles();
    const article = articles.find((item) => item.id === id);

    if (!article) {
      return res.status(404).json({ message: "Article not found" });
    }

    return res.json(article);
  } catch (error) {
    return res
      .status(500)
      .json({ message: "Failed to load article", error: error.message });
  }
}

async function getNewsByCategory(req, res) {
  try {
    const { cat } = req.params;
    const { page = 1, perPage = 10 } = req.query;

    const articles = await readArticles();
    const filtered = articles.filter(
      (article) =>
        String(article.category || "").toLowerCase() ===
        String(cat || "").toLowerCase(),
    );

    const result = paginate(filtered, Number(page), Number(perPage));

    return res.json({
      articles: result.data,
      total: result.total,
      page: result.page,
      perPage: result.perPage,
      totalPages: result.totalPages,
      category: cat,
    });
  } catch (error) {
    return res
      .status(500)
      .json({ message: "Failed to filter by category", error: error.message });
  }
}

async function searchNews(req, res) {
  try {
    const { q = "", page = 1, perPage = 10 } = req.query;

    if (!q) {
      return res.status(400).json({ message: "Query parameter q is required" });
    }

    const articles = await readArticles();
    const filtered = articles.filter((article) =>
      includeKeyword(article, String(q)),
    );
    const result = paginate(filtered, Number(page), Number(perPage));

    return res.json({
      articles: result.data,
      total: result.total,
      page: result.page,
      perPage: result.perPage,
      totalPages: result.totalPages,
      query: q,
    });
  } catch (error) {
    return res
      .status(500)
      .json({ message: "Failed to search articles", error: error.message });
  }
}

/** Trigger scraping process manually from REST API. */
async function scrapeNews(req, res) {
  try {
    const result = await runScraper();
    return res.json({
      message: "Scraping completed",
      result,
    });
  } catch (error) {
    return res
      .status(500)
      .json({ message: "Scraping failed", error: error.message });
  }
}

module.exports = {
  getAllNews,
  getNewsByCategory,
  getNewsById,
  scrapeNews,
  searchNews,
};

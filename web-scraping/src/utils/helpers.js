const fs = require("fs/promises");
const path = require("path");
const axios = require("axios");

const DATA_FILE = path.resolve(__dirname, "../../data/articles.json");

const DEFAULT_HEADERS = {
  "User-Agent":
    "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36",
  Accept:
    "text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8",
  "Accept-Language": "id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7",
};

/** Delay helper to avoid aggressive scraping patterns. */
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}

/** Normalize spacing so API responses stay clean and consistent. */
function cleanText(value) {
  if (!value || typeof value !== "string") {
    return "";
  }

  return value.replace(/\s+/g, " ").trim();
}

function safeGet(fn, fallback = null) {
  try {
    const value = fn();
    if (value === undefined || value === null) {
      return fallback;
    }
    return value;
  } catch (error) {
    return fallback;
  }
}

function toAbsoluteUrl(baseUrl, maybeRelativeUrl) {
  if (!maybeRelativeUrl) {
    return "";
  }

  try {
    return new URL(maybeRelativeUrl, baseUrl).toString();
  } catch (error) {
    return maybeRelativeUrl;
  }
}

function parseDate(value) {
  if (!value) {
    return null;
  }

  const date = new Date(value);
  if (Number.isNaN(date.getTime())) {
    return null;
  }

  return date.toISOString();
}

/** Fetch with retries for transient errors like timeout or temporary 5xx responses. */
async function requestWithRetry(url, options = {}, retries = 2) {
  let lastError;

  for (let attempt = 0; attempt <= retries; attempt += 1) {
    try {
      const response = await axios.get(url, {
        timeout: 12000,
        headers: DEFAULT_HEADERS,
        ...options,
      });
      return response;
    } catch (error) {
      lastError = error;

      if (attempt < retries) {
        await sleep(700 * (attempt + 1));
      }
    }
  }

  throw lastError;
}

async function ensureDataFile() {
  try {
    await fs.access(DATA_FILE);
  } catch (error) {
    await fs.mkdir(path.dirname(DATA_FILE), { recursive: true });
    await fs.writeFile(
      DATA_FILE,
      JSON.stringify({ articles: [] }, null, 2),
      "utf-8",
    );
  }
}

async function readArticles() {
  await ensureDataFile();
  const raw = await fs.readFile(DATA_FILE, "utf-8");

  try {
    const parsed = JSON.parse(raw);
    if (!Array.isArray(parsed.articles)) {
      return [];
    }
    return parsed.articles;
  } catch (error) {
    return [];
  }
}

async function writeArticles(articles) {
  await ensureDataFile();
  await fs.writeFile(DATA_FILE, JSON.stringify({ articles }, null, 2), "utf-8");
}

/** Merge by URL so repeated scraping updates existing entries instead of duplicating them. */
function mergeUniqueArticles(existing, incoming) {
  const map = new Map();

  existing.forEach((article) => {
    if (article && article.url) {
      map.set(article.url, article);
    }
  });

  incoming.forEach((article) => {
    if (!article || !article.url) {
      return;
    }

    const previous = map.get(article.url) || {};
    map.set(article.url, {
      ...previous,
      ...article,
      id: previous.id || article.id,
    });
  });

  return Array.from(map.values()).sort((a, b) => {
    const aTime = new Date(a.publishedAt || a.scrapedAt || 0).getTime();
    const bTime = new Date(b.publishedAt || b.scrapedAt || 0).getTime();
    return bTime - aTime;
  });
}

function paginate(items, page = 1, perPage = 10) {
  const safePage = Math.max(Number(page) || 1, 1);
  const safePerPage = Math.max(Number(perPage) || 10, 1);
  const start = (safePage - 1) * safePerPage;
  const data = items.slice(start, start + safePerPage);

  return {
    data,
    total: items.length,
    page: safePage,
    perPage: safePerPage,
    totalPages: Math.max(Math.ceil(items.length / safePerPage), 1),
  };
}

module.exports = {
  cleanText,
  DATA_FILE,
  mergeUniqueArticles,
  paginate,
  parseDate,
  readArticles,
  requestWithRetry,
  safeGet,
  sleep,
  toAbsoluteUrl,
  writeArticles,
};

const dotenv = require("dotenv");
const express = require("express");
const cors = require("cors");
const cron = require("node-cron");

const newsRoutes = require("./src/routes/newsRoutes");
const { runScraper } = require("./src/scrapers");

dotenv.config();

const app = express();
const port = Number(process.env.PORT || 3000);

app.use(cors());
app.use(express.json());

app.get("/", (req, res) => {
  res.json({
    service: "news-scraper",
    status: "ok",
    message: "News scraper API is running.",
  });
});

app.use("/api/news", newsRoutes);

if (String(process.env.ENABLE_CRON).toLowerCase() === "true") {
  const expression = process.env.CRON_EXPRESSION || "*/30 * * * *";
  cron.schedule(expression, async () => {
    try {
      await runScraper();
      // eslint-disable-next-line no-console
      console.log("[CRON] Scraping completed successfully");
    } catch (error) {
      // eslint-disable-next-line no-console
      console.error("[CRON] Scraping failed:", error.message);
    }
  });

  // eslint-disable-next-line no-console
  console.log(`[CRON] Scheduled with expression: ${expression}`);
}

app.listen(port, () => {
  // eslint-disable-next-line no-console
  console.log(`Server running at http://localhost:${port}`);
});

const express = require("express");

const {
  getAllNews,
  getNewsByCategory,
  getNewsById,
  scrapeNews,
  searchNews,
} = require("../controllers/newsController");

const router = express.Router();

router.get("/", getAllNews);
router.get("/search", searchNews);
router.get("/category/:cat", getNewsByCategory);
router.get("/:id", getNewsById);
router.post("/scrape", scrapeNews);

module.exports = router;

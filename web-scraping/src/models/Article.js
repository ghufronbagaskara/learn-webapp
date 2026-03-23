const mongoose = require("mongoose");

const articleSchema = new mongoose.Schema(
  {
    id: { type: String, required: true, unique: true },
    title: { type: String, required: true },
    url: { type: String, required: true, unique: true },
    category: { type: String, default: "Umum" },
    thumbnail: { type: String, default: "" },
    summary: { type: String, default: "" },
    content: { type: String, default: "" },
    author: { type: String, default: "" },
    publishedAt: { type: Date, default: null },
    tags: [{ type: String }],
    commentCount: { type: Number, default: null },
    scrapedAt: { type: Date, default: Date.now },
  },
  {
    timestamps: true,
  },
);

module.exports = mongoose.model("Article", articleSchema);

# News Scraper API (Bootcamp Day 33)

Backend service untuk scraping berita portal publik menggunakan Node.js, axios, cheerio, dan Express.

## Fitur

- Scraping daftar berita dari halaman utama portal (`BASE_URL`)
- Scraping detail artikel: konten, author, tag, kategori, komentar (jika tersedia)
- Retry mechanism saat request gagal
- Delay antar request untuk rate limiting sederhana
- Simpan hasil scraping ke file JSON (`data/articles.json`)
- REST API dengan pagination, filter kategori, search keyword, dan trigger scraping manual
- Optional scheduler (`node-cron`) untuk scraping otomatis berkala

## Target Portal

Default menggunakan: `https://www.kompas.com`

Portal bisa diganti lewat `.env` dengan mengubah `BASE_URL`.

## Struktur Folder

```text
news-scraper/
├── src/
│   ├── scrapers/
│   │   ├── index.js
│   │   ├── listScraper.js
│   │   └── detailScraper.js
│   ├── models/
│   │   └── Article.js
│   ├── routes/
│   │   └── newsRoutes.js
│   ├── controllers/
│   │   └── newsController.js
│   └── utils/
│       └── helpers.js
├── data/
│   └── articles.json
├── .env
├── .env.example
├── package.json
└── index.js
```

## Instalasi

```bash
npm install
```

## Menjalankan Aplikasi

Mode production:

```bash
npm start
```

Mode development:

```bash
npm run dev
```

Server berjalan di:

- `http://localhost:3000` (default)

## Environment Variables

Contoh `.env`:

```env
PORT=3000
BASE_URL=https://www.kompas.com
REQUEST_DELAY_MS=1200
SCRAPE_ARTICLE_LIMIT=12
ENABLE_CRON=false
CRON_EXPRESSION=*/30 * * * *
MONGO_URI=
```

Keterangan:

- `PORT`: port server
- `BASE_URL`: URL portal berita utama untuk list scraping
- `REQUEST_DELAY_MS`: delay antar detail request (ms)
- `SCRAPE_ARTICLE_LIMIT`: batas jumlah artikel per scraping
- `ENABLE_CRON`: aktif/nonaktif scraping terjadwal
- `CRON_EXPRESSION`: jadwal cron jika diaktifkan
- `MONGO_URI`: disiapkan jika ingin migrasi ke MongoDB (opsional)

## API Endpoints

### 1) Ambil semua berita (pagination + optional search)

```http
GET /api/news?page=1&perPage=10&q=teknologi
```

### 2) Ambil detail satu berita

```http
GET /api/news/:id
```

### 3) Filter berita berdasarkan kategori

```http
GET /api/news/category/:cat?page=1&perPage=10
```

### 4) Trigger scraping manual

```http
POST /api/news/scrape
```

### 5) Search berita berdasarkan keyword

```http
GET /api/news/search?q=ai&page=1&perPage=10
```

## Contoh Response

```json
{
  "articles": [
    {
      "id": "uuid-xxx",
      "title": "Judul Berita Contoh",
      "url": "https://news.portal.com/artikel/123",
      "category": "Teknologi",
      "thumbnail": "https://img.portal.com/thumb.jpg",
      "summary": "Ringkasan singkat berita...",
      "content": "Isi lengkap artikel berita...",
      "author": "Nama Penulis",
      "publishedAt": "2024-01-15T08:00:00Z",
      "tags": ["teknologi", "AI", "indonesia"],
      "commentCount": 0,
      "scrapedAt": "2024-01-15T09:00:00Z"
    }
  ],
  "total": 100,
  "page": 1,
  "perPage": 10,
  "totalPages": 10
}
```

## Catatan Penting

- Scraping HTML bersifat rentan terhadap perubahan selector dari situs target.
- Implementasi ini memakai fallback selector + `safeGet` agar satu field gagal tidak menggagalkan seluruh scraping.
- Data anti-duplicate berdasarkan URL artikel saat merge ke file JSON.
- File `src/models/Article.js` disiapkan untuk skenario migrasi ke MongoDB, meski penyimpanan default saat ini ke JSON.

# Online Course Chatbot — Spirit Online Course Platform

A Laravel-based Online Course Platform featuring "SpiritBot", an AI Study Assistant powered by Groq API (`llama-3.3-70b-versatile`).

## Features
- **Manual Authentication:** Simple login/logout system.
- **Personalized Dashboard:** Track course progress and stats.
- **AI Study Assistant (SpiritBot):** Chatbot that knows your profile, enrolled courses, and learning progress.
- **Responsive UI:** Built with Tailwind CSS and FontAwesome.

## Tech Stack
- **Backend:** Laravel 13
- **Frontend:** Blade + Tailwind CSS (via CDN)
- **Database:** MySQL
- **AI Engine:** Groq API (Model: `llama-3.3-70b-versatile`)

## Setup Instructions

1. **Clone the repository** (or copy the files).
2. **Install dependencies:**
   ```bash
   composer install
   ```
3. **Configure Environment:**
   - Copy `.env.example` to `.env` (if not exists).
   - Set your database credentials (MySQL).
   - **IMPORTANT:** Add your Groq API Key:
     ```
     GROQ_API_KEY=your_actual_key_here
     ```
4. **Setup Database:**
   - Ensure the database specified in `.env` exists.
   - Run migrations and seed data:
     ```bash
     php artisan migrate:fresh --seed
     ```
   - **Note:** If you encounter `sqlite` driver errors despite having `mysql` configured, ensure you don't have a system environment variable `DB_CONNECTION` overriding your `.env`. You can run:
     ```powershell
     $env:DB_CONNECTION="mysql"; php artisan migrate:fresh --seed
     ```
5. **Serve the application:**
   ```bash
   php artisan serve
   ```
6. **Access the application:**
   - Open `http://localhost:8000`
   - Use one of the demo accounts:
     - Email: `andi@example.com`
     - Password: `password`

## Demo Accounts
| Name | Email | Password | Role |
|------|-------|----------|------|
| Andi Pratama | andi@example.com | password | student |
| Sari Dewi | sari@example.com | password | student |
| Admin Spirit | admin@example.com | password | admin |

## AI Configuration
The chatbot logic is located in `app/Http/Controllers/ChatController.php`. It uses the `Http` facade to communicate with Groq API.
The configuration can be found in `config/services.php`.

# Laravel Xendit Integration

A Laravel application demonstrating how to integrate Xendit payment gateway (Invoices).

## Features
- Create invoices using Xendit API.
- Automatic status updates via Webhooks.
- Client-side status sync on success/failure redirect (Fallback).
- Basic Order and Payment management.

## Setup

1. **Install Dependencies**:
   ```bash
   composer install
   npm install && npm run build
   ```

2. **Environment Configuration**:
   Copy `.env.example` to `.env` and add your Xendit credentials:
   ```env
   XENDIT_SECRET_KEY=xnd_development_...
   XENDIT_PUBLIC_KEY=xnd_public_development_...
   XENDIT_CALLBACK_TOKEN=your_callback_token_from_xendit_dashboard
   ```

3. **Database Setup**:
   ```bash
   php artisan migrate
   ```

## Local Development & Webhook Testing

Xendit needs a public URL to send webhook notifications. If you are developing on `localhost`, follow these steps:

### 1. Using Ngrok
1. Start your local Laravel server:
   ```bash
   php artisan serve
   ```
2. Start ngrok (assuming your local server is on port 8000):
   ```bash
   ngrok http 8000
   ```
3. Copy the **Forwarding URL** provided by ngrok (e.g., `https://a1b2-c3d4.ngrok-free.app`).

### 2. Configure Xendit Dashboard
1. Go to the [Xendit Dashboard](https://dashboard.xendit.co/settings/developers#callbacks).
2. Set the **Invoices Paid** callback URL to:
   `https://your-ngrok-url.ngrok-free.app/api/webhook/xendit`
3. Click "Test and Save".

### 3. Payment Flow
1. Create an Order from the dashboard.
2. Click "Pay Now". You will be redirected to the Xendit Invoice page.
3. **Simulation**: On the Xendit test invoice page, click "Simulate Payment".
4. After payment, click "Back to Merchant".
5. You will land on the Success Page, which will automatically sync the status even if the webhook is slightly delayed.

## Troubleshooting
- **404 on Success Page**: If you see a 404 when returning from Xendit, ensure your routes in `web.php` are correctly ordered (static routes must come before parameterized routes).
- **Status remains PENDING**: 
    - Ensure your Ngrok tunnel is active.
    - Check `storage/logs/laravel.log` for "Xendit webhook received" entries.
    - Verify your `XENDIT_CALLBACK_TOKEN` matches what is in your Xendit Dashboard.

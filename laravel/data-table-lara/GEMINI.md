# Project Context: data-table-lara

This project is a modern Laravel application built with **Laravel 12**, **Inertia.js (React)**, and **Laravel Fortify** for authentication. It follows a robust, type-safe architecture using **TypeScript** and **Tailwind CSS 4**.

## Core Tech Stack

### Backend
- **Framework:** Laravel 12.0 (PHP 8.2+)
- **Authentication:** [Laravel Fortify](https://laravel.com/docs/fortify) (Headless auth backend)
- **Adapter:** [Inertia.js 2.0](https://inertiajs.com/) (Connects Laravel and React)
- **Database:** Standard Laravel migrations (Users, Cache, Jobs, Two-Factor Auth)
- **Routing:** Handled via `routes/web.php` and `routes/settings.php`

### Frontend
- **Library:** React 19
- **Build Tool:** Vite 7.0
- **Styling:** Tailwind CSS 4.0
- **Language:** TypeScript
- **UI Components:** Radix UI primitives, Lucide React icons (shadcn/ui-style implementation)
- **State Management:** Inertia.js (Server-driven state)

## Authentication Features (Powered by Fortify)
The project comes with a pre-configured authentication system located in `app/Actions/Fortify` and `resources/js/pages/auth`:
- **Registration & Login:** Standard email/password flows.
- **Two-Factor Authentication (2FA):** Includes setup, recovery codes, and challenge pages.
- **Password Management:** Reset, update, and confirmation.
- **Email Verification:** Built-in verification logic.
- **Profile Management:** Profile updates and account deletion.

## Project Structure Highlights
- `app/Http/Controllers/Settings`: Logic for profile, password, and 2FA updates.
- `resources/js/components`: Shared UI components (UI primitives, layouts, and navigation).
- `resources/js/layouts`: 
  - `app-layout.tsx`: Main authenticated layout with sidebar.
  - `auth-layout.tsx`: Layout for login/registration pages.
- `resources/js/pages`: Inertia page components for Dashboard, Welcome, Auth, and Settings.
- `resources/js/types`: Global and domain-specific TypeScript definitions.

## Key Development Commands
- `npm run dev`: Start Vite development server.
- `php artisan serve`: Start Laravel development server.
- `npm run lint`: Run ESLint and Prettier.
- `php artisan test`: Run PHPUnit tests.
- `npm run types:check`: Run TypeScript type checking.

## Design System
The project uses a custom UI system located in `resources/js/components/ui`, utilizing Radix UI for accessibility and Tailwind CSS for styling. Components are built using the `cva` (class-variance-authority) pattern for consistent variants.

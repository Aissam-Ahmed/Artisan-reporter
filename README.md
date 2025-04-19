# ArtisanReporter

A web-based Artisan command runner and CSV report generator built with Laravel.

![Laravel](https://img.shields.io/badge/Laravel-11.x-red)
![License](https://img.shields.io/badge/license-MIT-green)

## 🚀 Features

- 📦 **CSV Report Generator** – Generate and download reports from any database table, filtered by date.
- 🔐 **Secure Command Execution** – Uses Symfony Process to safely execute commands.

## 📸 Preview

![preview](public/preview.png) <!-- Add your screenshot here -->

## 🛠 Installation

1. Clone the repository & Install PHP:

```bash
git clone https://github.com/Aissam-Ahmed/Artisan-reporter.git
cd Artisan-reporter
composer install
```

3. Configure your environment:

```bash
cp .env.example .env
php artisan key:generate
```

4. Configure your database in `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=artisan_reporter
DB_USERNAME=root
DB_PASSWORD=
```

5. Run migrations and seed users:

```bash
php artisan migrate --seed
```

6. Run the server:

```bash
php artisan serve
```

7. Visit `http://localhost:8000/`

## ✨ Usage

- Type any safe shell or Artisan command in the web terminal (e.g. `php artisan list`).
- Generate reports using custom Artisan commands like:
```bash
php artisan report:generate users
```

```bash
php artisan report:generate users --from=2024-01-01 --to=2024-12-31
```

- The resulting CSV file will be downloadable via browser.

## ⚠️ Security Notes

- This tool is for development/demo purposes.
- Always validate and sanitize commands on production environments.
- Disable dangerous commands or use command whitelisting.

## 📁 Project Structure

```
├── app/Console/Commands
│   └── GenerateReportCommand.php
├── routes/web.php
└── public/preview.png
```

## 📖 Philosophy

> This project bridges CLI power and web simplicity. ArtisanReporter empowers Laravel developers to run commands, generate reports, and learn Artisan features from the comfort of a browser tab.

## 📝 License

This project is open-sourced under the [MIT license](LICENSE).


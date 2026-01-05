# Laravel Starter Kit

A modern, production-ready Laravel starter kit featuring React, Inertia.js, Filament, and TypeScript. This template provides a solid foundation for building full-stack applications with a beautiful admin panel and a modern frontend.

## 🚀 Features

- **Laravel 12** - The latest version of the Laravel framework
- **React 19** - Modern React with TypeScript support
- **Inertia.js** - Build single-page applications without the complexity
- **Filament 4** - Beautiful admin panel with full CRUD capabilities
- **TypeScript** - Type-safe JavaScript for better developer experience
- **Tailwind CSS 4** - Utility-first CSS framework
- **Vite** - Next-generation frontend tooling
- **Server-Side Rendering (SSR)** - Enhanced performance and SEO
- **Internationalization (i18n)** - Multi-language support with language switching
- **Laravel Wayfinder** - Type-safe route helpers for React
- **Testing** - Pest PHP testing framework configured
- **Code Quality** - ESLint, Prettier, PHPStan, Laravel Pint, and Rector

## 📋 Requirements

- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm
- SQLite, MySQL, or PostgreSQL

## 🛠️ Installation

### Quick Start

1. **Clone the repository:**
   ```bash
   git clone <repository-url> laravel-starter-kit
   cd laravel-starter-kit
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Install Node dependencies:**
   ```bash
   npm install
   ```

4. **Set up environment:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure your database** in `.env`:
   ```env
   DB_CONNECTION=sqlite
   # Or use MySQL/PostgreSQL
   ```

6. **Run migrations:**
   ```bash
   php artisan migrate
   ```

7. **Build assets:**
   ```bash
   npm run build
   ```

8. **Start the development server:**
   ```bash
   php artisan serve
   ```

   In another terminal, start Vite:
   ```bash
   npm run dev
   ```

### Using Composer Setup Script

Alternatively, you can use the provided setup script:

```bash
composer run setup
```

This will automatically:
- Install Composer dependencies
- Copy `.env.example` to `.env` if it doesn't exist
- Generate application key
- Run migrations
- Install npm dependencies
- Build assets

## 🎯 Usage

### Development

Start the Laravel development server and Vite dev server:

```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite dev server
npm run dev
```

Visit `http://localhost:8000` in your browser.

### Administration Panel

Access the Filament administration panel at:
```
http://localhost:8000/administration
```

### Building for Production

```bash
npm run build
```

For SSR builds:
```bash
npm run build:ssr
```

## 📁 Project Structure

```
laravel-starter-kit/
├── app/
│   ├── Administration/          # Filament admin panel resources
│   │   ├── Clusters/            # Filament clusters
│   │   ├── Livewire/            # Livewire components
│   │   ├── Pages/               # Admin pages
│   │   ├── Resources/           # Filament resources
│   │   ├── Widgets/             # Admin widgets
│   │   └── Providers/           # Panel providers
│   └── Core/                    # Core application logic
├── resources/
│   ├── js/
│   │   ├── pages/               # Inertia React pages
│   │   ├── routes/              # Route definitions
│   │   ├── actions/             # Action handlers
│   │   ├── wayfinder/           # Wayfinder route helpers
│   │   ├── types/               # TypeScript type definitions
│   │   ├── app.tsx              # Main React app entry
│   │   ├── ssr.tsx              # SSR entry point
│   │   └── wrapper.tsx          # Inertia wrapper
│   └── css/
│       ├── app.css              # Main stylesheet
│       └── administration/      # Admin panel styles
├── routes/
│   └── web.php                  # Web routes
└── tests/                       # Pest tests
```

## 🧪 Testing

This starter kit uses [Pest PHP](https://pestphp.com) for testing.

Run tests:
```bash
php artisan test
```

Or use Pest directly:
```bash
./vendor/bin/pest
```

## 🎨 Code Quality

### PHP

- **Laravel Pint** - Code style fixer
  ```bash
  composer run pint
  ```

- **PHPStan** - Static analysis
  ```bash
  composer run types
  ```

- **Rector** - Automated refactoring
  ```bash
  composer run rector
  ```

### JavaScript/TypeScript

- **ESLint** - Linting
  ```bash
  npm run lint
  ```

- **Prettier** - Code formatting
  ```bash
  npm run format
  ```

- **TypeScript** - Type checking
  ```bash
  npm run types
  ```

### Run All Checks

```bash
composer run test
```

This runs:
- Code formatting (PHP & JS)
- Linting (JS)
- Type checking (PHP & TS)
- Build verification
- Tests

## 🌍 Internationalization

The starter kit includes multi-language support using `laravel-lang/common` and `laravel-react-i18n`.

- Language files are located in `lang/`
- Language switching is available in the Filament admin panel
- React components can use the i18n hooks for translations

## 🔧 Configuration

### Vite Configuration

The Vite configuration (`vite.config.ts`) includes:
- React plugin with React Compiler
- Tailwind CSS plugin
- Laravel Vite plugin
- Wayfinder plugin for route helpers
- i18n plugin for translations

### Filament Configuration

Filament is configured in `app/Administration/Providers/AdministrationPanelProvider.php`:
- Panel ID: `administration`
- Path: `/administration`
- SPA mode enabled
- Database notifications
- Language switching plugin

## 📦 Key Packages

### Backend
- `laravel/framework` ^12.0
- `filament/filament` ^4.4
- `inertiajs/inertia-laravel` ^2.0
- `laravel/wayfinder` ^0.1.11
- `laravel-lang/common` ^6.7
- `bezhansalleh/filament-language-switch` ^4.0

### Frontend
- `react` ^19.2.0
- `@inertiajs/react` ^2.1.0
- `typescript` ^5.7.2
- `tailwindcss` ^4.1.18
- `laravel-react-i18n` ^2.0.5
- `@laravel/vite-plugin-wayfinder` ^0.1.3

### Development
- `pestphp/pest` ^4.3
- `larastan/larastan` ^3.8
- `laravel/pint` ^1.24
- `rector/rector` ^2.3
- `laravel/boost` ^1.8

## 🚢 Deployment

1. **Set up your production environment variables** in `.env`

2. **Install dependencies:**
   ```bash
   composer install --optimize-autoloader --no-dev
   npm ci
   ```

3. **Build assets:**
   ```bash
   npm run build
   ```

4. **Optimize Laravel:**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

5. **Run migrations:**
   ```bash
   php artisan migrate --force
   ```

## 📝 License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 🙏 Acknowledgments

- [Laravel](https://laravel.com)
- [Filament](https://filamentphp.com)
- [Inertia.js](https://inertiajs.com)
- [React](https://react.dev)
- [Tailwind CSS](https://tailwindcss.com)

---

Built with ❤️ using Laravel and React


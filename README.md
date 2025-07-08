# News Aggregator Backend

A Laravel-based backend application that aggregates news articles from multiple sources and provides a RESTful API to access them.

## Features

- Aggregates news articles from multiple sources:
  - The Guardian
  - The New York Times
  - NewsAPI
- Scheduled news import that runs twice a day (8:00 AM and 8:00 PM)
- RESTful API for accessing and filtering news articles
- Search functionality for finding articles by keyword
- User preference-based article filtering
- Metadata endpoints for categories, sources, and authors

## API Endpoints

### Articles

- `GET /api/` - Get all articles with optional filtering
- `GET /api/search` - Search articles by keyword
- `GET /api/preferences` - Get articles based on user preferences

### Metadata

- `GET /api/categories` - Get all available categories
- `GET /api/sources` - Get all available sources
- `GET /api/authors` - Get all available authors

## Commands

The application includes a command to import news from all configured sources:

```bash
php artisan import:news
```

This command is scheduled to run automatically twice a day (at 8:00 AM and 8:00 PM).

## Installation

1. Clone the repository
2. Install dependencies:
   ```bash
   composer install
   ```
3. Copy the `.env.example` file to `.env` and configure your environment variables
4. Generate an application key:
   ```bash
   php artisan key:generate
   ```
5. Run database migrations:
   ```bash
   php artisan migrate
   ```
6. Start the development server:
   ```bash
   php artisan serve
   ```

## Architecture

The application follows a feature-based architecture:

- **Services**: Handle the core business logic, including fetching articles from external sources
- **Features**: Encapsulate specific use cases or operations
- **Controllers**: Handle HTTP requests and responses
- **Models**: Represent database entities

## NOTE
The console command is fetching the data for the past 1 day and current day.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

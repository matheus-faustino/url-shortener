# URL Shortener API

A simple URL shortening service built with PHP and MySQL, containerized with Docker.

## Features

- Shorten long URLs
- Track click statistics
- Copy-to-clipboard functionality

## Installation

```bash
# Clone repository
git clone https://github.com/yourusername/url-shortener.git
cd url-shortener

# Start Docker containers
docker-compose up -d
```

Access the application at: http://localhost:8000

## API Endpoints

### Create Short URL
- **Endpoint**: `/api/create.php`
- **Method**: POST
- **Content-Type**: application/json
- **Request Body**:
  ```json
  {
    "url": "https://example.com/very/long/url"
  }
  ```
- **Response**:
  ```json
  {
    "message": "URL shortened with success",
    "shortUrl": "http://localhost:8080/api/redirect.php/Ab3X9z",
    "shortCode": "Ab3X9z"
  }
  ```

### Redirect to Original URL
- **Endpoint**: `/api/redirect.php/{shortCode}`
- **Method**: GET
- **Response**: 302 Redirect to original URL

### Get URL Statistics
- **Endpoint**: `/api/stats.php/{shortCode}`
- **Method**: GET
- **Response**:
  ```json
  {
    "original_url": "https://example.com/very/long/url",
    "short_code": "Ab3X9z",
    "created_at": "2025-05-03 14:30:45",
    "clicks": 42
  }
  ```

## Example Usage

```php
// Create short URL
$ch = curl_init('http://localhost:8080/api/create.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['url' => 'https://example.com']));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

$response = curl_exec($ch);
$result = json_decode($response, true);
echo "Short URL: " . $result['shortUrl'] . "\n";
```

## Tech Stack
- PHP 8.4
- MySQL
- Docker
- Tailwind CSS
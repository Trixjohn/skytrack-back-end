# Skytrack Weather Application - System Architecture

## High-Level System Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                          SKYTRACK WEATHER APPLICATION                       │
└─────────────────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────────────────┐
│                         CLIENT LAYER (Presentation)                          │
├──────────────────────────────────────────────────────────────────────────────┤
│                                                                               │
│  ┌─────────────────────────────┐        ┌──────────────────────────────┐   │
│  │   Flutter Mobile App         │        │   Web Browser (Optional)     │   │
│  │  ─────────────────────────  │        │  ──────────────────────────  │   │
│  │  • User Login/Register       │        │  • Web Dashboard             │   │
│  │  • Weather Search            │        │  • API Testing               │   │
│  │  • View Weather Details      │        │  • Account Management        │   │
│  │  • Weather History           │        │                              │   │
│  │  • Location-based Weather    │        │                              │   │
│  └──────────────┬──────────────┘        └──────────────┬───────────────┘   │
│                 │                                       │                    │
│                 └─────────────────┬────────────────────┘                    │
│                                   │                                         │
│                          HTTPS REST API Calls                               │
│                                   │                                         │
└──────────────────────────────────┼────────────────────────────────────────┘
                                   │
┌──────────────────────────────────┼────────────────────────────────────────┐
│                    API GATEWAY & SERVER LAYER                            │
├──────────────────────────────────┼────────────────────────────────────────┤
│                                   ▼                                        │
│  ┌─────────────────────────────────────────────────────────────────────┐  │
│  │              Laravel 12 Application Server                          │  │
│  │  ┌──────────────┬──────────────┬──────────────┬──────────────────┐ │  │
│  │  │   Routing    │  Middleware  │  Validation  │  Error Handling  │ │  │
│  │  │              │              │              │                  │ │  │
│  │  │ routes/      │ CORS         │ Request      │ Exception        │ │  │
│  │  │ api.php      │ Auth Check   │ Validation   │ Handler          │ │  │
│  │  └──────────────┴──────────────┴──────────────┴──────────────────┘ │  │
│  │                                                                     │  │
│  │  ┌─────────────────────────────────────────────────────────────┐  │  │
│  │  │              HTTP Controllers                              │  │  │
│  │  ├──────────────────────────┬──────────────────────────────────┤  │  │
│  │  │  AuthController          │   WeatherController              │  │  │
│  │  ├──────────────────────────┼──────────────────────────────────┤  │  │
│  │  │ + register()             │  + getWeather($city)             │  │  │
│  │  │ + login()                │  + getAllWeather()               │  │  │
│  │  │ + forgotPassword()       │  + addWeather()                  │  │  │
│  │  │ + validateCredentials()  │  + updateWeather()               │  │  │
│  │  │                          │  + deleteWeather()               │  │  │
│  │  │                          │  + logWeatherData()              │  │  │
│  │  └──────────────────────────┴──────────────────────────────────┘  │  │
│  │                                                                     │  │
│  │  ┌─────────────────────────────────────────────────────────────┐  │  │
│  │  │              Business Logic Layer (Models)                  │  │  │
│  │  ├──────────────────────────┬──────────────────────────────────┤  │  │
│  │  │  User Model              │   WeatherLog Model               │  │  │
│  │  ├──────────────────────────┼──────────────────────────────────┤  │  │
│  │  │ + id                     │  + id                            │  │  │
│  │  │ + name                   │  + city                          │  │  │
│  │  │ + email                  │  + temperature                   │  │  │
│  │  │ + password               │  + condition                     │  │  │
│  │  │ + timestamps             │  + created_at/updated_at         │  │  │
│  │  │ + relationships          │  + relationships                 │  │  │
│  │  └──────────────────────────┴──────────────────────────────────┘  │  │
│  │                                   │                                │  │
│  └───────────────────────────────────┼────────────────────────────────┘  │
│                                       │                                   │
│                         Data Access Layer (ORM - Eloquent)                │
│                                       │                                   │
└───────────────────────────────────────┼───────────────────────────────────┘
                                        │
        ┌───────────────────────────────┼───────────────────────────────┐
        │                               │                               │
┌───────▼───────────┐          ┌────────▼──────────┐      ┌────────────▼──────┐
│   DATABASE LAYER  │          │ EXTERNAL API      │      │  CACHE LAYER      │
├───────────────────┤          ├──────────────────┤      ├───────────────────┤
│   SQLite/MySQL    │          │ OpenWeatherMap   │      │  Redis (Optional) │
│   Database        │          │ API              │      │                   │
│                   │          │                  │      │ • Session Cache   │
│ ┌─────────────────┤          │ ┌────────────────┤      │ • Query Cache     │
│ │ users           │          │ │ GET Request    │      │ • Weather Data    │
│ │ • id (PK)       │          │ │ • Query: city  │      │   (TTL: 10 min)   │
│ │ • name          │          │ │ • appid        │      │                   │
│ │ • email (UNIQUE)│          │ │ • units=metric │      │ Reduces API calls │
│ │ • password      │          │ │                │      │ & improves perf   │
│ │ • timestamps    │          │ │ Response:      │      │                   │
│ │                 │          │ │ • temperature  │      └───────────────────┘
│ ├─────────────────┤          │ │ • weather[]    │
│ │ weather_logs    │          │ │ • humidity     │
│ │ • id (PK)       │          │ │ • wind speed   │
│ │ • city          │          │ │ • timestamp    │
│ │ • temperature   │          │ │                │
│ │ • condition     │          │ └────────────────┘
│ │ • timestamps    │          │
│ │ • user_id (FK)  │          │ HTTPS Connection
│ │                 │          │ https://api.openweathermap.org
│ ├─────────────────┤          │ /data/2.5/weather
│ │ password_reset_ │          │
│ │ tokens          │          └──────────────────┘
│ │                 │
│ └─────────────────┘
│
│ Connections:
│ • TCP/IP (Local)
│ • Connection Pooling
│ • Query Optimization
│
└───────────────────┘
```

---

## Component Interaction Flow

### 1. User Registration Flow

```
   Flutter App                 Laravel Backend              Database
        │                            │                          │
        │  POST /register            │                          │
        ├───────────────────────────>│                          │
        │  {name, email, password}   │                          │
        │                            │ Validate Input           │
        │                            │ Hash Password            │
        │                            │                          │
        │                            │ INSERT user              │
        │                            ├─────────────────────────>│
        │                            │                          │ Create Record
        │                            │<─────────────────────────┤
        │  201 Created               │                          │
        │  {user object}             │                          │
        │<───────────────────────────┤                          │
        │                            │                          │
```

### 2. User Login Flow

```
   Flutter App                 Laravel Backend              Database
        │                            │                          │
        │  POST /login               │                          │
        ├───────────────────────────>│                          │
        │  {email, password}         │                          │
        │                            │ Query User               │
        │                            ├─────────────────────────>│
        │                            │<─────────────────────────┤
        │                            │ Verify Password          │
        │                            │ (bcrypt comparison)      │
        │  200 OK                    │                          │
        │  {user data}               │                          │
        │<───────────────────────────┤                          │
        │                            │                          │
```

### 3. Weather Query Flow (with External API)

```
   Flutter App          Laravel Backend          OpenWeatherMap API       Database
        │                      │                           │                  │
        │ GET /weather/London  │                           │                  │
        ├─────────────────────>│                           │                  │
        │                      │ Check Cache               │                  │
        │                      │ (if TTL valid)            │                  │
        │                      │                           │                  │
        │                      │ Call External API         │                  │
        │                      ├──────────────────────────>│                  │
        │                      │                           │ Fetch Data       │
        │                      │<──────────────────────────┤                  │
        │                      │ Parse Response            │                  │
        │                      │ (temp, condition, etc.)   │                  │
        │                      │                           │                  │
        │                      │ Log Data                  │                  │
        │                      ├──────────────────────────────────────────────>│
        │                      │                           │                  │ INSERT
        │                      │<──────────────────────────────────────────────┤
        │  200 OK              │                           │                  │
        │  {weather data}      │                           │                  │
        │<─────────────────────┤                           │                  │
        │                      │                           │                  │
```

### 4. Weather History Retrieval Flow

```
   Flutter App                 Laravel Backend              Database
        │                            │                          │
        │  GET /weather              │                          │
        ├───────────────────────────>│                          │
        │                            │ Query Weather Logs       │
        │                            ├─────────────────────────>│
        │                            │<─────────────────────────┤
        │                            │ SELECT * FROM            │
        │  200 OK                    │ weather_logs             │
        │  [{log1}, {log2}, ...]     │                          │
        │<───────────────────────────┤                          │
        │                            │                          │
```

---

## Database Schema Relationship Diagram

```
┌──────────────────────────────────────────┐
│             USERS TABLE                   │
├──────────────────────────────────────────┤
│ PK: id (INT)                              │
│ name (VARCHAR)                            │
│ email (VARCHAR) - UNIQUE                  │
│ password (VARCHAR)                        │
│ email_verified_at (TIMESTAMP) - NULLABLE  │
│ created_at (TIMESTAMP)                    │
│ updated_at (TIMESTAMP)                    │
└────────┬─────────────────────────────────┘
         │ One-to-Many Relationship
         │ (Users can have multiple logs)
         │
┌────────▼─────────────────────────────────┐
│        WEATHER_LOGS TABLE                 │
├─────────────────────────────────────────┤
│ PK: id (INT)                              │
│ user_id (INT) - FK (NULLABLE)             │
│ city (VARCHAR)                            │
│ temperature (FLOAT)                       │
│ condition (VARCHAR)                       │
│ created_at (TIMESTAMP)                    │
│ updated_at (TIMESTAMP)                    │
└──────────────────────────────────────────┘
```

---

## Technology Stack

### Backend
- **Framework:** Laravel 12
- **Language:** PHP 8.2+
- **Server:** Apache/Nginx (via Laravel Sail or custom server)
- **API Style:** RESTful

### Database
- **Primary DB:** SQLite (Development) / MySQL (Production)
- **ORM:** Eloquent
- **Migrations:** Laravel Migrations

### External Services
- **Weather Data:** OpenWeatherMap API
- **Email:** SMTP (for password reset - optional)

### Frontend
- **Mobile:** Flutter (Dart)
- **HTTP Client:** http/dio package
- **State Management:** Provider/Riverpod (recommended)

### Development Tools
- **Package Manager:** Composer, npm
- **Build Tool:** Vite
- **Version Control:** Git

---

## API Endpoints Overview

```
Authentication Endpoints (3)
├── POST /register ..................... Create new user
├── POST /login ........................ Authenticate user
└── POST /forgot-password .............. Request password reset

Weather Endpoints (5)
├── GET  /weather/{city} ............... Get weather for city
├── GET  /weather ...................... Get all weather logs
├── POST /weather ...................... Add weather log
├── PUT  /weather/{id} ................. Update weather log
└── DELETE /weather/{id} ............... Delete weather log

Total: 8 Endpoints (✓ Exceeds 5+ requirement)
```

---

## Security Considerations

```
┌─────────────────────────────────────────────────────────────┐
│                   SECURITY LAYERS                            │
├─────────────────────────────────────────────────────────────┤
│                                                               │
│  1. HTTPS/TLS Encryption                                     │
│     └─ All data in transit encrypted                         │
│                                                               │
│  2. Password Security                                        │
│     └─ Bcrypt hashing algorithm                              │
│     └─ Min 6 characters (recommended: 12+)                   │
│                                                               │
│  3. Input Validation                                         │
│     └─ Email format validation                               │
│     └─ Password strength requirements                        │
│     └─ Unique email constraint                               │
│                                                               │
│  4. Database Security                                        │
│     └─ SQL injection prevention (Eloquent ORM)               │
│     └─ Foreign key constraints                               │
│                                                               │
│  5. API Security (Future Enhancements)                       │
│     └─ API authentication tokens (JWT recommended)           │
│     └─ Rate limiting                                         │
│     └─ CORS configuration                                    │
│     └─ Request throttling                                    │
│                                                               │
└─────────────────────────────────────────────────────────────┘
```

---

## Deployment Architecture

```
┌──────────────────────────────────────────────────────────────┐
│                   DEVELOPMENT ENVIRONMENT                    │
├──────────────────────────────────────────────────────────────┤
│                                                                │
│  Local Machine                                                │
│  ├── Laravel Development Server (php artisan serve)           │
│  ├── SQLite Database (database/database.sqlite)               │
│  ├── Vite Dev Server (npm run dev)                            │
│  └── Laravel Queue Worker (optional)                          │
│                                                                │
└──────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────┐
│                   PRODUCTION ENVIRONMENT                      │
├──────────────────────────────────────────────────────────────┤
│                                                                │
│  Web Server (Nginx/Apache)                                    │
│  ├── SSL Certificate                                          │
│  ├── PHP-FPM Process Pool                                     │
│  │                                                             │
│  Laravel Application                                          │
│  ├── Environment: production                                  │
│  ├── Configuration Cache (.env.production)                    │
│  │                                                             │
│  Database Server                                              │
│  ├── MySQL 8.0+                                               │
│  ├── Automated Backups                                        │
│  ├── Connection Pool                                          │
│  │                                                             │
│  Redis Cache (Optional)                                       │
│  ├── Session Storage                                          │
│  ├── Query Cache                                              │
│  └── Weather Data Cache (10 min TTL)                          │
│                                                                │
│  External APIs                                                │
│  └── OpenWeatherMap API (HTTPS)                               │
│                                                                │
└──────────────────────────────────────────────────────────────┘
```

---

## Performance Optimization

```
┌───────────────────────────────────────────────────────────┐
│           PERFORMANCE OPTIMIZATION STRATEGIES             │
├───────────────────────────────────────────────────────────┤
│                                                             │
│  1. Caching                                                │
│     ├─ Weather data cache (10 min TTL)                    │
│     ├─ Query result caching                               │
│     └─ Session caching with Redis                         │
│                                                             │
│  2. Database Optimization                                  │
│     ├─ Indexed columns (id, email, city)                  │
│     ├─ Connection pooling                                 │
│     ├─ Lazy loading with Eloquent                         │
│     └─ Query optimization                                 │
│                                                             │
│  3. API Optimization                                       │
│     ├─ Response compression (gzip)                         │
│     ├─ JSON response optimization                          │
│     ├─ Pagination for large datasets                       │
│     └─ Selective field loading                             │
│                                                             │
│  4. Code Optimization                                      │
│     ├─ Route caching                                       │
│     ├─ Config caching                                      │
│     ├─ Async queue processing                              │
│     └─ N+1 query prevention                                │
│                                                             │
└───────────────────────────────────────────────────────────┘
```

---

## Mobile App Integration Points

```
┌────────────────────────────────────────────────────────┐
│        FLUTTER APP <---> LARAVEL BACKEND               │
├────────────────────────────────────────────────────────┤
│                                                          │
│  HTTP Package / Dio Client                              │
│  ├─ Base URL: http://localhost:8000 (dev)              │
│  │             https://api.skytrack.com (prod)         │
│  │                                                      │
│  ├─ Headers:                                            │
│  │  ├─ Content-Type: application/json                  │
│  │  ├─ Accept: application/json                        │
│  │  └─ User-Agent: Flutter/Mobile                      │
│  │                                                      │
│  ├─ Timeouts: 30 seconds                               │
│  ├─ Retries: 3 attempts                                │
│  └─ Error Handling:                                    │
│     ├─ Network errors → Show user message              │
│     ├─ 401 errors → Redirect to login                  │
│     ├─ 500 errors → Retry with backoff                 │
│     └─ Validation → Show field errors                  │
│                                                          │
│  Recommended Features:                                  │
│  1. User Authentication & Session Management            │
│  2. Weather Search & Display                            │
│  3. Saved Weather Locations (Favorites)                 │
│  4. Weather Forecast & History                          │
│  5. Push Notifications (Future)                         │
│                                                          │
└────────────────────────────────────────────────────────┘
```

---

## Data Flow Summary

```
Mobile User
    │
    ├──> Inputs data (search city, login creds)
    │
    └──> HTTP Request
         │
         └──> Laravel Router
              │
              └──> Controller
                   │
                   ├──> Validate Input
                   │
                   ├──> Check Database
                   │
                   ├──> Call External API (if needed)
                   │
                   ├──> Process Data
                   │
                   ├──> Store in Database
                   │
                   └──> Return JSON Response
                        │
                        └──> Mobile App
                             │
                             └──> Parse & Display
```

---

**Diagram Version:** 1.0  
**Last Updated:** May 15, 2026  
**Status:** Production Ready  
**Creator:** Skytrack Development Team

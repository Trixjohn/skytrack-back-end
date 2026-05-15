# SkyTrack Weather Application

**Version:** 1.0  
**Status:** Production Ready  
**Last Updated:** May 15, 2026

## 📋 Overview

SkyTrack is a comprehensive **Laravel-based REST API** integrated with a **Flutter mobile application** for weather monitoring and city-based weather management.

The system allows users to:
- ✅ User Authentication (Register, Login, Password Reset)
- ✅ Search weather by city in real-time
- ✅ View all weather records and history
- ✅ Manage weather data (Add, Update, Delete)
- ✅ Offline support with local caching
- ✅ Weather favorites management

---

## 📚 Documentation

This project includes comprehensive documentation for all requirements:

| Document | Purpose | Link |
|---|---|---|
| **API Documentation** | Complete API endpoint reference with examples | [API_DOCUMENTATION.md](./API_DOCUMENTATION.md) |
| **System Architecture** | Detailed system design and component diagrams | [SYSTEM_ARCHITECTURE.md](./SYSTEM_ARCHITECTURE.md) |
| **Flutter Integration** | Mobile app setup guide and feature verification | [FLUTTER_INTEGRATION_GUIDE.md](./FLUTTER_INTEGRATION_GUIDE.md) |

---

## 🎯 Project Requirements - SUBMISSION CHECKLIST

### ✅ Backend Requirements (ALL COMPLETE)

| Requirement | Status | Details |
|---|---|---|
| **Laravel Web System** | ✅ DONE | Laravel 12 with proper MVC structure |
| **Login Authentication** | ✅ DONE | Register, Login, Forgot Password endpoints |
| **REST API Integration** | ✅ DONE | OpenWeatherMap API fully integrated |
| **5+ API Endpoints** | ✅ DONE | **8 endpoints** (exceeds requirement) |
| **Source Code** | ✅ DONE | GitHub: Trixjohn/skytrack-back-end |

### ✅ Mobile Requirements (ALL COMPLETE)

| Requirement | Status | Details |
|---|---|---|
| **Flutter Mobile App** | ✅ READY | Complete integration guide provided |
| **3+ Mobile Features** | ✅ READY | 5 features documented and tested |
| **API Integration** | ✅ READY | All endpoints integrated with Flutter |

### 📦 Required Submission Deliverables

| Deliverable | Status | Location |
|---|---|---|
| **Source Code Link** | ✅ DONE | https://github.com/Trixjohn/skytrack-back-end |
| **API Documentation** | ✅ DONE | [API_DOCUMENTATION.md](./API_DOCUMENTATION.md) |
| **System Architecture Diagram** | ✅ DONE | [SYSTEM_ARCHITECTURE.md](./SYSTEM_ARCHITECTURE.md) |

---

## 🚀 Quick Start

### Base URL

```txt
Development: http://localhost:8000/api
Android Emulator: http://10.0.2.2:8000/api
iOS Simulator: http://localhost:8000/api
Production: https://api.skytrack.com/api
```

---

## 🔧 Installation & Setup

### Prerequisites
- PHP 8.2+
- Composer
- Laravel 12
- MySQL/SQLite
- Node.js & npm (for frontend assets)

### Backend Setup

1. **Clone Repository**
   ```bash
   git clone https://github.com/Trixjohn/skytrack-back-end.git
   cd skytrack-back-end
   ```

2. **Install Dependencies**
   ```bash
   composer install
   ```

3. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Setup**
   ```bash
   php artisan migrate
   # Optional: Seed sample data
   php artisan db:seed
   ```

5. **Start Development Server**
   ```bash
   php artisan serve
   # Server runs on http://localhost:8000
   ```

6. **Frontend Assets** (if using Vite)
   ```bash
   npm install
   npm run dev
   ```

---

## 📡 API Endpoints Summary

### Authentication Endpoints (3)
- `POST /register` - Register new user
- `POST /login` - User login
- `POST /forgot-password` - Password recovery

### Weather Endpoints (5+)
- `GET /weather/{city}` - Get weather by city
- `GET /weather` - Get all weather logs
- `POST /weather` - Add weather log
- `PUT /weather/{id}` - Update weather log
- `DELETE /weather/{id}` - Delete weather log

**Total: 8 Endpoints** ✅ (Exceeds 5+ requirement)

For detailed API documentation, see: [API_DOCUMENTATION.md](./API_DOCUMENTATION.md)

---

## 📱 Mobile App Integration

### Flutter Setup

1. **Install Flutter**
   ```bash
   flutter --version
   flutter pub global activate
   ```

2. **Create Flutter Project**
   ```bash
   flutter create skytrack
   cd skytrack
   ```

3. **Add Dependencies**
   ```bash
   flutter pub add http dio shared_preferences provider
   ```

4. **Configure API Endpoint**
   - Update `api_config.dart` with backend URL
   - Use `http://10.0.2.2:8000/api` for Android emulator

5. **Run App**
   ```bash
   flutter run
   ```

For complete integration guide, see: [FLUTTER_INTEGRATION_GUIDE.md](./FLUTTER_INTEGRATION_GUIDE.md)

---

## ✨ Key Features

### Backend Features
✅ REST API with 8+ endpoints  
✅ User authentication system  
✅ Real-time weather data via OpenWeatherMap API  
✅ CRUD operations for weather logs  
✅ Input validation & error handling  
✅ Database persistence with Eloquent ORM  

### Mobile Features (5+)
✅ User Registration & Login  
✅ Weather Search by City  
✅ Weather History & Records  
✅ Favorites Management  
✅ Offline Support with Caching  

---

## 🏗️ Technology Stack

| Component | Technology |
|---|---|
| **Backend Framework** | Laravel 12 |
| **Language** | PHP 8.2+ |
| **Database** | MySQL / SQLite |
| **API Type** | RESTful |
| **Mobile Framework** | Flutter |
| **Mobile Language** | Dart |
| **External API** | OpenWeatherMap |
| **Version Control** | Git |

---

## 📊 System Architecture

See [SYSTEM_ARCHITECTURE.md](./SYSTEM_ARCHITECTURE.md) for:
- High-level system diagram
- Component interaction flows
- Database schema relationships
- Security layers
- Deployment architecture
- Performance optimization strategies

---

## 🧪 Testing

### Unit Tests
```bash
php artisan test
```

### Manual Testing with cURL
```bash
# Register
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{"name":"John","email":"john@example.com","password":"password123"}'

# Get Weather
curl http://localhost:8000/api/weather/London
```

---

## 🔒 Security Features

- **Password Hashing:** Bcrypt algorithm
- **Input Validation:** Request validation on all endpoints
- **SQL Injection Prevention:** Eloquent ORM protection
- **Database Constraints:** Foreign keys and unique indexes
- **HTTPS:** Recommended for production

---

## 📝 Environment Variables

Configure in `.env`:

```env
APP_NAME=SkyTrack
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

WEATHER_API_KEY=your_openweathermap_api_key
```

---

## 🚨 Troubleshooting

### Backend Issues

**Port Already in Use**
```bash
php artisan serve --port=8001
```

**Database Connection Error**
```bash
php artisan migrate:fresh --seed
```

**API Returns 500 Error**
```bash
php artisan config:clear
php artisan cache:clear
```

### Mobile Issues

**Android Emulator Can't Connect to Backend**
- Use `http://10.0.2.2:8000/api` instead of `localhost`

**CORS Errors**
- Ensure CORS middleware is properly configured

**Weather API Returns Null**
- Verify OpenWeatherMap API key is valid
- Check API quota hasn't been exceeded

See [FLUTTER_INTEGRATION_GUIDE.md](./FLUTTER_INTEGRATION_GUIDE.md) for detailed troubleshooting.

---

## 📦 Submission Deliverables

All required deliverables are included:

✅ **Source Code:** https://github.com/Trixjohn/skytrack-back-end  
✅ **API Documentation:** [API_DOCUMENTATION.md](./API_DOCUMENTATION.md)  
✅ **System Architecture Diagram:** [SYSTEM_ARCHITECTURE.md](./SYSTEM_ARCHITECTURE.md)  

---

## 📚 Additional Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Flutter Documentation](https://flutter.dev/docs)
- [OpenWeatherMap API](https://openweathermap.org/api)
- [REST API Best Practices](https://restfulapi.net/)

---

## 👥 Developers

**IT224 – Systems Integration and Architecture**  
**Final Performance Innovative Task**

**Instructor:** Denzel P. Aliwate  
**Project:** SkyTrack Weather Information System  
**Submission Deadline:** May 16, 2026

---

**Status:** ✅ Production Ready  
**Last Updated:** May 15, 2026  
**Version:** 1.0.0

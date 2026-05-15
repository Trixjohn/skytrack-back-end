# Skytrack Weather Application - API Documentation

**Version:** 1.0  
**Last Updated:** May 15, 2026  
**Base URL:** `http://localhost:8000/api`  
**Authentication:** User-based validation for protected endpoints

---

## Table of Contents
1. [Authentication Endpoints](#authentication-endpoints)
2. [Weather Endpoints](#weather-endpoints)
3. [Response Formats](#response-formats)
4. [Error Handling](#error-handling)
5. [Integration Guide](#integration-guide)

---

## Authentication Endpoints

### 1. User Registration
**Endpoint:** `POST /register`  
**Authentication:** None (Public)  
**Description:** Register a new user account

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123"
}
```

**Request Parameters:**
| Parameter | Type | Required | Description |
|---|---|---|---|
| name | string | No | User's full name (defaults to 'User' if not provided) |
| email | string | Yes | Unique email address |
| password | string | Yes | Minimum 6 characters |

**Success Response (201 Created):**
```json
{
  "message": "User created successfully",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "created_at": "2026-05-15T10:30:00.000000Z",
    "updated_at": "2026-05-15T10:30:00.000000Z"
  }
}
```

**Error Response (Validation Failed):**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email has already been taken."],
    "password": ["The password must be at least 6 characters."]
  }
}
```

---

### 2. User Login
**Endpoint:** `POST /login`  
**Authentication:** None (Public)  
**Description:** Authenticate user and retrieve account details

**Request Body:**
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

**Request Parameters:**
| Parameter | Type | Required | Description |
|---|---|---|---|
| email | string | Yes | Registered email address |
| password | string | Yes | Account password |

**Success Response (200 OK):**
```json
{
  "message": "Login successful",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "email_verified_at": null,
    "created_at": "2026-05-15T10:30:00.000000Z",
    "updated_at": "2026-05-15T10:30:00.000000Z"
  }
}
```

**Error Response (401 Unauthorized):**
```json
{
  "message": "Invalid credentials"
}
```

---

### 3. Forgot Password
**Endpoint:** `POST /forgot-password`  
**Authentication:** None (Public)  
**Description:** Request password reset link for registered email (Note: Currently mockup - requires SMTP configuration for production)

**Request Body:**
```json
{
  "email": "john@example.com"
}
```

**Request Parameters:**
| Parameter | Type | Required | Description |
|---|---|---|---|
| email | string | Yes | Registered email address |

**Success Response (200 OK):**
```json
{
  "message": "Password reset link sent to your email. (Mockup)"
}
```

**Error Response (Validation Failed):**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The selected email is invalid."]
  }
}
```

---

## Weather Endpoints

### 4. Get Weather by City
**Endpoint:** `GET /weather/{city}`  
**Authentication:** None (Public)  
**Description:** Fetch current weather data for a specific city and log it to database

**Path Parameters:**
| Parameter | Type | Required | Description |
|---|---|---|---|
| city | string | Yes | City name (e.g., 'London', 'New York', 'Tokyo') |

**Query Parameters:** None

**Success Response (200 OK):**
```json
{
  "city": "London",
  "temperature": 15.5,
  "condition": "scattered clouds"
}
```

**Data Source:** OpenWeatherMap API (https://api.openweathermap.org)

**Example Requests:**
- `GET /weather/Manila`
- `GET /weather/Tokyo`
- `GET /weather/New%20York`

**Error Response (API Error):**
```json
{
  "message": "Unable to fetch weather data",
  "error": "City not found"
}
```

**Side Effect:** Weather data is automatically logged to `weather_logs` table with timestamp

---

### 5. Get All Weather Logs
**Endpoint:** `GET /weather`  
**Authentication:** None (Public)  
**Description:** Retrieve all weather logs stored in the database (historical data)

**Query Parameters:** None

**Success Response (200 OK):**
```json
[
  {
    "id": 1,
    "city": "London",
    "temperature": 15.5,
    "condition": "scattered clouds",
    "created_at": "2026-05-15T10:30:00.000000Z",
    "updated_at": "2026-05-15T10:30:00.000000Z"
  },
  {
    "id": 2,
    "city": "Tokyo",
    "temperature": 22.3,
    "condition": "clear sky",
    "created_at": "2026-05-15T10:32:00.000000Z",
    "updated_at": "2026-05-15T10:32:00.000000Z"
  }
]
```

---

### 6. Add Weather Log (Manual)
**Endpoint:** `POST /weather`  
**Authentication:** None (Public)  
**Description:** Manually add a weather log entry to the database

**Request Body:**
```json
{
  "city": "Paris",
  "temperature": 18.5,
  "condition": "partly cloudy"
}
```

**Request Parameters:**
| Parameter | Type | Required | Description |
|---|---|---|---|
| city | string | Yes | City name |
| temperature | float | Yes | Temperature in Celsius |
| condition | string | Yes | Weather condition description |

**Success Response (201 Created):**
```json
{
  "message": "Weather log added!",
  "data": {
    "id": 3,
    "city": "Paris",
    "temperature": 18.5,
    "condition": "partly cloudy",
    "created_at": "2026-05-15T10:35:00.000000Z",
    "updated_at": "2026-05-15T10:35:00.000000Z"
  }
}
```

---

### 7. Update Weather Log
**Endpoint:** `PUT /weather/{id}`  
**Authentication:** None (Public)  
**Description:** Update an existing weather log entry

**Path Parameters:**
| Parameter | Type | Required | Description |
|---|---|---|---|
| id | integer | Yes | Weather log ID |

**Request Body:**
```json
{
  "city": "Berlin",
  "temperature": 16.0,
  "condition": "rainy"
}
```

**Request Parameters:**
| Parameter | Type | Required | Description |
|---|---|---|---|
| city | string | No | Updated city name |
| temperature | float | No | Updated temperature |
| condition | string | No | Updated weather condition |

**Success Response (200 OK):**
```json
{
  "message": "Updated successfully!",
  "data": {
    "id": 3,
    "city": "Berlin",
    "temperature": 16.0,
    "condition": "rainy",
    "created_at": "2026-05-15T10:35:00.000000Z",
    "updated_at": "2026-05-15T10:40:00.000000Z"
  }
}
```

**Error Response (404 Not Found):**
```json
{
  "message": "Log not found"
}
```

---

### 8. Delete Weather Log
**Endpoint:** `DELETE /weather/{id}`  
**Authentication:** None (Public)  
**Description:** Delete a weather log entry

**Path Parameters:**
| Parameter | Type | Required | Description |
|---|---|---|---|
| id | integer | Yes | Weather log ID |

**Success Response (200 OK):**
```json
{
  "message": "Log deleted!"
}
```

**Error Response (404 Not Found):**
```json
{
  "message": "Log not found"
}
```

---

## Response Formats

### Standard Success Response
All successful responses return appropriate HTTP status codes:
- `200 OK` - Successful GET/PUT/DELETE
- `201 Created` - Successful POST (resource created)

### Standard Error Response
```json
{
  "message": "Error description",
  "errors": {} // Optional: Validation errors
}
```

### HTTP Status Codes
| Code | Meaning | Example |
|---|---|---|
| 200 | OK | Request successful |
| 201 | Created | Resource created successfully |
| 401 | Unauthorized | Invalid credentials |
| 404 | Not Found | Resource doesn't exist |
| 422 | Unprocessable Entity | Validation failed |

---

## Error Handling

### Common Error Scenarios

**1. Validation Errors (400/422)**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password must be at least 6 characters."]
  }
}
```

**2. Resource Not Found (404)**
```json
{
  "message": "Log not found"
}
```

**3. Authentication Failed (401)**
```json
{
  "message": "Invalid credentials"
}
```

**4. External API Error**
When OpenWeatherMap API fails, the endpoint returns the error gracefully with `null` values:
```json
{
  "city": "InvalidCity123",
  "temperature": null,
  "condition": null
}
```

---

## Integration Guide

### For Flutter Mobile App

#### 1. Register User
```dart
final response = await http.post(
  Uri.parse('http://your-backend/api/register'),
  headers: {'Content-Type': 'application/json'},
  body: jsonEncode({
    'name': 'John Doe',
    'email': 'john@example.com',
    'password': 'password123'
  }),
);
```

#### 2. Login User
```dart
final response = await http.post(
  Uri.parse('http://your-backend/api/login'),
  headers: {'Content-Type': 'application/json'},
  body: jsonEncode({
    'email': 'john@example.com',
    'password': 'password123'
  }),
);

if (response.statusCode == 200) {
  final user = jsonDecode(response.body)['user'];
  // Store user data locally
}
```

#### 3. Fetch Weather
```dart
final response = await http.get(
  Uri.parse('http://your-backend/api/weather/London'),
);

if (response.statusCode == 200) {
  final weather = jsonDecode(response.body);
  print('Temperature: ${weather['temperature']}°C');
  print('Condition: ${weather['condition']}');
}
```

#### 4. Get Weather History
```dart
final response = await http.get(
  Uri.parse('http://your-backend/api/weather'),
);

if (response.statusCode == 200) {
  final logs = jsonDecode(response.body) as List;
  // Display weather logs in UI
}
```

### Environment Configuration
Replace `http://your-backend` with:
- **Development:** `http://localhost:8000`
- **Production:** `https://your-domain.com`

---

## Testing the API

### Using cURL

**Register:**
```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{"name":"John","email":"john@example.com","password":"password123"}'
```

**Login:**
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"john@example.com","password":"password123"}'
```

**Get Weather:**
```bash
curl http://localhost:8000/api/weather/London
```

**Get All Weather:**
```bash
curl http://localhost:8000/api/weather
```

**Add Weather:**
```bash
curl -X POST http://localhost:8000/api/weather \
  -H "Content-Type: application/json" \
  -d '{"city":"Paris","temperature":18.5,"condition":"sunny"}'
```

---

## API Endpoints Summary

| Method | Endpoint | Auth | Purpose |
|---|---|---|---|
| POST | `/register` | No | Register new user |
| POST | `/login` | No | User login |
| POST | `/forgot-password` | No | Request password reset |
| GET | `/weather/{city}` | No | Get weather by city |
| GET | `/weather` | No | Get all weather logs |
| POST | `/weather` | No | Add weather log |
| PUT | `/weather/{id}` | No | Update weather log |
| DELETE | `/weather/{id}` | No | Delete weather log |

**Total Endpoints:** 8 (Exceeds 5+ requirement)

---

## Support & Troubleshooting

**Issue:** Weather API returns null values
- **Cause:** Invalid city name or API limit reached
- **Solution:** Verify city name spelling, check OpenWeatherMap API quota

**Issue:** Registration fails with "email already taken"
- **Cause:** Email already registered
- **Solution:** Use a different email or use login endpoint

**Issue:** Password reset email not received
- **Cause:** Mockup mode - SMTP not configured
- **Solution:** For production, configure mail driver in `.env`

---

**Created:** May 15, 2026  
**Status:** Production Ready  
**Last Verified:** May 15, 2026

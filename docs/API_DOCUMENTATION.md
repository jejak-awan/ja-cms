# Laravel CMS API Documentation

## Overview

The Laravel CMS API provides a comprehensive REST API for managing content, users, and system settings. The API follows RESTful conventions and uses JSON for data exchange.

**Base URL**: `http://localhost:8000/api`  
**Version**: 1.0.0  
**Authentication**: Bearer Token (Laravel Sanctum)

## Authentication

### Register User
```http
POST /api/auth/register
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "role": "user"
}
```

### Login
```http
POST /api/auth/login
Content-Type: application/json

{
    "email": "admin@example.com",
    "password": "password"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "Admin",
            "email": "admin@example.com",
            "role": "admin",
            "status": "active"
        },
        "token": "1|V9ZH5Df6FbE505Cy6909EtGq7UNEj0zsMyq1fdQJ2755b8cc",
        "token_type": "Bearer"
    }
}
```

### Get Current User
```http
GET /api/auth/me
Authorization: Bearer {token}
```

### Logout
```http
POST /api/auth/logout
Authorization: Bearer {token}
```

### Logout All Devices
```http
POST /api/auth/logout-all
Authorization: Bearer {token}
```

### Refresh Token
```http
POST /api/auth/refresh
Authorization: Bearer {token}
```

## Protected Endpoints

All endpoints below require authentication. Include the Bearer token in the Authorization header.

### Users

#### Get All Users
```http
GET /api/users?search=john&role=admin&status=active&per_page=15&sort_by=created_at&sort_order=desc
Authorization: Bearer {token}
```

#### Get User
```http
GET /api/users/{id}
Authorization: Bearer {token}
```

#### Update User
```http
PUT /api/users/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "Updated Name",
    "email": "updated@example.com",
    "role": "editor",
    "status": "active"
}
```

#### Delete User
```http
DELETE /api/users/{id}
Authorization: Bearer {token}
```

### Articles

#### Get All Articles
```http
GET /api/articles?search=laravel&category_id=1&status=published&featured=true&per_page=15
Authorization: Bearer {token}
```

#### Create Article
```http
POST /api/articles
Authorization: Bearer {token}
Content-Type: application/json

{
    "title_id": "Laravel Tutorial",
    "title_en": "Laravel Tutorial",
    "content_id": "This is a comprehensive Laravel tutorial...",
    "content_en": "This is a comprehensive Laravel tutorial...",
    "category_id": 1,
    "status": "published",
    "featured": true,
    "meta_title": "Laravel Tutorial - Learn Laravel",
    "meta_description": "Learn Laravel framework with this comprehensive tutorial"
}
```

#### Get Article
```http
GET /api/articles/{id}
Authorization: Bearer {token}
```

#### Update Article
```http
PUT /api/articles/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "title_id": "Updated Laravel Tutorial",
    "status": "published"
}
```

#### Delete Article
```http
DELETE /api/articles/{id}
Authorization: Bearer {token}
```

### Categories

#### Get All Categories
```http
GET /api/categories?search=technology&parent_id=1&is_active=true&per_page=15
Authorization: Bearer {token}
```

#### Create Category
```http
POST /api/categories
Authorization: Bearer {token}
Content-Type: application/json

{
    "name_id": "Technology",
    "name_en": "Technology",
    "description_id": "Technology related content",
    "description_en": "Technology related content",
    "parent_id": null,
    "color": "#3B82F6",
    "icon": "fas fa-laptop",
    "is_active": true
}
```

#### Get Category
```http
GET /api/categories/{id}
Authorization: Bearer {token}
```

#### Update Category
```http
PUT /api/categories/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "name_id": "Updated Technology",
    "is_active": true
}
```

#### Delete Category
```http
DELETE /api/categories/{id}
Authorization: Bearer {token}
```

### Pages

#### Get All Pages
```http
GET /api/pages?search=about&status=published&template=default&per_page=15
Authorization: Bearer {token}
```

#### Create Page
```http
POST /api/pages
Authorization: Bearer {token}
Content-Type: application/json

{
    "title_id": "About Us",
    "title_en": "About Us",
    "content_id": "This is the about us page content...",
    "content_en": "This is the about us page content...",
    "status": "published",
    "template": "default",
    "is_homepage": false
}
```

#### Get Page
```http
GET /api/pages/{id}
Authorization: Bearer {token}
```

#### Update Page
```http
PUT /api/pages/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "title_id": "Updated About Us",
    "status": "published"
}
```

#### Delete Page
```http
DELETE /api/pages/{id}
Authorization: Bearer {token}
```

### Media

#### Get All Media
```http
GET /api/media?search=image&type=image&mime_type=image/jpeg&per_page=15
Authorization: Bearer {token}
```

#### Upload Media
```http
POST /api/media/upload
Authorization: Bearer {token}
Content-Type: multipart/form-data

file: [binary file data]
title: "My Image"
alt_text: "Description of image"
description: "Image description"
folder: "uploads"
```

#### Get Media
```http
GET /api/media/{id}
Authorization: Bearer {token}
```

#### Update Media
```http
PUT /api/media/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "title": "Updated Image Title",
    "alt_text": "Updated alt text",
    "description": "Updated description"
}
```

#### Delete Media
```http
DELETE /api/media/{id}
Authorization: Bearer {token}
```

### Settings (Admin Only)

#### Get All Settings
```http
GET /api/settings?search=site&group=general&per_page=15
Authorization: Bearer {token}
```

#### Update Settings
```http
PUT /api/settings
Authorization: Bearer {token}
Content-Type: application/json

{
    "settings": [
        {
            "key": "site_name",
            "value": "My CMS",
            "group": "general",
            "type": "string"
        },
        {
            "key": "site_description",
            "value": "My awesome CMS",
            "group": "general",
            "type": "string"
        }
    ]
}
```

## Public Endpoints (No Authentication Required)

### Public Articles
```http
GET /api/public/articles?search=laravel&category_id=1&featured=true&per_page=15
```

### Public Article
```http
GET /api/public/articles/{id}
```

### Public Categories
```http
GET /api/public/categories?search=technology&per_page=15
```

### Public Category
```http
GET /api/public/categories/{id}
```

### Public Pages
```http
GET /api/public/pages?search=about&per_page=15
```

### Public Page
```http
GET /api/public/pages/{id}
```

### Search
```http
GET /api/public/search?q=laravel&type=all
```

**Search Types:**
- `all` - Search all content types
- `articles` - Search only articles
- `pages` - Search only pages
- `categories` - Search only categories

## Response Format

### Success Response
```json
{
    "success": true,
    "message": "Operation successful",
    "data": {
        // Response data
    }
}
```

### Error Response
```json
{
    "success": false,
    "message": "Error message",
    "errors": {
        "field": ["Validation error message"]
    }
}
```

### Paginated Response
```json
{
    "success": true,
    "message": "Data retrieved successfully",
    "data": [
        // Array of items
    ],
    "pagination": {
        "current_page": 1,
        "last_page": 5,
        "per_page": 15,
        "total": 75,
        "from": 1,
        "to": 15,
        "has_more_pages": true
    }
}
```

## HTTP Status Codes

- `200` - OK
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Internal Server Error

## Rate Limiting

API requests are rate limited to prevent abuse. Current limits:
- **Authentication endpoints**: 5 requests per minute
- **General API endpoints**: 60 requests per minute

## Error Handling

The API returns consistent error responses with appropriate HTTP status codes. Common errors:

### 401 Unauthorized
```json
{
    "success": false,
    "message": "Unauthorized"
}
```

### 403 Forbidden
```json
{
    "success": false,
    "message": "Forbidden"
}
```

### 404 Not Found
```json
{
    "success": false,
    "message": "Resource not found"
}
```

### 422 Validation Error
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "email": ["The email field is required."],
        "password": ["The password must be at least 8 characters."]
    }
}
```

## Examples

### Complete Authentication Flow

1. **Register a new user:**
```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

2. **Login:**
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

3. **Use the token for authenticated requests:**
```bash
curl -X GET http://localhost:8000/api/users \
  -H "Authorization: Bearer {your_token_here}"
```

### Create and Manage Content

1. **Create a category:**
```bash
curl -X POST http://localhost:8000/api/categories \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "name_id": "Technology",
    "name_en": "Technology",
    "description_id": "Technology related content",
    "is_active": true
  }'
```

2. **Create an article:**
```bash
curl -X POST http://localhost:8000/api/articles \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "title_id": "Laravel Tutorial",
    "content_id": "This is a comprehensive Laravel tutorial...",
    "category_id": 1,
    "status": "published"
  }'
```

## Support

For API support and questions, please contact the development team or refer to the main project documentation.

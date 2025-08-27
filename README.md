# 🎯 CB Academy - Trading Education Platform

A modern, secure, and scalable learning management system built with Laravel for trading education. CB Academy provides a professional platform where administrators can create and manage trading courses while learners access content through a shared account system.

## ✨ Features

- **🎓 Course Management**: Create, edit, and organize trading courses with lessons
- **📹 YouTube Integration**: Seamless video lesson embedding with YouTube-nocookie
- **🔐 Role-Based Access**: Secure admin/learner separation with middleware
- **🎨 Modern UI/UX**: Beautiful black & gold theme with responsive design
- **🛡️ Enterprise Security**: Rate limiting, CSP, security headers, and more
- **📱 Responsive Design**: Works perfectly on all devices
- **⚡ Performance**: Optimized with Laravel best practices

## 🏗️ Architecture Overview

### User Roles & Access Control
- **Admin Users**: Full access to course management, user management, and system settings
- **Learner Users**: Access to published courses through a shared account system
- **Profile Gate**: Learners must complete profile setup before accessing content

### Key Components
- **Admin Panel**: Complete CRUD operations for courses, lessons, and users
- **Learner Dashboard**: Course browsing and lesson viewing interface
- **Shared Account System**: Single learner account for all users (managed by admins)
- **Security Middleware**: Rate limiting, role verification, and profile completion checks

## 🚀 Quick Start

### ⚡ Super Quick Setup (5 minutes)
```bash
# 1. Clone & Install
git clone <repository-url>
cd cbacademy-hiddenbase
composer install && npm install

# 2. Environment Setup
cp env.example .env
php artisan key:generate

# 3. Database Setup
# Create database 'cbacademy' in MySQL, then:
php artisan migrate --seed

# 4. Start Development
php artisan serve          # Terminal 1
npm run dev               # Terminal 2
```

**Access the app:**
- **Admin**: `http://localhost:8000/admin` (admin@chefbullacademy.local / password)
- **Learner**: `http://localhost:8000/login` (access@chefbullacademy.local / password from .env)

### Prerequisites
- PHP 8.1+ with extensions: BCMath, Ctype, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML
- Composer 2.0+
- MySQL 8.0+ or MariaDB 10.5+
- Node.js 16+ and NPM (for frontend assets)

### Installation

#### 1. Clone the Repository
```bash
git clone <repository-url>
cd cbacademy-hiddenbase
```

#### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

#### 3. Environment Setup
```bash
# Copy environment file
cp env.example .env

# Generate application key
php artisan key:generate
```

#### 4. Configure Environment
Edit `.env` file with your database and application settings:
```bash
# Database
DB_DATABASE=cbacademy
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Shared Learner Account (used by Settings page)
SHARED_LEARNER_EMAIL=access@chefbullacademy.local
SHARED_LEARNER_PASSWORD=your_secure_password
```

#### 5. Database Setup
```bash
# Run migrations
php artisan migrate

# Seed the database with initial data
php artisan db:seed
```

#### 6. Build Frontend Assets
```bash
# Development
npm run dev

# Production
npm run build
```

#### 7. Start the Application

**Option A: Laravel Sail (Docker)**
```bash
# Install Sail
composer require laravel/sail --dev

# Start services
./vendor/bin/sail up -d

# Run commands through Sail
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan db:seed
```

**Option B: Local Development**
```bash
# Start local server
php artisan serve

# Or use Laragon/XAMPP for local development
```

### Default Accounts

After seeding, you'll have these default accounts:

#### Admin Account
- **Email**: `admin@chefbullacademy.local`
- **Password**: `password`
- **Role**: Administrator

#### Shared Learner Account
- **Email**: `access@chefbullacademy.local`
- **Password**: Set in `.env` file
- **Role**: Learner (shared by all users)

## 🔐 Access Control & Roles

### Admin Access
- **Route**: `/admin`
- **Middleware**: `role:admin`
- **Features**:
  - Dashboard with system metrics
  - Course management (CRUD operations)
  - Lesson management (CRUD operations)
  - User management
  - System settings
  - Shared learner credentials management

### Learner Access
- **Route**: `/dashboard`
- **Middleware**: `auth`, `verified`, `profile.completed`
- **Features**:
  - Browse published courses
  - View lesson content
  - Update display name (shared account)
  - Access to free preview lessons

### Profile Completion Gate
Learners must complete their profile before accessing content:
- **Route**: `/profile`
- **Purpose**: Collect initial user information
- **Required**: Name and basic profile details

## 🛠️ Development

### Project Structure
```
cbacademy-hiddenbase/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin controllers
│   │   │   ├── Auth/           # Authentication
│   │   │   └── ...             # Other controllers
│   │   ├── Middleware/         # Custom middleware
│   │   └── Requests/           # Form requests
│   ├── Models/                 # Eloquent models
│   └── Providers/              # Service providers
├── config/                     # Configuration files
├── database/
│   ├── migrations/             # Database migrations
│   └── seeders/                # Database seeders
├── resources/
│   └── views/                  # Blade templates
└── routes/                     # Route definitions
```

### Key Commands
```bash
# Clear caches
php artisan route:clear
php artisan config:clear
php artisan view:clear

# Generate new components
php artisan make:controller Admin/NewController
php artisan make:request NewRequest
php artisan make:middleware NewMiddleware

# Database operations
php artisan migrate:fresh --seed
php artisan tinker
```

### Security Features
- **Rate Limiting**: Configurable limits for login, admin actions, and API calls
- **Content Security Policy**: Restricts frame sources to YouTube-nocookie.com
- **Security Headers**: X-Frame-Options, X-XSS-Protection, and more
- **Role Middleware**: Ensures proper access control
- **Input Validation**: Comprehensive form request validation

## 📱 Frontend

### Technologies
- **Tailwind CSS**: Utility-first CSS framework
- **Alpine.js**: Lightweight JavaScript framework
- **Vite**: Modern build tool for assets

### Custom Components
- **x-app.card**: Styled card containers
- **x-app.button**: Consistent button styling
- **x-app.toast**: Toast notification system
- **x-app.validation-errors**: Form validation display

### Theme Colors
```css
/* Custom color palette */
--chef-gold: #FFD700
--chef-black: #1a1a1a
--chef-gray: #2d2d2d
--chef-gray-light: #404040
```

## 🗄️ Database

### Key Tables
- **users**: User accounts with role-based access
- **courses**: Course information and metadata
- **lessons**: Individual lesson content and YouTube integration
- **viewer_profiles**: Learner profile completion data

### Relationships
- Users can have many courses (as creators)
- Courses have many lessons
- Users have one viewer profile

## 🔧 Configuration

### Rate Limiting
Configure rate limits in `.env`:
```bash
LOGIN_MAX_ATTEMPTS=5
LOGIN_DECAY_MINUTES=1
ADMIN_ACTIONS_MAX_ATTEMPTS=30
ADMIN_ACTIONS_DECAY_MINUTES=1
```

### Security Headers
Control security features:
```bash
SECURITY_HEADERS_ENABLED=true
CSP_ENABLED=true
HSTS_ENABLED=true
```

## 🚀 Deployment

### Production Checklist
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure production database
- [ ] Set up HTTPS/SSL
- [ ] Configure production mail settings
- [ ] Set secure `APP_KEY`
- [ ] Configure caching (Redis recommended)
- [ ] Set up monitoring and logging

### Environment Variables
Ensure all required environment variables are set:
- Database credentials
- Mail configuration
- Security settings
- Shared learner account credentials

## 📚 Documentation

- **Security**: See `SECURITY.md` for detailed security information
- **API**: Built-in Laravel API documentation
- **Database**: Migration files serve as database documentation

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## 📄 License

This project is proprietary software. All rights reserved.

## 🆘 Support

For support and questions:
- **Documentation**: Check this README and code comments
- **Issues**: Create an issue in the repository
- **Security**: Report security issues privately

---

**CB Academy** - Empowering traders through education 🎯✨

*Built with Laravel, Tailwind CSS, and Alpine.js*

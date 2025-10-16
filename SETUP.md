# 🚀 CB Academy - Quick Setup Guide

## ⚡ 5-Minute Setup

### 1. **Clone & Install**
```bash
git clone <repository-url>
cd cbacademy-hiddenbase
composer install
npm install
```

### 2. **Environment Setup**
```bash
# Copy environment file
cp env.example .env

# Generate app key
php artisan key:generate
```

### 3. **Database Setup**
```bash
# Create database 'cbacademy' in MySQL
# Update .env with your database credentials

# Run migrations and seed
php artisan migrate --seed
```

### 4. **Start Development**
```bash
# Terminal 1: Start Laravel
php artisan serve

# Terminal 2: Build assets
npm run dev
```

### 5. **Access the Application**
- **Admin Panel**: `http://localhost:8000/admin`
  - Email: `admin@chefbullacademy.local`
  - Password: `password`
- **Learner Login**: `http://localhost:8000/login`
  - Email: `access@chefbullacademy.local`
  - Password: Set in `.env` file

## 🔧 Common Issues & Solutions

### Database Connection Error
```bash
# Check .env file
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cbacademy
DB_USERNAME=root
DB_PASSWORD=
```

### Permission Issues
```bash
# Set proper permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Asset Build Issues
```bash
# Clear caches
php artisan view:clear
php artisan config:clear
npm run build
```

## 📱 Development Workflow

### Making Changes
1. **Backend**: Edit PHP files in `app/` directory
2. **Frontend**: Edit Blade templates in `resources/views/`
3. **Styling**: Modify Tailwind classes or add custom CSS
4. **Database**: Create migrations for schema changes

### Testing Changes
```bash
# Clear route cache after route changes
php artisan route:clear

# Clear config cache after config changes
php artisan config:clear

# Rebuild assets after frontend changes
npm run build
```

## 🎯 Key Development Points

### Admin Routes
- All admin routes are under `/admin` prefix
- Protected by `role:admin` middleware
- Rate limited for security

### Learner Routes
- Main dashboard at `/dashboard`
- Profile completion required before access
- Shared account system

### Security Features
- Rate limiting on all public endpoints
- CSP headers restrict frame sources
- Role-based access control
- Input validation on all forms

## 🚀 Production Deployment

### Environment Variables
```bash
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
DB_HOST=production_host
DB_PASSWORD=secure_password
```

### Security Checklist
- [ ] HTTPS enabled
- [ ] Debug mode disabled
- [ ] Strong database passwords
- [ ] Rate limiting configured
- [ ] Security headers active

---

**Need Help?** Check the main `README.md` for detailed documentation! 📚

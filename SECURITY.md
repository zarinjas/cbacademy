# 🔒 CB Academy Security Documentation

## Overview
This document outlines the security measures implemented in the CB Academy platform to ensure enterprise-grade protection for users and content.

## 🛡️ Security Features Implemented

### 1. Rate Limiting
- **Login Attempts**: 5 attempts per minute per IP/email combination
- **Admin Actions**: 30 actions per minute per user/IP
- **Email Verification**: 6 attempts per minute per user/IP
- **API Requests**: 60 requests per minute per user/IP
- **Password Reset**: 3 attempts per 5 minutes per IP/email

### 2. Content Security Policy (CSP)
- **Frame Sources**: Only allows YouTube-nocookie.com for embedded videos
- **Script Sources**: Restricted to self and necessary YouTube domains
- **Style Sources**: Allows Google Fonts and inline styles
- **Object Sources**: Completely blocked for security
- **Frame Ancestors**: Set to 'none' to prevent clickjacking

### 3. Security Headers
- **X-Content-Type-Options**: nosniff
- **X-Frame-Options**: DENY
- **X-XSS-Protection**: 1; mode=block
- **Referrer-Policy**: strict-origin-when-cross-origin
- **Permissions-Policy**: Restricts geolocation, microphone, camera access

### 4. Authentication Security
- **Shared Account System**: Single learner account prevents credential sharing
- **Role-Based Access Control**: Strict admin/learner separation
- **Session Management**: Secure session handling with timeouts
- **Password Policies**: Strong password requirements enforced

### 5. Content Security
- **YouTube Integration**: Only stores URLs and IDs, never downloads videos
- **Input Sanitization**: All user inputs are properly escaped
- **File Upload Restrictions**: No file upload functionality implemented
- **SQL Injection Protection**: Eloquent ORM with parameter binding

### 6. Route Protection
- **Admin Routes**: Protected by role middleware and rate limiting
- **Authentication Routes**: Rate limited to prevent brute force
- **Signed Routes**: Email verification uses signed URLs
- **Hidden Registration**: Registration routes commented out for security

## 🔧 Configuration

### Environment Variables
```bash
# Rate Limiting
LOGIN_MAX_ATTEMPTS=5
LOGIN_DECAY_MINUTES=1
ADMIN_ACTIONS_MAX_ATTEMPTS=30
ADMIN_ACTIONS_DECAY_MINUTES=1

# Security Headers
SECURITY_HEADERS_ENABLED=true
CSP_ENABLED=true
HSTS_ENABLED=true
```

### Throttle Configuration
```php
// config/throttle.php
'login' => [
    'max_attempts' => env('LOGIN_MAX_ATTEMPTS', 5),
    'decay_minutes' => env('LOGIN_DECAY_MINUTES', 1),
],
```

## 🚨 Security Best Practices

### For Developers
1. **Always escape user input** in Blade templates using `{{ }}` or `{!! !!}` appropriately
2. **Use Form Requests** for validation and authorization
3. **Implement rate limiting** on all public endpoints
4. **Use HTTPS** in production environments
5. **Regular security audits** of dependencies

### For Administrators
1. **Monitor login attempts** for suspicious activity
2. **Regular password updates** for shared learner account
3. **Keep dependencies updated** to latest secure versions
4. **Monitor error logs** for security-related issues
5. **Backup data regularly** with secure storage

## 🔍 Security Monitoring

### Logs to Monitor
- Failed login attempts
- Rate limit violations
- Admin action patterns
- Error logs for security issues

### Security Headers Check
Use browser developer tools or security testing tools to verify:
- Content Security Policy headers
- X-Frame-Options
- X-Content-Type-Options
- Other security headers

## 🆘 Incident Response

### If Security Breach Suspected
1. **Immediate Actions**:
   - Change admin passwords
   - Review access logs
   - Check for unauthorized changes
   - Contact security team

2. **Investigation**:
   - Review authentication logs
   - Check rate limiting violations
   - Analyze user activity patterns
   - Review system changes

3. **Recovery**:
   - Reset compromised accounts
   - Update security configurations
   - Implement additional monitoring
   - Document lessons learned

## 📚 Additional Resources

- [Laravel Security Documentation](https://laravel.com/docs/security)
- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [Content Security Policy](https://developer.mozilla.org/en-US/docs/Web/HTTP/CSP)
- [Rate Limiting Best Practices](https://laravel.com/docs/rate-limiting)

## 📞 Security Contact

For security-related issues or questions:
- **Email**: security@cbacademy.com
- **Emergency**: +1-XXX-XXX-XXXX
- **Response Time**: Within 24 hours for non-critical issues

---

**Last Updated**: {{ date('Y-m-d') }}
**Version**: 1.0
**Security Level**: Enterprise Grade

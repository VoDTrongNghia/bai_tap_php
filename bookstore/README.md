# 📚 PHP Bookstore Project

A clean, well-structured PHP bookstore application with comprehensive frontend logging system.

## 🏗️ Project Structure

```
bookstore/
├── 📁 app/                    # Application logic (MVC)
│   ├── Controllers/           # Request handlers
│   ├── Models/               # Data models
│   ├── Repositories/          # Database queries
│   ├── Database.php           # Database connection
│   └── bootstrap.php          # App initialization
├── 📁 public/                 # Web accessible files
│   ├── assets/               # CSS, JS, images
│   │   ├── js/              # JavaScript files
│   │   │   └── frontend-logger.js  # Logging system
│   │   └── styles/          # CSS files
│   ├── admin/                # Admin panel
│   └── index.php             # Frontend router
├── 📁 views/                  # Template files
│   ├── pages/                # Page templates
│   ├── layouts/              # Layout templates
│   └── partials/             # Reusable components
├── 📁 api/                    # API endpoints
│   └── logs.php              # Frontend logging API
├── 📁 database/               # Database setup
│   ├── create_*.sql          # Table creation scripts
│   ├── insert_*.sql          # Data insertion scripts
│   └── add_category_field.sql # Schema updates
├── 📁 logs/                   # Log storage (database-based)
│   └── .htaccess             # Security configuration
├── config.php                # Application configuration
├── migrate_logs.php          # Database migration for logs
├── logger_demo.php           # Logging system demo
├── logs_dashboard.php        # Real-time log viewer
└── performance_demo.html     # Performance optimization demo
```

## 🚀 Features

### Core Functionality
- **MVC Architecture**: Clean separation of concerns
- **User Authentication**: Login, registration, session management
- **Product Management**: Browse, search, and filter books
- **Shopping Cart**: Add to cart, manage quantities
- **Order Processing**: Checkout and order history
- **Admin Panel**: Manage products, users, and orders

### Frontend Logging System
- **User Interaction Tracking**: Clicks, scrolls, hovers
- **Error Monitoring**: JavaScript errors, failed requests
- **Performance Metrics**: Page load time, image loading, memory usage
- **Real-time Dashboard**: Live log viewing and filtering
- **Session Analytics**: User behavior analysis

### Performance Optimizations
- **Lazy Loading**: Images load on demand
- **Database Optimization**: Efficient queries with pagination
- **CSS GPU Acceleration**: Smooth animations
- **WebP Image Format**: Optimized banner images

## 📊 Logging System

The frontend logging system provides comprehensive monitoring:

### What's Tracked
- User interactions (clicks, scrolls, hovers)
- Image loading performance and errors
- JavaScript errors and exceptions
- Page performance metrics
- Session information and device data

### Access Points
- **Dashboard**: `/logs_dashboard.php` - Real-time log viewer
- **Demo**: `/logger_demo.php` - Interactive testing interface
- **API**: `/api/logs.php` - Log data endpoint

## 🛠️ Setup Instructions

### 1. Database Setup
```bash
# Import database tables
mysql -u username -p database_name < database/create_books_table.sql
mysql -u username -p database_name < database/create_nguoi_dung_table.sql
mysql -u username -p database_name < database/create_orders_table.sql

# Insert sample data
mysql -u username -p database_name < database/insert_sample_books.sql
mysql -u username -p database_name < database/insert_admin_account.sql

# Create logging tables
php migrate_logs.php
```

### 2. Configuration
Edit `config.php` with your database credentials:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'bookstore');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
```

### 3. Web Server
Ensure your web server points to the `public/` directory as the document root.

## 🔧 Admin Access

Default admin credentials:
- Email: admin@example.com
- Password: 123456

## 📱 Usage

### For Users
1. Browse books on homepage
2. Search for specific titles
3. Add books to cart
4. Checkout and place orders
5. View order history

### For Developers
1. Monitor frontend performance via `/logs_dashboard.php`
2. Test logging features with `/logger_demo.php`
3. View performance optimizations at `/performance_demo.html`

## 🧹 Clean Structure Benefits

- **Maintainable**: Clear separation of concerns
- **Scalable**: Modular architecture
- **Debuggable**: Comprehensive logging system
- **Performant**: Optimized database queries and frontend
- **Secure**: Proper access controls and validation

## 📝 Notes

- All logs are stored in database tables (not files)
- Old logs (30+ days) are automatically cleaned up
- Performance monitoring is enabled by default
- The logging system has minimal impact on page performance

## 🤝 Contributing

When adding new features:
1. Follow the MVC pattern
2. Add appropriate logging for user interactions
3. Update the database schema if needed
4. Test with the demo pages

---

**Project Status**: ✅ Production Ready
**Last Updated**: November 2025

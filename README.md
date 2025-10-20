# Sihat Al-Hajj Platform 🕋

A comprehensive healthcare management platform designed specifically for Hajj pilgrims, providing real-time medical support, health tracking, and emergency services during the pilgrimage.

## 📋 Project Description

**Sihat Al-Hajj** (صحة الحاج) is an innovative digital healthcare platform that bridges the gap between pilgrims and medical services during Hajj. The platform enables pilgrims to access medical assistance, track their health data, request emergency services, and communicate with healthcare providers seamlessly. Built with modern web technologies and following Saudi Vision 2030 principles, the platform ensures a safe and healthy pilgrimage experience.

### Key Objectives
- Provide instant access to medical services for pilgrims
- Enable real-time health monitoring and tracking
- Facilitate communication between pilgrims and healthcare providers
- Streamline medical request management and emergency response
- Support multilingual access for international pilgrims
- Ensure data security and privacy compliance

## ✨ Features

### For Pilgrims
- 🏥 **Medical Request System**: Submit and track medical assistance requests with urgency levels
- 📊 **Health Data Tracking**: Record vital signs, medications, allergies, and medical history
- 🚨 **Emergency Services**: Quick access to emergency contacts and location sharing
- 📱 **QR Code Profile**: Instant medical information access for healthcare providers
- 🌍 **Location Services**: Find nearby medical centers and teams
- 📝 **Digital Health Records**: Secure storage of medical documents and prescriptions
- 🔔 **Real-time Notifications**: Updates on medical requests and health alerts

### For Medical Personnel
- 👨‍⚕️ **Request Management**: View, assign, and respond to medical requests
- 📋 **Patient Information**: Access pilgrim health records and medical history
- 🗺️ **Team Coordination**: Track medical team locations and availability
- 📈 **Performance Analytics**: Monitor response times and service quality
- 💬 **Communication Tools**: Direct messaging with pilgrims and team members

### For Administrators
- 👥 **User Management**: Create, edit, and manage all user accounts
- 📊 **System Dashboard**: Real-time statistics and system health monitoring
- 🏥 **Medical Centers Management**: Manage medical facilities and their services
- 👨‍⚕️ **Medical Teams Management**: Coordinate medical teams and their assignments
- 🛠️ **Services Management**: Configure digital services and features
- 📈 **Analytics & Reports**: Comprehensive reporting and data visualization
- 🌐 **Content Management**: Manage multilingual content and system settings

### Technical Features
- 🌍 **Bilingual Support**: Full Arabic and English support with RTL/LTR layouts
- 🎨 **Theme System**: Light and dark theme switching with user preferences
- 📱 **Responsive Design**: Mobile-first design optimized for all devices
- 🏗️ **MVC Architecture**: Clean, maintainable code structure
- 🔐 **Security**: CSRF protection, input validation, and secure authentication
- 🔄 **Real-time Updates**: Dynamic content updates without page refresh
- 🚀 **Performance Optimized**: Fast loading times and efficient database queries
- 📡 **API Ready**: RESTful API endpoints for mobile app integration

## 🛠️ Technologies

### Backend
- **PHP 7.4+**: Server-side programming language
- **MySQL 5.7+**: Relational database management system
- **Custom MVC Framework**: Laravel-inspired architecture
- **PDO**: Secure database connections with prepared statements

### Frontend
- **HTML5 & CSS3**: Modern web standards
- **Tailwind CSS 3.x**: Utility-first CSS framework
- **JavaScript (Vanilla)**: Interactive user interfaces
- **Font Awesome 6**: Icon library
- **Alpine.js**: Lightweight JavaScript framework for interactivity

### Server Requirements
- **Web Server**: Apache 2.4+ or Nginx 1.16+
- **PHP**: Version 7.4 or higher
- **Database**: MySQL 5.7+ or MariaDB 10.2+
- **PHP Extensions**: PDO, Mbstring, OpenSSL, JSON, Ctype, XML

### Development Tools
- **Node.js 14+**: For Tailwind CSS compilation
- **Git**: Version control system
- **XAMPP/WAMP/MAMP**: Local development environment

## 🚀 Installation & Setup

### Step 1: Clone the Repository

```bash
# Clone the project
git clone <repository-url> sihat-al-haj
cd sihat-al-haj

# Or download and extract the ZIP file to your web server directory
# For XAMPP: C:\xampp\htdocs\sihat-al-haj
# For WAMP: C:\wamp64\www\sihat-al-haj
# For MAMP: /Applications/MAMP/htdocs/sihat-al-haj
```

### Step 2: Create Database

1. **Open phpMyAdmin** or MySQL command line
2. **Create a new database** named `sihat_al_haj`:

```sql
CREATE DATABASE sihat_al_haj CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Step 3: Import Database

**Using phpMyAdmin:**
1. Select the `sihat_al_haj` database
2. Click on the "Import" tab
3. Choose the file: `database/sihat_al_haj.sql`
4. Click "Go" to import

**Using MySQL Command Line:**
```bash
mysql -u root -p sihat_al_haj < database/sihat_al_haj.sql
```

**Using XAMPP:**
```bash
cd C:\xampp\mysql\bin
mysql -u root sihat_al_haj < C:\xampp\htdocs\sihat-al-haj\database\sihat_al_haj.sql
```

### Step 4: Configure Database Connection

1. **Open** `config/database.php`
2. **Update** the database credentials:

```php
return [
    'host' => 'localhost',
    'database' => 'sihat_al_haj',
    'username' => 'root',        // Your MySQL username
    'password' => '',            // Your MySQL password (empty for XAMPP)
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
];
```

### Step 5: Start Your Server

**For XAMPP:**
1. Open XAMPP Control Panel
2. Start Apache and MySQL services
3. Access the project at: `http://localhost/sihat-al-haj`

**For WAMP:**
1. Start WAMP server
2. Access the project at: `http://localhost/sihat-al-haj`

**For MAMP:**
1. Start MAMP server
2. Access the project at: `http://localhost:8888/sihat-al-haj`

### Step 6: Set Permissions (Linux/Mac)

```bash
chmod -R 755 storage/
chmod -R 755 public/uploads/
```

## 🔐 Admin Access

After installation, you can access the admin panel with the following credentials:

**Admin Login URL:** `http://localhost/sihat-al-haj/login`

**Admin Credentials:**
- **Email:** `admin@sihatalhaj.com`
- **Phone:** `+9661111111111`
- **ID Number:** `1111111111`
- **Password:** `555555555`

> ⚠️ **Important:** Change the admin password immediately after first login for security purposes.

### User Roles

The platform supports four user types:

1. **Administrator** (`administrator`)
   - Full system access
   - User management
   - System configuration
   - Analytics and reports

2. **Medical Personnel** (`medical_personnel`)
   - Manage medical requests
   - Access patient information
   - Team coordination

3. **Pilgrim** (`pilgrim`)
   - Personal health tracking
   - Submit medical requests
   - Access medical services

## 📁 Project Structure

```
sihat-al-haj/
├── app/
│   ├── Controllers/          # Application controllers
│   │   ├── Admin/           # Admin panel controllers
│   │   ├── Auth/            # Authentication controllers
│   │   └── Public/          # Public page controllers
│   ├── Models/              # Database models
│   ├── Views/               # View templates
│   │   ├── layouts/         # Layout templates
│   │   ├── components/      # Reusable components
│   │   └── pages/           # Page views
│   └── Core/                # Core framework classes
├── config/                  # Configuration files
│   ├── database.php         # Database configuration
│   └── app.php              # Application settings
├── database/                # Database files
│   └── sihat_al_haj.sql     # Database schema and data
├── public/                  # Public web files
│   ├── assets/              # Static assets
│   │   ├── css/            # Stylesheets
│   │   ├── js/             # JavaScript files
│   │   └── images/         # Images and icons
│   ├── uploads/             # User uploaded files
│   └── index.php            # Application entry point
├── resources/               # Resources
│   └── lang/                # Language files
│       ├── ar.php          # Arabic translations
│       └── en.php          # English translations
├── routes/                  # Route definitions
│   └── web.php              # Web routes
├── storage/                 # Storage directory
│   └── logs/                # Application logs
└── README.md                # This file
```

## 🌐 Language Support

The platform supports both Arabic and English:

- **Arabic (العربية)**: Default language with RTL layout
- **English**: Full English translation with LTR layout
- **Language Switching**: Users can switch languages from the navigation bar
- **Translation Files**: Located in `resources/lang/`

## 🎨 Theme Support

- **Light Theme**: Default bright theme
- **Dark Theme**: Eye-friendly dark mode
- **Theme Persistence**: User preference saved in browser
- **Automatic Switching**: Toggle from navigation bar

## 🔒 Security Features

- ✅ CSRF protection on all forms
- ✅ Input validation and sanitization
- ✅ SQL injection prevention (prepared statements)
- ✅ XSS protection with output escaping
- ✅ Secure password hashing (bcrypt)
- ✅ Session security and management
- ✅ Role-based access control
- ✅ Secure file upload handling

## 🐛 Troubleshooting

### Common Issues

**Database Connection Error:**
- Verify MySQL is running
- Check credentials in `config/database.php`
- Ensure database `sihat_al_haj` exists

**Page Not Found (404):**
- Check `.htaccess` file exists in root
- Verify Apache `mod_rewrite` is enabled
- Clear browser cache

**Language Not Switching:**
- Check language files in `resources/lang/`
- Clear browser cookies
- Verify session is working

**Styles Not Loading:**
- Check `public/assets/css/app.css` exists
- Verify file permissions
- Clear browser cache

## 📞 Support

For technical support or questions:
- Review error logs in `storage/logs/`
- Check database connection settings
- Verify file permissions
- Contact the development team

## 📄 License

This platform is proprietary software developed for the Sihat Al-Hajj project.
All rights reserved © 2024

## 🙏 Acknowledgments

Developed in alignment with Saudi Vision 2030 to enhance healthcare services during Hajj pilgrimage.

---

**Made with ❤️ for the safety and health of Hajj pilgrims**
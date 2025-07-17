# POS System - User Role Selection

A Laravel-based Point of Sale (POS) system with role-based user authentication and dashboards.

## Features

- **Role-based User Selection**: Choose between Administrator, Supervisor, and Cashier roles
- **Interactive UI**: Beautiful, responsive design with smooth animations
- **Role-specific Dashboards**: Each role has a customized dashboard with relevant information
- **User Registration**: Complete registration system with role selection
- **Email Verification**: Secure email verification system for new accounts
- **Demo Authentication**: Pre-configured demo credentials for testing

## User Roles & Demo Credentials

### Administrator
- **Username**: `admin`
- **Password**: `admin123`
- **Features**: Full system access, user management, system settings, reports

### Supervisor
- **Username**: `supervisor`
- **Password**: `super123`
- **Features**: Team management, performance monitoring, shift scheduling

### Cashier
- **Username**: `cashier`
- **Password**: `cash123`
- **Features**: Sales processing, transaction management, inventory viewing

## Installation & Setup

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd pos-mini-project
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup** (optional for demo)
   ```bash
   php artisan migrate
   ```

5. **Start the development server**
   ```bash
   php artisan serve
   ```

6. **Access the application**
   - Open your browser and go to `http://localhost:8000`
   - You'll see the role selection page with three buttons

## How to Use

### Demo Login
1. **Select a Role**: Click on one of the three role buttons (Administrator, Supervisor, or Cashier)
2. **Login**: The login form will appear with pre-filled username based on the selected role
3. **Enter Credentials**: Use the demo credentials listed above
4. **Access Dashboard**: After successful login, you'll be redirected to the role-specific dashboard
5. **Logout**: Use the logout button in the dashboard to return to the role selection page

### New User Registration
1. **Access Registration**: Click the "Register" button in any dashboard
2. **Fill Form**: Complete the registration form with your details
3. **Select Role**: Choose your preferred role (Administrator, Supervisor, or Cashier)
4. **Verify Email**: Check your email and click the verification link
5. **Login**: Use your new credentials to log in to the system

## File Structure

```
pos-mini-project/
├── app/
│   ├── Http/Controllers/
│   │   └── AuthController.php          # Handles authentication logic
│   ├── Models/
│   │   └── User.php                    # User model with role support
│   └── Notifications/
│       └── EmailVerificationNotification.php  # Email verification
├── resources/views/
│   ├── home.blade.php                  # Main role selection page
│   ├── auth/
│   │   ├── register.blade.php          # Registration form
│   │   └── email-verified.blade.php    # Email verification success
│   ├── emails/
│   │   └── verify-email.blade.php      # Email verification template
│   └── dashboard/
│       ├── administrator.blade.php     # Administrator dashboard
│       ├── supervisor.blade.php        # Supervisor dashboard
│       └── cashier.blade.php           # Cashier dashboard
├── routes/
│   └── web.php                         # Application routes
└── README.md                           # This file
```

## Technical Details

- **Framework**: Laravel 10
- **Frontend**: Tailwind CSS for styling
- **Authentication**: Session-based with demo credentials and database users
- **Email Verification**: Laravel's built-in email verification system
- **Database**: MySQL/PostgreSQL with user roles and verification
- **Responsive Design**: Works on desktop and mobile devices
- **AJAX**: Form submission using fetch API

## Customization

### Adding New Roles
1. Add the role to the `$demoUsers` array in `AuthController.php`
2. Create a new dashboard view in `resources/views/dashboard/`
3. Update the role selection buttons in `home.blade.php`

### Modifying Dashboards
Each dashboard view can be customized independently:
- `administrator.blade.php` - Purple theme, system management features
- `supervisor.blade.php` - Blue theme, team management features  
- `cashier.blade.php` - Green theme, sales and transaction features

### Styling
The application uses Tailwind CSS. You can modify the classes in the blade templates to change the appearance.

## Security Notes

⚠️ **Important**: This is a demo application with hardcoded credentials. For production use:

1. ✅ Implement proper database authentication (already implemented)
2. ✅ Use Laravel's built-in authentication system (already implemented)
3. ✅ Hash passwords properly (already implemented)
4. ✅ Implement proper session management (already implemented)
5. ✅ Add CSRF protection (already included)
6. ✅ Use environment variables for sensitive data (already implemented)
7. ✅ Email verification system (already implemented)

### Email Configuration
For email verification to work in production, configure your email settings in `.env`:
```
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="POS System"
```

For development, emails are logged to `storage/logs/laravel.log`.

## Support

For questions or issues, please refer to the Laravel documentation or create an issue in the repository.
"# pos-projecy" 

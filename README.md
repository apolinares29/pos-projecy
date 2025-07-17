# POS System - User Role Selection

A Laravel-based Point of Sale (POS) system with role-based user authentication, product management, and dashboards.

## Features

- **Role-based User Selection**: Administrator, Supervisor, and Cashier roles
- **Interactive UI**: Responsive design with smooth animations
- **Role-specific Dashboards**: Customized dashboards for each role
- **User Registration & Email Verification**
- **Product Management**: Add, edit, and manage products (with images and discounts)
- **Sales Processing**: Manage sales and sale items
- **Activity Logs**: Track system activities
- **System Analytics & Reports**
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
   cd pos-projecy
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

4. **Database setup**
   - Create a database and update your `.env` file with the correct DB credentials.
   - Run migrations:
     ```bash
     php artisan migrate
     ```
   - (Optional) Seed dummy data for products:
     ```bash
     php artisan db:seed --class=ProductSeeder
     ```
   - To seed all data (including users, products, sales, etc.):
     ```bash
     php artisan db:seed
     ```
   - To reset and re-seed everything:
     ```bash
     php artisan migrate:fresh --seed
     ```

5. **Start the development server**
   ```bash
   php artisan serve
   ```

6. **Access the application**
   - Open your browser and go to `http://localhost:8000`

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

### Product Management & Seeding
- To add dummy products for testing, run:
  ```bash
  php artisan db:seed --class=ProductSeeder
  ```
- Product images are stored in `public/products/`.
- You can manage products from the Administrator or Supervisor dashboard.

## File Structure

```
pos-projecy/
├── app/
│   ├── Helpers/                  # Activity logger and helpers
│   ├── Http/Controllers/         # Controllers for all roles and features
│   ├── Models/                   # Eloquent models (User, Product, Sale, etc.)
│   ├── Notifications/            # Email and 2FA notifications
│   └── Providers/                # Service providers
├── config/                       # Configuration files
├── database/
│   ├── factories/                # Model factories
│   ├── migrations/               # Database migrations
│   └── seeders/                  # Seeders (ProductSeeder, etc.)
├── public/
│   └── products/                 # Uploaded product images
├── resources/
│   ├── views/                    # Blade templates for all roles and features
│   └── css/js/                   # Frontend assets
├── routes/
│   └── web.php                   # Application routes
├── storage/                      # Logs, cache, etc.
└── README.md                     # This file
```

## Technical Details

- **Framework**: Laravel 10
- **Frontend**: Tailwind CSS
- **Authentication**: Session-based, email verification, 2FA
- **Database**: MySQL/PostgreSQL
- **Product Images**: Uploaded and stored in `public/products/`
- **Activity Logs**: Track user/system actions
- **Responsive Design**: Works on desktop and mobile

## Customization

### Adding New Roles
1. Add the role to the relevant logic in `User.php` and controllers
2. Create a new dashboard view in `resources/views/dashboard/`
3. Update the role selection in `home.blade.php`

### Modifying Dashboards
Each dashboard view can be customized independently:
- `administrator.blade.php` - System management features
- `supervisor.blade.php` - Team management features
- `cashier.blade.php` - Sales and transaction features

### Styling
The application uses Tailwind CSS. Modify classes in blade templates to change appearance.

## Security Notes

⚠️ **Important**: This is a demo application. For production use:

- Use secure, unique credentials
- Configure environment variables for sensitive data
- Ensure email and session security
- Remove or update demo users

### Email Configuration
For email verification, configure your email settings in `.env`:
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

# RS Sehat Selalu - Hospital Queue System Setup Guide

## System Overview

A digital queuing system for Rumah Sakit "Sehat Selalu" built with Laravel 12, Livewire 3, and PostgreSQL.

### Technology Stack
- **Backend**: Laravel 12
- **Frontend**: Livewire 3, TailwindCSS
- **Database**: PostgreSQL (Railway)
- **Authentication**: Laravel Socialite (Google OAuth)

## Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js & NPM
- PostgreSQL database (Railway account)
- Google Cloud Console account (for OAuth)

## Installation Steps

### 1. Clone and Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### 2. Environment Configuration

```bash
# Copy environment file
copy .env.example .env

# Generate application key
php artisan key:generate
```

### 3. Configure Database (Railway PostgreSQL)

1. Create a PostgreSQL database on Railway (https://railway.app)
2. Get your database credentials from Railway
3. Update `.env` file:

```env
DB_CONNECTION=pgsql
DB_HOST=your-railway-host.railway.app
DB_PORT=5432
DB_DATABASE=railway
DB_USERNAME=postgres
DB_PASSWORD=your-railway-password
```

### 4. Configure Google OAuth

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select existing one
3. Enable Google+ API
4. Create OAuth 2.0 credentials:
   - Application type: Web application
   - Authorized redirect URIs: `http://localhost:8000/auth/google/callback` (development)
   - For production: `https://your-domain.com/auth/google/callback`

5. Update `.env` file:

```env
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

### 5. Run Migrations and Seeders

```bash
# Run migrations
php artisan migrate

# Seed initial loket data
php artisan db:seed --class=LoketSeeder
```

### 6. Build Assets

```bash
# Build frontend assets
npm run build

# Or for development with hot reload
npm run dev
```

### 7. Start the Application

```bash
# Start Laravel development server
php artisan serve

# The application will be available at http://localhost:8000
```

## System Features

### 1. Patient Queue Page (`/pasien`)
- Public access
- Select loket (service counter)
- Generate queue number automatically
- Display assigned queue number

### 2. Staff Panel (`/petugas`)
- Requires Google authentication
- Select assigned loket
- View waiting queues
- Call next patient
- Mark queue as completed
- Real-time updates every 3 seconds

### 3. Display Screen (`/display`)
- Public access
- Shows all currently called queue numbers
- Real-time updates every 3 seconds
- Full-screen display mode
- Shows loket name and queue number

## Database Schema

### Table: `lokets`
- `id`: Primary Key
- `nama_loket`: String (e.g., "Pendaftaran Umum", "Poli Gigi")
- `deskripsi`: Text (optional)
- `created_at`, `updated_at`: Timestamps

### Table: `antrians`
- `id`: Primary Key
- `loket_id`: Foreign Key → lokets
- `nomor_antrian`: String (e.g., "A001", "B003")
- `status`: Enum ('menunggu', 'dipanggil', 'selesai')
- `waktu_panggil`: Timestamp (nullable)
- `created_at`, `updated_at`: Timestamps

### Table: `users`
- `id`: Primary Key
- `name`: String
- `email`: String (unique)
- `google_id`: String (nullable, unique)
- `avatar`: String (nullable)
- `password`: String (nullable)
- `created_at`, `updated_at`: Timestamps

## API Endpoints

### Loket Management
- `GET /api/lokets` - Get all lokets
- `POST /api/lokets` - Create new loket
- `GET /api/lokets/{id}` - Get specific loket
- `PUT /api/lokets/{id}` - Update loket
- `DELETE /api/lokets/{id}` - Delete loket

### Queue Management
- `POST /api/antrians/ambil` - Take new queue number
  - Body: `{ "loket_id": 1 }`
- `PATCH /api/antrians/{id}/status` - Update queue status
  - Body: `{ "status": "dipanggil" }`
- `GET /api/antrians/current` - Get currently called queues
- `GET /api/antrians/menunggu?loket_id=1` - Get waiting queues for loket
- `GET /api/antrians/history` - Get queue history (paginated)

## Deployment to Railway

### 1. Prepare for Production

Update `.env` for production:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app.railway.app

# Update Google OAuth redirect URI
GOOGLE_REDIRECT_URI=https://your-app.railway.app/auth/google/callback
```

### 2. Deploy to Railway

1. Install Railway CLI: `npm install -g @railway/cli`
2. Login: `railway login`
3. Initialize project: `railway init`
4. Link to PostgreSQL: `railway add postgresql`
5. Deploy: `railway up`

### 3. Run Migrations on Railway

```bash
railway run php artisan migrate --force
railway run php artisan db:seed --class=LoketSeeder
```

## Troubleshooting

### Database Connection Issues
- Verify Railway PostgreSQL credentials
- Check if Railway database is active
- Ensure IP whitelist includes your server

### Google OAuth Issues
- Verify redirect URI matches exactly
- Check if Google+ API is enabled
- Ensure credentials are correct in `.env`

### Livewire Not Updating
- Clear cache: `php artisan cache:clear`
- Clear config: `php artisan config:clear`
- Rebuild assets: `npm run build`

## Security Considerations

1. **Never commit `.env` file** - Contains sensitive credentials
2. **Use HTTPS in production** - Required for Google OAuth
3. **Keep dependencies updated** - Run `composer update` regularly
4. **Implement rate limiting** - Prevent API abuse
5. **Regular database backups** - Use Railway's backup features

## Support

For issues or questions, please refer to:
- Laravel Documentation: https://laravel.com/docs
- Livewire Documentation: https://livewire.laravel.com
- Railway Documentation: https://docs.railway.app

## License

This project is proprietary software for RS Sehat Selalu.

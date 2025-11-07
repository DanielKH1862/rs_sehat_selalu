# Quick Start Guide - RS Sehat Selalu Queue System

## 🚀 Get Started in 5 Minutes

### Step 1: Install Dependencies (2 min)

```bash
composer install
npm install
```

### Step 2: Configure Environment (1 min)

```bash
# Copy environment file
copy .env.example .env

# Generate app key
php artisan key:generate
```

### Step 3: Setup Database (1 min)

Update `.env` with your Railway PostgreSQL credentials:

```env
DB_CONNECTION=pgsql
DB_HOST=your-railway-host.railway.app
DB_PORT=5432
DB_DATABASE=railway
DB_USERNAME=postgres
DB_PASSWORD=your-password
```

Run migrations:

```bash
php artisan migrate
php artisan db:seed --class=LoketSeeder
```

### Step 4: Configure Google OAuth (Optional - for staff login)

1. Get credentials from [Google Cloud Console](https://console.cloud.google.com/)
2. Update `.env`:

```env
GOOGLE_CLIENT_ID=your-client-id
GOOGLE_CLIENT_SECRET=your-secret
```

### Step 5: Start Application (1 min)

```bash
# Terminal 1: Start Laravel
php artisan serve

# Terminal 2: Build assets
npm run dev
```

Visit: **http://localhost:8000**

## 📱 Using the System

### For Patients
1. Go to **http://localhost:8000/pasien**
2. Select your service (loket)
3. Click "Ambil Nomor Antrian"
4. Your queue number will be displayed

### For Staff
1. Go to **http://localhost:8000/login**
2. Login with Google
3. Select your loket
4. Click "Panggil" to call next patient
5. Click "Selesai" when done

### For Display Screen
1. Go to **http://localhost:8000/display**
2. Full-screen mode recommended (F11)
3. Shows all currently called queues
4. Auto-updates every 3 seconds

## 🎯 Default Lokets (Service Counters)

After seeding, you'll have:
- **Pendaftaran Umum** (Registration)
- **Poli Gigi** (Dental)
- **Poli Umum** (General Practice)
- **Farmasi** (Pharmacy)
- **Kasir** (Cashier)

## 🔧 Common Commands

```bash
# Clear all caches
php artisan optimize:clear

# Run migrations
php artisan migrate:fresh --seed

# Check routes
php artisan route:list

# Run tests
php artisan test
```

## 📊 API Testing

Test the API with curl or Postman:

```bash
# Get all lokets
curl http://localhost:8000/api/lokets

# Take a queue number
curl -X POST http://localhost:8000/api/antrians/ambil \
  -H "Content-Type: application/json" \
  -d '{"loket_id": 1}'

# Get current queues
curl http://localhost:8000/api/antrians/current
```

## 🐛 Troubleshooting

**Database connection failed?**
- Check Railway database is running
- Verify credentials in `.env`
- Test connection: `php artisan migrate:status`

**Livewire not working?**
- Clear cache: `php artisan cache:clear`
- Rebuild assets: `npm run build`

**Google login not working?**
- Check redirect URI matches exactly
- Verify credentials in `.env`
- Enable Google+ API in Cloud Console

## 📚 Next Steps

- Read [SETUP.md](SETUP.md) for detailed setup
- Read [ARCHITECTURE.md](ARCHITECTURE.md) for system design
- Configure production deployment on Railway

## 🎉 You're Ready!

The system is now running. Test all three interfaces:
- Patient page: Take queue numbers
- Staff panel: Manage queues
- Display screen: View called queues

Happy queueing! 🏥

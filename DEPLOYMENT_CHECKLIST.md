# Deployment Checklist - RS Sehat Selalu

## Pre-Deployment Checklist

### 1. Local Testing ✓
- [ ] Run `composer install` successfully
- [ ] Run `npm install` successfully
- [ ] Database migrations work: `php artisan migrate`
- [ ] Seeders work: `php artisan db:seed --class=LoketSeeder`
- [ ] All pages load correctly:
  - [ ] Home page (`/`)
  - [ ] Patient page (`/pasien`)
  - [ ] Staff panel (`/petugas`)
  - [ ] Display screen (`/display`)
  - [ ] Login page (`/login`)
- [ ] Google OAuth login works
- [ ] Queue generation works
- [ ] Queue calling works
- [ ] Real-time updates work (3s polling)
- [ ] API endpoints respond correctly

### 2. Code Quality ✓
- [ ] No PHP errors or warnings
- [ ] No JavaScript console errors
- [ ] All routes defined and working
- [ ] Database relationships correct
- [ ] Input validation in place
- [ ] Error handling implemented

### 3. Security ✓
- [ ] `.env` file not in version control
- [ ] `.gitignore` properly configured
- [ ] CSRF protection enabled
- [ ] SQL injection protection (Eloquent)
- [ ] XSS protection (Blade escaping)
- [ ] Secure session configuration

---

## Railway Deployment Steps

### Step 1: Create Railway Account
1. Go to https://railway.app
2. Sign up with GitHub
3. Verify email

### Step 2: Create PostgreSQL Database
1. Click "New Project"
2. Select "Provision PostgreSQL"
3. Note down connection details:
   - Host
   - Port
   - Database name
   - Username
   - Password

### Step 3: Create Web Service
1. In same project, click "New"
2. Select "GitHub Repo"
3. Connect your repository
4. Railway will auto-detect Laravel

### Step 4: Configure Environment Variables

In Railway dashboard, add these variables:

```env
APP_NAME=RS Sehat Selalu
APP_ENV=production
APP_KEY=base64:... (generate with php artisan key:generate --show)
APP_DEBUG=false
APP_URL=https://your-app.railway.app

DB_CONNECTION=pgsql
DB_HOST=${{Postgres.PGHOST}}
DB_PORT=${{Postgres.PGPORT}}
DB_DATABASE=${{Postgres.PGDATABASE}}
DB_USERNAME=${{Postgres.PGUSER}}
DB_PASSWORD=${{Postgres.PGPASSWORD}}

SESSION_DRIVER=database
SESSION_LIFETIME=120

GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI=https://your-app.railway.app/auth/google/callback
```

### Step 5: Configure Build Settings

In Railway settings:
- **Build Command**: `composer install --no-dev && npm install && npm run build`
- **Start Command**: `php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT`

### Step 6: Deploy

1. Push code to GitHub
2. Railway auto-deploys
3. Monitor build logs
4. Wait for deployment to complete

### Step 7: Run Post-Deployment Commands

Using Railway CLI:

```bash
# Install Railway CLI
npm install -g @railway/cli

# Login
railway login

# Link to project
railway link

# Run migrations
railway run php artisan migrate --force

# Seed initial data
railway run php artisan db:seed --class=LoketSeeder

# Clear and cache config
railway run php artisan config:cache
railway run php artisan route:cache
railway run php artisan view:cache
```

### Step 8: Configure Custom Domain (Optional)

1. In Railway dashboard, go to Settings
2. Click "Generate Domain" or add custom domain
3. Update `APP_URL` in environment variables
4. Update Google OAuth redirect URI

---

## Google OAuth Configuration

### Development Setup
1. Go to https://console.cloud.google.com
2. Create new project: "RS Sehat Selalu"
3. Enable APIs: Google+ API
4. Create OAuth 2.0 credentials:
   - Application type: Web application
   - Name: "RS Sehat Selalu - Dev"
   - Authorized redirect URIs:
     - `http://localhost:8000/auth/google/callback`

### Production Setup
1. In same Google Cloud project
2. Add production redirect URI:
   - `https://your-app.railway.app/auth/google/callback`
3. Or create separate OAuth credentials for production
4. Update `.env` with production credentials

---

## Post-Deployment Verification

### Functional Testing
- [ ] Visit home page - loads correctly
- [ ] Visit `/pasien` - can select loket
- [ ] Generate queue number - works
- [ ] Visit `/login` - Google OAuth button visible
- [ ] Login with Google - redirects correctly
- [ ] Visit `/petugas` - requires authentication
- [ ] Select loket - loads queues
- [ ] Call patient - status updates
- [ ] Complete service - queue marked done
- [ ] Visit `/display` - shows called queues
- [ ] Real-time updates - polling works
- [ ] API endpoints - respond correctly

### Performance Testing
- [ ] Page load times < 3 seconds
- [ ] Database queries optimized
- [ ] No N+1 query problems
- [ ] Real-time updates smooth
- [ ] No memory leaks

### Security Testing
- [ ] HTTPS enabled (Railway default)
- [ ] `/petugas` requires authentication
- [ ] Google OAuth works
- [ ] Session management secure
- [ ] CSRF tokens present
- [ ] No sensitive data in logs

---

## Monitoring & Maintenance

### Daily Checks
- [ ] Check Railway logs for errors
- [ ] Verify database connection
- [ ] Test queue generation
- [ ] Monitor response times

### Weekly Checks
- [ ] Review error logs
- [ ] Check database size
- [ ] Verify backups
- [ ] Test all features

### Monthly Checks
- [ ] Update dependencies: `composer update`
- [ ] Update npm packages: `npm update`
- [ ] Review security advisories
- [ ] Optimize database
- [ ] Review and archive old queues

---

## Backup Strategy

### Database Backups
Railway provides automatic backups:
- Daily snapshots
- 7-day retention
- Manual backup option

### Manual Backup
```bash
# Export database
railway run pg_dump > backup.sql

# Import database
railway run psql < backup.sql
```

### Code Backups
- GitHub repository (version control)
- Regular commits
- Tagged releases

---

## Rollback Plan

If deployment fails:

1. **Check Logs**
   ```bash
   railway logs
   ```

2. **Rollback to Previous Deployment**
   - In Railway dashboard
   - Go to Deployments
   - Click previous successful deployment
   - Click "Redeploy"

3. **Database Rollback**
   ```bash
   railway run php artisan migrate:rollback
   ```

4. **Clear Caches**
   ```bash
   railway run php artisan cache:clear
   railway run php artisan config:clear
   railway run php artisan route:clear
   railway run php artisan view:clear
   ```

---

## Support Contacts

### Technical Support
- Laravel Documentation: https://laravel.com/docs
- Livewire Documentation: https://livewire.laravel.com
- Railway Support: https://railway.app/help

### Emergency Contacts
- System Administrator: [Your contact]
- Database Administrator: [Your contact]
- Railway Support: support@railway.app

---

## Success Criteria

Deployment is successful when:
- ✅ All pages load without errors
- ✅ Database migrations completed
- ✅ Google OAuth login works
- ✅ Queue generation works
- ✅ Queue management works
- ✅ Display screen updates in real-time
- ✅ API endpoints respond correctly
- ✅ HTTPS enabled
- ✅ No console errors
- ✅ Performance acceptable (< 3s page load)

---

## Quick Commands Reference

```bash
# Local Development
php artisan serve
npm run dev
php artisan migrate
php artisan db:seed

# Railway Deployment
railway login
railway link
railway up
railway run php artisan migrate --force
railway run php artisan db:seed --class=LoketSeeder

# Cache Management
railway run php artisan config:cache
railway run php artisan route:cache
railway run php artisan view:cache
railway run php artisan optimize

# Debugging
railway logs
railway run php artisan tinker
railway run php artisan route:list
```

---

## Final Checklist Before Go-Live

- [ ] All environment variables set
- [ ] Database migrations run
- [ ] Initial data seeded
- [ ] Google OAuth configured
- [ ] Custom domain configured (if applicable)
- [ ] HTTPS enabled
- [ ] All pages tested
- [ ] Performance acceptable
- [ ] Backups configured
- [ ] Monitoring in place
- [ ] Documentation complete
- [ ] Team trained on system
- [ ] Support contacts documented

---

**Deployment Date**: _______________  
**Deployed By**: _______________  
**Railway URL**: _______________  
**Status**: ⬜ Pending | ⬜ In Progress | ⬜ Complete

---

Good luck with your deployment! 🚀

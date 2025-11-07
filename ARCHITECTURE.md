# RS Sehat Selalu - System Architecture Documentation

## System Design Overview

This document details the complete architecture, database design, API specifications, and frontend component logic for the "Sehat Selalu" Hospital Queue System.

## Core Design Principles

| Principle | Description |
|-----------|-------------|
| **Technology Stack** | Laravel 12 (backend), Livewire 3 (frontend), PostgreSQL (database), Railway (hosting) |
| **Real-Time Responsiveness** | Near real-time updates using Livewire's wire:poll (3-second intervals) |
| **Ease of Use** | Intuitive interfaces for both patients and staff |
| **Secure Access** | Gmail OAuth authentication for staff access |
| **Data Integrity** | Clear queue status management and proper relational integrity |

## Database Design

### Entity Relationship Diagram

```
┌─────────────────┐         ┌──────────────────┐
│     lokets      │         │    antrians      │
├─────────────────┤         ├──────────────────┤
│ id (PK)         │────┐    │ id (PK)          │
│ nama_loket      │    └───<│ loket_id (FK)    │
│ deskripsi       │         │ nomor_antrian    │
│ created_at      │         │ status           │
│ updated_at      │         │ waktu_panggil    │
└─────────────────┘         │ created_at       │
                            │ updated_at       │
                            └──────────────────┘

┌─────────────────┐
│     users       │
├─────────────────┤
│ id (PK)         │
│ name            │
│ email (unique)  │
│ google_id       │
│ avatar          │
│ password        │
│ created_at      │
│ updated_at      │
└─────────────────┘
```

### Table Specifications

#### `lokets` Table
```sql
CREATE TABLE lokets (
    id BIGSERIAL PRIMARY KEY,
    nama_loket VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

**Purpose**: Stores service counter information (e.g., Registration, Dental, Pharmacy)

#### `antrians` Table
```sql
CREATE TABLE antrians (
    id BIGSERIAL PRIMARY KEY,
    loket_id BIGINT NOT NULL REFERENCES lokets(id) ON DELETE CASCADE,
    nomor_antrian VARCHAR(255) NOT NULL,
    status VARCHAR(20) CHECK (status IN ('menunggu', 'dipanggil', 'selesai')) DEFAULT 'menunggu',
    waktu_panggil TIMESTAMP NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    INDEX idx_loket_status (loket_id, status),
    INDEX idx_status (status)
);
```

**Purpose**: Stores queue entries with automatic number generation

**Status Flow**: `menunggu` → `dipanggil` → `selesai`

**Queue Number Format**: `{PREFIX}{NUMBER}` (e.g., "P001", "G015")
- Prefix: First letter of loket name
- Number: Sequential 3-digit number, resets daily

## Backend Architecture

### Models

#### Loket Model (`app/Models/Loket.php`)

**Relationships**:
- `hasMany(Antrian::class)` - All queues for this loket
- `antrianMenunggu()` - Waiting queues only
- `antrianDipanggil()` - Currently called queues

**Key Methods**:
```php
public function antrians(): HasMany
public function antrianMenunggu(): HasMany
public function antrianDipanggil(): HasMany
```

#### Antrian Model (`app/Models/Antrian.php`)

**Relationships**:
- `belongsTo(Loket::class)` - Parent loket

**Key Methods**:
```php
public static function generateNomorAntrian(int $loketId): string
public function scopeByStatus($query, string $status)
public function scopeToday($query)
```

**Queue Number Generation Logic**:
1. Get loket prefix (first letter of nama_loket)
2. Find last queue number for today for this loket
3. Increment number
4. Format as PREFIX + 3-digit number (e.g., "P001")

### API Controllers

#### LoketController (`app/Http/Controllers/Api/LoketController.php`)

**Endpoints**:
- `GET /api/lokets` - List all lokets
- `POST /api/lokets` - Create new loket
- `GET /api/lokets/{id}` - Get specific loket
- `PUT /api/lokets/{id}` - Update loket
- `DELETE /api/lokets/{id}` - Delete loket

#### AntrianController (`app/Http/Controllers/Api/AntrianController.php`)

**Endpoints**:

1. **POST /api/antrians/ambil**
   - Purpose: Generate new queue number
   - Request: `{ "loket_id": 1 }`
   - Response: `{ "success": true, "data": { "id": 1, "nomor_antrian": "P001", ... } }`

2. **PATCH /api/antrians/{id}/status**
   - Purpose: Update queue status
   - Request: `{ "status": "dipanggil" }`
   - Auto-sets `waktu_panggil` when status = 'dipanggil'

3. **GET /api/antrians/current**
   - Purpose: Get all currently called queues
   - Optional: `?loket_id=1` to filter by loket

4. **GET /api/antrians/menunggu**
   - Purpose: Get waiting queues for specific loket
   - Required: `?loket_id=1`

5. **GET /api/antrians/history**
   - Purpose: Get paginated queue history
   - Optional filters: `?loket_id=1&status=selesai&date=2024-01-01`

### Authentication System

**Provider**: Laravel Socialite (Google OAuth)

**Flow**:
1. User clicks "Login with Google"
2. Redirect to Google OAuth (`/auth/google`)
3. User authorizes application
4. Callback to `/auth/google/callback`
5. Create or update user record
6. Login user and redirect to `/petugas`

**Protected Routes**: All `/petugas` routes require authentication

## Frontend Architecture

### Livewire Components

#### 1. AmbilAntrian Component (`app/Livewire/AmbilAntrian.php`)

**Purpose**: Patient queue number retrieval

**Properties**:
- `$lokets` - Available service counters
- `$selectedLoketId` - Currently selected loket
- `$nomorAntrian` - Generated queue number
- `$showSuccess` - Display success state

**Methods**:
```php
public function mount() // Load lokets
public function ambilAntrian() // Generate queue number
public function resetForm() // Reset to initial state
```

**User Flow**:
1. Display all available lokets
2. User selects a loket
3. User clicks "Ambil Nomor Antrian"
4. System generates queue number
5. Display queue number to user
6. Option to return and take another number

#### 2. PetugasLoket Component (`app/Livewire/PetugasLoket.php`)

**Purpose**: Staff queue management

**Properties**:
- `$lokets` - All service counters
- `$selectedLoketId` - Staff's assigned loket
- `$antrianMenunggu` - Waiting queue list
- `$antrianDipanggil` - Currently called queue

**Methods**:
```php
public function mount() // Load lokets
public function selectLoket($loketId) // Assign staff to loket
public function loadAntrians() // Refresh queue data
public function panggilAntrian($antrianId) // Call next patient
public function selesaiAntrian($antrianId) // Mark as completed
```

**Real-Time Updates**: `wire:poll.3s="loadAntrians"`

**User Flow**:
1. Staff selects their loket
2. View list of waiting patients
3. Click "Panggil" to call next patient
4. Patient appears in "Sedang Dipanggil" section
5. Click "Selesai" when service is complete
6. Queue moves to completed status

**Business Rules**:
- Only one queue can be "dipanggil" per loket at a time
- Cannot call next patient while one is being served
- Queues are called in FIFO order (oldest first)

#### 3. DisplayAntrian Component (`app/Livewire/DisplayAntrian.php`)

**Purpose**: Public display screen for called queues

**Properties**:
- `$antrianDipanggil` - All currently called queues (all lokets)

**Methods**:
```php
public function mount() // Initial load
public function loadAntrians() // Refresh display
```

**Real-Time Updates**: `wire:poll.3s="loadAntrians"`

**Display Format**:
```
┌─────────────────────────────┐
│   Nomor Antrian: P001       │
│   Loket: Pendaftaran Umum   │
│   Dipanggil: 14:30:15       │
└─────────────────────────────┘
```

**Features**:
- Full-screen optimized layout
- Large, readable fonts
- Color-coded for visibility
- Animated pulse effect for attention
- Shows multiple queues simultaneously

### Page Structure

#### 1. Home Page (`/`)
- Welcome screen
- Three main action cards:
  - Ambil Antrian (Patient)
  - Panel Petugas (Staff - requires auth)
  - Display Antrian (Public display)
- Feature highlights

#### 2. Patient Page (`/pasien`)
- Public access
- Livewire component: `<livewire:ambil-antrian />`
- Mobile-responsive design

#### 3. Staff Panel (`/petugas`)
- Requires authentication
- Livewire component: `<livewire:petugas-loket />`
- Real-time queue management

#### 4. Display Screen (`/display`)
- Public access
- Livewire component: `<livewire:display-antrian />`
- Fullscreen layout (no navigation)
- Optimized for large displays

#### 5. Login Page (`/login`)
- Google OAuth button
- Redirect to `/petugas` after successful login

## Security Architecture

### Authentication
- **Method**: OAuth 2.0 via Google
- **Session**: Laravel session management
- **Middleware**: `auth` middleware protects staff routes

### Authorization
- Staff can only access `/petugas` when authenticated
- Public pages: `/`, `/pasien`, `/display`
- API endpoints are open (can be restricted if needed)

### Data Validation
- All form inputs validated
- Foreign key constraints enforced
- Enum validation for status field

## Performance Optimization

### Database Indexing
```sql
INDEX idx_loket_status ON antrians(loket_id, status);
INDEX idx_status ON antrians(status);
```

### Caching Strategy
- Loket data can be cached (rarely changes)
- Queue data must be real-time (no caching)

### Real-Time Updates
- Livewire polling every 3 seconds
- Minimal data transfer (only changed records)
- Efficient queries with proper indexing

## Deployment Architecture

### Railway Platform

**Components**:
1. **Web Service**: Laravel application
2. **PostgreSQL Database**: Managed database instance
3. **Environment Variables**: Secure credential storage

**Scaling**:
- Horizontal scaling supported
- Database connection pooling
- CDN for static assets

### Production Checklist

- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure production database
- [ ] Set up Google OAuth production credentials
- [ ] Enable HTTPS
- [ ] Configure session driver (database recommended)
- [ ] Set up database backups
- [ ] Configure logging
- [ ] Set up monitoring

## Testing Strategy

### Unit Tests
- Model methods (queue number generation)
- API endpoints
- Authentication flow

### Integration Tests
- Complete queue workflow
- Real-time updates
- Multi-loket scenarios

### User Acceptance Testing
- Patient flow: Take queue → Wait → Called
- Staff flow: Login → Select loket → Call patients
- Display: Real-time updates visible

## Maintenance & Monitoring

### Daily Tasks
- Monitor queue generation
- Check authentication logs
- Verify real-time updates

### Weekly Tasks
- Database cleanup (old completed queues)
- Review error logs
- Performance monitoring

### Monthly Tasks
- Database optimization
- Security updates
- Backup verification

## Future Enhancements

1. **SMS/WhatsApp Notifications**: Notify patients when their turn is near
2. **Queue Estimation**: Show estimated wait time
3. **Analytics Dashboard**: Queue statistics and reports
4. **Multi-language Support**: Indonesian and English
5. **Mobile App**: Native iOS/Android applications
6. **Appointment System**: Pre-booking with queue priority
7. **Voice Announcement**: Audio calling system
8. **Digital Signage Integration**: Multiple display screens

## Support & Documentation

- **Laravel Docs**: https://laravel.com/docs/12.x
- **Livewire Docs**: https://livewire.laravel.com/docs/3.x
- **Railway Docs**: https://docs.railway.app
- **Tailwind CSS**: https://tailwindcss.com/docs

---

**Document Version**: 1.0  
**Last Updated**: 2024  
**Maintained By**: Development Team

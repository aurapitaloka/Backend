# Setup API Routes untuk Frontend Integration

## Langkah-langkah Setup

### 1. Install Laravel Sanctum

```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

### 2. Update User Model

Edit `app/Models/Pengguna.php`:

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Pengguna extends Authenticatable
{
    use HasApiTokens, Notifiable;
    
    // ... existing code
}
```

### 3. Create API Routes

Create/edit `routes/api.php`:

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\FiksiController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfileController;

// Public routes
Route::post('/login', [AuthController::class, 'apiLogin']);
Route::post('/register', [AuthController::class, 'apiRegister']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'apiLogout']);
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'apiIndex']);
    
    // Resources
    Route::apiResource('materi', MateriController::class);
    Route::apiResource('fiksi', FiksiController::class);
    Route::apiResource('pengguna', PenggunaController::class);
    Route::apiResource('level', LevelController::class);
    Route::apiResource('mata-pelajaran', MataPelajaranController::class);
    
    // Additional endpoints
    Route::get('/level/aktif', [LevelController::class, 'aktif']);
    Route::get('/mata-pelajaran/aktif', [MataPelajaranController::class, 'aktif']);
});
```

### 4. Update AuthController

Add API methods to `app/Http/Controllers/AuthController.php`:

```php
use Illuminate\Http\JsonResponse;
use Laravel\Sanctum\PersonalAccessToken;

public function apiLogin(Request $request): JsonResponse
{
    $request->validate([
        'email' => 'required|email',
        'kata_sandi' => 'required|string',
    ]);

    $pengguna = Pengguna::where('email', $request->email)->first();

    if (!$pengguna || !Hash::check($request->kata_sandi, $pengguna->kata_sandi)) {
        return response()->json([
            'message' => 'Email atau kata sandi salah.'
        ], 401);
    }

    if (!$pengguna->status_aktif) {
        return response()->json([
            'message' => 'Akun Anda tidak aktif. Silakan hubungi administrator.'
        ], 403);
    }

    $token = $pengguna->createToken('auth-token')->plainTextToken;

    return response()->json([
        'message' => 'Login berhasil',
        'token' => $token,
        'user' => [
            'id' => $pengguna->id,
            'nama' => $pengguna->nama,
            'email' => $pengguna->email,
            'peran' => $pengguna->peran,
        ]
    ]);
}

public function apiLogout(Request $request): JsonResponse
{
    $request->user()->currentAccessToken()->delete();

    return response()->json([
        'message' => 'Logout berhasil'
    ]);
}

public function user(Request $request): JsonResponse
{
    return response()->json([
        'user' => $request->user()
    ]);
}
```

### 5. Update Controllers untuk API Response

Update controllers to return JSON for API requests:

```php
// Example: MateriController
public function index(Request $request)
{
    $materi = Materi::with(['pengguna', 'level', 'mataPelajaran'])
        ->orderBy('created_at', 'desc')
        ->paginate(10);
    
    if ($request->wantsJson() || $request->is('api/*')) {
        return response()->json($materi);
    }
    
    return view('dashboard.materi', compact('materi'));
}
```

### 6. Configure CORS

Edit `config/cors.php`:

```php
'paths' => ['api/*', 'sanctum/csrf-cookie'],
'allowed_origins' => ['http://localhost:3000', 'http://localhost:5173'],
'allowed_methods' => ['*'],
'allowed_headers' => ['*'],
'supports_credentials' => true,
```

### 7. Update Kernel

Ensure `HandleCors` middleware is enabled in `bootstrap/app.php` or `app/Http/Kernel.php`.

---

## API Endpoints (After Setup)

### Base URL
```
http://127.0.0.1:8000/api
```

### Authentication Flow

1. **Login:**
```http
POST /api/login
Content-Type: application/json

{
  "email": "superadmin@akses.com",
  "kata_sandi": "password"
}
```

**Response:**
```json
{
  "message": "Login berhasil",
  "token": "1|xxxxxxxxxxxx",
  "user": {
    "id": 1,
    "nama": "Super Admin",
    "email": "superadmin@akses.com",
    "peran": "guru"
  }
}
```

2. **Use Token:**
```http
GET /api/materi
Authorization: Bearer 1|xxxxxxxxxxxx
Accept: application/json
```

3. **Logout:**
```http
POST /api/logout
Authorization: Bearer 1|xxxxxxxxxxxx
```

---

## Frontend Integration Example

```javascript
// api.js
const API_BASE_URL = 'http://127.0.0.1:8000/api';

class ApiClient {
  constructor() {
    this.token = localStorage.getItem('token');
  }

  async login(email, password) {
    const response = await fetch(`${API_BASE_URL}/login`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({ email, kata_sandi: password })
    });

    const data = await response.json();
    
    if (response.ok) {
      this.token = data.token;
      localStorage.setItem('token', data.token);
      return data;
    }
    
    throw new Error(data.message || 'Login failed');
  }

  async request(endpoint, options = {}) {
    const url = `${API_BASE_URL}${endpoint}`;
    const headers = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      ...options.headers
    };

    if (this.token) {
      headers['Authorization'] = `Bearer ${this.token}`;
    }

    const response = await fetch(url, {
      ...options,
      headers
    });

    if (response.status === 401) {
      // Token expired, redirect to login
      this.logout();
      window.location.href = '/login';
      return;
    }

    return response.json();
  }

  async getMateri(page = 1) {
    return this.request(`/materi?page=${page}`);
  }

  async createMateri(formData) {
    return this.request('/materi', {
      method: 'POST',
      body: JSON.stringify(formData)
    });
  }

  logout() {
    this.token = null;
    localStorage.removeItem('token');
  }
}

export default new ApiClient();
```

---

## Testing API

### Using Postman

1. Create new collection
2. Add environment variable: `base_url = http://127.0.0.1:8000/api`
3. Add login request:
   - Method: POST
   - URL: `{{base_url}}/login`
   - Body: JSON with email and kata_sandi
4. Save token from response
5. Add other requests with Bearer token authentication

### Using cURL

```bash
# Login
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"superadmin@akses.com","kata_sandi":"password"}'

# Get Materi (use token from login)
curl -X GET http://127.0.0.1:8000/api/materi \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"
```

---

## Notes

1. **Token Storage**: Store token securely (localStorage, sessionStorage, or httpOnly cookie)
2. **Token Expiry**: Sanctum tokens don't expire by default, but you can configure expiry
3. **Refresh Token**: Consider implementing refresh token mechanism for better security
4. **Rate Limiting**: Add rate limiting to prevent abuse
5. **Validation**: All inputs are validated server-side
6. **Error Handling**: Always handle API errors gracefully in frontend


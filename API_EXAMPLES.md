# API Usage Examples

## JavaScript/Fetch Examples

### 1. Login

```javascript
async function login(email, password, rememberMe = false) {
  const formData = new FormData();
  formData.append('email', email);
  formData.append('kata_sandi', password);
  if (rememberMe) {
    formData.append('ingat_sandi', 'on');
  }
  formData.append('_token', getCsrfToken());

  try {
    const response = await fetch('http://127.0.0.1:8000/login', {
      method: 'POST',
      body: formData,
      credentials: 'include', // Important for cookies
      redirect: 'manual' // Handle redirect manually
    });

    if (response.status === 302 || response.ok) {
      // Login successful
      const location = response.headers.get('Location');
      if (location) {
        window.location.href = location;
      }
      return { success: true };
    } else {
      // Login failed
      const html = await response.text();
      // Parse error from HTML or handle accordingly
      return { success: false, error: 'Login failed' };
    }
  } catch (error) {
    console.error('Login error:', error);
    return { success: false, error: error.message };
  }
}

// Usage
login('superadmin@akses.com', 'password', true);
```

### 2. Get All Materi

```javascript
async function getMateri(page = 1, perPage = 10) {
  try {
    const response = await fetch(
      `http://127.0.0.1:8000/dashboard/materi?page=${page}&per_page=${perPage}`,
      {
        method: 'GET',
        credentials: 'include',
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      }
    );

    if (response.ok) {
      const data = await response.json();
      return { success: true, data };
    } else {
      return { success: false, error: 'Failed to fetch materi' };
    }
  } catch (error) {
    console.error('Error:', error);
    return { success: false, error: error.message };
  }
}

// Usage
getMateri(1, 10).then(result => {
  if (result.success) {
    console.log('Materi:', result.data);
  }
});
```

### 3. Create Materi

```javascript
async function createMateri(materiData, file = null) {
  const formData = new FormData();
  
  formData.append('judul', materiData.judul);
  formData.append('deskripsi', materiData.deskripsi || '');
  formData.append('mata_pelajaran_id', materiData.mata_pelajaran_id || '');
  formData.append('level_id', materiData.level_id || '');
  formData.append('tipe_konten', materiData.tipe_konten);
  
  if (materiData.tipe_konten === 'teks') {
    formData.append('konten_teks', materiData.konten_teks);
  } else if (file) {
    formData.append('file_path', file);
  }
  
  formData.append('jumlah_halaman', materiData.jumlah_halaman || '');
  formData.append('status_aktif', materiData.status_aktif ? '1' : '0');
  formData.append('_token', getCsrfToken());

  try {
    const response = await fetch('http://127.0.0.1:8000/dashboard/materi', {
      method: 'POST',
      body: formData,
      credentials: 'include',
      redirect: 'manual'
    });

    if (response.status === 302 || response.ok) {
      return { success: true };
    } else {
      const html = await response.text();
      // Parse validation errors from HTML
      return { success: false, error: 'Validation failed' };
    }
  } catch (error) {
    console.error('Error:', error);
    return { success: false, error: error.message };
  }
}

// Usage
const fileInput = document.querySelector('input[type="file"]');
createMateri({
  judul: 'Matematika Dasar',
  deskripsi: 'Materi pembelajaran',
  mata_pelajaran_id: 1,
  level_id: 1,
  tipe_konten: 'file',
  jumlah_halaman: 10,
  status_aktif: true
}, fileInput.files[0]);
```

### 4. Update Materi

```javascript
async function updateMateri(id, materiData, file = null) {
  const formData = new FormData();
  
  formData.append('_method', 'PUT');
  formData.append('judul', materiData.judul);
  formData.append('deskripsi', materiData.deskripsi || '');
  formData.append('mata_pelajaran_id', materiData.mata_pelajaran_id || '');
  formData.append('level_id', materiData.level_id || '');
  formData.append('tipe_konten', materiData.tipe_konten);
  
  if (materiData.tipe_konten === 'teks') {
    formData.append('konten_teks', materiData.konten_teks);
  } else if (file) {
    formData.append('file_path', file);
  }
  
  formData.append('jumlah_halaman', materiData.jumlah_halaman || '');
  formData.append('status_aktif', materiData.status_aktif ? '1' : '0');
  formData.append('_token', getCsrfToken());

  try {
    const response = await fetch(`http://127.0.0.1:8000/dashboard/materi/${id}`, {
      method: 'POST', // Laravel uses POST with _method=PUT
      body: formData,
      credentials: 'include',
      redirect: 'manual'
    });

    if (response.status === 302 || response.ok) {
      return { success: true };
    } else {
      return { success: false, error: 'Update failed' };
    }
  } catch (error) {
    console.error('Error:', error);
    return { success: false, error: error.message };
  }
}
```

### 5. Delete Materi

```javascript
async function deleteMateri(id) {
  const formData = new FormData();
  formData.append('_method', 'DELETE');
  formData.append('_token', getCsrfToken());

  try {
    const response = await fetch(`http://127.0.0.1:8000/dashboard/materi/${id}`, {
      method: 'POST', // Laravel uses POST with _method=DELETE
      body: formData,
      credentials: 'include',
      redirect: 'manual'
    });

    if (response.status === 302 || response.ok) {
      return { success: true };
    } else {
      return { success: false, error: 'Delete failed' };
    }
  } catch (error) {
    console.error('Error:', error);
    return { success: false, error: error.message };
  }
}
```

### 6. Get Level and Mata Pelajaran (for dropdowns)

```javascript
async function getLevels() {
  try {
    const response = await fetch('http://127.0.0.1:8000/dashboard/level', {
      method: 'GET',
      credentials: 'include',
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    });

    if (response.ok) {
      const data = await response.json();
      return { success: true, data: data.data || data };
    } else {
      return { success: false, error: 'Failed to fetch levels' };
    }
  } catch (error) {
    console.error('Error:', error);
    return { success: false, error: error.message };
  }
}

async function getMataPelajarans() {
  try {
    const response = await fetch('http://127.0.0.1:8000/dashboard/mata-pelajaran', {
      method: 'GET',
      credentials: 'include',
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    });

    if (response.ok) {
      const data = await response.json();
      return { success: true, data: data.data || data };
    } else {
      return { success: false, error: 'Failed to fetch mata pelajaran' };
    }
  } catch (error) {
    console.error('Error:', error);
    return { success: false, error: error.message };
  }
}

// Usage - Populate dropdowns
Promise.all([getLevels(), getMataPelajarans()]).then(([levelsResult, mpResult]) => {
  if (levelsResult.success) {
    const levelSelect = document.getElementById('level_id');
    levelsResult.data.forEach(level => {
      const option = document.createElement('option');
      option.value = level.id;
      option.textContent = level.nama;
      levelSelect.appendChild(option);
    });
  }
  
  if (mpResult.success) {
    const mpSelect = document.getElementById('mata_pelajaran_id');
    mpResult.data.forEach(mp => {
      const option = document.createElement('option');
      option.value = mp.id;
      option.textContent = mp.nama;
      mpSelect.appendChild(option);
    });
  }
});
```

### 7. Helper Functions

```javascript
// Get CSRF Token
function getCsrfToken() {
  const meta = document.querySelector('meta[name="csrf-token"]');
  return meta ? meta.getAttribute('content') : '';
}

// Or from cookie
function getCsrfTokenFromCookie() {
  const name = 'XSRF-TOKEN';
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) {
    return parts.pop().split(';').shift();
  }
  return '';
}

// Check if user is authenticated
async function checkAuth() {
  try {
    const response = await fetch('http://127.0.0.1:8000/dashboard', {
      method: 'GET',
      credentials: 'include',
      redirect: 'manual'
    });
    
    return response.status !== 302 || response.headers.get('Location') !== '/login';
  } catch (error) {
    return false;
  }
}

// Logout
async function logout() {
  const formData = new FormData();
  formData.append('_token', getCsrfToken());

  try {
    const response = await fetch('http://127.0.0.1:8000/logout', {
      method: 'POST',
      body: formData,
      credentials: 'include',
      redirect: 'manual'
    });

    if (response.status === 302) {
      window.location.href = '/login';
    }
  } catch (error) {
    console.error('Logout error:', error);
  }
}

### 8. Profile

#### Get Profile
```javascript
async function getProfile() {
  try {
    const response = await fetch('http://127.0.0.1:8000/dashboard/profile', {
      method: 'GET',
      credentials: 'include',
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    });

    if (response.ok) {
      const data = await response.json();
      return { success: true, data };
    } else {
      return { success: false, error: 'Failed to fetch profile' };
    }
  } catch (error) {
    console.error('Error:', error);
    return { success: false, error: error.message };
  }
}
```

#### Update Profile
```javascript
async function updateProfile(nama, email) {
  const formData = new FormData();
  formData.append('_method', 'PUT');
  formData.append('nama', nama);
  formData.append('email', email);
  formData.append('_token', getCsrfToken());

  try {
    const response = await fetch('http://127.0.0.1:8000/dashboard/profile', {
      method: 'POST', // Laravel uses POST with _method=PUT
      body: formData,
      credentials: 'include',
      redirect: 'manual'
    });

    if (response.status === 302 || response.ok) {
      return { success: true };
    } else {
      const html = await response.text();
      // Parse validation errors from HTML
      return { success: false, error: 'Validation failed' };
    }
  } catch (error) {
    console.error('Error:', error);
    return { success: false, error: error.message };
  }
}

// Usage
updateProfile('John Doe', 'john@example.com');
```

#### Update Password
```javascript
async function updatePassword(currentPassword, newPassword, confirmPassword) {
  const formData = new FormData();
  formData.append('_method', 'PUT');
  formData.append('kata_sandi_lama', currentPassword);
  formData.append('kata_sandi_baru', newPassword);
  formData.append('kata_sandi_konfirmasi', confirmPassword);
  formData.append('_token', getCsrfToken());

  try {
    const response = await fetch('http://127.0.0.1:8000/dashboard/profile/password', {
      method: 'POST', // Laravel uses POST with _method=PUT
      body: formData,
      credentials: 'include',
      redirect: 'manual'
    });

    if (response.status === 302 || response.ok) {
      return { success: true };
    } else {
      const html = await response.text();
      // Parse validation errors from HTML
      return { success: false, error: 'Password update failed' };
    }
  } catch (error) {
    console.error('Error:', error);
    return { success: false, error: error.message };
  }
}

// Usage
updatePassword('oldpassword', 'newpassword123', 'newpassword123');
```

---

## Axios Examples

### Setup Axios

```javascript
import axios from 'axios';

const api = axios.create({
  baseURL: 'http://127.0.0.1:8000',
  withCredentials: true, // Important for cookies
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
    'Accept': 'application/json'
  }
});

// Add CSRF token to requests
api.interceptors.request.use(config => {
  const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
  if (token) {
    config.headers['X-CSRF-TOKEN'] = token;
  }
  return config;
});
```

### Using Axios

```javascript
// Login
async function login(email, password) {
  try {
    const response = await api.post('/login', {
      email,
      kata_sandi: password,
      ingat_sandi: true
    }, {
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      }
    });
    return { success: true, data: response.data };
  } catch (error) {
    return { success: false, error: error.response?.data || error.message };
  }
}

// Get Materi
async function getMateri(page = 1) {
  try {
    const response = await api.get('/dashboard/materi', {
      params: { page }
    });
    return { success: true, data: response.data };
  } catch (error) {
    return { success: false, error: error.response?.data || error.message };
  }
}

// Create Materi with file
async function createMateri(materiData, file) {
  const formData = new FormData();
  Object.keys(materiData).forEach(key => {
    formData.append(key, materiData[key]);
  });
  if (file) {
    formData.append('file_path', file);
  }
  formData.append('_token', getCsrfToken());

  try {
    const response = await api.post('/dashboard/materi', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
    return { success: true, data: response.data };
  } catch (error) {
    return { success: false, error: error.response?.data || error.message };
  }
}
```

---

## React Example

```jsx
import { useState, useEffect } from 'react';
import axios from 'axios';

const api = axios.create({
  baseURL: 'http://127.0.0.1:8000',
  withCredentials: true
});

function MateriList() {
  const [materi, setMateri] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetchMateri();
  }, []);

  const fetchMateri = async () => {
    try {
      setLoading(true);
      const response = await api.get('/dashboard/materi', {
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      });
      setMateri(response.data.data || []);
      setError(null);
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  if (loading) return <div>Loading...</div>;
  if (error) return <div>Error: {error}</div>;

  return (
    <div>
      <h1>Materi</h1>
      {materi.map(item => (
        <div key={item.id}>
          <h3>{item.judul}</h3>
          <p>{item.deskripsi}</p>
        </div>
      ))}
    </div>
  );
}

export default MateriList;
```

---

## Vue.js Example

```vue
<template>
  <div>
    <h1>Materi</h1>
    <div v-if="loading">Loading...</div>
    <div v-else-if="error">Error: {{ error }}</div>
    <div v-else>
      <div v-for="item in materi" :key="item.id">
        <h3>{{ item.judul }}</h3>
        <p>{{ item.deskripsi }}</p>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      materi: [],
      loading: true,
      error: null
    };
  },
  mounted() {
    this.fetchMateri();
  },
  methods: {
    async fetchMateri() {
      try {
        this.loading = true;
        const response = await axios.get('http://127.0.0.1:8000/dashboard/materi', {
          withCredentials: true,
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          }
        });
        this.materi = response.data.data || [];
        this.error = null;
      } catch (err) {
        this.error = err.message;
      } finally {
        this.loading = false;
      }
    }
  }
};
</script>
```

---

## Notes

1. **Credentials**: Always include `credentials: 'include'` or `withCredentials: true` for session cookies
2. **CSRF Token**: Required for POST, PUT, DELETE requests
3. **Content-Type**: Use `multipart/form-data` for file uploads
4. **Redirect Handling**: Set `redirect: 'manual'` to handle redirects manually
5. **JSON Response**: Add `Accept: application/json` header to get JSON responses instead of HTML


# Integrasi SB Admin 2 Template

## Ringkasan
Layout admin telah diubah dari layout custom Tailwind CSS ke SB Admin 2 template yang berbasis Bootstrap 4.

## File yang Dibuat

### 1. Layout Utama
- `resources/views/layouts/admin.blade.php` - Layout utama admin dengan SB Admin 2
- `resources/views/layouts/partials/admin-sidebar.blade.php` - Sidebar navigasi admin
- `resources/views/layouts/partials/admin-topbar.blade.php` - Topbar dengan user menu dan notifikasi

### 2. Component
- `app/View/Components/AdminLayout.php` - Component untuk layout admin

## File yang Diupdate

Semua halaman admin telah diupdate untuk menggunakan `<x-admin-layout>` menggantikan `<x-app-layout>`:

### Halaman Admin
- `resources/views/admin/dashboard.blade.php`
- `resources/views/admin/employees/index.blade.php`
- `resources/views/admin/attendances/index.blade.php`
- `resources/views/admin/attendances/show.blade.php`
- `resources/views/admin/academic-events.blade.php`
- `resources/views/admin/bills.blade.php`
- `resources/views/admin/face-registrations.blade.php`
- `resources/views/admin/office-locations.blade.php`
- `resources/views/admin/settings.blade.php`

### Master Data
- `resources/views/admin/master-data/admin.blade.php`
- `resources/views/admin/master-data/division.blade.php`
- `resources/views/admin/master-data/shift.blade.php`
- `resources/views/admin/master-data/job-title.blade.php`
- `resources/views/admin/master-data/education.blade.php`

### Import/Export
- `resources/views/admin/import-export/attendances.blade.php`
- `resources/views/admin/import-export/users.blade.php`

### Tasks
- `resources/views/admin/tasks/index.blade.php`

## Fitur SB Admin 2

### Sidebar
- Collapsible sidebar dengan toggle button
- Menu dengan icon Font Awesome
- Dropdown menu untuk Master Data dan Import/Export
- Active state untuk menu yang sedang dibuka
- Responsive untuk mobile

### Topbar
- Search bar
- Notifikasi alerts dan messages
- User dropdown dengan profile photo
- Logout modal

### Styling
- Bootstrap 4 components
- Font Awesome icons
- Responsive design
- Card-based layout untuk konten

## Assets yang Digunakan

### CSS
- Font Awesome 6.5.1 (CDN)
- Google Fonts - Nunito
- SB Admin 2 CSS (CDN)
- Tailwind CSS (existing, untuk compatibility)

### JavaScript
- jQuery 3.7.1
- Bootstrap 4.6.2
- jQuery Easing
- SB Admin 2 JS

## Cara Penggunaan

### Membuat Halaman Admin Baru

```blade
<x-admin-layout>
    <x-slot name="header">
        <h1 class="h3 mb-0 text-gray-800">Judul Halaman</h1>
    </x-slot>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Card Title</h6>
        </div>
        <div class="card-body">
            <!-- Konten halaman -->
        </div>
    </div>
</x-admin-layout>
```

### Menambah Menu Sidebar

Edit file `resources/views/layouts/partials/admin-sidebar.blade.php`:

```blade
<li class="nav-item {{ request()->routeIs('admin.menu-baru') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('admin.menu-baru') }}">
        <i class="fas fa-fw fa-icon-name"></i>
        <span>Menu Baru</span>
    </a>
</li>
```

### Menambah Dropdown Menu

```blade
<li class="nav-item {{ request()->routeIs('admin.group.*') ? 'active' : '' }}">
    <a class="nav-link {{ request()->routeIs('admin.group.*') ? '' : 'collapsed' }}" href="#" 
       data-toggle="collapse" data-target="#collapseGroup"
       aria-expanded="{{ request()->routeIs('admin.group.*') ? 'true' : 'false' }}">
        <i class="fas fa-fw fa-folder"></i>
        <span>Group Menu</span>
    </a>
    <div id="collapseGroup" class="collapse {{ request()->routeIs('admin.group.*') ? 'show' : '' }}" 
         data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Sub Menu:</h6>
            <a class="collapse-item" href="{{ route('admin.group.item1') }}">Item 1</a>
            <a class="collapse-item" href="{{ route('admin.group.item2') }}">Item 2</a>
        </div>
    </div>
</li>
```

## Komponen Bootstrap yang Tersedia

### Cards
```html
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Card Title</h6>
    </div>
    <div class="card-body">Content</div>
</div>
```

### Buttons
```html
<button class="btn btn-primary">Primary</button>
<button class="btn btn-success">Success</button>
<button class="btn btn-danger">Danger</button>
<button class="btn btn-warning">Warning</button>
<button class="btn btn-info">Info</button>
```

### Alerts
```html
<div class="alert alert-success alert-dismissible fade show" role="alert">
    Success message
    <button type="button" class="close" data-dismiss="alert">
        <span>&times;</span>
    </button>
</div>
```

### Tables
```html
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Column 1</th>
                <th>Column 2</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Data 1</td>
                <td>Data 2</td>
            </tr>
        </tbody>
    </table>
</div>
```

## Catatan

1. Layout user (non-admin) masih menggunakan layout lama dengan Tailwind CSS
2. Livewire components tetap berfungsi normal dengan layout baru
3. Dark mode dari layout lama tidak tersedia di SB Admin 2
4. Mobile bottom navigation hanya untuk user, tidak untuk admin

## Referensi

- SB Admin 2 Documentation: https://startbootstrap.com/theme/sb-admin-2
- Bootstrap 4 Documentation: https://getbootstrap.com/docs/4.6/
- Font Awesome Icons: https://fontawesome.com/icons

@php
    $isContentPage = request()->is('admin/content', 'admin/portfolios*', 'admin/posts*', 'admin/activities*', 'admin/sliders*', 'admin/categories*', 'admin/tags*');
    $isProfilePage = request()->is('admin/profile*');
    $isDashboardPage = request()->is('admin/dashboard');
@endphp
<nav class="admin-bottom-navigation" aria-label="Navigasi aplikasi">
    <a href="{{ route('admin.dashboard') }}" class="{{ $isDashboardPage ? 'active' : '' }}" @if($isDashboardPage) aria-current="page" @endif>
        <i class="bi bi-house-door" aria-hidden="true"></i><span>Beranda</span>
    </a>
    <a href="{{ route('admin.content') }}" class="{{ $isContentPage ? 'active' : '' }}" @if($isContentPage) aria-current="page" @endif>
        <i class="bi bi-grid" aria-hidden="true"></i><span>Konten</span>
    </a>
    <a href="{{ route('admin.profile.edit') }}" class="{{ $isProfilePage ? 'active' : '' }}" @if($isProfilePage) aria-current="page" @endif>
        <i class="bi bi-person" aria-hidden="true"></i><span>Profil</span>
    </a>
    <button type="button" class="{{ !$isContentPage && !$isProfilePage && !$isDashboardPage ? 'active' : '' }}" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false">
        <i class="bi bi-list" aria-hidden="true"></i><span>Menu</span>
    </button>
</nav>

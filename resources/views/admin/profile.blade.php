@extends('admin.layout')

@section('title', 'Управление профилем - ' . config('app.name'))
@section('description', 'Редактирование персональной информации и настроек профиля')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Управление профилем</h1>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Редактировать профиль</h5>
            </div>
            <div class="card-body">
                <!-- Bootstrap Nav Tabs -->
                <ul class="nav nav-tabs mb-4" id="profileTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ (!isset($tab) || $tab === 'basic') ? 'active' : '' }}" 
                                id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" 
                                type="button" role="tab" aria-controls="basic" aria-selected="{{ (!isset($tab) || $tab === 'basic') ? 'true' : 'false' }}">
                            <i class="bi bi-person"></i>
                            <span class="d-none d-md-inline ms-2">Основная информация</span>
                            <span class="d-md-none">Инфо</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ (isset($tab) && $tab === 'images') ? 'active' : '' }}" 
                                id="images-tab" data-bs-toggle="tab" data-bs-target="#images" 
                                type="button" role="tab" aria-controls="images" aria-selected="{{ (isset($tab) && $tab === 'images') ? 'true' : 'false' }}">
                            <i class="bi bi-image"></i>
                            <span class="d-none d-md-inline ms-2">Изображения</span>
                            <span class="d-md-none">Фото</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ (isset($tab) && $tab === 'social') ? 'active' : '' }}" 
                                id="social-tab" data-bs-toggle="tab" data-bs-target="#social" 
                                type="button" role="tab" aria-controls="social" aria-selected="{{ (isset($tab) && $tab === 'social') ? 'true' : 'false' }}">
                            <i class="bi bi-share"></i>
                            <span class="d-none d-md-inline ms-2">Социальные сети</span>
                            <span class="d-md-none">Соцсети</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ (isset($tab) && $tab === 'security') ? 'active' : '' }}" 
                                id="security-tab" data-bs-toggle="tab" data-bs-target="#security" 
                                type="button" role="tab" aria-controls="security" aria-selected="{{ (isset($tab) && $tab === 'security') ? 'true' : 'false' }}">
                            <i class="bi bi-shield-lock"></i>
                            <span class="d-none d-md-inline ms-2">Безопасность</span>
                            <span class="d-md-none">Пароль</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ (isset($tab) && $tab === 'sections') ? 'active' : '' }}" 
                                id="sections-tab" data-bs-toggle="tab" data-bs-target="#sections" 
                                type="button" role="tab" aria-controls="sections" aria-selected="{{ (isset($tab) && $tab === 'sections') ? 'true' : 'false' }}">
                            <i class="bi bi-layout-text-window"></i>
                            <span class="d-none d-md-inline ms-2">Управление разделами</span>
                            <span class="d-md-none">Разделы</span>
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="profileTabContent">
                    <!-- Основная информация -->
                    <div class="tab-pane fade {{ (!isset($tab) || $tab === 'basic') ? 'show active' : '' }}" 
                         id="basic" role="tabpanel" aria-labelledby="basic-tab">
                        @include('admin.profile.basic')
                    </div>

                    <!-- Изображения -->
                    <div class="tab-pane fade {{ (isset($tab) && $tab === 'images') ? 'show active' : '' }}" 
                         id="images" role="tabpanel" aria-labelledby="images-tab">
                        @include('admin.profile.images')
                    </div>

                    <!-- Социальные сети -->
                    <div class="tab-pane fade {{ (isset($tab) && $tab === 'social') ? 'show active' : '' }}" 
                         id="social" role="tabpanel" aria-labelledby="social-tab">
                        @include('admin.profile.social')
                    </div>

                    <!-- Безопасность -->
                    <div class="tab-pane fade {{ (isset($tab) && $tab === 'security') ? 'show active' : '' }}" 
                         id="security" role="tabpanel" aria-labelledby="security-tab">
                        @include('admin.profile.security')
                    </div>

                    <!-- Управление разделами -->
                    <div class="tab-pane fade {{ (isset($tab) && $tab === 'sections') ? 'show active' : '' }}" 
                         id="sections" role="tabpanel" aria-labelledby="sections-tab">
                        @include('admin.profile.sections')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('admin.profile.social-modals')
@include('admin.profile.scripts')
@endsection



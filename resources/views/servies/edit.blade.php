@extends('layouts.app')

@section('title', __('تعديل الخدمة'))

@include('servies.partials.form-styles')

@section('content')
<div class="container-fluid servf-page">
    <div class="servf-shell">
        <section class="servf-hero">
            <div class="servf-hero-inner">
                <div>
                    <span class="servf-badge">
                        <i class="bi bi-pencil-square"></i>
                        {{ __('تعديل الخدمة') }}
                    </span>
                    <h1 class="servf-title">{{ $service->name }}</h1>
                    <p class="servf-subtitle">{{ __('حدّث اسم الخدمة الحالية ليبقى متوافقًا مع استخداماتها داخل النظام ومع واجهة الإدارة الحديثة.') }}</p>
                </div>

                <div class="servf-actions">
                    <a href="{{ route('servies.index') }}" class="servf-btn-muted">
                        <i class="bi bi-arrow-right-circle"></i>
                        {{ __('العودة إلى الخدمات') }}
                    </a>
                </div>
            </div>
        </section>

        <div class="servf-grid">
            <section class="servf-panel">
                <div class="servf-panel-header">
                    <div class="servf-panel-title-wrap">
                        <span class="servf-panel-icon"><i class="bi bi-sliders2"></i></span>
                        <div>
                            <h2 class="servf-panel-title">{{ __('تحديث بيانات الخدمة') }}</h2>
                            <p class="servf-panel-subtitle">{{ __('يمكنك تعديل اسم الخدمة الحالية وسيظهر التحديث في كل الأماكن التي تعتمد على هذا الاسم داخل النظام.') }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('servies.update', $service->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @include('servies.partials.form-fields', [
                        'isEdit' => true,
                        'service' => $service,
                    ])

                    <div class="servf-footer">
                        <p class="servf-footer-note">{{ __('يفضل الحفاظ على التسمية مفهومة ومتناسقة مع بقية الخدمات حتى يبقى الاختيار واضحًا في الشاشات المرتبطة بها.') }}</p>

                        <div class="servf-footer-actions">
                            <button type="submit" class="servf-btn">
                                <i class="bi bi-check2-circle"></i>
                                {{ __('حفظ التحديث') }}
                            </button>
                            <a href="{{ route('servies.index') }}" class="servf-btn-muted">
                                <i class="bi bi-x-circle"></i>
                                {{ __('إلغاء') }}
                            </a>
                        </div>
                    </div>
                </form>
            </section>

            <aside class="servf-side-stack">
                <section class="servf-mini-card">
                    <h3 class="servf-mini-title">
                        <i class="bi bi-info-circle"></i>
                        {{ __('ملخص سريع') }}
                    </h3>
                    <div class="servf-stat-grid">
                        <div class="servf-stat-box">
                            <strong>#{{ $service->id }}</strong>
                            <span>{{ __('رقم الخدمة') }}</span>
                        </div>
                        <div class="servf-stat-box">
                            <strong>{{ $service->created_at?->format('Y-m-d') }}</strong>
                            <span>{{ __('تاريخ الإنشاء') }}</span>
                        </div>
                        <div class="servf-stat-box">
                            <strong>{{ $service->updated_at?->diffForHumans() }}</strong>
                            <span>{{ __('آخر تحديث') }}</span>
                        </div>
                        <div class="servf-stat-box">
                            <strong>{{ __('مرن') }}</strong>
                            <span>{{ __('يمكن تعديل الاسم وإعادة استخدامه مباشرة') }}</span>
                        </div>
                    </div>
                </section>
            </aside>
        </div>
    </div>
</div>
@endsection

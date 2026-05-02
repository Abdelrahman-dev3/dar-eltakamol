@extends('layouts.app')

@section('title', __('تعديل بيانات المساهم'))

@include('contributors.partials.form-styles')

@section('content')
<div class="container-fluid contributor-form-page">
    <div class="contributor-form-shell">
        <section class="contributor-form-hero">
            <div class="contributor-form-hero-inner">
                <div>
                    <span class="contributor-form-badge">
                        <i class="bi bi-pencil-fill"></i>
                        {{ __('تعديل بيانات المساهم') }} #{{ $contributor->id }}
                    </span>
                    <h1 class="contributor-form-title">{{ __('مراجعة دقيقة للبيانات قبل اعتماد التحديث') }}</h1>
                    <p class="contributor-form-subtitle">
                        {{ __('حدّث بيانات المساهم مع توثيق سبب التعديل ورفع الملفات الجديدة من نفس الشاشة، مع إبراز المعلومات الحالية بشكل أوضح وأسهل للمراجعة.') }}
                    </p>
                </div>

                <div class="contributor-form-actions">
                    <a href="{{ route('contributors.show', $contributor->id) }}" class="contributor-form-btn">
                        <i class="bi bi-eye"></i>
                        {{ __('عرض الملف') }}
                    </a>
                    <a href="{{ route('contributors.index') }}" class="contributor-form-btn-muted">
                        <i class="bi bi-arrow-right-circle"></i>
                        {{ __('العودة للمساهمين') }}
                    </a>
                </div>
            </div>
        </section>

        <div class="contributor-form-grid">
            <section class="contributor-panel">
                <div class="contributor-panel-header">
                    <div class="contributor-panel-title-wrap">
                        <span class="contributor-panel-icon"><i class="bi bi-sliders2"></i></span>
                        <div>
                            <h2 class="contributor-panel-title">{{ __('تحديث بيانات الملف') }}</h2>
                            <p class="contributor-panel-subtitle">{{ __('النموذج الحالي يعرض نفس حقول الإدخال مع تحسين المعاينة والتنبيه لسبب التعديل.') }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('contributors.update', $contributor->id) }}" method="POST" enctype="multipart/form-data"
                    data-contributor-form
                    data-confirm-message="{{ __('هل أنت متأكد من حفظ التغييرات؟') }}"
                    data-name-label="{{ __('الاسم') }}"
                    data-id-label="{{ __('رقم الهوية') }}"
                    data-empty-name="{{ __('يرجى إدخال اسم المساهم') }}"
                    data-empty-id="{{ __('يرجى إدخال رقم الهوية') }}">
                    @csrf
                    @method('PUT')

                    @include('contributors.partials.form-fields', [
                        'isEdit' => true,
                        'contributor' => $contributor,
                        'companies' => $companies,
                        'departments' => $departments,
                    ])

                    <div class="contributor-form-footer">
                        <p class="contributor-form-footer-note">
                            {{ __('سيتم تسجيل التعديل مع السبب الذي تدخله هنا، لذلك احرص على كتابة وصف واضح يسهل الرجوع إليه لاحقًا.') }}
                        </p>

                        <div class="contributor-form-footer-actions">
                            <button type="submit" class="contributor-form-btn">
                                <i class="bi bi-save2"></i>
                                {{ __('حفظ التغييرات') }}
                            </button>
                            <a href="{{ route('contributors.show', $contributor->id) }}" class="contributor-form-btn-muted">
                                <i class="bi bi-eye"></i>
                                {{ __('عرض') }}
                            </a>
                            <a href="{{ route('contributors.index') }}" class="contributor-form-btn-muted">
                                <i class="bi bi-x-circle"></i>
                                {{ __('إلغاء') }}
                            </a>
                        </div>
                    </div>
                </form>
            </section>

            <aside class="contributor-side-stack">
                <section class="contributor-mini-card">
                    <h3 class="contributor-mini-title">
                        <i class="bi bi-person-badge"></i>
                        {{ __('ملخص سريع') }}
                    </h3>
                    <div class="contributor-stat-grid">
                        <div class="contributor-stat-box">
                            <strong>#{{ $contributor->id }}</strong>
                            <span>{{ __('رقم المساهم في النظام') }}</span>
                        </div>
                        <div class="contributor-stat-box">
                            <strong>{{ number_format($contributor->share_count_cr ?? 0, 0) }}</strong>
                            <span>{{ __('إجمالي الأسهم الحالية') }}</span>
                        </div>
                        <div class="contributor-stat-box">
                            <strong>{{ $contributor->documents()->count() }}</strong>
                            <span>{{ __('عدد الوثائق المرفوعة') }}</span>
                        </div>
                        <div class="contributor-stat-box">
                            <strong>{{ collect($contributor->membership_labels)->count() }}</strong>
                            <span>{{ __('عدد العضويات الإشرافية') }}</span>
                        </div>
                    </div>
                </section>

                <section class="contributor-mini-card">
                    <h3 class="contributor-mini-title">
                        <i class="bi bi-clock-history"></i>
                        {{ __('الحالة الحالية') }}
                    </h3>
                    <div class="contributor-meta-list">
                        <div class="contributor-meta-item">
                            <i class="bi bi-calendar-plus"></i>
                            <div>{{ __('أنشئ في') }}: {{ $contributor->created_at->format('Y-m-d H:i') }}</div>
                        </div>
                        <div class="contributor-meta-item">
                            <i class="bi bi-arrow-repeat"></i>
                            <div>{{ __('آخر تحديث') }}: {{ $contributor->updated_at->format('Y-m-d H:i') }}</div>
                        </div>
                        <div class="contributor-meta-item">
                            <i class="bi bi-hourglass-split"></i>
                            <div>{{ __('منذ الإنشاء') }}: {{ $contributor->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                </section>

                <section class="contributor-mini-card">
                    <h3 class="contributor-mini-title">
                        <i class="bi bi-link-45deg"></i>
                        {{ __('الارتباطات') }}
                    </h3>
                    <div class="contributor-meta-list">
                        @if ($contributor->user)
                            <div class="contributor-meta-item">
                                <i class="bi bi-person-check"></i>
                                <div>{{ __('الحساب المرتبط') }}: {{ $contributor->user->name }}</div>
                            </div>
                            <div class="contributor-meta-item">
                                <i class="bi bi-envelope"></i>
                                <div>{{ __('البريد الإلكتروني') }}: {{ $contributor->user->email }}</div>
                            </div>
                        @else
                            <div class="contributor-meta-item">
                                <i class="bi bi-person-x"></i>
                                <div>{{ __('لا يوجد حساب مستخدم مرتبط بهذا المساهم حتى الآن.') }}</div>
                            </div>
                        @endif
                    </div>
                </section>
            </aside>
        </div>
    </div>
</div>
@endsection

@include('contributors.partials.form-scripts')

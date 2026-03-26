@extends('layouts.app')

@section('title', __('تعديل اللائحة'))

@include('regulations.partials.form-styles')

@section('content')
<div class="container-fluid reg-form-page">
    <div class="reg-form-shell">
        <section class="reg-form-hero">
            <div class="reg-form-hero-inner">
                <div>
                    <span class="reg-form-badge">
                        <i class="bi bi-pencil-fill"></i>
                        {{ __('تعديل اللائحة') }} #{{ $regulation->id }}
                    </span>
                    <h1 class="reg-form-title">{{ __('حدّث اسم اللائحة أو استبدل الملف من نفس الشاشة') }}</h1>
                    <p class="reg-form-subtitle">
                        {{ __('راجع الاسم الحالي، نزّل الملف الموجود عند الحاجة، ثم استبدله بملف جديد دون فقدان وضوح البيانات داخل القائمة.') }}
                    </p>
                </div>

                <div class="reg-form-actions">
                    <a href="{{ route('regulations.show', $regulation) }}" class="reg-form-btn">
                        <i class="bi bi-eye"></i>
                        {{ __('عرض اللائحة') }}
                    </a>
                    <a href="{{ route('regulations.index') }}" class="reg-form-btn-muted">
                        <i class="bi bi-arrow-right-circle"></i>
                        {{ __('العودة للوائح') }}
                    </a>
                </div>
            </div>
        </section>

        <div class="reg-form-grid">
            <section class="reg-panel">
                <div class="reg-panel-header">
                    <div class="reg-panel-title-wrap">
                        <span class="reg-panel-icon"><i class="bi bi-sliders2"></i></span>
                        <div>
                            <h2 class="reg-panel-title">{{ __('تحديث بيانات اللائحة') }}</h2>
                            <p class="reg-panel-subtitle">{{ __('يمكنك تعديل الاسم الحالي أو رفع ملف بديل مع الاحتفاظ بالملف القديم إذا لم تحدد ملفًا جديدًا.') }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('regulations.update', $regulation) }}" method="POST" enctype="multipart/form-data"
                    data-reg-form
                    data-confirm-message="{{ __('هل أنت متأكد من حفظ تعديلات اللائحة؟') }}"
                    data-name-label="{{ __('اسم اللائحة') }}"
                    data-empty-name="{{ __('يرجى إدخال اسم اللائحة') }}"
                    data-require-name="true">
                    @csrf
                    @method('PUT')

                    @include('regulations.partials.form-fields', [
                        'isEdit' => true,
                        'regulation' => $regulation,
                    ])

                    <div class="reg-form-footer">
                        <p class="reg-form-footer-note">
                            {{ __('سيتم الاحتفاظ بالملف الحالي تلقائيًا إذا لم يتم اختيار ملف بديل. وعند الرفع الجديد ستُستبدل بيانات الملف السابقة بالكامل.') }}
                        </p>

                        <div class="reg-form-footer-actions">
                            <button type="submit" class="reg-form-btn">
                                <i class="bi bi-save2"></i>
                                {{ __('حفظ التغييرات') }}
                            </button>
                            <a href="{{ route('regulations.show', $regulation) }}" class="reg-form-btn-muted">
                                <i class="bi bi-eye"></i>
                                {{ __('عرض') }}
                            </a>
                            <a href="{{ route('regulations.index') }}" class="reg-form-btn-muted">
                                <i class="bi bi-x-circle"></i>
                                {{ __('إلغاء') }}
                            </a>
                        </div>
                    </div>
                </form>
            </section>

            <aside class="reg-side-stack">
                <section class="reg-mini-card">
                    <h3 class="reg-mini-title">
                        <i class="bi bi-file-earmark-text"></i>
                        {{ __('ملخص سريع') }}
                    </h3>
                    <div class="reg-stat-grid">
                        <div class="reg-stat-box">
                            <strong>#{{ $regulation->id }}</strong>
                            <span>{{ __('رقم اللائحة') }}</span>
                        </div>
                        <div class="reg-stat-box">
                            <strong>{{ strtoupper($regulation->file_extension) }}</strong>
                            <span>{{ __('امتداد الملف الحالي') }}</span>
                        </div>
                        <div class="reg-stat-box">
                            <strong>{{ $regulation->file_size_human }}</strong>
                            <span>{{ __('حجم الملف الحالي') }}</span>
                        </div>
                        <div class="reg-stat-box">
                            <strong>{{ $regulation->updated_at->diffForHumans() }}</strong>
                            <span>{{ __('آخر تحديث') }}</span>
                        </div>
                    </div>
                </section>

                <section class="reg-mini-card">
                    <h3 class="reg-mini-title">
                        <i class="bi bi-clock-history"></i>
                        {{ __('حالة الملف') }}
                    </h3>
                    <div class="reg-meta-list">
                        <div class="reg-meta-item">
                            <i class="bi bi-calendar-plus"></i>
                            <div>{{ __('أنشئ في') }}: {{ $regulation->created_at->format('Y-m-d H:i') }}</div>
                        </div>
                        <div class="reg-meta-item">
                            <i class="bi bi-arrow-repeat"></i>
                            <div>{{ __('آخر تحديث') }}: {{ $regulation->updated_at->format('Y-m-d H:i') }}</div>
                        </div>
                        <div class="reg-meta-item">
                            <i class="bi bi-file-earmark-code"></i>
                            <div>{{ __('نوع الملف') }}: {{ $regulation->file_type ?: __('غير محدد') }}</div>
                        </div>
                    </div>
                </section>
            </aside>
        </div>
    </div>
</div>
@endsection

@include('regulations.partials.form-scripts')

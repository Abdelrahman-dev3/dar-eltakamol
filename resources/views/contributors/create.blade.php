@extends('layouts.app')

@section('title', __('إضافة مساهم جديد'))

@include('contributors.partials.form-styles')

@section('content')
<div class="container-fluid contributor-form-page">
    <div class="contributor-form-shell">
        <section class="contributor-form-hero">
            <div class="contributor-form-hero-inner">
                <div>
                    <span class="contributor-form-badge">
                        <i class="bi bi-person-plus-fill"></i>
                        {{ __('إضافة مساهم جديد') }}
                    </span>
                    <h1 class="contributor-form-title">{{ __('بناء ملف مساهم واضح ومنظم من أول خطوة') }}</h1>
                </div>

                <div class="contributor-form-actions">
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
                        <span class="contributor-panel-icon"><i class="bi bi-pencil-square"></i></span>
                        <div>
                            <h2 class="contributor-panel-title">{{ __('نموذج إضافة المساهم') }}</h2>
                            <p class="contributor-panel-subtitle">{{ __('كل الحقول المهمة مرتبة في أقسام قصيرة لتقليل الأخطاء وتسريع الإدخال.') }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('contributors.store') }}" method="POST" enctype="multipart/form-data"
                    data-contributor-form
                    data-confirm-message="{{ __('هل أنت متأكد من إضافة هذا المساهم؟') }}"
                    data-name-label="{{ __('الاسم') }}"
                    data-id-label="{{ __('رقم الهوية') }}"
                    data-empty-name="{{ __('يرجى إدخال اسم المساهم') }}"
                    data-empty-id="{{ __('يرجى إدخال رقم الهوية') }}">
                    @csrf

                    @include('contributors.partials.form-fields', ['isEdit' => false, 'contributor' => null])

                    <div class="contributor-form-footer">
                        <p class="contributor-form-footer-note">
                            {{ __('سيتم حفظ البيانات مباشرة مع المرفقات المضافة. يمكنك تعديل أي بيانات لاحقًا من شاشة العرض أو التعديل.') }}
                        </p>

                        <div class="contributor-form-footer-actions">
                            <button type="submit" class="contributor-form-btn">
                                <i class="bi bi-check2-circle"></i>
                                {{ __('حفظ المساهم') }}
                            </button>
                            <a href="{{ route('contributors.index') }}" class="contributor-form-btn-muted">
                                <i class="bi bi-x-circle"></i>
                                {{ __('إلغاء') }}
                            </a>
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>
@endsection

@include('contributors.partials.form-scripts')

@extends('layouts.app')

@section('title', __('تعديل الاجتماع'))

@include('meetings.partials.form-styles')

@section('content')
<div class="container-fluid meeting-form-page">
    <div class="meeting-form-shell">
        <section class="meeting-form-hero">
            <div class="meeting-form-hero-inner">
                <div>
                    <span class="meeting-form-badge">
                        <i class="bi bi-pencil-fill"></i>
                        {{ __('تعديل الاجتماع') }} #{{ $meeting->id }}
                    </span>
                    <h1 class="meeting-form-title">{{ __('راجع تفاصيل الاجتماع قبل اعتماد التحديث') }}</h1>
                    <p class="meeting-form-subtitle">
                        {{ __('حدّث بيانات الاجتماع والمدعوين والمرفقات من نفس الشاشة مع إبراز الحالة الحالية وعدد الملفات المرتبطة به.') }}
                    </p>
                </div>

                <div class="meeting-form-actions">
                    <a href="{{ route('meetings.show', $meeting) }}" class="meeting-form-btn">
                        <i class="bi bi-eye"></i>
                        {{ __('عرض الاجتماع') }}
                    </a>
                    <a href="{{ route('meetings.index') }}" class="meeting-form-btn-muted">
                        <i class="bi bi-arrow-right-circle"></i>
                        {{ __('العودة للاجتماعات') }}
                    </a>
                </div>
            </div>
        </section>

        <div class="meeting-form-grid">
            <section class="meeting-panel">
                <div class="meeting-panel-header">
                    <div class="meeting-panel-title-wrap">
                        <span class="meeting-panel-icon"><i class="bi bi-sliders2"></i></span>
                        <div>
                            <h2 class="meeting-panel-title">{{ __('تحديث بيانات الاجتماع') }}</h2>
                            <p class="meeting-panel-subtitle">{{ __('نفس الحقول الأساسية مع عرض المرفقات الحالية وإمكانية إضافة ملفات جديدة مباشرة.') }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('meetings.update', $meeting) }}" method="POST" enctype="multipart/form-data"
                    data-meeting-form
                    data-confirm-message="{{ __('هل أنت متأكد من حفظ تغييرات الاجتماع؟') }}"
                    data-name-label="{{ __('اسم الاجتماع') }}"
                    data-date-label="{{ __('التاريخ') }}"
                    data-empty-name="{{ __('يرجى إدخال اسم الاجتماع') }}"
                    data-empty-url="{{ __('يرجى إدخال رابط الاجتماع') }}"
                    data-empty-date="{{ __('يرجى إدخال تاريخ الاجتماع') }}">
                    @csrf
                    @method('PUT')

                    @include('meetings.partials.form-fields', [
                        'isEdit' => true,
                        'meeting' => $meeting,
                        'users' => $users,
                    ])

                    <div class="meeting-form-footer">
                        <p class="meeting-form-footer-note">
                            {{ __('سيتم مزامنة قائمة المدعوين الحالية وإضافة أي مرفقات جديدة مباشرة بعد الحفظ، مع الإبقاء على المرفقات الحالية ما لم يتم حذفها يدويًا.') }}
                        </p>

                        <div class="meeting-form-footer-actions">
                            <button type="submit" class="meeting-form-btn">
                                <i class="bi bi-save2"></i>
                                {{ __('حفظ التغييرات') }}
                            </button>
                            <a href="{{ route('meetings.show', $meeting) }}" class="meeting-form-btn-muted">
                                <i class="bi bi-eye"></i>
                                {{ __('عرض') }}
                            </a>
                            <a href="{{ route('meetings.index') }}" class="meeting-form-btn-muted">
                                <i class="bi bi-x-circle"></i>
                                {{ __('إلغاء') }}
                            </a>
                        </div>
                    </div>
                </form>
            </section>

            <aside class="meeting-side-stack">
                <section class="meeting-mini-card">
                    <h3 class="meeting-mini-title">
                        <i class="bi bi-calendar2-week"></i>
                        {{ __('ملخص سريع') }}
                    </h3>
                    <div class="meeting-stat-grid">
                        <div class="meeting-stat-box">
                            <strong>#{{ $meeting->id }}</strong>
                            <span>{{ __('رقم الاجتماع في النظام') }}</span>
                        </div>
                        <div class="meeting-stat-box">
                            <strong>{{ number_format($meeting->users->count()) }}</strong>
                            <span>{{ __('عدد المدعوين الحالي') }}</span>
                        </div>
                        <div class="meeting-stat-box">
                            <strong>{{ number_format($meeting->attachments->count()) }}</strong>
                            <span>{{ __('المرفقات الحالية') }}</span>
                        </div>
                        <div class="meeting-stat-box">
                            <strong>{{ $meeting->date->diffForHumans() }}</strong>
                            <span>{{ __('وضع الموعد الحالي') }}</span>
                        </div>
                    </div>
                </section>

                <section class="meeting-mini-card">
                    <h3 class="meeting-mini-title">
                        <i class="bi bi-clock-history"></i>
                        {{ __('الحالة الحالية') }}
                    </h3>
                    <div class="meeting-meta-list">
                        <div class="meeting-meta-item">
                            <i class="bi bi-calendar-plus"></i>
                            <div>{{ __('أنشئ في') }}: {{ $meeting->created_at->format('Y-m-d H:i') }}</div>
                        </div>
                        <div class="meeting-meta-item">
                            <i class="bi bi-arrow-repeat"></i>
                            <div>{{ __('آخر تحديث') }}: {{ $meeting->updated_at->format('Y-m-d H:i') }}</div>
                        </div>
                        <div class="meeting-meta-item">
                            <i class="bi bi-link-45deg"></i>
                            <div>{{ __('الرابط الحالي') }}: {{ $meeting->url }}</div>
                        </div>
                    </div>
                </section>

                <section class="meeting-mini-card">
                    <h3 class="meeting-mini-title">
                        <i class="bi bi-lightning-charge"></i>
                        {{ __('وصول سريع') }}
                    </h3>
                    <div class="meeting-tip-list">
                        <div class="meeting-tip-item">
                            <i class="bi bi-box-arrow-up-right"></i>
                            <div><a href="{{ $meeting->url }}" target="_blank" rel="noopener noreferrer">{{ __('فتح رابط الاجتماع الحالي') }}</a></div>
                        </div>
                        <div class="meeting-tip-item">
                            <i class="bi bi-eye"></i>
                            <div><a href="{{ route('meetings.show', $meeting) }}">{{ __('الانتقال إلى صفحة العرض الكاملة') }}</a></div>
                        </div>
                    </div>
                </section>
            </aside>
        </div>
    </div>
</div>
@endsection

@include('meetings.partials.form-scripts')

@extends('layouts.app')

@section('title', 'فترات التداول')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">فترات التداول</h1>
        <a href="{{ route('trading-periods.create') }}" class="btn btn-primary">إضافة فترة</a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>السنة</th>
                        <th>الاسم</th>
                        <th>العرض والطلب</th>
                        <th>المعالجة</th>
                        <th>الصفقات الخاصة</th>
                        <th>الحالة</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($periods as $period)
                        <tr>
                            <td>{{ $period->year }}</td>
                            <td>{{ $period->name }}</td>
                            <td>{{ $period->offer_starts_at->format('Y-m-d') }} - {{ $period->offer_ends_at->format('Y-m-d') }}</td>
                            <td>{{ $period->processing_starts_at->format('Y-m-d') }} - {{ $period->processing_ends_at->format('Y-m-d') }}</td>
                            <td>{{ $period->private_starts_at->format('Y-m-d') }} - {{ $period->private_ends_at->format('Y-m-d') }}</td>
                            <td>{{ $period->is_active ? 'نشطة' : 'موقوفة' }}</td>
                            <td><a href="{{ route('trading-periods.edit', $period) }}" class="btn btn-sm btn-outline-primary">تعديل</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">لا توجد فترات تداول.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $periods->links() }}</div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'تفاصيل التزام شراء الشركة')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">التزام شراء الشركة #{{ $obligation->id }}</h1>
        <a href="{{ route('company-purchase-obligations.edit', $obligation) }}" class="btn btn-primary">تحديث التقييم</a>
    </div>

    <div class="card card-body">
        <dl class="row mb-0">
            <dt class="col-md-3">عرض البيع</dt><dd class="col-md-9">#{{ $obligation->sell_share_id }}</dd>
            <dt class="col-md-3">البائع</dt><dd class="col-md-9">{{ $obligation->seller->name ?? '-' }}</dd>
            <dt class="col-md-3">الكمية</dt><dd class="col-md-9">{{ number_format((float) $obligation->shares_count, 2) }}</dd>
            <dt class="col-md-3">سنة الاستحقاق</dt><dd class="col-md-9">{{ $obligation->due_year }}</dd>
            <dt class="col-md-3">السعر</dt><dd class="col-md-9">{{ number_format((float) $obligation->amount_per_share, 2) }}</dd>
            <dt class="col-md-3">القيمة العادلة</dt><dd class="col-md-9">{{ number_format((float) $obligation->fair_value, 2) }}</dd>
            <dt class="col-md-3">المقيم المختار</dt><dd class="col-md-9">{{ $obligation->selected_appraiser ?: '-' }}</dd>
            <dt class="col-md-3">الحالة</dt><dd class="col-md-9">{{ $obligation->status }}</dd>
        </dl>
    </div>
</div>
@endsection

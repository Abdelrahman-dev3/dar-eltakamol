@extends('layouts.app')

@section('title', 'تحديث التزام شراء الشركة')

@section('content')
<div class="container py-4">
    <h1 class="h3 mb-3">تحديث التزام شراء الشركة #{{ $obligation->id }}</h1>

    <form action="{{ route('company-purchase-obligations.update', $obligation) }}" method="POST" class="card card-body">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">سعر السهم</label>
                <input type="number" step="0.01" min="0" name="amount_per_share" class="form-control" value="{{ old('amount_per_share', $obligation->amount_per_share) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">القيمة العادلة</label>
                <input type="number" step="0.01" min="0" name="fair_value" class="form-control" value="{{ old('fair_value', $obligation->fair_value) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">طريقة السداد</label>
                <select name="payment_kind" class="form-control">
                    <option value="cash" @selected(old('payment_kind', $obligation->payment_kind) === 'cash')>نقداً</option>
                    <option value="in_kind" @selected(old('payment_kind', $obligation->payment_kind) === 'in_kind')>عينياً</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">الحالة</label>
                <select name="status" class="form-control">
                    <option value="scheduled" @selected(old('status', $obligation->status) === 'scheduled')>مجدول</option>
                    <option value="paid" @selected(old('status', $obligation->status) === 'paid')>مسدد</option>
                    <option value="cancelled" @selected(old('status', $obligation->status) === 'cancelled')>ملغي</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">المقيمون الثلاثة</label>
                <textarea name="appraisers" class="form-control" rows="4">{{ old('appraisers', implode("\n", $obligation->appraisers ?? [])) }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">المقيم المختار</label>
                <input type="text" name="selected_appraiser" class="form-control" value="{{ old('selected_appraiser', $obligation->selected_appraiser) }}">
                <label class="form-label mt-3">ملاحظات</label>
                <textarea name="notes" class="form-control" rows="2">{{ old('notes', $obligation->notes) }}</textarea>
            </div>
            <div class="col-md-3">
                <label class="form-label">تاريخ التقييم</label>
                <input type="date" name="valuation_date" class="form-control" value="{{ old('valuation_date', $obligation->valuation_date?->format('Y-m-d')) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">تاريخ الاستحقاق</label>
                <input type="date" name="due_date" class="form-control" value="{{ old('due_date', $obligation->due_date?->format('Y-m-d')) }}">
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger mt-3">{{ $errors->first() }}</div>
        @endif

        <div class="mt-4 d-flex gap-2">
            <button class="btn btn-primary">حفظ</button>
            <a href="{{ route('company-purchase-obligations.show', $obligation) }}" class="btn btn-secondary">إلغاء</a>
        </div>
    </form>
</div>
@endsection

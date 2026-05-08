<form action="{{ $action }}" method="POST" class="card card-body">
    @csrf
    @if($method)
        @method($method)
    @endif

    <div class="row g-3">
        <div class="col-md-2">
            <label class="form-label">السنة</label>
            <input type="number" name="year" class="form-control" value="{{ old('year', $period->year ?: now()->year) }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">اسم الفترة</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $period->name) }}" required>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <label class="form-check">
                <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', $period->is_active ?? true) ? 'checked' : '' }}>
                <span class="form-check-label">نشطة</span>
            </label>
        </div>
        <div class="col-md-3">
            <label class="form-label">بداية العرض والطلب</label>
            <input type="date" name="offer_starts_at" class="form-control" value="{{ old('offer_starts_at', $period->offer_starts_at?->format('Y-m-d')) }}" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">نهاية العرض والطلب</label>
            <input type="date" name="offer_ends_at" class="form-control" value="{{ old('offer_ends_at', $period->offer_ends_at?->format('Y-m-d')) }}" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">بداية المعالجة</label>
            <input type="date" name="processing_starts_at" class="form-control" value="{{ old('processing_starts_at', $period->processing_starts_at?->format('Y-m-d')) }}" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">نهاية المعالجة</label>
            <input type="date" name="processing_ends_at" class="form-control" value="{{ old('processing_ends_at', $period->processing_ends_at?->format('Y-m-d')) }}" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">بداية الصفقات الخاصة</label>
            <input type="date" name="private_starts_at" class="form-control" value="{{ old('private_starts_at', $period->private_starts_at?->format('Y-m-d')) }}" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">نهاية الصفقات الخاصة</label>
            <input type="date" name="private_ends_at" class="form-control" value="{{ old('private_ends_at', $period->private_ends_at?->format('Y-m-d')) }}" required>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger mt-3">{{ $errors->first() }}</div>
    @endif

    <div class="mt-4 d-flex gap-2">
        <button class="btn btn-primary">حفظ</button>
        <a href="{{ route('trading-periods.index') }}" class="btn btn-secondary">إلغاء</a>
    </div>
</form>

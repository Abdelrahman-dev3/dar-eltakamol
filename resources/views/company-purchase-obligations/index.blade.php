@extends('layouts.app')

@section('title', 'التزامات شراء الشركة')

@section('content')
<div class="container py-4">
    <h1 class="h3 mb-3">التزامات شراء الشركة</h1>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>العرض</th>
                        <th>البائع</th>
                        <th>الكمية</th>
                        <th>سنة الاستحقاق</th>
                        <th>القيمة</th>
                        <th>الحالة</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($obligations as $obligation)
                        <tr>
                            <td>#{{ $obligation->sell_share_id }}</td>
                            <td>{{ $obligation->seller->name ?? '-' }}</td>
                            <td>{{ number_format((float) $obligation->shares_count, 2) }}</td>
                            <td>{{ $obligation->due_year }}</td>
                            <td>{{ number_format((float) $obligation->total_amount, 2) }}</td>
                            <td>{{ $obligation->status }}</td>
                            <td><a href="{{ route('company-purchase-obligations.show', $obligation) }}" class="btn btn-sm btn-outline-primary">عرض</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">لا توجد التزامات حالياً.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $obligations->links() }}</div>
</div>
@endsection

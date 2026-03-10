@extends('layouts.app')

@section('title', __('الحجوزات'))

@section('content')
<style>
    .toggle-switch {
    display: inline-block;
    position: relative;
    width: 60px;
    height: 34px;
    }

    .toggle-input {
    display: none;
    }

    .toggle-label {
    position: absolute;
    top: 0;
    left: 0;
    width: 60px;
    height: 34px;
    background-color: #ccc;
    border-radius: 34px;
    cursor: pointer;
    transition: background-color 0.3s;
    }

    .toggle-label:before {
    content: "";
    position: absolute;
    top: 2px;
    left: 2px;
    width: 30px;
    height: 30px;
    background-color: #fff;
    border-radius: 50%;
    transition: transform 0.3s;
    }

    .toggle-input:checked + .toggle-label {
    background-color: #4fbf26;
    }

    .toggle-input:checked + .toggle-label:before {
    transform: translateX(26px);
    }

</style>
<div class="row">
    <div class="col-md-12">
        <h2>{{ __('الحجوزات') }}</h2>
        <p>
            <a href="{{ route('bookings.create') }}" class="btn btn-primary">{{ __('إضافة حجز جديد') }}</a>
        </p>

        <div class="table-responsive">
            <table class="table table-striped table-bordered" style="text-align: center;">
                <thead>
                    <tr>
                        <th style="text-align: center;">#</th>
                        <th style="text-align: center;">{{ __('الخدمة') }}</th>
                        <th style="text-align: center;">{{ __('طالب الخدمة') }}</th>
                        <th style="text-align: center;">{{ __('تاريخ ووقت الخدمة') }}</th>
                        <th style="text-align: center;">{{ __('ملااحظات') }}</th>
                        <th style="text-align: center;">{{ __('الحالة') }}</th>
                        <th style="text-align: center;">{{ __('اخر تعديل') }}</th>
                        <th style="text-align: center;">{{ __('الإجراءات') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $booking->service->name }}</td>
                            <td>{{ $booking->user->name }}</td>
                            <td>{{ $booking->booking_date->format('d/m/y') }} {{$booking->booking_time->format('h:i')}}</td>
                            <td>{{ $booking->notes ?? '' }}</td>
                            <td>
                                <div class="toggle-switch">
                                    <input class="toggle-input" id="toggle_{{ $booking->id }}" type="checkbox" @checked($booking->status == 'confirmed') />
                                    <label class="toggle-label" title="{{$booking->status}}" for="toggle_{{ $booking->id }}"></label>
                                </div>
                            </td>
                            <td>{{ $booking->updated_at->diffForHumans() }}</td>
                            <td>
                                <a href="{{ route('bookings.edit',$booking->id) }}" style="padding: 8px 14px;" class="btn btn-sm btn-primary">{{ __('تعديل') }}</a>
                                <form action="{{ route('bookings.destroy',$booking->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="padding: 8px 14px;" class="btn btn-sm btn-danger" onclick="return confirm('{{ __('هل أنت متأكد من الحذف؟') }}')">
                                        {{ __('حذف') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">{{ __('لا توجد حجوزات') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
    </div>
</div>
<script>
document.querySelectorAll('.toggle-input').forEach(toggle => {
    toggle.addEventListener('change', async (e) => {
        const bookingId = e.target.id.replace('toggle_', '');
        const newStatus = e.target.checked ? 'confirmed' : 'pending';

        try {
            const response = await fetch(`/bookings/${bookingId}/status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status: newStatus })
            });

            const data = await response.json();

            if (response.ok) {
                e.target.nextElementSibling.title = data.status;
            } else {
                alert(data.message || 'حدث خطأ أثناء التحديث');
                e.target.checked = !e.target.checked; // ترجع السويتش لو فشل التحديث
            }

        } catch (error) {
            alert('خطأ في الاتصال بالسيرفر');
            e.target.checked = !e.target.checked;
        }
    });
});
</script>

@endsection

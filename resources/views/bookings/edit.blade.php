@extends('layouts.app')

@section('title', __('اضافة حجز جديد'))

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
        }

        .settings-card {
            background-color: var(--card-bg);
            border-radius: 25px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            padding: 40px 30px;
        }

        .settings-card h2 {
            color: var(--text-primary);
            font-size: 28px;
            margin-bottom: 30px;
            border-bottom: 3px solid var(--golden-light);
            padding-bottom: 10px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        label {
            color: var(--text-secondary);
            font-weight: 500;
            margin-bottom: 5px;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            border-radius: 15px;
            font-size: 16px;
            outline: none;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.3);
        }

        button {
            background-color: var(--primary-color);
            color: var(--text-white);
            padding: 14px;
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        button:hover {
            background-color: var(--primary-hover);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        @media (max-width: 600px) {
            .settings-card {
                padding: 30px 20px;
            }

            .settings-card h2 {
                font-size: 24px;
            }
        }
        select {
            width: 100%;
            padding: 10px 40px 10px 15px;
            font-size: 1.8rem;
            border: 2px solid #c9a34e; /* لون ذهبي ناعم */
            border-radius: 10px;
            background-color: #fff;
            color: #333;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml;utf8,<svg fill='%23c9a34e' height='24' viewBox='0 0 24 24' width='24' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/></svg>");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 18px;
            transition: all 0.3s ease;
            }

            /* تأثير عند المرور */
            select:hover {
            border-color: #aa863f;
            box-shadow: 0 0 8px rgba(170, 134, 63, 0.3);
            }

            /* عند التركيز (focus) */
            select:focus {
            outline: none;
            border-color: #aa863f;
            box-shadow: 0 0 10px rgba(170, 134, 63, 0.4);
            }

            /* شكل العناصر داخل القائمة */
            select option {
            padding: 10px;
            font-size: 1.8rem;
            background-color: #fff;
            color: #333;
            }
    </style>

    <div class="container">
        <div class="settings-card">
            <h2>{{ __('تعديل حجز') }}</h2>
            <form action="{{ route('bookings.update' , $booking->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div>
                    <select name="service_id" id="service_id">
                        <option value="">{{ __('اختر الخدمة') }}</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" @selected(old('service_id', $booking->service_id) == $service->id)>{{ $service->name }}</option>
                        @endforeach
                    </select>
                    @error('service_id')
                        <span style="color: var(--danger-color); font-size: 14px;">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <select name="user_id" id="user_id">
                        <option value="">{{ __('اختر طالب الخدمة') }}</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @selected(old('user_id', $booking->user_id) == $user->id)>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <span style="color: var(--danger-color); font-size: 14px;">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="date">{{ __('التاريخ') }}</label>
                    <input type="text" id="date" value="{{$booking->booking_date}}" name="date">
                    @error('date')
                        <span style="color: var(--danger-color); font-size: 14px;">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="time">{{ __('الوقت') }}</label>
                    <input type="text" id="time" name="time"  value="{{$booking->booking_time}}">
                    @error('time')
                        <span style="color: var(--danger-color); font-size: 14px;">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="notes">{{ __('ملاحظات') }}</label>
                    <input type="text" name="notes" value="{{ old('notes', $booking->notes) }}">
                    @error('notes')
                        <span style="color: var(--danger-color); font-size: 14px;">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit">{{ __('تحديث') }}</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
        document.addEventListener("DOMContentLoaded", function() {
        flatpickr("#time", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: false,
            minuteIncrement: 1,
            placeholder: "اختر الوقت",
        });
        flatpickr("#date", {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "F j, Y",
            locale: "ar",
        });
    });
</script>
@endsection

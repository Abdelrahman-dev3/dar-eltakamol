@extends('layouts.app')

@section('title', __('الاعدادات الرئيسية'))

@section('content')
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
    </style>

    <div class="container">
        <div class="settings-card">
            <h2>{{ __('تعديل خدمة') }}</h2>
            <form action="{{ route('servies.update' , $service->id) }}" method="POST">
                @method('PUT')
                @csrf
                <div>
                    <label for="service">{{ __('اسم الخدمة') }}</label>
                    <input type="text" name="service" value="{{$service->name}}" placeholder="ادخل اسم الخدمة" value="{{ old('service') }}">
                    @error('service')
                        <span style="color: var(--danger-color); font-size: 14px;">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit">{{ __('تحديث') }}</button>
            </form>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('title', __('الاعدادات الرئيسية'))

@section('content')
    <style>
        /* ===== ألوان المشروع ===== */
        :root {
            --primary-color: #aa863f;     
            --primary-hover: #957a36;     
            --secondary-color: #8b7355;   
            --accent-color: #c4a85a;      
            --success-color: #059669;     
            --warning-color: #d97706;     
            --danger-color: #dc2626;      
            --sidebar-bg: #aa863f;        
            --sidebar-hover: #957a36;     
            --content-bg: #f8fafc;        
            --card-bg: #ffffff;            
            --border-color: #e2e8f0;      
            --text-primary: #1e293b;      
            --text-secondary: #64748b;    
            --text-light: #94a3b8;        
            --text-white: #ffffff;        
            --golden-light: #d4af37;      
            --golden-dark: #8b6914;       
        }

        /* ===== إعدادات عامة ===== */
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* ===== محتوى الصفحة ===== */
        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
        }

        /* ===== كرت الإعدادات ===== */
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

        /* ===== الفورم ===== */
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

        input[type="number"] {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            border-radius: 15px;
            font-size: 16px;
            outline: none;
            transition: all 0.3s ease;
        }

        input[type="number"]:focus {
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

        /* ===== responsive ===== */
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
            <h2>إعدادات المشروع</h2>
            <form action="{{ route('settings.store') }}" method="POST">
                @csrf
                <div>
                    <label for="base_price">السعر الأساسي للسهم</label>
                    <input type="number" id="base_price" name="settings[base_price]" value="{{$stock ?? 0}}" placeholder="ادخل السعر" min="0" value="{{ old('settings[base_price]', $settings->base_price ?? '') }}">
                    @error('base_price')
                        <span style="color: var(--danger-color); font-size: 14px;">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <div style="display: flex;justify-content: space-between;">
                        <label for="SOTY">{{ __('النسبة المئوية لبيع الأسهم علي ٣ سنوات') }}</label>
                        <span id="main_per" style="display: none;gap: 6px;" ><span id="per"></span>{{ __('مقدار السنه الواحدة') }}</span>
                    </div>
                    <input type="number" id="SOTY" name="settings[SOTY]" value="{{$SOTY ?? 0}}" placeholder="ادخل السعر" min="0" value="{{ old('settings[SOTY]', $settings->SOTY ?? '') }}">
                    @error('SOTY')
                        <span style="color: var(--danger-color); font-size: 14px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="border-top: 2px solid var(--border-color); padding-top: 25px; margin-top: 10px;">
                    <h3 style="color: var(--text-primary); font-size: 20px; margin-bottom: 20px;">فترات التداول</h3>
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <label for="trading_period_open" style="margin: 0; flex: 1;">
                            {{ __('فتح فترة التداول') }}
                        </label>
                        <label style="position: relative; display: inline-block; width: 60px; height: 30px; margin: 0;">
                            <input type="checkbox" 
                                   id="trading_period_open" 
                                   name="settings[trading_period_open]" 
                                   value="1" 
                                   {{ $trading_period_open ? 'checked' : '' }}
                                   style="opacity: 0; width: 0; height: 0;">
                            <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; border-radius: 30px; transition: 0.3s;">
                                <span style="position: absolute; content: ''; height: 22px; width: 22px; left: 4px; bottom: 4px; background-color: white; border-radius: 50%; transition: 0.3s;"></span>
                            </span>
                        </label>
                        <span id="trading_status" style="font-weight: 600; color: {{ $trading_period_open ? 'var(--success-color)' : 'var(--danger-color)' }};">
                            {{ $trading_period_open ? 'مفتوح' : 'مغلق' }}
                        </span>
                    </div>
                    <small style="color: var(--text-secondary); display: block; margin-top: 10px;">
                        {{ __('عند إغلاق فترة التداول، لن يتمكن المستخدمون من إجراء معاملات الأسهم') }}
                    </small>
                </div>

                <button type="submit">حفظ الإعدادات</button>
            </form>
        </div>
    </div>
    <script>
        let SOTY = document.getElementById('SOTY');
        SOTY.addEventListener('input', function() {
            let per = document.getElementById('per');
            let main_per = document.getElementById('main_per');
            per.innerHTML = (this.value / 3).toFixed(2) + '%';
            main_per.style.display = 'flex';
        });
        document.addEventListener('DOMContentLoaded', function() {
            let SOTYInput = document.getElementById('SOTY').value;
            let per = document.getElementById('per');
            let main_per = document.getElementById('main_per');
            if (SOTYInput > 0) {
                per.innerHTML = (SOTYInput / 3).toFixed(2) + '%';
                main_per.style.display = 'flex';
            }

            // Trading period toggle
            const tradingToggle = document.getElementById('trading_period_open');
            const tradingStatus = document.getElementById('trading_status');
            const toggleSpan = tradingToggle.nextElementSibling;
            const toggleCircle = toggleSpan.querySelector('span');

            tradingToggle.addEventListener('change', function() {
                if (this.checked) {
                    toggleSpan.style.backgroundColor = 'var(--success-color)';
                    toggleCircle.style.transform = 'translateX(30px)';
                    tradingStatus.textContent = 'مفتوح';
                    tradingStatus.style.color = 'var(--success-color)';
                } else {
                    toggleSpan.style.backgroundColor = '#ccc';
                    toggleCircle.style.transform = 'translateX(0)';
                    tradingStatus.textContent = 'مغلق';
                    tradingStatus.style.color = 'var(--danger-color)';
                }
            });

            // Set initial state
            if (tradingToggle.checked) {
                toggleSpan.style.backgroundColor = 'var(--success-color)';
                toggleCircle.style.transform = 'translateX(30px)';
            }
        });
    </script>

@endsection

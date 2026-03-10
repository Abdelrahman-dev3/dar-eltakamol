@extends('layouts.app')

@section('title', __('إضافة عرض بيع أسهم'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        {{ __('إضافة عرض بيع أسهم') }}
                        <div class="pull-left">
                            <a href="{{ route('sell-shares.index') }}" class="btn btn-default btn-sm">
                                <span class="glyphicon glyphicon-arrow-right"></span> {{ __('رجوع') }}
                            </a>
                        </div>
                    </h3>
                </div>
                <div class="panel-body">
                    <form action="{{ route('sell-shares.store') }}" method="POST">
                        @csrf

                        <div class="form-group @error('user_id') has-error @enderror">
                            <label for="user_id">{{ __('المساهم') }} <span class="text-danger">*</span></label>
                            <select name="user_id" id="user_id" class="form-control" required>
                                <option value="">{{ __('اختر المساهم') }}</option>
                                @foreach(\App\Models\Contributor::with('user')->get() as $contributor)
                                    <option value="{{ $contributor->id }}" {{ old('user_id') == $contributor->id ? 'selected' : '' }}>
                                        {{ $contributor->name ?? $contributor->user->name ?? __('غير معروف') }}
                                        @if($contributor->share_count_cr)
                                            ({{ number_format($contributor->share_count_cr, 0) }} {{ __('سهم') }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group @error('count') has-error @enderror">
                        <div style="display: flex;justify-content: space-between;">
                            <label for="count">{{ __('عدد الأسهم المراد بيعها') }} <span class="text-danger">*</span></label>
                            <span id="contributor-info" style="display: flex;"></span>
                        </div>
                        <input type="number" name="count" id="count" class="form-control" 
                                   value="{{ old('count') }}" min="1" step="0.01" required
                                   placeholder="{{ __('أدخل عدد الأسهم') }}">
                            @error('count')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group @error('amount_per_share') has-error @enderror">
                            <label for="amount_per_share">{{ __('السعر لكل سهم') }} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="amount_per_share" id="amount_per_share" class="form-control" 
                                       value="{{ old('amount_per_share') }}" min="0.01" step="0.01" required
                                       placeholder="{{ __('أدخل السعر لكل سهم') }}">
                                <span class="input-group-addon">{{ __('ريال') }}</span>
                            </div>
                            @error('amount_per_share')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>
                            <input type="hidden" name="input_count" id="input_count">
                        <div class="form-group @error('end_date') has-error @enderror">
                            <label for="end_date">{{ __('تاريخ انتهاء العرض') }}</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" 
                                   value="{{ old('end_date') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                   placeholder="{{ __('اختياري - تاريخ انتهاء صحة العرض') }}">
                                   <small class="text-muted">{{ __('إذا لم تحدد تاريخ انتهاء، سيبقى العرض نشطاً حتى يتم إلغاؤه') }}</small>
                                   @error('end_date')
                                   <span class="help-block">{{ $message }}</span>
                                   @enderror
                                </div>
                            
                        <div class="form-group @error('notes') has-error @enderror">
                            <label for="notes">{{ __('ملاحظات') }}</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3" 
                                      placeholder="{{ __('أدخل أي ملاحظات إضافية حول عرض البيع (اختياري)') }}">{{ old('notes') }}</textarea>
                            <small class="text-muted">{{ __('يمكنك إضافة ملاحظات إضافية حول عرض البيع') }}</small>
                            @error('notes')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Total Amount Display -->
                        <div class="form-group">
                            <div class="alert alert-info">
                                <h4>{{ __('ملخص العرض') }}</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>{{ __('عدد الأسهم') }}:</strong> <span id="display-count">0</span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>{{ __('السعر لكل سهم') }}:</strong> <span id="display-price">0.00</span> {{ __('ريال') }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3><strong>{{ __('المبلغ الإجمالي') }}: <span id="display-total">0.00</span> {{ __('ريال') }}</strong></h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <span class="glyphicon glyphicon-floppy-disk"></span> {{ __('حفظ عرض البيع') }}
                            </button>
                            <a href="{{ route('sell-shares.index') }}" class="btn btn-default">
                                <span class="glyphicon glyphicon-arrow-right"></span> {{ __('إلغاء') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Information Panel -->
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h4 class="panel-title">{{ __('معلومات مهمة') }}</h4>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5><span class="glyphicon glyphicon-info-sign"></span> {{ __('حول عروض البيع') }}</h5>
                                    <ul>
                                        <li>{{ __('يمكن للمساهمين إنشاء عروض بيع لأسهمهم') }}</li>
                                        <li>{{ __('العروض تظهر للمشترين المحتملين') }}</li>
                                        <li>{{ __('يمكن تحديد تاريخ انتهاء للعرض') }}</li>
                                        <li>{{ __('العرض يبقى نشطاً حتى يتم بيعه أو إلغاؤه') }}</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h5><span class="glyphicon glyphicon-warning-sign"></span> {{ __('تنبيهات مهمة') }}</h5>
                                    <ul>
                                        <li>{{ __('تأكد من صحة عدد الأسهم المتاحة') }}</li>
                                        <li>{{ __('السعر يجب أن يكون منطقياً') }}</li>
                                        <li>{{ __('يمكن تعديل العرض لاحقاً') }}</li>
                                        <li>{{ __('العرض سيكون مرئياً للمشترين') }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('user_id').addEventListener('change', function() {
    const userId = this.value;
    const infoDiv = document.getElementById('contributor-info');

    if (!userId || userId === '') {
        infoDiv.innerHTML = '';
        return;
    }

    fetch(`/contributors/share/${userId}`)
        .then(response => response.json())
        .then(data => {
            infoDiv.innerHTML = `
                <div style="display: flex;justify-content: space-between;align-items: center;background: #fff;border-radius: 12px;padding: 0 16px;border: 1px solid #f1f5f9;">
                    <span style="color: #aa863f; font-weight: 700; font-size: 17px;">${data.available_shares ?? 0}</span>
                        <span style="color: #64748b; font-weight: 500;">الأسهم المتاحة للبيع</span>
                </div>

                <div style="display: flex;justify-content: space-between;align-items: center;background: #fff;border-radius: 12px;padding: 0 16px;border: 1px solid #f1f5f9;">
                    <span style="color: #1e293b; font-weight: 700; font-size: 17px;">${data.total_shares ?? 0}</span>
                    <span style="color: #64748b; font-weight: 500;">عدد الأسهم</span>
                </div>`;
                let input_count = document.getElementById('input_count').value = data.available_shares;
        })
        .catch(error => {
            console.error(error);
            infoDiv.innerHTML = 'حدث خطأ أثناء جلب البيانات.';
        });
});

$(document).ready(function() {
    // Set minimum date to tomorrow
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    $('#end_date').attr('min', tomorrow.toISOString().split('T')[0]);

    // Real-time calculation
    function calculateTotal() {
        const count = parseFloat($('#count').val()) || 0;
        const price = parseFloat($('#amount_per_share').val()) || 0;
        const total = count * price;
        
        $('#display-count').text(count.toLocaleString());
        $('#display-price').text(price.toFixed(2));
        $('#display-total').text(total.toFixed(2));
    }

    // Bind events
    $('#count, #amount_per_share').on('input', calculateTotal);
    
    // Initial calculation
    calculateTotal();

    // Form validation
    $('form').on('submit', function(e) {
        const count = parseFloat($('#count').val());
        const price = parseFloat($('#amount_per_share').val());
        const userId = $('#user_id').val();
        
        if (!userId) {
            alert('{{ __("يرجى اختيار المساهم") }}');
            e.preventDefault();
            return false;
        }
        
        if (!count || count <= 0) {
            alert('{{ __("يرجى إدخال عدد صحيح من الأسهم") }}');
            e.preventDefault();
            return false;
        }
        
        if (!price || price <= 0) {
            alert('{{ __("يرجى إدخال سعر صحيح لكل سهم") }}');
            e.preventDefault();
            return false;
        }
        
        // Confirm before submitting
        if (!confirm('{{ __("هل أنت متأكد من إنشاء عرض البيع؟") }}\n\n{{ __("عدد الأسهم") }}: ' + count + '\n{{ __("السعر لكل سهم") }}: ' + price.toFixed(2) + ' {{ __("ريال") }}\n{{ __("المبلغ الإجمالي") }}: ' + (count * price).toFixed(2) + ' {{ __("ريال") }}')) {
            e.preventDefault();
            return false;
        }
    });

    // Contributor selection handler
    $('#user_id').change(function() {
        const selectedOption = $(this).find('option:selected');
        const shareCount = selectedOption.text().match(/\(([0-9,]+)\s*سهم\)/);
        
        if (shareCount) {
            const availableShares = parseInt(shareCount[1].replace(/,/g, ''));
            $('#count').attr('max', availableShares);
            
            if (availableShares > 0) {
                $('#count').attr('placeholder', '{{ __("الحد الأقصى") }}: ' + availableShares.toLocaleString() + ' {{ __("سهم") }}');
            }
        }
    });
});
</script>
@endpush

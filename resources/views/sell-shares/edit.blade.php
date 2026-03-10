@extends('layouts.app')

@section('title', __('تعديل عرض البيع'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        {{ __('تعديل عرض البيع') }} #{{ $sellShare->id }}
                        <div class="pull-left">
                            <a href="{{ route('sell-shares.show', $sellShare->id) }}" class="btn btn-info btn-sm">
                                <span class="glyphicon glyphicon-eye-open"></span> {{ __('عرض') }}
                            </a>
                            <a href="{{ route('sell-shares.index') }}" class="btn btn-default btn-sm">
                                <span class="glyphicon glyphicon-arrow-right"></span> {{ __('رجوع') }}
                            </a>
                        </div>
                    </h3>
                </div>
                <div class="panel-body">
                    <form action="{{ route('sell-shares.update', $sellShare->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>{{ __('المساهم') }}</label>
                            <div class="alert alert-info">
                                <strong>{{ $sellShare->seller->name ?? $sellShare->seller->user->name ?? __('غير معروف') }}</strong>
                                <small class="text-muted">{{ __('لا يمكن تغيير المساهم بعد إنشاء العرض') }}</small>
                            </div>
                        </div>

                        <div class="form-group @error('count') has-error @enderror">
                            <div style="display: flex;justify-content: space-between;">
                                <label for="count">{{ __('عدد الأسهم المراد بيعها') }} <span class="text-danger">*</span></label>
                                <span id="contributor-info" style="display: flex;"></span>
                            </div>
                            <input type="number" name="count" id="count" class="form-control" 
                                   value="{{ old('count', $sellShare->count) }}" min="1" step="0.01" required
                                   placeholder="{{ __('أدخل عدد الأسهم') }}">
                                   @error('count')
                                   <span class="help-block">{{ $message }}</span>
                                   @enderror
                                </div>
                                <input type="hidden" name="user_id" id="user_id"value="{{ $sellShare->seller->id }}">
                                <input type="hidden" name="input_count" id="input_count">
                        <div class="form-group @error('amount_per_share') has-error @enderror">
                            <label for="amount_per_share">{{ __('السعر لكل سهم') }} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="amount_per_share" id="amount_per_share" class="form-control" 
                                       value="{{ old('amount_per_share', $sellShare->amount_per_share) }}" min="0.01" step="0.01" required
                                       placeholder="{{ __('أدخل السعر لكل سهم') }}">
                                <span class="input-group-addon">{{ __('ريال') }}</span>
                            </div>
                            @error('amount_per_share')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group @error('end_date') has-error @enderror">
                            <label for="end_date">{{ __('تاريخ انتهاء العرض') }}</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" 
                                   value="{{ old('end_date', $sellShare->end_date ? $sellShare->end_date->format('Y-m-d') : '') }}" 
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                   placeholder="{{ __('اختياري - تاريخ انتهاء صحة العرض') }}">
                            <small class="text-muted">{{ __('إذا لم تحدد تاريخ انتهاء، سيبقى العرض نشطاً حتى يتم إلغاؤه') }}</small>
                            @error('end_date')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group @error('notes') has-error @enderror">
                            <label for="notes">{{ __('ملاحظات') }}</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3" 
                                      placeholder="{{ __('أدخل أي ملاحظات إضافية حول عرض البيع (اختياري)') }}">{{ old('notes', $sellShare->notes) }}</textarea>
                            <small class="text-muted">{{ __('يمكنك إضافة ملاحظات إضافية حول عرض البيع') }}</small>
                            @error('notes')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group @error('line_notes') has-error @enderror">
                            <label for="line_notes">{{ __('* اسباب التعديل') }}</label>
                            <textarea name="line_notes" id="line_notes" class="form-control" rows="3" placeholder="{{ __('اكتب سبب التعديل الذي قمت به') }}">{{ old('line_notes') }}</textarea>
                            @error('line_notes')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Total Amount Display -->
                        <div class="form-group">
                            <div class="alert alert-info">
                                <h4>{{ __('ملخص العرض') }}</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>{{ __('عدد الأسهم') }}:</strong> <span id="display-count">{{ number_format($sellShare->count, 0) }}</span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>{{ __('السعر لكل سهم') }}:</strong> <span id="display-price">{{ number_format($sellShare->amount_per_share, 2) }}</span> {{ __('ريال') }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3><strong>{{ __('المبلغ الإجمالي') }}: <span id="display-total">{{ number_format($sellShare->total_amount, 2) }}</span> {{ __('ريال') }}</strong></h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Current Status Information -->
                        <div class="form-group">
                            <label>{{ __('حالة العرض الحالية') }}</label>
                            <div class="alert alert-warning">
                                @if($sellShare->ad_status == \App\Models\SellShares::AD_STATUS_INITIAL)
                                    <span class="label label-default">{{ __('مبدئي') }}</span>
                                @elseif($sellShare->ad_status == \App\Models\SellShares::AD_STATUS_ACTIVE)
                                    <span class="label label-success">{{ __('نشط') }}</span>
                                @elseif($sellShare->ad_status == \App\Models\SellShares::AD_STATUS_COMPLETED)
                                    <span class="label label-primary">{{ __('مكتمل') }}</span>
                                @elseif($sellShare->ad_status == \App\Models\SellShares::AD_STATUS_CANCELLED)
                                    <span class="label label-danger">{{ __('ملغي') }}</span>
                                @else
                                    <span class="label label-default">{{ __('غير محدد') }}</span>
                                @endif
                                <small class="text-muted">{{ __('يمكن تعديل العرض حتى يتم إلغاؤه أو إكماله') }}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <span class="glyphicon glyphicon-floppy-disk"></span> {{ __('حفظ التغييرات') }}
                            </button>
                            <a href="{{ route('sell-shares.show', $sellShare->id) }}" class="btn btn-info">
                                <span class="glyphicon glyphicon-eye-open"></span> {{ __('عرض') }}
                            </a>
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
                            <h4 class="panel-title">{{ __('معلومات العرض') }}</h4>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>{{ __('تاريخ الإنشاء') }}:</strong> {{ $sellShare->created_at->format('Y-m-d H:i') }}<br>
                                    <strong>{{ __('آخر تحديث') }}:</strong> {{ $sellShare->updated_at->format('Y-m-d H:i') }}<br>
                                    <strong>{{ __('تاريخ الإدراج') }}:</strong> {{ $sellShare->insert_date->format('Y-m-d H:i') }}
                                </div>
                                <div class="col-md-6">
                                    <strong>{{ __('المدة منذ الإنشاء') }}:</strong> {{ $sellShare->created_at->diffForHumans() }}<br>
                                    <strong>{{ __('المدة منذ آخر تحديث') }}:</strong> {{ $sellShare->updated_at->diffForHumans() }}<br>
                                    @if($sellShare->end_date)
                                        <strong>{{ __('المدة حتى الانتهاء') }}:</strong> {{ $sellShare->end_date->diffForHumans() }}
                                    @endif
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
    const userId = document.getElementById('user_id').value;
    const infoDiv = document.getElementById('contributor-info');

    if (!userId || userId === '') {
        infoDiv.innerHTML = '';
    }

    fetch(`/contributors/share/${userId}`)
        .then(response => response.json())
        .then(data => {
            infoDiv.innerHTML = `
                <div style="display: flex; justify-content: space-between; align-items: center; background: #fff; border-radius: 12px; padding: 0 16px; border: 1px solid #f1f5f9;">
                    <span style="color: #aa863f; font-weight: 700; font-size: 17px;">${data.available_shares ?? 0}</span>
                    <span style="color: #64748b; font-weight: 500;">الأسهم المتاحة للبيع</span>
                </div>
            
                <div style="display: flex; justify-content: space-between; align-items: center; background: #fff; border-radius: 12px; padding: 0 16px; border: 1px solid #f1f5f9; margin-bottom: 6px;">
                    <span style="color: #1e293b; font-weight: 700; font-size: 17px;">${data.total_shares ?? 0}</span>
                    <span style="color: #64748b; font-weight: 500;">عدد الأسهم</span>
                </div>
                `;
            document.getElementById('input_count').value = data.available_shares ?? 0;
        })
        .catch(error => {
            console.error(error);
            infoDiv.innerHTML = 'حدث خطأ أثناء جلب البيانات.';
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
        
        // Check if offer can be edited
        @if($sellShare->ad_status == \App\Models\SellShares::AD_STATUS_COMPLETED)
            alert('{{ __("لا يمكن تعديل عرض مكتمل") }}');
            e.preventDefault();
            return false;
        @elseif($sellShare->ad_status == \App\Models\SellShares::AD_STATUS_CANCELLED)
            alert('{{ __("لا يمكن تعديل عرض ملغي") }}');
            e.preventDefault();
            return false;
        @endif
        
        // Confirm before submitting
        if (!confirm('{{ __("هل أنت متأكد من حفظ التغييرات؟") }}\n\n{{ __("عدد الأسهم") }}: ' + count + '\n{{ __("السعر لكل سهم") }}: ' + price.toFixed(2) + ' {{ __("ريال") }}\n{{ __("المبلغ الإجمالي") }}: ' + (count * price).toFixed(2) + ' {{ __("ريال") }}')) {
            e.preventDefault();
            return false;
        }
    });

    // Disable form if offer cannot be edited
    @if($sellShare->ad_status == \App\Models\SellShares::AD_STATUS_COMPLETED || $sellShare->ad_status == \App\Models\SellShares::AD_STATUS_CANCELLED)
        $('form input, form textarea').prop('disabled', true);
        $('form button[type="submit"]').prop('disabled', true);
    @endif
});
</script>
@endpush

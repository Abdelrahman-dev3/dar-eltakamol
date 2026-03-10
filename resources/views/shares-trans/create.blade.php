@extends('layouts.app')

@section('title', __('إضافة معاملة أسهم جديدة'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        {{ __('إضافة معاملة أسهم جديدة') }}
                        <div class="pull-left">
                            <a href="{{ route('shares-trans.index') }}" class="btn btn-default btn-sm">
                                <span class="glyphicon glyphicon-arrow-right"></span> {{ __('رجوع') }}
                            </a>
                        </div>
                    </h3>
                </div>
                <div class="panel-body">
                    <form action="{{ route('shares-trans.store') }}" method="POST">
                        @csrf

                        <div class="form-group @error('date') has-error @enderror">
                            <label for="date">{{ __('تاريخ المعاملة') }} <span class="text-danger">*</span></label>
                            <input type="date" name="date" id="date" class="form-control" 
                                   value="{{ old('date', date('Y-m-d')) }}" required>
                            @error('date')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group @error('trans_type') has-error @enderror">
                            <label for="trans_type">{{ __('نوع المعاملة') }} <span class="text-danger">*</span></label>
                            <select name="trans_type" id="trans_type" class="form-control" required>
                                <option value="">{{ __('اختر نوع المعاملة') }}</option>
                                <option value="1" {{ old('trans_type') == '1' ? 'selected' : '' }}>
                                    {{ __('شراء') }}
                                </option>
                                <option value="2" {{ old('trans_type') == '2' ? 'selected' : '' }}>
                                    {{ __('بيع') }}
                                </option>
                                <option value="3" {{ old('trans_type') == '3' ? 'selected' : '' }}>
                                    {{ __('تحويل') }}
                                </option>
                                <option value="4" {{ old('trans_type') == '4' ? 'selected' : '' }}>
                                    {{ __('أرباح') }}
                                </option>
                            </select>
                            @error('trans_type')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group @error('notes') has-error @enderror">
                            <label for="notes">{{ __('ملاحظات') }}</label>
                            <textarea name="notes" id="notes" class="form-control" rows="4" 
                                      placeholder="{{ __('أدخل ملاحظات حول المعاملة (اختياري)') }}">{{ old('notes') }}</textarea>
                            <small class="text-muted">{{ __('يمكنك إضافة ملاحظات إضافية حول هذه المعاملة') }}</small>
                            @error('notes')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <span class="glyphicon glyphicon-floppy-disk"></span> {{ __('حفظ المعاملة') }}
                            </button>
                            <a href="{{ route('shares-trans.index') }}" class="btn btn-default">
                                <span class="glyphicon glyphicon-arrow-right"></span> {{ __('إلغاء') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Transaction Types Information -->
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h4 class="panel-title">{{ __('أنواع المعاملات المتاحة') }}</h4>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5><span class="label label-success">{{ __('شراء') }}</span></h5>
                                    <p>{{ __('معاملات شراء أسهم جديدة من المساهمين') }}</p>
                                    
                                    <h5><span class="label label-danger">{{ __('بيع') }}</span></h5>
                                    <p>{{ __('معاملات بيع أسهم من قبل المساهمين') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h5><span class="label label-info">{{ __('تحويل') }}</span></h5>
                                    <p>{{ __('تحويل أسهم بين المساهمين') }}</p>
                                    
                                    <h5><span class="label label-warning">{{ __('أرباح') }}</span></h5>
                                    <p>{{ __('توزيعات أرباح على المساهمين') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Next Steps Information -->
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <h4 class="panel-title">{{ __('الخطوات التالية') }}</h4>
                        </div>
                        <div class="panel-body">
                            <ol>
                                <li>{{ __('بعد إنشاء المعاملة، يمكنك إضافة تفاصيل المعاملة (Share Transaction Lines)') }}</li>
                                <li>{{ __('تحديد المساهمين والمبالغ المرتبطة بكل معاملة') }}</li>
                                <li>{{ __('مراجعة المعاملة قبل اعتمادها') }}</li>
                                <li>{{ __('اعتماد المعاملة بعد التأكد من صحة البيانات') }}</li>
                            </ol>
                            <div class="alert alert-info">
                                <strong>{{ __('ملاحظة') }}:</strong> {{ __('سيتم إنشاء المعاملة بحالة "غير معتمد" ويمكنك تعديلها أو اعتمادها لاحقاً') }}
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
$(document).ready(function() {
    // Set default date to today
    if (!$('#date').val()) {
        $('#date').val('{{ date('Y-m-d') }}');
    }

    // Form validation
    $('form').on('submit', function(e) {
        const transType = $('#trans_type').val();
        const date = $('#date').val();
        
        if (!transType) {
            alert('{{ __("يرجى اختيار نوع المعاملة") }}');
            e.preventDefault();
            return false;
        }
        
        if (!date) {
            alert('{{ __("يرجى تحديد تاريخ المعاملة") }}');
            e.preventDefault();
            return false;
        }
        
        // Confirm before submitting
        if (!confirm('{{ __("هل أنت متأكد من إنشاء هذه المعاملة؟") }}')) {
            e.preventDefault();
            return false;
        }
    });

    // Transaction type change handler
    $('#trans_type').change(function() {
        const selectedType = $(this).val();
        const notesField = $('#notes');
        
        // Auto-suggest notes based on transaction type
        if (selectedType && !notesField.val()) {
            const suggestions = {
                '1': '{{ __("معاملة شراء أسهم جديدة") }}',
                '2': '{{ __("معاملة بيع أسهم") }}',
                '3': '{{ __("معاملة تحويل أسهم") }}',
                '4': '{{ __("معاملة توزيع أرباح") }}'
            };
            
            if (suggestions[selectedType]) {
                notesField.attr('placeholder', suggestions[selectedType]);
            }
        }
    });
});
</script>
@endpush

@extends('layouts.app')

@section('title', __('تعديل معاملة الأسهم'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        {{ __('تعديل معاملة الأسهم') }} #{{ $shares_tran->id }}
                        <div class="pull-left">
                            <a href="{{ route('shares-trans.show', $shares_tran->id) }}" class="btn btn-info btn-sm">
                                <span class="glyphicon glyphicon-eye-open"></span> {{ __('عرض') }}
                            </a>
                            <a href="{{ route('shares-trans.index') }}" class="btn btn-default btn-sm">
                                <span class="glyphicon glyphicon-arrow-right"></span> {{ __('رجوع') }}
                            </a>
                        </div>
                    </h3>
                </div>
                <div class="panel-body">
                    <form action="{{ route('shares-trans.update', $shares_tran->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group @error('date') has-error @enderror">
                            <label for="date">{{ __('تاريخ المعاملة') }} <span class="text-danger">*</span></label>
                            <input type="date" name="date" id="date" class="form-control" 
                                   value="{{ old('date', $shares_tran->date->format('Y-m-d')) }}" required>
                            @error('date')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group @error('trans_type') has-error @enderror">
                            <label for="trans_type">{{ __('نوع المعاملة') }} <span class="text-danger">*</span></label>
                            <select name="trans_type" id="trans_type" class="form-control" required>
                                <option value="">{{ __('اختر نوع المعاملة') }}</option>
                                <option value="1" {{ old('trans_type', $shares_tran->trans_type) == '1' ? 'selected' : '' }}>
                                    {{ __('شراء') }}
                                </option>
                                <option value="2" {{ old('trans_type', $shares_tran->trans_type) == '2' ? 'selected' : '' }}>
                                    {{ __('بيع') }}
                                </option>
                                <option value="3" {{ old('trans_type', $shares_tran->trans_type) == '3' ? 'selected' : '' }}>
                                    {{ __('تحويل') }}
                                </option>
                                <option value="4" {{ old('trans_type', $shares_tran->trans_type) == '4' ? 'selected' : '' }}>
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
                                      placeholder="{{ __('أدخل ملاحظات حول المعاملة (اختياري)') }}">{{ old('notes', $shares_tran->notes) }}</textarea>
                            <small class="text-muted">{{ __('يمكنك إضافة ملاحظات إضافية حول هذه المعاملة') }}</small>
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


                        <!-- Transaction Status Information -->
                        <div class="form-group">
                            <label>{{ __('حالة المعاملة') }}</label>
                            <div class="alert alert-info">
                                @if($shares_tran->posted)
                                    <span class="label label-success">{{ __('معتمد') }}</span>
                                    <small class="text-muted">{{ __('هذه المعاملة معتمدة ولا يمكن تعديلها') }}</small>
                                @else
                                    <span class="label label-warning">{{ __('غير معتمد') }}</span>
                                    <small class="text-muted">{{ __('يمكن تعديل هذه المعاملة حتى يتم اعتمادها') }}</small>
                                @endif
                            </div>
                        </div>

                        <!-- Transaction Details Count -->
                        <div class="form-group">
                            <label>{{ __('عدد التفاصيل') }}</label>
                            <div class="alert alert-info">
                                <strong>{{ $shares_tran->shareTransLines->count() }}</strong> {{ __('تفصيل') }}
                                @if($shares_tran->shareTransLines->count() > 0)
                                    <a href="{{ route('share-trans-lines.index', ['trans_id' => $shares_tran->id]) }}" class="btn btn-xs btn-primary">
                                        <span class="glyphicon glyphicon-list"></span> {{ __('عرض التفاصيل') }}
                                    </a>
                                @else
                                    <a href="{{ route('share-trans-lines.create', ['trans_id' => $shares_tran->id]) }}" class="btn btn-xs btn-success">
                                        <span class="glyphicon glyphicon-plus"></span> {{ __('إضافة تفاصيل') }}
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            @if(!$shares_tran->posted)
                                <button type="submit" class="btn btn-primary">
                                    <span class="glyphicon glyphicon-floppy-disk"></span> {{ __('حفظ التغييرات') }}
                                </button>
                            @else
                                <button type="button" class="btn btn-primary" disabled>
                                    <span class="glyphicon glyphicon-lock"></span> {{ __('المعاملة معتمدة - لا يمكن التعديل') }}
                                </button>
                            @endif
                            <a href="{{ route('shares-trans.show', $shares_tran->id) }}" class="btn btn-info">
                                <span class="glyphicon glyphicon-eye-open"></span> {{ __('عرض') }}
                            </a>
                            <a href="{{ route('shares-trans.index') }}" class="btn btn-default">
                                <span class="glyphicon glyphicon-arrow-right"></span> {{ __('إلغاء') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Transaction Information Panel -->
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h4 class="panel-title">{{ __('معلومات المعاملة') }}</h4>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>{{ __('رقم المعاملة') }}:</strong> {{ $shares_tran->id }}<br>
                                    <strong>{{ __('تاريخ الإنشاء') }}:</strong> {{ $shares_tran->created_at->format('Y-m-d H:i') }}<br>
                                    <strong>{{ __('آخر تحديث') }}:</strong> {{ $shares_tran->updated_at->format('Y-m-d H:i') }}
                                </div>
                                <div class="col-md-6">
                                    <strong>{{ __('نوع المعاملة') }}:</strong> 
                                    @if($shares_tran->trans_type == 1)
                                        <span class="label label-success">{{ __('شراء') }}</span>
                                    @elseif($shares_tran->trans_type == 2)
                                        <span class="label label-danger">{{ __('بيع') }}</span>
                                    @elseif($shares_tran->trans_type == 3)
                                        <span class="label label-info">{{ __('تحويل') }}</span>
                                    @elseif($shares_tran->trans_type == 4)
                                        <span class="label label-warning">{{ __('أرباح') }}</span>
                                    @else
                                        <span class="label label-default">{{ __('غير محدد') }}</span>
                                    @endif
                                    <br>
                                    <strong>{{ __('الحالة') }}:</strong> 
                                    @if($shares_tran->posted)
                                        <span class="label label-success">{{ __('معتمد') }}</span>
                                    @else
                                        <span class="label label-warning">{{ __('غير معتمد') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction Lines Summary -->
            @if($shares_tran->shareTransLines->count() > 0)
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <h4 class="panel-title">{{ __('ملخص تفاصيل المعاملة') }}</h4>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-condensed">
                                    <thead>
                                        <tr>
                                            <th>{{ __('المساهم') }}</th>
                                            <th>{{ __('عدد الأسهم') }}</th>
                                            <th>{{ __('المبلغ') }}</th>
                                            <th>{{ __('الحالة') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($shares_tran->shareTransLines as $line)
                                        <tr>
                                            <td>{{ $line->contributor->name ?? __('غير معروف') }}</td>
                                            <td>{{ number_format($line->count_debit - $line->count_credit, 0) }}</td>
                                            <td>{{ number_format($line->amount_per_share * ($line->count_debit - $line->count_credit), 2) }} {{ __('ريال') }}</td>
                                            <td>
                                                @if($line->posted)
                                                    <span class="label label-success">{{ __('معتمد') }}</span>
                                                @else
                                                    <span class="label label-warning">{{ __('غير معتمد') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center">
                                <a href="{{ route('share-trans-lines.index', ['trans_id' => $shares_tran->id]) }}" class="btn btn-primary">
                                    <span class="glyphicon glyphicon-list"></span> {{ __('عرض جميع التفاصيل') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
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
        
        // Check if transaction is posted
        @if($shares_tran->posted)
            alert('{{ __("لا يمكن تعديل معاملة معتمدة") }}');
            e.preventDefault();
            return false;
        @endif
        
        // Confirm before submitting
        if (!confirm('{{ __("هل أنت متأكد من حفظ التغييرات؟") }}')) {
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

    // Disable form if transaction is posted
    @if($shares_tran->posted)
        $('form input, form select, form textarea').prop('disabled', true);
        $('form button[type="submit"]').prop('disabled', true);
    @endif
});
</script>
@endpush


@extends('layouts.app')

@section('title', __('تعديل خيار الاستطلاع') . ' - ' . $pollOption->option_text)

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        {{ __('تعديل خيار الاستطلاع') }}
                        <div class="pull-left">
                            <a href="{{ route('poll-options.show', $pollOption) }}" class="btn btn-info btn-sm">
                                <span class="glyphicon glyphicon-eye-open"></span> {{ __('عرض') }}
                            </a>
                            <a href="{{ route('poll-options.index') }}" class="btn btn-default btn-sm">
                                <span class="glyphicon glyphicon-arrow-right"></span> {{ __('العودة') }}
                            </a>
                        </div>
                    </h3>
                </div>
                <div class="panel-body">
                    <form action="{{ route('poll-options.update', $pollOption) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="poll_id" class="control-label">{{ __('الاستطلاع') }} <span class="text-danger">*</span></label>
                            <select name="poll_id" id="poll_id" class="form-control" required>
                                <option value="">{{ __('اختر الاستطلاع') }}</option>
                                @foreach($polls as $poll)
                                    <option value="{{ $poll->id }}" 
                                            {{ (old('poll_id', $pollOption->poll_id) == $poll->id) ? 'selected' : '' }}>
                                        {{ $poll->question }} 
                                        @if($poll->start_date)
                                            ({{ $poll->start_date->format('Y-m-d') }} - {{ $poll->end_date->format('Y-m-d') }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('poll_id')
                                <span class="help-block text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="option_text" class="control-label">{{ __('نص الخيار') }} <span class="text-danger">*</span></label>
                            <input type="text" name="option_text" id="option_text" class="form-control" 
                                   value="{{ old('option_text', $pollOption->option_text) }}" 
                                   placeholder="{{ __('أدخل نص الخيار') }}" 
                                   required maxlength="255">
                            @error('option_text')
                                <span class="help-block text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="votes" class="control-label">{{ __('عدد الأصوات') }}</label>
                            <input type="number" name="votes" id="votes" class="form-control" 
                                   value="{{ old('votes', $pollOption->votes) }}" 
                                   min="0" 
                                   placeholder="{{ __('عدد الأصوات') }}">
                            @error('votes')
                                <span class="help-block text-danger">{{ $message }}</span>
                            @enderror
                            <small class="help-block">{{ __('تعديل عدد الأصوات قد يؤثر على نتائج الاستطلاع') }}</small>
                        </div>

                        <div class="alert alert-info">
                            <h5>{{ __('معلومات الخيار') }}</h5>
                            <p><strong>{{ __('تاريخ الإنشاء:') }}</strong> {{ $pollOption->created_at->format('Y-m-d H:i:s') }}</p>
                            <p><strong>{{ __('آخر تحديث:') }}</strong> {{ $pollOption->updated_at->format('Y-m-d H:i:s') }}</p>
                            <p><strong>{{ __('الاستطلاع الحالي:') }}</strong> 
                                <a href="{{ route('polls.show', $pollOption->poll_id) }}" target="_blank">
                                    {{ $pollOption->poll->question ?? __('غير محدد') }}
                                </a>
                            </p>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-success btn-block">
                                        <span class="glyphicon glyphicon-save"></span> {{ __('حفظ التغييرات') }}
                                    </button>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ route('poll-options.show', $pollOption) }}" class="btn btn-info btn-block">
                                        <span class="glyphicon glyphicon-eye-open"></span> {{ __('عرض') }}
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ route('poll-options.index') }}" class="btn btn-default btn-block">
                                        <span class="glyphicon glyphicon-arrow-right"></span> {{ __('إلغاء') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-focus on the option text field
    document.getElementById('option_text').focus();
    
    // Add some validation feedback
    const form = document.querySelector('form');
    const pollSelect = document.getElementById('poll_id');
    const optionText = document.getElementById('option_text');
    
    form.addEventListener('submit', function(e) {
        if (!pollSelect.value) {
            e.preventDefault();
            alert('{{ __("يرجى اختيار الاستطلاع") }}');
            pollSelect.focus();
            return false;
        }
        
        if (!optionText.value.trim()) {
            e.preventDefault();
            alert('{{ __("يرجى إدخال نص الخيار") }}');
            optionText.focus();
            return false;
        }
        
        // Confirm if votes are being changed significantly
        const currentVotes = {{ $pollOption->votes }};
        const newVotes = parseInt(document.getElementById('votes').value) || 0;
        
        if (Math.abs(newVotes - currentVotes) > 10) {
            if (!confirm('{{ __("هل أنت متأكد من تغيير عدد الأصوات بشكل كبير؟ قد يؤثر هذا على نتائج الاستطلاع.") }}')) {
                e.preventDefault();
                return false;
            }
        }
    });
});
</script>
@endsection

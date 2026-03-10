@extends('layouts.app')

@section('title', __('إضافة خيار استطلاع جديد'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        {{ __('إضافة خيار استطلاع جديد') }}
                        <div class="pull-left">
                            <a href="{{ route('poll-options.index') }}" class="btn btn-default btn-sm">
                                <span class="glyphicon glyphicon-arrow-right"></span> {{ __('العودة') }}
                            </a>
                        </div>
                    </h3>
                </div>
                <div class="panel-body">
                    <form action="{{ route('poll-options.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="poll_id" class="control-label">{{ __('الاستطلاع') }} <span class="text-danger">*</span></label>
                            <select name="poll_id" id="poll_id" class="form-control" required>
                                <option value="">{{ __('اختر الاستطلاع') }}</option>
                                @foreach($polls as $poll)
                                    <option value="{{ $poll->id }}" {{ old('poll_id') == $poll->id ? 'selected' : '' }}>
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
                                   value="{{ old('option_text') }}" 
                                   placeholder="{{ __('أدخل نص الخيار') }}" 
                                   required maxlength="255">
                            @error('option_text')
                                <span class="help-block text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="votes" class="control-label">{{ __('عدد الأصوات الأولي') }}</label>
                            <input type="number" name="votes" id="votes" class="form-control" 
                                   value="{{ old('votes', 0) }}" 
                                   min="0" 
                                   placeholder="{{ __('عدد الأصوات الأولي (افتراضي: 0)') }}">
                            @error('votes')
                                <span class="help-block text-danger">{{ $message }}</span>
                            @enderror
                            <small class="help-block">{{ __('يمكن ترك هذا الحقل فارغاً ليبدأ من صفر') }}</small>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-success btn-block">
                                        <span class="glyphicon glyphicon-plus"></span> {{ __('إضافة الخيار') }}
                                    </button>
                                </div>
                                <div class="col-md-6">
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
    });
});
</script>
@endsection

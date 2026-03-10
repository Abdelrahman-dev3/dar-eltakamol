@extends('layouts.app')

@section('title', __('تعديل إجابة الاستطلاع') . ' - ' . ($pollAnswer->user->name ?? __('غير معروف')))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        {{ __('تعديل إجابة الاستطلاع') }}
                        <div class="pull-left">
                            <a href="{{ route('poll-answers.show', $pollAnswer) }}" class="btn btn-info btn-sm">
                                <span class="glyphicon glyphicon-eye-open"></span> {{ __('عرض') }}
                            </a>
                            <a href="{{ route('poll-answers.index') }}" class="btn btn-default btn-sm">
                                <span class="glyphicon glyphicon-arrow-right"></span> {{ __('العودة') }}
                            </a>
                        </div>
                    </h3>
                </div>
                <div class="panel-body">
                    <form action="{{ route('poll-answers.update', $pollAnswer) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="poll_id" class="control-label">{{ __('الاستطلاع') }} <span class="text-danger">*</span></label>
                            <select name="poll_id" id="poll_id" class="form-control" required>
                                <option value="">{{ __('اختر الاستطلاع') }}</option>
                                @foreach($polls as $poll)
                                    <option value="{{ $poll->id }}" 
                                            {{ (old('poll_id', $pollAnswer->poll_id) == $poll->id) ? 'selected' : '' }}>
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
                            <label for="poll_option_id" class="control-label">{{ __('الخيار المختار') }} <span class="text-danger">*</span></label>
                            <select name="poll_option_id" id="poll_option_id" class="form-control" required>
                                <option value="">{{ __('اختر الخيار') }}</option>
                                @foreach($pollOptions as $option)
                                    <option value="{{ $option->id }}" 
                                            data-poll-id="{{ $option->poll_id }}"
                                            {{ (old('poll_option_id', $pollAnswer->poll_option_id) == $option->id) ? 'selected' : '' }}>
                                        {{ $option->option_text }} 
                                        @if($option->poll)
                                            ({{ $option->poll->question }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('poll_option_id')
                                <span class="help-block text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="user_id" class="control-label">{{ __('المستخدم') }} <span class="text-danger">*</span></label>
                            <select name="user_id" id="user_id" class="form-control" required>
                                <option value="">{{ __('اختر المستخدم') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" 
                                            {{ (old('user_id', $pollAnswer->user_id) == $user->id) ? 'selected' : '' }}>
                                        {{ $user->name }} 
                                        @if($user->email)
                                            ({{ $user->email }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <span class="help-block text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="answer_date" class="control-label">{{ __('تاريخ الإجابة') }}</label>
                            <input type="datetime-local" name="answer_date" id="answer_date" class="form-control" 
                                   value="{{ old('answer_date', $pollAnswer->answer_date->format('Y-m-d\TH:i')) }}">
                            @error('answer_date')
                                <span class="help-block text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <h5>{{ __('معلومات الإجابة الحالية') }}</h5>
                            <p><strong>{{ __('المستخدم:') }}</strong> {{ $pollAnswer->user->name ?? __('غير معروف') }}</p>
                            <p><strong>{{ __('الاستطلاع:') }}</strong> {{ $pollAnswer->poll->question ?? __('غير محدد') }}</p>
                            <p><strong>{{ __('الخيار المختار:') }}</strong> {{ $pollAnswer->pollOption->option_text ?? __('غير محدد') }}</p>
                            <p><strong>{{ __('تاريخ الإجابة:') }}</strong> {{ $pollAnswer->answer_date->format('Y-m-d H:i:s') }}</p>
                            <p><strong>{{ __('تاريخ الإنشاء:') }}</strong> {{ $pollAnswer->created_at->format('Y-m-d H:i:s') }}</p>
                            <p><strong>{{ __('آخر تحديث:') }}</strong> {{ $pollAnswer->updated_at->format('Y-m-d H:i:s') }}</p>
                        </div>

                        <div class="alert alert-warning">
                            <h5>{{ __('تحذير') }}</h5>
                            <p>{{ __('تعديل إجابة الاستطلاع قد يؤثر على نتائج الاستطلاع وإحصائياته. تأكد من صحة البيانات قبل الحفظ.') }}</p>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-success btn-block">
                                        <span class="glyphicon glyphicon-save"></span> {{ __('حفظ التغييرات') }}
                                    </button>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ route('poll-answers.show', $pollAnswer) }}" class="btn btn-info btn-block">
                                        <span class="glyphicon glyphicon-eye-open"></span> {{ __('عرض') }}
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ route('poll-answers.index') }}" class="btn btn-default btn-block">
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
    const pollSelect = document.getElementById('poll_id');
    const optionSelect = document.getElementById('poll_option_id');
    const userSelect = document.getElementById('user_id');
    
    // Filter options based on selected poll
    function filterOptions() {
        const selectedPollId = pollSelect.value;
        const options = optionSelect.querySelectorAll('option');
        
        options.forEach(option => {
            if (option.value === '') {
                option.style.display = 'block';
                return;
            }
            
            const optionPollId = option.getAttribute('data-poll-id');
            if (selectedPollId && optionPollId !== selectedPollId) {
                option.style.display = 'none';
                if (option.selected) {
                    option.selected = false;
                }
            } else {
                option.style.display = 'block';
            }
        });
    }
    
    pollSelect.addEventListener('change', filterOptions);
    
    // Initial filter
    filterOptions();
    
    // Add validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        if (!pollSelect.value) {
            e.preventDefault();
            alert('{{ __("يرجى اختيار الاستطلاع") }}');
            pollSelect.focus();
            return false;
        }
        
        if (!optionSelect.value) {
            e.preventDefault();
            alert('{{ __("يرجى اختيار الخيار") }}');
            optionSelect.focus();
            return false;
        }
        
        if (!userSelect.value) {
            e.preventDefault();
            alert('{{ __("يرجى اختيار المستخدم") }}');
            userSelect.focus();
            return false;
        }
        
        // Check if option belongs to selected poll
        const selectedOption = optionSelect.options[optionSelect.selectedIndex];
        const optionPollId = selectedOption.getAttribute('data-poll-id');
        if (pollSelect.value !== optionPollId) {
            e.preventDefault();
            alert('{{ __("الخيار المختار لا ينتمي للاستطلاع المحدد") }}');
            optionSelect.focus();
            return false;
        }
        
        // Confirm changes
        if (!confirm('{{ __("هل أنت متأكد من حفظ التغييرات؟ قد يؤثر هذا على نتائج الاستطلاع.") }}')) {
            e.preventDefault();
            return false;
        }
    });
});
</script>
@endsection

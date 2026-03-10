@extends('layouts.app')

@section('title', __('إضافة إجابة استطلاع جديدة'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        {{ __('إضافة إجابة استطلاع جديدة') }}
                        <div class="pull-left">
                            <a href="{{ route('poll-answers.index') }}" class="btn btn-default btn-sm">
                                <span class="glyphicon glyphicon-arrow-right"></span> {{ __('العودة') }}
                            </a>
                        </div>
                    </h3>
                </div>
                <div class="panel-body">
                    <form action="{{ route('poll-answers.store') }}" method="POST">
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
                            <label for="poll_option_id" class="control-label">{{ __('الخيار المختار') }} <span class="text-danger">*</span></label>
                            <select name="poll_option_id" id="poll_option_id" class="form-control" required>
                                <option value="">{{ __('اختر الخيار') }}</option>
                                @foreach($pollOptions as $option)
                                    <option value="{{ $option->id }}" 
                                            data-poll-id="{{ $option->poll_id }}"
                                            {{ old('poll_option_id') == $option->id ? 'selected' : '' }}>
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
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
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
                                   value="{{ old('answer_date', now()->format('Y-m-d\TH:i')) }}">
                            @error('answer_date')
                                <span class="help-block text-danger">{{ $message }}</span>
                            @enderror
                            <small class="help-block">{{ __('إذا ترك فارغاً، سيتم استخدام التاريخ والوقت الحالي') }}</small>
                        </div>

                        <div class="alert alert-info">
                            <h5>{{ __('ملاحظات مهمة') }}</h5>
                            <ul>
                                <li>{{ __('تأكد من أن المستخدم لم يسبق له التصويت في نفس الاستطلاع') }}</li>
                                <li>{{ __('الخيار المختار يجب أن يكون من نفس الاستطلاع المحدد') }}</li>
                                <li>{{ __('يمكن تعديل تاريخ الإجابة إذا لزم الأمر') }}</li>
                            </ul>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-success btn-block">
                                        <span class="glyphicon glyphicon-plus"></span> {{ __('إضافة الإجابة') }}
                                    </button>
                                </div>
                                <div class="col-md-6">
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
    pollSelect.addEventListener('change', function() {
        const selectedPollId = this.value;
        const options = optionSelect.querySelectorAll('option');
        
        options.forEach(option => {
            if (option.value === '') {
                option.style.display = 'block';
                return;
            }
            
            const optionPollId = option.getAttribute('data-poll-id');
            if (selectedPollId && optionPollId !== selectedPollId) {
                option.style.display = 'none';
                option.selected = false;
            } else {
                option.style.display = 'block';
            }
        });
        
        // Reset option selection if no poll is selected
        if (!selectedPollId) {
            optionSelect.value = '';
        }
    });
    
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
    });
    
    // Auto-focus on poll select
    pollSelect.focus();
});
</script>
@endsection

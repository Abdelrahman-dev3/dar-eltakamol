@extends('layouts.app')

@section('title', __('إضافة استطلاع جديد'))

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">{{ __('إضافة استطلاع جديد') }}</h3>
            </div>
            <div class="panel-body">
                <form action="{{ route('polls.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="question">{{ __('السؤال') }} <span class="text-danger">*</span></label>
                        <textarea name="question" id="question" class="form-control @error('question') is-invalid @enderror" rows="3" required>{{ old('question') }}</textarea>
                        @error('question')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="start_date">{{ __('تاريخ البدء') }} <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}" required>
                                @error('start_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="end_date">{{ __('تاريخ الانتهاء') }} <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}" required>
                                @error('end_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                {{ __('تفعيل الاستطلاع') }}
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="zoom_meeting_id">{{ __('اجتماع الزوم') }}</label>
                        <select name="zoom_meeting_id" id="zoom_meeting_id" class="form-control @error('zoom_meeting_id') is-invalid @enderror">
                            <option value="">{{ __('-- اختر اجتماع الزوم (اختياري) --') }}</option>
                            @forelse($zoomMeetings as $zoomMeeting)
                                <option value="{{ $zoomMeeting->id }}" {{ old('zoom_meeting_id') == $zoomMeeting->id ? 'selected' : '' }}>
                                    {{ $zoomMeeting->title }}
                                    @if($zoomMeeting->meeting_date)
                                        ({{ $zoomMeeting->meeting_date->format('Y-m-d H:i') }})
                                    @endif
                                </option>
                            @empty
                                <option value="" disabled>{{ __('لا توجد اجتماعات زوم متاحة') }}</option>
                            @endforelse
                        </select>
                        @error('zoom_meeting_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="referenced_users">{{ __('المستخدمون المشاركون في الاستطلاع') }}</label>
                        <select name="referenced_users[]" id="referenced_users" class="form-control @error('referenced_users') is-invalid @enderror" multiple size="5">
                            @forelse($users as $user)
                                <option value="{{ $user->id }}" {{ in_array($user->id, old('referenced_users', [])) ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @empty
                                <option value="" disabled>{{ __('لا توجد مستخدمين متاحين') }}</option>
                            @endforelse
                        </select>
                        <small class="help-block">{{ __('يمكنك اختيار عدة مستخدمين. اضغط Ctrl (أو Cmd على Mac) للاختيار المتعدد. (اختياري)') }}</small>
                        @error('referenced_users')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="options-container">
                        <label>{{ __('خيارات الاستطلاع') }} <span class="text-danger">*</span></label>
                        <div class="alert alert-info">
                            {{ __('قم بإضافة خيارات الاستطلاع. يجب أن يكون هناك خياران على الأقل.') }}
                        </div>
                        
                        <div class="form-group option-group">
                            <label>{{ __('Chọn Option 1') }}</label>
                            <div class="input-group">
                                <input type="text" name="options[]" class="form-control @error('options') is-invalid @enderror" placeholder="{{ __('أدخل الخيار الأول') }}" required>
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-danger btn-remove-option" disabled>
                                        <span class="glyphicon glyphicon-minus"></span>
                                    </button>
                                </span>
                            </div>
                        </div>

                        <div class="form-group option-group">
                            <label>{{ __('Chọn Option 2') }}</label>
                            <div class="input-group">
                                <input type="text" name="options[]" class="form-control @error('options') is-invalid @enderror" placeholder="{{ __('أدخل الخيار الثاني') }}" required>
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-danger btn-remove-option">
                                        <span class="glyphicon glyphicon-minus"></span>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>

                    @error('options')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror

                    <div class="form-group">
                        <button type="button" class="btn btn-success" id="add-option">
                            <span class="glyphicon glyphicon-plus"></span> {{ __('إضافة خيار') }}
                        </button>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <span class="glyphicon glyphicon-save"></span> {{ __('حفظ الاستطلاع') }}
                        </button>
                        <a href="{{ route('polls.index') }}" class="btn btn-default">
                            <span class="glyphicon glyphicon-arrow-right"></span> {{ __('إلغاء') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let optionCount = 2;
    
    $('#add-option').click(function() {
        optionCount++;
        let newOption = `
            <div class="form-group option-group">
                <label>{{ __('Chọn Option') }} ${optionCount}</label>
                <div class="input-group">
                    <input type="text" name="options[]" class="form-control" placeholder="{{ __('أدخل الخيار') }}">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-danger btn-remove-option">
                            <span class="glyphicon glyphicon-minus"></span>
                        </button>
                    </span>
                </div>
            </div>
        `;
        $('#options-container').append(newOption);
        updateRemoveButtons();
    });

    $(document).on('click', '.btn-remove-option', function() {
        $(this).closest('.option-group').remove();
        optionCount--;
        updateRemoveButtons();
    });

    function updateRemoveButtons() {
        let optionGroups = $('.option-group');
        optionGroups.each(function(index) {
            let btn = $(this).find('.btn-remove-option');
            if (optionGroups.length <= 2) {
                btn.prop('disabled', true);
            } else {
                btn.prop('disabled', false);
            }
        });
    }

    // Set default dates
    let now = new Date();
    let nextWeek = new Date(now.getTime() + 7 * 24 * 60 * 60 * 1000);
    
    $('#start_date').val(formatDate(now));
    $('#end_date').val(formatDate(nextWeek));
});

function formatDate(date) {
    return date.getFullYear() + '-' + 
           String(date.getMonth() + 1).padStart(2, '0') + '-' + 
           String(date.getDate()).padStart(2, '0') + 'T' + 
           String(date.getHours()).padStart(2, '0') + ':' + 
           String(date.getMinutes()).padStart(2, '0');
}
</script>
@endpush

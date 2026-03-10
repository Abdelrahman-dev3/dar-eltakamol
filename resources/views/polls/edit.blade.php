@extends('layouts.app')

@section('title', __('تعديل الاستطلاع'))

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">{{ __('تعديل الاستطلاع') }}</h3>
            </div>
            <div class="panel-body">
                <form action="{{ route('polls.update', $poll) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label for="question">{{ __('السؤال') }} <span class="text-danger">*</span></label>
                        <textarea name="question" id="question" class="form-control @error('question') is-invalid @enderror" rows="3" required>{{ old('question', $poll->question) }}</textarea>
                        @error('question')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="start_date">{{ __('تاريخ البدء') }} <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', $poll->start_date->format('Y-m-d\TH:i:f')) }}" required>
                                @error('start_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="end_date">{{ __('تاريخ الانتهاء') }} <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', $poll->end_date->format('Y-m-d\TH:i:f')) }}" required>
                                @error('end_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $poll->is_active) ? 'checked' : '' }}>
                                {{ __('تفعيل الاستطلاع') }}
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="zoom_meeting_id">{{ __('اجتماع الزوم') }}</label>
                        <select name="zoom_meeting_id" id="zoom_meeting_id" class="form-control @error('zoom_meeting_id') is-invalid @enderror">
                            <option value="">{{ __('-- اختر اجتماع الزوم (اختياري) --') }}</option>
                            @forelse($zoomMeetings as $zoomMeeting)
                                <option value="{{ $zoomMeeting->id }}" {{ old('zoom_meeting_id', $poll->zoom_meeting_id) == $zoomMeeting->id ? 'selected' : '' }}>
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
                                <option value="{{ $user->id }}" {{ in_array($user->id, old('referenced_users', $poll->referencedUsers->pluck('id')->toArray())) ? 'selected' : '' }}>
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

                    <h4>{{ __('خيارات الاستطلاع الحالية') }}</h4>
                    <div class="alert alert-info">
                        {{ __('ملاحظة: تعديل الخيارات سيؤثر على نتائج الاستطلاع الحالية.') }}
                    </div>

                    @if($poll->pollOptions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ __('الخيار') }}</th>
                                        <th>{{ __('عدد الأصوات') }}</th>
                                        <th>{{ __('الإجراءات') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($poll->pollOptions as $option)
                                        <tr>
                                            <td>{{ $option->option_text }}</td>
                                            <td>{{ $option->votes }}</td>
                                            <td>
                                                <button type="button" class="btn btn-warning btn-xs edit-option" data-option-id="{{ $option->id }}" data-option-text="{{ $option->option_text }}">
                                                    <span class="glyphicon glyphicon-edit"></span> {{ __('تعديل') }}
                                                </button>
                                                <button type="button" class="btn btn-danger btn-xs delete-option" data-option-id="{{ $option->id }}">
                                                    <span class="glyphicon glyphicon-trash"></span> {{ __('حذف') }}
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <div class="form-group">
                        <button type="button" class="btn btn-success" id="add-option">
                            <span class="glyphicon glyphicon-plus"></span> {{ __('إضافة خيار جديد') }}
                        </button>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <span class="glyphicon glyphicon-save"></span> {{ __('حفظ التعديلات') }}
                        </button>
                        <a href="{{ route('polls.show', $poll) }}" class="btn btn-default">
                            <span class="glyphicon glyphicon-arrow-right"></span> {{ __('إلغاء') }}
                        </a>
                    </div>
                </form>

                <!-- Edit Option Modal -->
                <div class="modal fade" id="editOptionModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                                <h4 class="modal-title">{{ __('تعديل الخيار') }}</h4>
                            </div>
                            <div class="modal-body">
                                <form id="editOptionForm">
                                    <div class="form-group">
                                        <label for="edit_option_text">{{ __('نص الخيار') }}</label>
                                        <input type="text" name="option_text" id="edit_option_text" class="form-control" required>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('إلغاء') }}</button>
                                <button type="button" class="btn btn-primary" id="saveOption">{{ __('حفظ') }}</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add Option Modal -->
                <div class="modal fade" id="addOptionModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                                <h4 class="modal-title">{{ __('إضافة خيار جديد') }}</h4>
                            </div>
                            <div class="modal-body">
                                <form id="addOptionForm">
                                    <div class="form-group">
                                        <label for="new_option_text">{{ __('نص الخيار') }}</label>
                                        <input type="text" name="option_text" id="new_option_text" class="form-control" required>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('إلغاء') }}</button>
                                <button type="button" class="btn btn-success" id="addNewOption">{{ __('إضافة') }}</button>
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
    let editingOptionId = null;
    
    // Edit option
    $('.edit-option').click(function() {
        editingOptionId = $(this).data('option-id');
        const optionText = $(this).data('option-text');
        $('#edit_option_text').val(optionText);
        $('#editOptionModal').modal('show');
    });
    
    // Save edited option
    $('#saveOption').click(function() {
        const newText = $('#edit_option_text').val();
        if (newText.trim() === '') {
            alert('{{ __("يرجى إدخال نص الخيار.") }}');
            return;
        }
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        $.ajax({
            url: '/poll-options/' + editingOptionId,
            type: 'PUT',
            data: {
                option_text: newText,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                location.reload();
            },
            error: function(xhr) {
                alert('{{ __("حدث خطأ أثناء حفظ التعديل.") }}');
            }
        });
    });
    
    // Add new option
    $('#add-option').click(function() {
        $('#addOptionModal').modal('show');
    });
    
    $('#addNewOption').click(function() {
        const optionText = $('#new_option_text').val();
        if (optionText.trim() === '') {
            alert('{{ __("يرجى إدخال نص الخيار.") }}');
            return;
        }
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        $.ajax({
            url: '/poll-options',
            type: 'POST',
            data: {
                poll_id: {{ $poll->id }},
                option_text: optionText,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                location.reload();
            },
            error: function(xhr) {
                alert('{{ __("حدث خطأ أثناء إضافة الخيار.") }}');
            }
        });
    });
    
    // Delete option
    $('.delete-option').click(function() {
        const optionId = $(this).data('option-id');
        if (confirm('{{ __("هل أنت متأكد من حذف هذا الخيار؟ سيتم حذف جميع الأصوات المرتبطة به.") }}')) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            $.ajax({
                url: '/poll-options/' + optionId,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    location.reload();
                },
                error: function(xhr) {
                    alert('{{ __("حدث خطأ أثناء حذف الخيار.") }}');
                }
            });
        }
    });
});
</script>
@endpush

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class MeetingAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'meeting_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'mime_type',
        'description',
        'uploaded_by',
    ];

    /**
     * Get the meeting that owns the attachment.
     */
    public function meeting(): BelongsTo
    {
        return $this->belongsTo(Meeting::class);
    }

    /**
     * Get the user who uploaded the attachment.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get the full file path.
     */
    public function getFullPathAttribute(): string
    {
        return Storage::disk('public')->path($this->file_path);
    }

    /**
     * Get the file URL.
     */
    public function getFileUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->file_path);
    }

    /**
     * Get file icon based on type.
     */
    public function getFileIconAttribute(): string
    {
        $extension = strtolower(pathinfo($this->file_name, PATHINFO_EXTENSION));

        return match ($extension) {
            'pdf' => 'fa-file-pdf',
            'doc', 'docx' => 'fa-file-word',
            'xls', 'xlsx' => 'fa-file-excel',
            'ppt', 'pptx' => 'fa-file-powerpoint',
            'zip', 'rar', '7z' => 'fa-file-archive',
            'jpg', 'jpeg', 'png', 'gif', 'bmp' => 'fa-file-image',
            'mp4', 'avi', 'mov' => 'fa-file-video',
            'mp3', 'wav' => 'fa-file-audio',
            'txt' => 'fa-file-alt',
            default => 'fa-file',
        };
    }

    /**
     * Get human readable file size.
     */
    public function getFileSizeHumanAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $power = $bytes > 0 ? floor(log($bytes, 1024)) : 0;
        
        return number_format($bytes / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
    }
}



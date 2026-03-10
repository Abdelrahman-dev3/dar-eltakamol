<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Regulation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'meeting_id',
        'file_path',
        'original_filename',
        'file_type',
        'file_size',
    ];

    /**
     * Get the meeting this regulation belongs to.
     */
    public function meeting(): BelongsTo
    {
        return $this->belongsTo(Meeting::class);
    }

    /**
     * Get the file URL for the regulation.
     */
    public function getFileUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }

    /**
     * Get the file size in human readable format.
     */
    public function getFileSizeHumanAttribute(): string
    {
        if (!$this->file_size) return 'غير محدد';

        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get the file extension.
     */
    public function getFileExtensionAttribute(): string
    {
        return pathinfo($this->original_filename, PATHINFO_EXTENSION);
    }

    /**
     * Get the file icon based on type.
     */
    public function getFileIconAttribute(): string
    {
        $extension = strtolower($this->file_extension);

        $icons = [
            // Documents
            'pdf' => 'fa-file-pdf-o',
            'doc' => 'fa-file-word-o',
            'docx' => 'fa-file-word-o',
            'xls' => 'fa-file-excel-o',
            'xlsx' => 'fa-file-excel-o',
            'ppt' => 'fa-file-powerpoint-o',
            'pptx' => 'fa-file-powerpoint-o',
            'txt' => 'fa-file-text-o',

            // Images
            'jpg' => 'fa-file-image-o',
            'jpeg' => 'fa-file-image-o',
            'png' => 'fa-file-image-o',
            'gif' => 'fa-file-image-o',
            'bmp' => 'fa-file-image-o',

            // Archives
            'zip' => 'fa-file-archive-o',
            'rar' => 'fa-file-archive-o',
            '7z' => 'fa-file-archive-o',
        ];

        return $icons[$extension] ?? 'fa-file-o';
    }
}


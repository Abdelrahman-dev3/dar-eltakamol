<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modification extends Model
{
    protected $fillable = [
        'page_name',
        'note',
        'modified_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'modified_by');
    }

    public static function logChange(string $page_name, ?string $note, int $modified_by)
    {
        
        self::create([
            'page_name' => $page_name,
            'note' => $note,
            'modified_by' => $modified_by,
        ]);
    }
}

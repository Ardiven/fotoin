<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'file_url',
        'file_type',
    ];

    // Relationships
    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    // Accessors
    public function getIsImageAttribute()
    {
        return in_array($this->file_type, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
    }

    public function getIsDocumentAttribute()
    {
        return in_array($this->file_type, ['pdf', 'doc', 'docx', 'txt']);
    }
}

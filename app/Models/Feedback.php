<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedbacks';
    protected $fillable = ['title','description','category_id'];
    use HasFactory;
    public function votes() {
        return $this->hasMany(Vote::class, 'feedback_id', 'id');
    }
    public function comments() {
        return $this->hasMany(Comment::class, 'feedback_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

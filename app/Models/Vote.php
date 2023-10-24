<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $table = 'votes';
    protected $fillable = ['feedback_id'];
    use HasFactory;
    public function feedback() {
        return $this->belongsTo(Feedback::class, 'feedback_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'message', 'rating', 'approved'];
}
// The Feedback model represents the feedback data structure.
// It uses the HasFactory trait for factory support and defines fillable attributes for mass assignment.
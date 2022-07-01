<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['website_id', 'title', 'description', 'body', 'published'];

    /**
     * Get the website the post belongs to.
     */
    public function website()
    {
        return $this->belongsTo(Website::class);
    }
}

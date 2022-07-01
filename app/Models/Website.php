<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    use HasFactory;

    protected $fillable = ['url', 'name'];

    /**
     * Get all of the subscriptions for the website.
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}

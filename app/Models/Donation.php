<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    //
    protected $fillable = [
        'donor_id', 'book_title', 'author', 'publisher', 'year_published', 'quantity', 'status',
    ];

    public function donor()
    {
        return $this->belongsTo(User::class, 'donor_id');
    }
}

<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'isbn', 'title', 'author', 'publisher', 'year_published', 'quantity',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }
}

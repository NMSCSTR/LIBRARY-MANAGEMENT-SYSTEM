<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookCopy extends Model
{
    //
    protected $fillable = [
        'book_id',
        'copy_number',
        'status',
        'shelf_location',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function borrows()
    {
        return $this->hasMany(Borrow::class, 'book_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'copy_id');
    }

}

<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookCopy extends Model
{
    //
    protected $fillable = [
        'book_id',
        'copy_number',
        'status', // available, borrowed, lost, damaged
        'shelf_location',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function borrowRecords()
    {
        return $this->hasMany(BorrowRecord::class);
    }
}

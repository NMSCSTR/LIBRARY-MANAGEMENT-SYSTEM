<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title', 'isbn', 'author_id', 'category_id', 'publisher_id', 'supplier_id', 'copies_available', 'year_published', 'place_published'
    ];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function copies()
    {
        return $this->hasMany(BookCopy::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }
}

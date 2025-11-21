<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'book_id', 'borrow_date', 'due_date', 'return_date', 'status',
    ];

    protected $casts = [
        'borrow_date' => 'datetime',
        'due_date'    => 'datetime',
        'return_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function getStatusAttribute($value)
    {
        if ($value === 'borrowed' && $this->due_date < now()) {
            return 'overdue';
        }
        return $value;
    }
}

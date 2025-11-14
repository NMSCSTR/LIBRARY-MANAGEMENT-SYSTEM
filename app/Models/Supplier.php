<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    //
    protected $fillable = ['name', 'address', 'contact_person', 'email', 'phone'];

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}

<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{

    protected $table = 'reservations';

    protected $guarded = ['id'];

    /*
     *
     * Scope
     *
    */

    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeNow($query)
    {
        return $query->where('date_start', '>=', now());
    }
}
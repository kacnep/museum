<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Output extends Model
{

    protected $table = 'outputs';

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
        return $query->where('output', '>=', now());
    }

}
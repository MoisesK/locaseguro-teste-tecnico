<?php

declare(strict_types=1);

namespace App\Shared\Infra\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $fillable = [
        'owner_id',
        'city',
        'street',
        'zip_code',
        'number',
        'amount',
    ];

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }
}

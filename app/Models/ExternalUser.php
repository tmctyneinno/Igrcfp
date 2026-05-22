<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExternalUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'external_user_id',
        'client',
    ];

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'external_user_id', 'external_user_id')
            ->where('client', $this->client);
    }
}

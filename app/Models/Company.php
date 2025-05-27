<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name'
    ];

    /**
     * @brief   Get the user records associated with the company.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\User, $this>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * @brief   Get the module records associated with the company.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Module, $this>
     */
    public function modules(): HasMany
    {
        return $this->hasMany(Module::class);
    }
}

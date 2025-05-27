<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'company_id'
    ];

    /**
     * @brief   Get modules associated with the course.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsToMany<\App\Models\Module, $this>
     */
    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class);
    }

    /**
     * @brief   Get the status associated with the course.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Status, $this>
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }
}

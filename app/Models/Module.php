<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Module extends Model
{
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',
        'company_id',
        'status_id'
    ];

    /**
     * The attributes that are assigned.
     *
     * @var list<string>
     */
    protected $appends = [
        'status_slug'
    ];

    /**
     * @brief   Get courses associated with the module.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsToMany<\App\Models\Course, $this>
     */
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class);
    }

    /**
     * @brief   Get the company associated with the module.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Company, $this>
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * @brief   Get the status associated with the module.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Status, $this>
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * @brief   Get the status as slug associated with the module (draft, published, trash, ...).
     *
     * @return  string
     */
    public function getStatusSlugAttribute(): string
    {
        return $this->status->status;
    }
}

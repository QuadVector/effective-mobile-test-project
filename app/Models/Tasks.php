<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tasks extends Model
{
    protected $table = 'tasks';
    protected $fillable = ['title', 'description', 'creator_id', 'executor_id', 'end_date', 'is_done'];

    /**
     * @return BelongsTo
     */
    // Relation to the user who created the task (creator)
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Relation to the user who performs the task (executor)
     *
     * @return BelongsTo
     */
    public function executor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'executor_id');
    }
}

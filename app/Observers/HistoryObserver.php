<?php

namespace App\Observers;

use App\Models\History;
use Illuminate\Support\Facades\Auth;


class HistoryObserver
{

    public function created($model)
    {
        History::create([
            'user_id' => Auth::id(),
            'table_name' => $model->getTable(),
            'record_id' => $model->getKey(),
            'action' => 'created',
            'old_values' => null,
            'new_values' => $model->toArray(),
            'created_at' => now(),
        ]);
    }

    public function updated($model)
    {
        $changes = $model->getChanges();
        $original = $model->getOriginal();
        History::create([
            'user_id' => Auth::id(),
            'table_name' => $model->getTable(),
            'record_id' => $model->getKey(),
            'action' => 'updated',
            'old_values' => $original,
            'new_values' => $model->toArray(),
            'created_at' => now(),
        ]);
    }

    public function deleted($model)
    {
        History::create([
            'user_id' => Auth::id(),
            'table_name' => $model->getTable(),
            'record_id' => $model->getKey(),
            'action' => 'deleted',
            'old_values' => $model->toArray(),
            'new_values' => null,
            'created_at' => now(),
        ]);
    }
}

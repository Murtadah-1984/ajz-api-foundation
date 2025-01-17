<?php

namespace App\Domains\Shared\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use LogicException;

abstract class CustomModel extends Model
{
    public function newEloquentBuilder($query): Builder
    {
        throw new LogicException(sprintf('Model %s must defined `newEloquentBuilder`', get_class()));
    }
}

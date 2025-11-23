<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;

trait HasNumber
{
    public static function bootHasNumber()
    {
        static::creating(function (Model $model) {
            $model->number = self::generateNumber($model);
        });
    }

    public static function generateNumber(Model $model): string
    {
        $prefix = self::getNumberPrefix();
        $companyInitials = $model->company->initials();
        $timestamp = now()->format('YmdHis');

        return "{$prefix}-{$companyInitials}-{$timestamp}";
    }

    abstract protected static function getNumberPrefix(): string;
}

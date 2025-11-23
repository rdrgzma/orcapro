<?php

namespace App\Models;

use App\Models\Concerns\HasNumber;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Budget extends Model
{
    use HasFactory, HasNumber;

    protected $fillable = [
        'company_id', 'client_id', 'name', 'subtotal', 'discount_value', 'discount_type',
        'tax_value', 'tax_type', 'additional_fees', 'total', 'status', 'token'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($budget) {
            if (empty($budget->token)) {
                $budget->token = Str::random(32);
            }
        });
    }

    public function items()
    {
        return $this->hasMany(BudgetItem::class);
    }

    public function workOrder()
    {
        return $this->hasOne(WorkOrder::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    protected static function getNumberPrefix(): string
    {
        return 'BUD';
    }
}

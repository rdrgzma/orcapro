<?php

namespace App\Models;

use App\Models\Concerns\HasNumber;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    use HasFactory, HasNumber;

    protected $fillable = [
        'company_id', 'client_id', 'budget_id', 'status'
    ];

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    protected static function getNumberPrefix(): string
    {
        return 'OS';
    }
}

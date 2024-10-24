<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Vaccine;
use App\Models\Regional;
use App\Models\Vaccination;

class Spot extends Model {
    use HasFactory;

    protected $hidden = [
        'regional_id',
        'vaccines',
        'available_vaccines'
    ];

    protected $with = [];

    protected $appends = [
        'available_vaccines'
    ];

    public function vaccines() {
        return $this->belongsToMany(Vaccine::class, 'spot_vaccines');
    }
    public function regional() {
        return $this->belongsTo(Regional::class);
    }
    public function vaccinations() {
        return $this->hasMany(Vaccination::class);
    }
    public function getAvailableVaccinesAttribute() {
        $vaccines = Vaccine::all();
        $availableVaccines = [];

        foreach ($vaccines as $vaccine) {
            $availableVaccines[$vaccine->name] = $this->vaccines->where('name', $vaccine->name)->first() != null;
        }

        return $availableVaccines;
    }
}

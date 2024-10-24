<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Spot;
use App\Models\Vaccine;
use App\Models\Medical;

class Vaccination extends Model {
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];
    protected $hidden = [
        'id',
        'date',
        'society_id',
        'spot_id',
        'vaccine_id',
        'doctor_id',
        'officer_id',
    ];

    protected $appends = [
        'queue',
        'vaccination_date',
        'status'
    ];
    protected $with = [
        'spot',
        'vaccine',
        'vaccinator'
    ];

    public function getStatusAttribute() {
        return $this->vaccine_id == null ? 'registered' : 'done';
    }
    public function getQueueAttribute() {
        return $this->dose;
    }
    public function getVaccinationDateAttribute() {
        return $this->date;
    }
    public function spot() {
        return $this->belongsTo(Spot::class);
    }
    public function vaccine() {
        return $this->belongsTo(Vaccine::class);
    }
    public function vaccinator() {
        return $this->belongsTo(Medical::class, 'doctor_id');
    }
}

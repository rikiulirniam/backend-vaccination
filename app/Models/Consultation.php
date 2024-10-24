<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Medical;
use App\Models\Society;


class Consultation extends Model {
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    protected $hidden = [
        'society_id',
        'doctor_id',
    ];

    protected $with = [
        'doctor'
    ];

    public function doctor() {
        return $this->belongsTo(Medical::class, 'doctor_id');
    }
    public function society() {
        return $this->belongsTo(Society::class);
    }
}

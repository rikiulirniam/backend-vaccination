<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Regional;
use App\Models\Consultation;
use App\Models\Vaccination;

class Society extends Authenticatable {
    use HasFactory;

    public $timestamps = false;

    protected $hidden = [
        // 'id',
        'id_card_number',
        'password',
        'regional_id',
        'login_tokens'
    ];
    protected $with = [
        'regional'
    ];

    public function regional() {
        return $this->belongsTo(Regional::class);
    }
    public function consultation() {
        return $this->hasOne(Consultation::class);
    }
    public function vaccinations() {
        return $this->hasMany(Vaccination::class);
    }

    public function createToken() {
        $this->login_tokens = hash('md5', $this->id_card_number);
        $this->save();

        return $this->login_tokens;
    }
    public function deleteToken() {
        $this->login_tokens = null;
        $this->save();
    }
}

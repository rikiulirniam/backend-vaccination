<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Spot;

class Regional extends Model {
    use HasFactory;

    public function spots() {
        return $this->hasMany(Spot::class);
    }
}

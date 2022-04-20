<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;
    protected $fillable = [
        'status'
    ];
    public function get_kota(){
        return $this->hasMany(Kota::class, 'id_status', 'id');
    }
    public function get_angkutan(){
        return $this->hasMany(JenisAngkutan::class, 'id_status', 'id');
    }
}

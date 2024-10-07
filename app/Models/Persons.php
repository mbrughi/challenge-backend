<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persons extends Model
{
    use HasFactory;

    protected $fillable = [
      'nome',
      'cognome',
      'data_nascita',
      'email',
      'telefono',
      'codice_fiscale',
  ];
  

    public function user() {
      return $this->belongsTo(User::class);
    }
}

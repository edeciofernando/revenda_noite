<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Carro extends Model
{
    protected $fillable = array('modelo', 'cor', 'ano', 'preco', 'combustivel');

    public function getCombustivelAttribute($value) {
        if ($value=="A") {
            return "Álcool";
        } else if ($value=="G") {
            return "Gasolina";
        } else if ($value=="D") {
            return "Diesel";
        } else if ($value=="F") {
            return "Flex";
        }
    }
    
    public function setPrecoAttribute($value) {
        $novo1 = str_replace('.', '', $value);
        $novo2 = str_replace(',', '.', $novo1);
        $this->attributes['preco'] = $novo2;
    }
}

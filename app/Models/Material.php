<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;
     // Relacionamento com a categoria
     public function category()
     {
         return $this->belongsTo(Material_category::class, 'category_id');  // Relaciona com a categoria
     }
}

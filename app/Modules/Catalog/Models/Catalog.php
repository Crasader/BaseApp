<?php

namespace App\Modules\Catalog\Models;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;

class Catalog extends Model
{
    use HasRoles;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'name','url','description','public'
    ];


    /**s
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

}
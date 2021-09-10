<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
	use HasFactory;
	use \App\Traits\UsesUuid;
   
	public $timestamps = true;

    protected $fillable = [
		'id',
		'filename',
        'url',
		'size'		
    ];
	
	
}

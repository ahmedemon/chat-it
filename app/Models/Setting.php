<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'domain',
        'site_logo',
        'site_greetings',
        'site_link',
        'link_title',
        'btn_color'
    ];
}

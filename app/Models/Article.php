<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
  protected $table = 'articles';

  protected $fillable = [
    'title',
    'title_slug',
    'description',
    'url',
    'image_url',
    'source',
    'category',
    'published_at',
    'author',
    'content',
    'created_at',
    'updated_at',
    'deleted_at',
  ];
}
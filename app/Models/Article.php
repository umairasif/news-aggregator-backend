<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
  const ARTICLE_SOURCE_NEWS_API = 'NewsApi';
  const ARTICLE_SOURCE_GUARDIAN = 'Guardian';
  const ARTICLE_SOURCE_NYT = 'The New York Times';

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
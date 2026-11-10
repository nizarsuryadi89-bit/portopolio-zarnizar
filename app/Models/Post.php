<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'image',
        'category_id',
        'is_published',
        'published_at',
        'views',
        'likes',
        'dislikes',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_published' => 'boolean',
        'views' => 'integer',
        'likes' => 'integer',
        'dislikes' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function getContentWithAutoLinksAttribute(): string
    {
        return static::autoLinkUrls($this->content ?? '');
    }

    public static function autoLinkUrls(string $html): string
    {
        if (trim($html) === '') {
            return $html;
        }

        $parts = preg_split('/(<[^>]+>)/u', $html, -1, PREG_SPLIT_DELIM_CAPTURE);
        if ($parts === false) {
            return $html;
        }

        $inLink = 0;
        $inIgnored = 0;
        $result = [];

        foreach ($parts as $part) {
            if ($part === '') {
                continue;
            }

            if ($part[0] === '<' && str_ends_with($part, '>')) {
                if (preg_match('/^<\s*a\b/i', $part)) {
                    $inLink++;
                } elseif (preg_match('/^<\s*\/\s*a\s*>/i', $part)) {
                    $inLink = max(0, $inLink - 1);
                } elseif (preg_match('/^<\s*(pre|code|script|style)\b/i', $part)) {
                    $inIgnored++;
                } elseif (preg_match('/^<\s*\/\s*(pre|code|script|style)\s*>/i', $part)) {
                    $inIgnored = max(0, $inIgnored - 1);
                }

                $result[] = $part;

                continue;
            }

            if ($inLink > 0 || $inIgnored > 0) {
                $result[] = $part;

                continue;
            }

            $replaced = preg_replace_callback('/(https?:\/\/[^\s<>\"]+)/u', function (array $m): string {
                $url = $m[1];
                $trailing = '';

                while (str_ends_with($url, ')') && substr_count($url, ')') > substr_count($url, '(')) {
                    $trailing = ')'.$trailing;
                    $url = substr($url, 0, -1);
                }

                while ($url !== '' && preg_match('/[,\.\?!;:\x22\x27]$/u', $url)) {
                    $trailing = substr($url, -1).$trailing;
                    $url = substr($url, 0, -1);
                }

                if ($url === '' || ! filter_var($url, FILTER_VALIDATE_URL)) {
                    return $m[0];
                }

                $escaped = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');

                return '<a href="'.$escaped.'" target="_blank" rel="noopener noreferrer">'.$escaped.'</a>'.$trailing;
            }, $part);

            $result[] = $replaced ?? $part;
        }

        return implode('', $result);
    }
}

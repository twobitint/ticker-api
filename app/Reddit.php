<?php

namespace App;

use App\Models\Post;
use Illuminate\Support\Facades\Http;

class Reddit
{
    public static function updateRising($sub)
    {
        // Try to http query reddit post data.
        $results = Http::get('https://reddit.com/r/' . $sub . '/rising.json')
            ->json('data.children');

        if (!$results) {
            return;
        }

        foreach ($results as $result) {
            $data = $result['data'];
            $url = 'https://reddit.com' . $data['permalink'];
            $post = Post::firstOrNew(['url' => $url]);

            // Update post data.
            $post->title = $data['title'];
            $post->content = html_entity_decode($data['selftext_html']);
            $post->source = 'reddit';
            $post->author = $data['author'];
            $post->category = strtolower($sub);
            $post->subcategory = self::cleanFlair($data['link_flair_text'] ?? null);
            $post->posted_at = $data['created_utc'];
            $post->score = $data['score'];
            $post->score_confidence = $data['upvote_ratio'];

            $post->save();

            // Update stocks referenced by the post.
            $post->updateStocks();
        }
    }

    protected static function cleanFlair($string)
    {
        $string = preg_replace('/:.*:/', '', $string);
        $string = trim($string);
        $string = strtolower($string);
        return $string;
    }
}

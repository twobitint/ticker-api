<?php

namespace App;

use App\Models\Post;
use Illuminate\Support\Facades\Http;

class Reddit
{
    public static function updatePosts($posts)
    {
        $names = $posts->pluck('url')->map(function ($url) {
            preg_match('/comments\/(.{6})/', $url, $matches);
            return 't3_'.$matches[1];
        });
        $results = Http::get('https://reddit.com/by_id/' . $names->implode(',') . '.json?limit=100')
            ->json('data.children');

        if (!$results) {
            return;
        }

        $posts = [];
        foreach ($results as $result) {
            if ($post = self::postFromData($result['data'])) {
                $posts[] = $post;
            }
        }
        return $posts;
    }

    public static function updatePost($post)
    {
        $data = Http::get($post->url . '.json')->json('0.data.children.0.data');
        return self::postFromData($data);
    }

    public static function updateHot($sub)
    {
        return self::updateSub($sub, 'hot');
    }

    public static function updateRising($sub)
    {
        return self::updateSub($sub, 'rising');
    }

    protected static function updateSub($sub, $type)
    {
        // Try to http query reddit post data.
        $results = Http::get('https://reddit.com/r/' . $sub . '/' . $type . '.json')
            ->json('data.children');

        if (!$results) {
            return;
        }

        $posts = [];
        foreach ($results as $result) {
            if ($post = self::postFromData($result['data'])) {
                $posts[] = $post;
            }
        }
        return $posts;
    }

    protected static function postFromData($data)
    {
        $url = 'https://reddit.com' . $data['permalink'];
        $post = Post::firstOrNew(['url' => $url]);

        // Check if post is deleted.
        if ($data['removed_by_category']) {
            if ($post->id) {
                $post->delete();
            }

            return null;
        }

        // Don't update a post within 15 minutes.
        if ($post->id && now()->diffInMinutes($post->updated_at) < 15) {
            return $post;
        }

        // Update post data.
        $post->title = $data['title'];
        $post->content = html_entity_decode($data['selftext_html']);
        $post->source = 'reddit';
        $post->author = $data['author'];
        $post->category = strtolower($data['subreddit']);
        $post->subcategory = self::cleanFlair($data['link_flair_text'] ?? null);
        $post->comment_count = $data['num_comments'] ?? null;
        $post->posted_at = $data['created_utc'];
        $post->score = $data['score'];
        $post->score_confidence = $data['upvote_ratio'];

        if (!$post->id) {
            $post->velocity = 0;
            $post->popularity = 0;
        } else {
            if ($elapsed = $post->updated_at->diffInSeconds(now())) {
                $post->velocity = ($post->score - $post->getOriginal('score')) / $elapsed * 60 * 60;
            } else {
                $post->velocity = 0;
            }
            // $min = -100;
            // $max = 100;
            // $post->popularity = max($min, min($max, $post->velocity));
            $post->popularity = $post->velocity;
        }

        $post->updated_at = now();

        // We're going to set our own score based on sub size to try to
        // normalize things a bit.
        // $minimumWeightedScore = 0;
        // $minutesOld = $post->posted_at->diffInMinutes(now());
        // $weightedScore = max(
        //     $minimumWeightedScore,
        //     $post->score * 10 / log($data['subreddit_subscribers'])
        // );
        // $post->popularity = max(0, -0.5 * pow($minutesOld / 60, 3) + $weightedScore);

        // Clamp popularity to a range of velocities.


        //$post->score = (int)(sqrt($data['score'] * 4 / log($data['subreddit_subscribers'])));

        $post->save();

        // Update stocks referenced by the post.
        $post->updateStocks();

        return $post;
    }

    protected static function cleanFlair($string)
    {
        $string = preg_replace('/:.*:/', '', $string);
        $string = trim($string);
        $string = strtolower($string);
        return $string;
    }
}

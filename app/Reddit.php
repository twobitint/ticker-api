<?php

namespace App;

use App\Models\Post;
use Illuminate\Support\Facades\Http;

class Reddit
{
    public static function updatePosts($posts)
    {
        foreach ($posts as $post) {
            self::updatePost($post);
        }
        return $posts;
    }

    public static function updatePost($post)
    {
        $data = Http::get($post->url . '.json')->json('0.data.children.0.data');
        return self::postFromData($data);
    }

    public static function updateRising($sub)
    {
        // Try to http query reddit post data.
        $results = Http::get('https://reddit.com/r/' . $sub . '/rising.json')
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

        // We're going to set our own score based on sub size to try to
        // normalize things a bit.
        $quarterDaysOld = $post->posted_at->diffInMinutes(now()) / (60 * 8);
        $weightedScore = $post->score * 10 / log($data['subreddit_subscribers']);
        $post->popularity = $weightedScore / pow(max(0, $quarterDaysOld - 2) + 1, 1.8);

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

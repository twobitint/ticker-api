<?php

namespace App;

use App\Models\Post;
use Illuminate\Support\Facades\Http;

class Reddit
{
    /**
     * This is the main method used to run background updates for
     * post data. Since this is query heavy, and we don't really care that much
     * about what happens here, limit the impact by only updating a subset.
     */
    public static function updateRecentPosts()
    {
        // Some update rules:
        //   - Don't update a post that's over a week old
        //   - Don't update a post that's been updated in the last 15 minutes
        //   - Only update up to 10 posts per call
        //   - Prefer higher scoring posts
        $posts = Post::where('posted_at', '>', now()->subDays(7))
            ->where('updated_at', '<', now()->subMinutes(15))
            ->orderBy('score', 'desc')
            ->limit(10)
            ->get();

        self::updatePosts($posts);
    }

    public static function updatePosts($posts)
    {
        foreach ($posts as $post) {
            self::updatePost($post);
        }
    }

    public static function updatePost($post)
    {
        $data = Http::get($post->url . '.json')->json('0.data.children.0.data');
        self::postFromData($data);
    }

    public static function updateRising($sub)
    {
        // Try to http query reddit post data.
        $results = Http::get('https://reddit.com/r/' . $sub . '/rising.json')
            ->json('data.children');

        if (!$results) {
            return;
        }

        foreach ($results as $result) {
            self::postFromData($result['data']);
        }
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
        $post->posted_at = $data['created_utc'];
        $post->score = $data['score'];
        $post->score_confidence = $data['upvote_ratio'];

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

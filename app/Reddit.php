<?php

namespace App;

use App\Models\Mention;
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

    public static function updateComments($sub)
    {
        $results = Http::get('https://reddit.com/r/' . $sub . '/comments.json?limit=100')
            ->json('data.children');

        if (!$results) {
            return;
        }

        $comments = [];
        foreach ($results as $result) {
            if ($comment = self::commentFromData($result['data'])) {
                $posts[] = $comment;
            }
        }
        return $comments;
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

    protected static function commentFromData($data)
    {
        $url = 'https://reddit.com' . $data['permalink'];
        $comment = Mention::firstOrNew(['url' => $url]);

        // Check if post is deleted.
        if ($data['removal_reason']) {
            if ($comment->id) {
                $comment->delete();
            }

            return null;
        }

        // Update comment data.
        $comment->type = 'comment';
        $comment->content = html_entity_decode($data['body_html']);
        $comment->source = 'reddit';
        $comment->author = $data['author'];
        $comment->subreddit = strtolower($data['subreddit']);
        $comment->category = 'comment';
        $comment->posted_at = $data['created_utc'];
        $comment->score = $data['score'];

        // Always update timestamp.
        $comment->updated_at = now();

        $comment->save();

        // Update stocks referenced by the post.
        $comment->updateStocks();

        return $comment;
    }

    protected static function postFromData($data)
    {
        $url = 'https://reddit.com' . $data['permalink'];
        $post = Mention::firstOrNew(['url' => $url]);

        // Check if post is deleted.
        if ($data['removed_by_category']) {
            if ($post->id) {
                $post->delete();
            }

            return null;
        }

        // Update post data.
        $post->type = 'post';
        $post->title = $data['title'];
        $post->content = html_entity_decode($data['selftext_html']);
        $post->source = 'reddit';
        $post->author = $data['author'];
        $post->subreddit = strtolower($data['subreddit']);
        $post->category = self::cleanFlair($data['link_flair_text'] ?? null);
        $post->comment_count = $data['num_comments'] ?? null;
        $post->posted_at = $data['created_utc'];
        $post->score = $data['score'];

        // Always update timestamp.
        $post->updated_at = now();

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

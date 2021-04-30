<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function Create(Request $request)
    {
        $request->validate([
            'description' =>  ['required', 'string', 'max:600'],
            'post_image' =>  ['mimes:jpeg,jpg,png', 'required', 'max:10000']
        ]);
        $path = uplodeFile($request->file('post_image'), 'post');
        $data = [
            'user_id' => auth()->user()->id,
            'description' => $request->description,
            'image_path' => $path
        ];
        if (Post::create($data))
            return redirect(route('front.home'));
        else
            abort(500);
    }

    public function like(Post $post)
    {
        $data = [
            'user_id' => auth()->user()->id,
            'post_id' => $post->id
        ];
        $like = Like::where($data)->first();
        if ($like) {
            $like->delete();
            return response(['status' => true, 'message' => 'like remove', 'type' => 1, 'count' => Like::wherePost_id($post->id)->get()->count()]);
        }

        if (Like::create($data)) {
            return response(['status' => true, 'message' => 'like add', 'type' => 0, 'count' => Like::wherePost_id($post->id)->get()->count()]);
        } else {
            return response(['status' => false, 'message' => 'something went wrong please try again later'], 500);
        }
    }

    public function comment(Request $request)
    {
        $request->validate([
            'comment' => ['required']
        ]);

        $post = Post::find($request->post_id);
        if (!$post) {
            return response(['status' => false, 'meassage' => 'post not found']);
        }

        $comment = Comment::create([
            'user_id' => auth()->user()->id,
            'post_id' => $request->post_id,
            'comment' => $request->comment
        ]);
        return response(['status' => true, 'comment' => $comment, 'userName' => auth()->user()->name, 'count' => Comment::wherePost_id($request->post_id)->get()->count()]);
    }

    public function allcomment(Post $post)
    {
        $comment = Comment::with('user')->wherePost_id($post->id)->latest()->get();
        return response(['status' => true, 'comment' => $comment, 'count' => $comment->count()]);
    }
}

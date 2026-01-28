<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index() {
        $posts = Post::paginate(2);
        return $posts;
    }

    public function show(Post $post) {
        // dd($id);
        // return Post::find($id);
        return $post;
    }

    public function destroy(Post $post) {
        $post->delete();
        return "deleted successfully";
    }

    public function store(StorePostRequest $request) {
        // $post = new Post;
        // $post->title = $request->title;
        // $post->content = $request->content;
        // $post->user_id = $request->user_id;
        // $post->save();
        $data = $request->validated();
        $post = Post::create($data);
        return $post;
    }
}

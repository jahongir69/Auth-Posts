<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        return response()->json(Post::with('user')->get());
    }

    public function store(PostStoreRequest $request)
    {
        $post = Auth::user()->posts()->create($request->validated());
        return response()->json(['message' => 'Post created', 'post' => $post]);
    }

    public function show($id)
    {
        return response()->json(Post::with('user')->findOrFail($id));
    }

    public function update(PostUpdateRequest $request, $id)
    {
        $post = Post::findOrFail($id);
        if ($post->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $post->update($request->validated());
        return response()->json(['message' => 'Post updated', 'post' => $post]);
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        if ($post->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $post->delete();
        return response()->json(['message' => 'Post deleted']);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
class PostController extends Controller
{
    public function index()
    {
        return response()->json(Post::with('user')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required'
        ]);

        $post = Auth::user()->posts()->create($request->only('title', 'description'));

        return response()->json(['message' => 'Post created', 'post' => $post]);
    }

    public function show($id)
    {
        return response()->json(Post::with('user')->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        if ($post->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'required',
            'description' => 'required'
        ]);

        $post->update($request->only('title', 'description'));

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
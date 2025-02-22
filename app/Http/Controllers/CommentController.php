<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, $postId)
    {
        $request->validate([
            'body' => 'required'
        ]);

        $post = Post::findOrFail($postId);

        $comment = $post->comments()->create([
            'body' => $request->body,
            'user_id' => Auth::id()
        ]);

        return response()->json(['message' => 'Comment added', 'comment' => $comment]);
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        if ($comment->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return response()->json(['message' => 'Comment deleted']);
    }
}

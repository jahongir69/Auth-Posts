<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPostOwner
{
    
    public function handle(Request $request, Closure $next)
    {
        $postId = $request->route('id'); 
        $post = Post::find($postId);

        if (!$post || $post->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}
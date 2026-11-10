<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostReactionController extends Controller
{
    public function react(Request $request, string $slug): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'in:like,dislike'],
        ]);

        $post = Post::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $sessionKey = 'post_reaction_'.$post->id;
        $current = $request->session()->get($sessionKey);
        $type = $validated['type'];
        $newStatus = null;

        DB::transaction(function () use ($post, $sessionKey, $current, $type, $request, &$newStatus): void {
            if ($current === $type) {
                if ($type === 'like') {
                    $post->decrement('likes');
                } else {
                    $post->decrement('dislikes');
                }
                $request->session()->forget($sessionKey);
                $newStatus = null;
            } elseif ($current !== null) {
                if ($type === 'like') {
                    $post->increment('likes');
                    $post->decrement('dislikes');
                } else {
                    $post->increment('dislikes');
                    $post->decrement('likes');
                }
                $request->session()->put($sessionKey, $type);
                $newStatus = $type;
            } else {
                if ($type === 'like') {
                    $post->increment('likes');
                } else {
                    $post->increment('dislikes');
                }
                $request->session()->put($sessionKey, $type);
                $newStatus = $type;
            }

            $post->refresh();
            if ($post->likes < 0 || $post->dislikes < 0) {
                $post->update([
                    'likes' => max(0, (int) $post->likes),
                    'dislikes' => max(0, (int) $post->dislikes),
                ]);
            }
        });

        $post->refresh();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'action' => $newStatus,
                'likes' => (int) $post->likes,
                'dislikes' => (int) $post->dislikes,
                'userReaction' => $newStatus,
            ]);
        }

        return back()->with('status', 'Terima kasih atas tanggapan Anda!');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('category')->latest()->get();

        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();

        return view('admin.posts.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'image' => 'nullable|image|max:2048',
            'is_published' => 'nullable',
        ]);

        $validated['slug'] = Str::slug($validated['title']).'-'.uniqid();
        $validated['is_published'] = $request->has('is_published');
        if ($validated['is_published']) {
            $validated['published_at'] = now();
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('posts', 'public');
        }

        $post = Post::create($validated);

        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        return redirect()->route('admin.posts.index')->with('success', 'Article created successfully.');
    }

    public function edit(Post $post)
    {
        $categories = Category::all();
        $tags = Tag::all();

        return view('admin.posts.edit', compact('post', 'categories', 'tags'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'image' => 'nullable|image|max:2048',
            'is_published' => 'nullable',
        ]);

        $validated['is_published'] = $request->has('is_published');

        if ($validated['is_published'] && ! $post->is_published) {
            $validated['published_at'] = now();
        } elseif (! $validated['is_published']) {
            $validated['published_at'] = null;
        }

        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $validated['image'] = $request->file('image')->store('posts', 'public');
        }

        $post->update($validated);

        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        } else {
            $post->tags()->sync([]);
        }

        return redirect()->route('admin.posts.index')->with('success', 'Article updated successfully.');
    }

    public function destroy(Post $post)
    {
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }
        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Article deleted successfully.');
    }
}

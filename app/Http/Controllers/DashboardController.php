<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Storage;


class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('d.posts.posts', [
            'posts' => Post::where('user_id', Auth::user()->id)->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('d.posts.new', [
            'categories' => Category::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->file('thumbnail')->store('uploads');

        $validData = $request->validate([
            'title' => 'required|max:30',
            'slug' => 'required|unique:posts',
            'category_id' => 'required',
            'thumbnail' => [File::image()
                ->max(3 * 1024)],
            'content' => 'required'
        ]);

        if ($request->file('thumbnail')) {
            $validData['thumbnail'] = $request->file('thumbnail')->store('uploads');
        }

        $validData['user_id'] = Auth::user()->id;

        $validData['excrpt'] =  Str::limit(strip_tags($request->content), 145, '...');

        // ddd($validData);

        Post::create($validData);

        return redirect('/dashboard/posts')->with('succss', 'Successfully added new post');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        if ($post->author->id != Auth::user()->id) {
            abort(403);
        }

        return view('d.posts.post', [
            'post' => $post
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        if ($post->author->id != Auth::user()->id) {
            abort(403);
        }

        return view('d.posts.edit', [
            'post' => $post,
            'categories' => Category::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *b
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $data = [
            'title' => 'required|max:175',
            'category_id' => 'required',
            'content' => 'required'
        ];

        if ($request->slug != $post->slug) {
            $data['slug'] = 'required|unique:posts';
        };

        $validData = $request->validate($data);

        if ($request->file('thumbnail')) {
            if ($post->thumbnail != null) {
                Storage::delete($post->thumbnail);
            }
            $validData['thumbnail'] = $request->file('thumbnail')->store('uploads');
        }

        $validData['user_id'] = Auth::user()->id;
        $validData['excrpt'] =  Str::limit(strip_tags($request->content), 145, '...');

        Post::where('id', $post->id)->update($validData);

        return redirect('/dashboard/posts')->with('succss', 'Successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        if ($post->thumbnail) {
            Storage::delete($post->thumbnail);
        }

        Post::destroy($post->id);

        return redirect('/dashboard/posts')->with('succss', 'Successfully deleted');
    }
}

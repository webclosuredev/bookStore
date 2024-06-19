<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Post;

class PostController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->only(['title', 'description', 'location', 'author', 'parent_id', 'category']);
        $validator = Validator::make($data, [
            'title' => [
                'required',
                'string'
            ],
            'description' => [
                'required',
                'string'
            ],
            'location' => [
                'required',
                'string'
            ]
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()
                    ->toArray()
            ], Response::HTTP_BAD_REQUEST);
        }        
        $post = Post::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'location' => $data['location'],
            'parent_id' => $data['parent_id'],
            'category' => $data['category'],
            'author' => auth('api')->user()->id,
        ]);
        return response()->json($post, 200);
    }

    public function update(Request $request)
    {
        $data = $request->only(['id', 'title', 'description', 'location', 'author', 'parent_id', 'category']);
        $validator = Validator::make($data, [
            'title' => [
                'required',
                'string'
            ],
            'description' => [
                'required',
                'string'
            ],
            'location' => [
                'required',
                'string'
            ]
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()
                    ->toArray()
            ], Response::HTTP_BAD_REQUEST);
        }        
        $post = Post::find($data['id'])->get()[0];
        $post->fill($request->all());
        $post->save();
        return response()->json($post, 200);
    }

    public function myPosts()
    {
        $posts = Post::where('author', auth('api')->user()->id)
                        ->where('parent_id', null)
                        ->with(['comments'])
                        ->select('id', 'title', 'description', 'location', 'created_at')
                        ->get();
        return response()->json([
            'data' => $posts
        ], 200);
    }

    public function all()
    {
        $posts = Post::where('parent_id', null)->with(['comments', 'user'])->get();
        return response()->json([
            'data' => $posts
        ], 200);
    }

    public function comment(Request $request, $id)
    {
        $data = $request->only(['description', 'parent_id']);
        $validator = Validator::make($data, [
            'description' => [
                'required',
                'string'
            ]
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()
                    ->toArray()
            ], Response::HTTP_BAD_REQUEST);
        }
        $comment = Post::create();
        return response()->json($comment, 200);
    }

}
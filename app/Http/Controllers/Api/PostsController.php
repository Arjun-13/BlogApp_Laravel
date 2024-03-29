<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostsController extends Controller
{
    public function create(Request $request) {
        
        $post = new Post;
        $post->user_id = Auth::user()->id;
        $post->desc = $request->desc;
        
        //check if post has photo
        if($request->photo != '') {
            //choose unique name for photo
            $photo = time().'jpg';
            //need to link storage folder to public
            file_put_contents('storage/posts/'.$photo, base64_decode($request->photo));
            $post->photo = $photo;
        }
        
        $post->save();
        $post->user;
        return response()->json([
            'success' => true,
            'message' => 'posted',
            'post' => $post
        ]);
   
    }
    
    public function update(Request $request) {
        $post = Post::find($request->id);
        //check if the user is editing his own post
        if(Auth::user()->id != $request->id) {
            return response()->json([
                'success' => false,
                'message' => 'unauthorized access'
            ]);
        }
        $post->desc = $request->desc;
        $post->update();
        return response()->json([
            'success' => true,
            'message' => 'post edited'
        ]);
    }
    
    public function delete(Request $request) {
        $post = Post::find($request->id);
        //check if the user is editing his own post
        if(Auth::user()->id != $request->id) {
            return response()->json([
                'success' => false,
                'message' => 'unauthorized access'
            ]);
        }
        
        //check if post have photo to delete
        if($post->photo != '') {
            Storage::delete('public/posts/'.$post->photo);
        }
        $post->delete();
        return response()->json([
            'success' => true,
            'message' => 'post deleted'
        ]);
    }
    
    public function posts() {
        $posts = Post::orderBy('id', 'desc')->get();
        foreach ($posts as $post) {
            //get user of post
            $post->user;
            //comments count
            $post['commentsCount'] = count($post->comments);
            //likes count
            $post['likesCount'] = count($post->likes);
            //check if user like his own post
            $post['selfLike'] = false;
            foreach ($post->likes as $like) {
                if($like->user_id == Auth::user()->id) {
                     $post['selfLike'] = true;
                }
            }
        }
        
        return response()->json([
            'success' => true,
            'posts' => $posts
        ]);
    }
}

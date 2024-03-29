<?php

namespace App\Http\Controllers\Api;

use App\Comment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentsController extends Controller
{
    public function create(Request $request){
        $comment = new Comment;
        $comment->user_id = Auth::user()->id;
        $comment->post_id = $request->id;
        $comment->comment - $request->comment;
        $comment->save();
        
        return response()->json([
            'success' => true,
            'message' => 'comment added'
        ]);
    }
    
    public function update(Request $request) {
        $comment = Comment::find($request->id);
        //check if user is editing his own comment
        if($comment->id != Auth::user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'unauthorized access'
            ]);
        }
        $comment->comment = $request->comment;
        $comment->update();
        
        return response()->json([
            'success' => true,
            'message' => 'comment edited'
        ]);
    }
    
    public function delete(Request $request) {
        $comment = Comment::find($request->id);
        //check if user is deleting his own comment
        if($comment->id != Auth::user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'unauthorized access'
            ]);
        }
        
        $comment->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'comment deleted'
        ]);
    }
    
    public function comments(Request $request) {
        $comments = Comment::where('post_id', $request->id).get();
        
        foreach ($comments as $comment){
            $comment->user;
        }
        
        return response()->json([
            'success' => true,
            'comments' => $comments
        ]);
    }
}

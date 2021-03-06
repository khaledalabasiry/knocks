<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\obj;
use App\Comment;
use App\Reply;

class CommentController extends Controller
{
    public function create(Request $request){
      $comment = new Comment();

      $comment->initialize(json_encode($request->submit_object));
      return 'done';
    }
    public function retrive(Request $request){
    $request->validate([
      'comment'=>'required'
    ]);

    $comment = Comment::find($request->comment);
    if(obj::find($comment->object_id)->isAvailable(auth()->user()->id))
    //if($comment->user_id != auth()->user()->id)return 'invalid';
      return $comment;
      else return 'invalid';

  }
    public function getComments(Request $request){
      $request->validate([
        'knock' => 'required' ,
      ]);
      if($request->max == null){
        if(Knock::find($request->knock)->comments())
          return Knock::find($request->knock)->comments()->get()->pluck('id');
        else return array();
      }else{
        if(Knock::find($request->knock)->comments()->where('id' , '>' , $request->max))
          return Knock::find($request->knock)->comments()->where('id' , '>' , $request->max)->get()->pluck('id');
        else return array();
      }
    }
    public function getReplies(Request $request){
      $request->validate([
        'knock' => 'required' ,
      ]);
      if($request->max == null){
        $replies = Comment::find($request->knock)->commentReplies();
        if($replies != null)
          return $replies->pluck('id');
        else return array();
      }else{
        $replies = Comment::find($request->knock)->commentReplies()->where('id' , '>' , $request->max);
        if($replies)
          return $replies->pluck('id');
        else return array();
      }
    }

}

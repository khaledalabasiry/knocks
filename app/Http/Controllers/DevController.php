<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\obj;
use App\Reaction;
use App\Ballon;
use App\Circle;
use App\Language;
use App\Comment;
use App\Reply;
use App\Knock;
use App\Blob;
use App\Privacy_preset;

class DevController extends Controller
{
    public function resetKnocks(Request $request){
    	//Delet Ballons & their Objects
    	Ballon::truncate();
    	//Delete Reactions & their Objects
    	Reaction::truncate();
    	//Delete Replies & their Objects
    	$reply = Reply::all();
    	foreach($reply as $rep)
    		$rep->delete();
    	//Delete Comments & their Objects
    	$comment = Comment::all();
    	foreach($comment as $rep)
    		$rep->delete();
    	//Delete Blobs & their Objects
    	Blob::truncate();
    	//Delete Knocks & their Objects
    	$knock = Knock::all();
    	foreach($knock as $rep)
    		$rep->delete();

    	return 'done';


    }

    public function resetUsers(Request $request){
    	//Delet Ballons & their Objects
    	Ballon::truncate();
    	//Delete Reactions & their Objects
    	Reaction::truncate();
    	//Delete Replies & their Objects
    	$reply = Reply::all();
    	foreach($reply as $rep)
    		$rep->delete();
    	//Delete Comments & their Objects
    	$comment = Comment::all();
    	foreach($comment as $rep)
    		$rep->delete();
    	//Delete Blobs & their Objects
    	Blob::truncate();
    	//Delete Knocks & their Objects
    	$knock = Knock::all();
    	foreach($knock as $rep)
    		$rep->delete();

    	$object = obj::all();
    	foreach($object as $rep)
    		$rep->delete();

    	$user = User::all();
    	foreach($user as $rep)
    		$rep->delete();

    	return 'done';


    }

    public function reinstall(Request $request){
        $v = new Privacy_preset();
        $v->name = 'valid';
        $v->save();

        $v = new Privacy_preset();
        $v->name = 'invalid';
        $v->save();

        $v = new Privacy_preset();
        $v->name = 'invalid_for_all';
        $v->save();

        $v = new Language();
        $v->name = 'en';
        $v->alignment = 'left';
        $v->font_family = 'titillium';
        $v->display_name = 'English';
        $v->save();

        $v = new Language();
        $v->name = 'ar-sa';
        $v->alignment = 'right';
        $v->font_family = 'cairo';
        $v->display_name = 'لاعربيه';
        $v->save();

        return 'done';
    }
}

?>

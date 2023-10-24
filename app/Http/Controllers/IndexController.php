<?php

namespace App\Http\Controllers;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function index(){
        $feedbacks = Feedback::paginate(4);
        return View::make('index')->with(['feedbacks' => $feedbacks]);
    }

    public function getFeedback($id){
        if(Auth::check()){
            $feedback = Feedback::with(['comments','votes' => function ($query) {
                $query->where('user_id', Auth::user()->id);
            }])->find($id);
        }
        else{
            $feedback = Feedback::with('comments')->find($id);
        }
        
        return View::make('feedbackDetail')->with(['feedback' => $feedback]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('addFeedback');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $requested_data = $request->data;
        $data = array();
        parse_str($requested_data, $data);
        try{
            if ($data['category'] == 'new') {
                $category = new Category;
                $category->name = $data['new_category'];
                $category->slug = Str::slug($data['new_category']);
                $category->save();
                $selectedCategory = $category->id;
            } else {
                $selectedCategory = (int)$data['category'];
            }
            $feedback = new Feedback;
            $feedback->title = $data['title'];
            $feedback->category_id = $selectedCategory;
            $feedback->description = $data['description'];
            $feedback->user_id = Auth::user()->id;
            $feedback->save();
            return response()->json([
                'message' => "Feedback has been added successfully."
            ], 200);
        }catch (\Exception $e) {
            \Log::error($e);
            return response()->json([
                'message' => "Sorry! Something went wrong."
            ], 422);
        }
       

    }
    public function getFeedback(Request $request){
        if ($request->ajax()) {
            $data =  Feedback::join('users', 'feedbacks.user_id', '=', 'users.id')
            ->join('categories', 'feedbacks.category_id', '=', 'categories.id')
            ->select('feedbacks.*', 'users.name as username', 'categories.name as category_name')
            ->get();

            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
    
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editFeedback">Edit</a>';
    
                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteFeedback">Delete</a>';
    
                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view("dashboard.feedback");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function show(Feedback $feedback)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $feedback = Feedback::find($id);
        return view("dashboard.update-feedback")->with(['feedback' => $feedback]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Feedback $feedback)
    {
        try{
            if ($request->category == 'new') {
                $category = new Category;
                $category->name = $request->new_category;
                $category->slug = Str::slug($request->new_category);
                $category->save();
                $selectedCategory = $category->id;
            } else {
                $selectedCategory = (int)$request->category;
            }
            if($request->has('is_commentable')){
                $is_commentable = 1;
            }else{
                $is_commentable = 0;
            }
            DB::table("feedbacks")->where('id', $request->id)->update(['title' => $request->title, 'description'=>$request->description, 'category_id'=>$selectedCategory,'is_commentable'=>$is_commentable]);
            return redirect()->back()->with('success', 'Feedback has beed updated successfully.');
        }catch (\Exception $e) {
            \Log::error($e);
            return redirect()->back()->with('error', 'Sorry! Something went wrong.');

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Feedback::find($id)->delete();
        if($user){
            return response()->json([
                'message' => "feedBack has been deleted successfully."
            ], 200);
        }
    }
}

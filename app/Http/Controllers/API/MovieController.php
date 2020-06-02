<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Movie;
use File;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $movies = Movie::orderBy('id', 'desc')->paginate(25);
        return response()->json($movies);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'year' => 'required',
            'banner' => 'required',
            'cover' => 'required',
            'vote' => 'required',
            'view' => 'required',
        ]);
        $banner = $request->file('banner');

        $banner_name = rand() . '.' . $banner->getClientOriginalExtension();
        $banner->move(public_path('images/banner'), $banner_name);

        $cover = $request->file('cover');
        $cover_name = rand() . '.' . $cover->getClientOriginalExtension();
        $cover->move(public_path('images/cover'), $cover_name);

        $form_data = array(
            'name'       =>   $request->name,
            'year'       =>   $request->year,
            'banner'       =>   $banner_name,
            'cover'       =>   $cover_name,
            'vote'       =>   $request->vote,
            'view'       =>   $request->view,
        );

        Movie::create($form_data);
        return response()->json('successfully added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $banner_name = $request->previous_banner;
        $cover_name = $request->previous_cover;

        $this->validate($request, [
            'name' => 'required',
            'year' => 'required',
            'vote' => 'required',
            'view' => 'required',
        ]);

        $banner = $request->file('banner');
        if($banner != '')
        {
            $banner_path = "images/banner/".$banner_name;  // delete previous image
            if(File::exists($banner_path)) {
                File::delete($banner_path);
            }
            $banner_name = rand() . '.' . $banner->getClientOriginalExtension();
            $banner->move(public_path('images/banner'), $banner_name);
        }

        $cover = $request->file('cover');
        if($cover != '')
        {
            $cover_path = "images/cover/".$cover_name;  // delete previous image
            if(File::exists($cover_path)) {
                File::delete($cover_path);
            }
            $cover_name = rand() . '.' . $cover->getClientOriginalExtension();
            $cover->move(public_path('images/cover'), $cover_name);
        }


        $form_data = array(
            'name'       =>   $request->name,
            'year'       =>   $request->year,
            'banner'       =>   $banner_name,
            'cover'       =>   $cover_name,
            'vote'       =>   $request->vote,
            'view'       =>   $request->view,
        );

        Movie::whereId($id)->update($form_data);
        return response()->json('successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $movie = Movie::find($id);
        $movie->delete();
        return response()->json('successfully deleted');
    }

    public function getMovies()
    {
        $movies = Movie::orderByRaw('RAND()')->take(2)->get();
        return response()->json($movies);
    }

    public function rateMovie(Request $request)
    {

        Movie::find($request->viewed)->increment('view');
        Movie::find($request->rated)->increment('view');
        Movie::find($request->rated)->increment('vote');

     return response()->json('successfully rated');
 }
}

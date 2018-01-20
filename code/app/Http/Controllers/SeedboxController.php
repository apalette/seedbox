<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Image;
use Auth;

use App\Models\Tvshow;
use App\Models\Season;
use App\Models\Episode;
use App\Models\Download;
use App\Models\Movie;

class SeedboxController extends Controller

{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
	
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	return view('home', [
        	'downloads' => Download::orderBy('created_at', 'DESC')->limit(12)->get(),
        	'tvshows' => Tvshow::orderBy('created_at', 'DESC')->limit(12)->get(),
        	'movies' => Movie::where('type', 1)->orderBy('created_at', 'DESC')->limit(12)->get(),
        	'cartoons' => Movie::where('type', 3)->orderBy('created_at', 'DESC')->limit(12)->get()
        ]);
    }
	
	/**
     * Show the form to add a file.
     *
     * @return \Illuminate\Http\Response
     */
    public function fileCreate()
    {
        return view('file-create', [
        	'tvshows' => Tvshow::orderBy('name', 'ASC')->get()
        ]);
    }
	
	/**
     * Save a movie.
     *
     * @return \Illuminate\Http\Response
     */
    protected function movieCreateSave(Request $request, $type)
	{
		$this->validate($request, [
			'movie_name' => 'required',
			'download_url' => 'required|url'
		]);
		
		$path = ($type == 1) ? 'films' : 'anime';
		
		// Create Movie
		$tag = str_slug($request->movie_name);
		$movie = new Movie;
		$movie->type = $type;
		$movie->name = $request->movie_name;
		
		// Save Movie Picture
		if ($request->movie_poster && (filter_var($request->movie_poster, FILTER_VALIDATE_URL) !== false)) {
			try 
			{
			    $image = Image::make($request->movie_poster);
				$image->fit(182, 268);
				if ($image->save(base_path().'/public/app/images/posters/'.$path.'/'.$tag.'.jpg', 80)) {
					$movie->poster = 1;
				}
				
			}
			catch(Exception $e)
			{
			}
		}
		
		// Save download
		$download = new Download;
		$download->url = $request->download_url;
		$download->destination = $path.'/'.$tag;
		$download->user_id = Auth::user()->id;
		$download->save();
		
		// Save Movie
		$movie->download_id = $download->id;
		$movie->save();
		
		return redirect()->back()->with('message', 'The '.($type == 1 ? 'movie' : 'cartoon').' has been saved!');
	}
	
	/**
     * Save a tv show.
     *
     * @return \Illuminate\Http\Response
     */
    protected function tvShowCreateSave(Request $request)
	{
		$this->validate($request, [
			'season_number' => 'required|integer|min:1|max:50',
			'episode_number' => 'required|integer|min:1|max:150',
			'episode_name' => 'required',
			'download_url' => 'required|url'
		]);
		
		// Save Tvshow
		if ($request->tvshow_new == 0) {
			$this->validate($request, [
				'tvshow_name' => 'required'
			]);
			
			$tag = str_slug($request->tvshow_name);
			$tvshow = Tvshow::where('tag', $tag)->first();
			if (! $tvshow) {
				$tvshow = new Tvshow;
				$tvshow->tag = $tag;
				$tvshow->name = $request->tvshow_name;
				$tvshow->save();
			}
		}
		else {
			$tvshow = Tvshow::where('id', $request->tvshow_new)->first();
			if (! $tvshow) {
				return redirect()->back()->withErrors(['The tv show doesn\'t exist'])->withInput();
			}
		}
		
		// Save Season
		if (! $tvshow->seasons->contains('number', $request->season_number)) {
			$season = new Season;
			$season->number = $request->season_number;
			$tvshow->seasons()->save($season);
		}
		else {
			$season = $tvshow->seasons()->where('number', $request->season_number)->first();
		}
		
		// Save Season Picture
		if ($season->poster == 0) {
			if ($request->season_poster && (filter_var($request->season_poster, FILTER_VALIDATE_URL) !== false)) {
				try 
				{
				    $image = Image::make($request->season_poster);
					$image->fit(182, 268);
					if ($image->save(base_path().'/public/app/images/posters/series/'.$tvshow->tag.'-'.str_pad($season->number, 2, '0', STR_PAD_LEFT).'.jpg', 80)) {
						$season->poster = 1;
						$season->save();
					}
					
				}
				catch(Exception $e)
				{
				}
			}
		}
		
		// Check if episode already exists
		if ($season->episodes->contains('number', $request->episode_number)) {
			return redirect()->back()->withErrors(['The episode already exists'])->withInput();
		}
		
		// Save download
		$season_path = str_pad($season->number, 2, '0', STR_PAD_LEFT);
		$download = new Download;
		$download->url = $request->download_url;
		$download->destination = 'series/'.$tvshow->tag.'/'.'season'.$season_path.'/'.$tvshow->tag.'.'.$season_path.'x'.str_pad($request->episode_number, 2, '0', STR_PAD_LEFT);
		$download->user_id = Auth::user()->id;
		$download->save();
		
		// Save Episode
		$episode = new Episode;
		$episode->number = $request->episode_number;
		$episode->name = $request->episode_name;
		$episode->download_id = $download->id;
		$season->episodes()->save($episode);
		
		return redirect()->back()->with('message', 'The episode has been saved!');
	}
	
	/**
     * Save a file.
     *
     * @return \Illuminate\Http\Response
     */
    public function fileCreateSave(Request $request)
    {
    	/*
		 * Validate category
		 * 2 = tv show
		 */
    	$this->validate($request, [
			'category' => 'required|in:1,2,3'
		]);
		
    	switch ($request->category) {
    		case 1:
				return $this->movieCreateSave($request, 1);
			case 2:
				return $this->tvShowCreateSave($request);
			case 3:
				return $this->movieCreateSave($request, 3);
    	}
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Tvshow;
use App\Models\Season;
use App\Models\Episode;
use App\Models\Download;

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
        //return view('home');
        return redirect('/file-create');
    }
	
	/**
     * Show the form to add a file.
     *
     * @return \Illuminate\Http\Response
     */
    public function fileCreate()
    {
        return view('file-create');
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
			'episode_number' => 'required|integer|min:1|max:50',
			'episode_name' => 'required',
			'download_url' => 'required|url'
		]);
		
		// Save Tvshow
		if ($request->tv_item_id == 0) {
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
			//$tvshow
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
		
		// Check if episode already exists
		if ($season->episodes->contains('number', $request->episode_number)) {
			return redirect()->back()->withErrors(['The episode already exists'])->withInput();
		}
		
		// Save download
		$download = new Download;
		$download->url = $request->download_url;
		$download->destination = 'series/'.$tvshow->tag.'/season'.str_pad($season->number, 2, '0', STR_PAD_LEFT).'/'.str_pad($request->episode_number, 2, '0', STR_PAD_LEFT);
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
			'category' => 'required|in:2'
		]);
		
    	switch ($request->category) {
			case 2:
				return $this->tvShowCreateSave($request);
    	}
    }
}
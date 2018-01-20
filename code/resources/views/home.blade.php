@extends('layouts.app')

@section('content')
<div class="container">
	<div class="text-right">
		<a class="btn btn-primary" href="{{url('/file-create')}}">Add a file</a>
	</div>
	@if ($downloads->count() == 0)
	<h2>No file</h2>
	@else
	<h2>Recently added</h2>
	<ul class="list-unstyled list-inline">
		@foreach ($downloads as $download)
		<li class="app-item">
			@if ($download->episode)
			@if ($download->episode->season->poster == 0)
			<div class="line no-poster"><span class="glyphicon glyphicon-film"></span></div>
			@else
			<div class="line"><img src="{{url('app/images/posters/series/')}}/{{$download->episode->season->tvshow->tag}}-{{str_pad($download->episode->season->number, 2, '0', STR_PAD_LEFT)}}.jpg" class="img-responsive" /></div>
			@endif
			<h3 class="line">{{$download->episode->season->tvshow->name}}</h3>
			<h4 class="line">S{{str_pad($download->episode->season->number, 2, '0', STR_PAD_LEFT)}}E{{str_pad($download->episode->number, 2, '0', STR_PAD_LEFT)}}</h4>
			<div class="small line">By {{$download->user->first_name}}</div>
			<div class="line">
				<span class="label label-danger">TV show</span>&nbsp;
				@if ($download->upload_status == 0)
				<span class="label label-warning">Pending</span>
				@elseif ($download->upload_status == 1)
				<span class="label label-info">Downloading...</span>
				@elseif ($download->upload_status == 2)
				<span class="label label-success">Available</span>
				@endif
			</div>
			@else
			@if ($download->movie->poster == 0)
			<div class="line no-poster"><span class="glyphicon glyphicon-film"></span></div>
			@else
			<div class="line"><img src="{{url('app/images/posters/'.($download->movie->type == 1 ? 'films' : 'anime').'/')}}/{{str_slug($download->movie->name)}}.jpg" class="img-responsive" /></div>
			@endif
			<h3 class="line">{{$download->movie->name}}</h3>
			<div class="small line">By {{$download->user->first_name}}</div>
			<div class="line">
				@if ($download->movie->type == 1)
				<span class="label label-warning">Movie</span>&nbsp;
				@else
				<span class="label label-info">Cartoon</span>&nbsp;
				@endif
				@if ($download->upload_status == 0)
				<span class="label label-warning">Pending</span>
				@elseif ($download->upload_status == 1)
				<span class="label label-info">Downloading...</span>
				@elseif ($download->upload_status == 2)
				<span class="label label-success">Available</span>
				@endif
			</div>
			@endif
		</li>
		@endforeach
	</ul>
	@endif
	
	@if ($tvshows->count() > 0)
	<h2>My TV shows</h2>
	<ul class="list-unstyled list-inline app-list">
		@foreach ($tvshows as $tvshow)
		@php
		$season = $tvshow->seasons()->orderby('number','DESC')->first();
		$episode = $season ? $season->episodes()->orderby('number','DESC')->first() : null;
    	@endphp
    	@if ($episode)
		<li class="app-item">
			@if ($season->poster == 0)
			<div class="line no-poster"><span class="glyphicon glyphicon-film"></span></div>
			@else
			<div class="line"><img src="{{url('app/images/posters/series/')}}/{{$tvshow->tag}}-{{str_pad($season->number, 2, '0', STR_PAD_LEFT)}}.jpg" class="img-responsive" /></div>
			@endif
			<h3 class="line">{{$tvshow->name}}</h3>
			<h4 class="line">S{{str_pad($season->number, 2, '0', STR_PAD_LEFT)}}E{{str_pad($episode->number, 2, '0', STR_PAD_LEFT)}}</h4>
			<div class="small line">By {{$episode->download->user->first_name}}</div>
			<div class="line">
				<span class="label label-danger">TV show</span>&nbsp;
				@if ($episode->download->upload_status == 0)
				<span class="label label-warning">Pending</span>
				@elseif ($episode->download->upload_status == 1)
				<span class="label label-info">Downloading...</span>
				@elseif ($episode->download->upload_status == 2)
				<span class="label label-success">Available</span>
				@endif
			</div>
		</li>
		@endif
		@endforeach
	</ul>
	@endif
	
	@if ($movies->count() > 0)
	<h2>My movies</h2>
	<ul class="list-unstyled list-inline">
		@foreach ($movies as $movie)
		<li class="app-item">
			@if ($movie->poster == 0)
			<div class="line no-poster"><span class="glyphicon glyphicon-film"></span></div>
			@else
			<div class="line"><img src="{{url('app/images/posters/films/')}}/{{str_slug($movie->name)}}.jpg" class="img-responsive" /></div>
			@endif
			<h3 class="line">{{$movie->name}}</h3>
			<div class="small line">By {{$movie->download->user->first_name}}</div>
			<div class="line">
				<span class="label label-warning">Movie</span>
				@if ($movie->download->upload_status == 0)
				<span class="label label-warning">Pending</span>
				@elseif ($movie->download->upload_status == 1)
				<span class="label label-info">Downloading...</span>
				@elseif ($movie->download->upload_status == 2)
				<span class="label label-success">Available</span>
				@endif
			</div>
		</li>
		@endforeach
	</ul>
	@endif
	
	@if ($cartoons->count() > 0)
	<h2>My cartoons</h2>
	<ul class="list-unstyled list-inline">
		@foreach ($cartoons as $cartoon)
		<li class="app-item">
			@if ($cartoon->poster == 0)
			<div class="line no-poster"><span class="glyphicon glyphicon-film"></span></div>
			@else
			<div class="line"><img src="{{url('app/images/posters/anime/')}}/{{str_slug($cartoon->name)}}.jpg" class="img-responsive" /></div>
			@endif
			<h3 class="line">{{$cartoon->name}}</h3>
			<div class="small line">By {{$cartoon->download->user->first_name}}</div>
			<div class="line">
				<span class="label label-info">Cartoon</span>
				@if ($cartoon->download->upload_status == 0)
				<span class="label label-warning">Pending</span>
				@elseif ($cartoon->download->upload_status == 1)
				<span class="label label-info">Downloading...</span>
				@elseif ($cartoon->download->upload_status == 2)
				<span class="label label-success">Available</span>
				@endif
			</div>
		</li>
		@endforeach
	</ul>
	@endif
</div>
@endsection

@section('js')
<script>
setInterval(function() {
	window.location.reload();
}, 30000); 	
</script>
@endsection

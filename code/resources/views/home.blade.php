@extends('layouts.app')

@section('content')
<div class="container">
	<div class="text-right">
		<a class="btn btn-primary" href="{{url('/file-create')}}">Add a file</a>
	</div>
	@if ($recents->count() == 0)
	<h2>No file</h2>
	@else
	<h2>Recently added</h2>
	<ul class="list-unstyled list-inline">
		@foreach ($recents as $recent)
		<li class="app-item">
			@if ($recent->season->poster == 0)
			<div class="line no-poster"><span class="glyphicon glyphicon-film"></span></div>
			@else
			<div class="line"><img src="{{url('app/images/posters/series/')}}/{{$recent->season->tvshow->tag}}-{{str_pad($recent->season->number, 2, '0', STR_PAD_LEFT)}}.jpg" class="img-responsive" /></div>
			@endif
			<h3 class="line">{{$recent->season->tvshow->name}}</h3>
			<h4 class="line">S{{str_pad($recent->season->number, 2, '0', STR_PAD_LEFT)}}E{{str_pad($recent->number, 2, '0', STR_PAD_LEFT)}}</h4>
			<div class="small line">By {{$recent->download->user->first_name}}</div>
			<div class="line">
				<span class="label label-danger">TV show</span>&nbsp;
				@if ($recent->download->upload_status == 0)
				<span class="label label-warning">Pending</span>
				@elseif ($recent->download->upload_status == 1)
				<span class="label label-info">Downloading...</span>
				@elseif ($recent->download->upload_status == 2)
				<span class="label label-success">Available</span>
				@endif
			</div>
		</li>
		@if (1 == 2)
		<li class="app-item">
			<div class="line"><img src="https://images-na.ssl-images-amazon.com/images/M/MV5BNzI5MzM3MzkxNF5BMl5BanBnXkFtZTgwOTkyMjI4MTI@._V1_UX182_CR0,0,182,268_AL_.jpg" class="img-responsive" /></div>
			<h3 class="line">Alien Covenant (2017)</h3>
			<div class="small line">By username</div>
			<div class="line"><span class="label label-warning">Movie</span> <span class="label label-info">In progress</span></div>
		</li>
		<li class="app-item">
			<div class="line"><img src="https://images-na.ssl-images-amazon.com/images/M/MV5BMTQ4MjA3MzUwOF5BMl5BanBnXkFtZTgwNDU1MjMxNjE@._V1_UY268_CR4,0,182,268_AL_.jpg" class="img-responsive" /></div>
			<h3 class="line">Zoo</h3>
			<h4 class="line">S03E06</h4>
			<div class="small line">By username</div>
			<div class="line"><span class="label label-danger">TV show</span> <span class="label label-success">Completed</span></div>
		</li>
		<li class="app-item">
			<div class="line"><img src="https://dg31sz3gwrwan.cloudfront.net/poster/326887/1198331-4-optimized.jpg" class="img-responsive" /></div>
			<h3 class="line">The mist</h3>
			<h4 class="line">S01E07</h4>
			<div class="small line">By username</div>
			<div class="line"><span class="label label-danger">TV show</span> <span class="label label-success">Completed</span></div>
		</li>
		@endif
		@endforeach
	</ul>
	@endif
	@if (1 == 2)
	<h2>My movies</h2>
	<ul class="list-unstyled list-inline">
		<li class="app-item">
			<div class="line"><img src="https://images-na.ssl-images-amazon.com/images/M/MV5BNzI5MzM3MzkxNF5BMl5BanBnXkFtZTgwOTkyMjI4MTI@._V1_UX182_CR0,0,182,268_AL_.jpg" class="img-responsive" /></div>
			<h3 class="line">Alien Covenant (2017)</h3>
			<div class="small line">By username</div>
			<div class="line"><span class="label label-warning">Movie</span> <span class="label label-info">In progress</span></div>
		</li>
	</ul>
	@endif
	
	@if ($tvshows->count() > 0)
	<h2>My TV shows</h2>
	<ul class="list-unstyled list-inline app-list">
		@if (1 == 2)
		<li class="app-item">
			<div class="line"><img src="https://images-na.ssl-images-amazon.com/images/M/MV5BMTQ4MjA3MzUwOF5BMl5BanBnXkFtZTgwNDU1MjMxNjE@._V1_UY268_CR4,0,182,268_AL_.jpg" class="img-responsive" /></div>
			<h3 class="line">Zoo</h3>
			<h4 class="line">S03E06</h4>
			<div class="small line">By username</div>
			<div class="line"><span class="label label-danger">TV show</span> <span class="label label-success">Completed</span></div>
		</li>
		<li class="app-item">
			<div class="line"><img src="https://dg31sz3gwrwan.cloudfront.net/poster/326887/1198331-4-optimized.jpg" class="img-responsive" /></div>
			<h3 class="line">The mist</h3>
			<h4 class="line">S01E07</h4>
			<div class="small line">By username</div>
			<div class="line"><span class="label label-danger">TV show</span> <span class="label label-success">Completed</span></div>
		</li>
		@endif
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
</div>
@endsection

@section('js')
<script>
setInterval(function() {
	window.location.reload();
}, 30000); 	
</script>
@endsection

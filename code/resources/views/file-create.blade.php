@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
        	<div class="panel panel-default">
                <div class="panel-heading"><a class="btn btn-default" href="{{url('/')}}"><span class="glyphicon glyphicon-home"></span></a>&nbsp;&nbsp; New file</div>

                <div class="panel-body">
                	@if ($errors->any())
					<div class="alert alert-danger">
						<ul>
						    @foreach ($errors->all() as $error)
						        <li>{{ $error }}</li>
						    @endforeach
						</ul>
					</div>
					@endif
					@if(session()->has('message'))
					<div class="alert alert-success">
						{{ session()->get('message') }}
					</div>
					@endif
                   	<form class="form-wrapper" method="post">
                   		{{Form::token()}}
						<div class="form-group{{ $errors->has('category') ? ' has-error' : '' }}">
					    	<label for="item_type">Category</label>
					    	<select class="form-control" id="item_type" name="category" required>
					    		<option value="" selected disabled>Please select</option>
					    		<option value="1" data-type="movie">Movie</option>
					    		<option value="2" data-type="tv"{{(old('category') == 2) ? ' selected' : ''}}>TV show</option>
					    	</select>
						</div>
						<div class="form-block form-block-type-tv hide">
							<h3>TV show informations</h3>
					  		<div class="form-group">
					    		<label for="tv_item_id">Name</label>
					    		<select class="form-control" id="tv_item_id" name="tvshow_new">
					    			@if ($tvshows->count() > 0)
					    			<option value="" selected disabled>Please select</option>
					    			@foreach ($tvshows as $tvshow)
						    		<option value="{{$tvshow->id}}"{{(old('tvshow_new') == $tvshow->id) ? ' selected' : ''}}>{{$tvshow->name}}</option>
						    		@endforeach
						    		<optgroup label="Or ...">
						    			<option value="0"{{(old('tvshow_new') == 0) ? ' selected' : ''}}>Create a new TV show</option>
						    		</optgroup>
					    			@else
						    		<option value="0"{{(old('tvshow_new') == 0) ? ' selected' : ''}}>Create a new TV show</option>
						    		@endif
						    	</select>
						    </div>
						    <div class="form-group hide{{ $errors->has('tvshow_name') ? ' has-error' : '' }}" id="tv_item_name_block">
						    	<input type="text" name="tvshow_name" class="form-control" id="tv_name" placeholder="Enter TV show name" value="{{old('tvshow_name')}}" />
					    	</div>
							<div class="row">
								<div class="col-xs-12 col-sm-5">
									<div class="form-group{{ $errors->has('season_number') ? ' has-error' : '' }}">
										<label for="season-num">Season</label>
										<div class="input-group">
											<span class="input-group-addon"><i class="small">Num :</i></span>
											<input type="number" name="season_number" class="form-control" id="season-num" min="1" max="50" placeholder="Enter season number" value="{{old('season_number')}}">
										</div>
									</div>
									<div class="form-group{{ $errors->has('season_poster') ? ' has-error' : '' }}">
										<input type="url" name="season_poster" class="form-control" id="season-poster" placeholder="Enter season poster url" value="{{old('season_poster')}}">
									</div>
								</div>
								<div class="col-xs-12 col-sm-6 col-sm-offset-1">
									<div class="form-group{{ $errors->has('episode_number') ? ' has-error' : '' }}">
										<label for="episode-num">Episode</label>
										<div class="input-group">
											<span class="input-group-addon"><i class="small">Num :</i></span>
											<input type="number" name="episode_number" class="form-control span8" id="episode-num" min="1" max="150" placeholder="Enter episode number" value="{{old('episode_number')}}">
										</div>
									</div>
									<div class="form-group{{ $errors->has('episode_name') ? ' has-error' : '' }}">
										<input type="text" class="form-control" id="tv_item_name" name="episode_name" placeholder="Enter episode name" value="{{old('episode_name')}}">
									</div>
								</div>
							</div>
						</div>
						<div class="form-block form-block-type-movie hide">
							<h3>Movie informations</h3>
						    <div class="form-group">
						    	<label for="movie_item_name">Name</label>
						    	<input type="text" class="form-control" id="movie_item_name" name="movie_name" placeholder="Enter movie name" value="{{old('movie_name')}}" />
					    	</div>
							<div class="form-group">
								<label for="movie_item_poster">Poster url</label>
						    	<input type="url" class="form-control" id="movie_item_poster" placeholder="Enter movie poster url" name="movie_poster" value="{{old('movie_poster')}}">
							</div>
						</div>
						<div class="form-group{{ $errors->has('download_url') ? ' has-error' : '' }}">
					    	<label for="item_url">File url</label>
					    	<input class="form-control" id="item_url" name="download_url" type="url" placeholder="Enter video url" value="{{old('download_url')}}" required />
						</div>
						<div class="form-group text-right">
							<input type="submit" class="btn btn-primary" value="Add" />
						</div>
					</form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
	$(document).ready(function(){
		$('#item_type').on('change', function(){
			var t = $(this).find('option:selected').data('type');
			$('.form-block').addClass('hide');
			$('.form-block input, .form-block select').removeAttr('required').removeAttr('pattern');
			$('.form-block-type-'+t).removeClass('hide');
			$('.form-block-type-'+t+' input, .form-block-type-'+t+' select').prop('required', true).prop('pattern','.*[^ ].*');
			$('#season-poster').removeAttr('required');
			if (t == 'tv') {
				$('#tv_item_id').trigger('change');
			}
		});
		
		$('#tv_item_id').on('change', function(){
			var v = $(this).val();
			$('#tv_item_name_block').toggleClass('hide', ! (v == 0));
			if (v == 0) {
				$('#tv_name').prop('required', true).prop('pattern','.*[^ ].*');
			}
			else {
				$('#tv_name').removeAttr('required').removeAttr('pattern');
			}
		});
		
		$('#item_type').trigger('change');
	});
</script>
@endsection

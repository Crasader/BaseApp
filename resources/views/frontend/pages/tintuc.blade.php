@extends('frontend.common')

@section('customstyle')
@endsection

@section('content')
<div class="blockContent">
	@if(count($news))
	@foreach($news as $post)
    	<div class="blogItem normalCat">
    		<a class="cover" href="{{ url('tin-tuc').'/'.$post->url_key }}">
    			<img src="http://127.0.0.1/laravelfull-nghia/public/assets/images/article.jpg" alt="">
    		</a>
	      	<div class="detail">
	      		<a class="title" href="{{ url('tin-tuc').'/'.$post->url_key }}">{{ $post->title }}</a>
	        	<div class="info-meta">
	        		<span class="info-inline"><i class="fa fa-clock-o"></i>{{ $post->created_at->format('H:i') }}</span>
	        		<span class="info-inline"><i class="fa fa-calendar-o"></i>{{ $post->created_at->format('d/m/Y') }}</span>
	        	</div>
	        	<p>{{ $post->short_description }}</p>
	        	<a href="{{ url('tin-tuc').'/'.$post->url_key }}" class="btn btn-viewmore">Xem chi tiết</a>
	      	</div>
	    </div>
	@endforeach
    {{ $news->links() }}
    @endif
</div>
@endsection


@section('js-footer')
@endsection
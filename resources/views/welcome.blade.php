{{-- resouces/views/welcome.blade.php --}}
@extends('layouts.default')

@section('content')
<div class="flex-center position-ref full-height">
	<div class="content">
		<div class="title m-b-md">
			JkkCrawler
		</div>

		<div class="links">
			<a href="{{ url('/docs') }}">Documentation</a>
			<a href="{{ url('/news') }}">News</a>
			<a href="{{ url('/list') }}">List</a>
		</div>
	</div>
</div>
@endsection

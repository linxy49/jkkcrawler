{{-- resouces/views/welcome.blade.php --}}
@extends('layouts.default')

@section('content')
<div class="flex-center position-ref full-height">
	<div class="content">
		<div class="title m-b-md">
			JkkCrawler
		</div>

		<div class="links">
			<a href="{{ url('/all') }}">JKK東京の物件一覧</a>
			<a href="{{ url('/news') }}">最近の物件更新</a>
			<a href="{{ url('/list') }}">空室物件・募集中戸数</a>
		</div>
	</div>
</div>
@endsection

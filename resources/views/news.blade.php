{{-- resouces/views/news.blade.php --}}
@extends('layouts.default')

@section('content')
<div class="flex-center position-ref full-height">
	<div class="content">
		<div class="title m-b-md">
			News
		</div>
		@if (count($recent) > 0)
		<div class="table-responsive">
			<table class="table">
				<tr>
					<th>市区部</th><th>住宅名</th><th>間取り</th><th>床面積[m2]</th><th>家賃[円]</th><th>共益費[円]</th><th>募集戸数</th><th>変更日時</th>
				</tr>
				@foreach ($recent as $index => $data)
				@if ($index%2 == 0)
					<tr class="info">
				@else
					<tr>
				@endif
					<td>{{$data->sikubu}}</td>
					<td>{{$data->name}}</td>
					<td>{{$data->madori}}</td>
					<td>{{$data->yukamenseki}}</td>
					<td>{{$data->yachin}}</td>
					<td>{{$data->kyoekihi}}</td>
					<td>{{$data->kosu}}</td>
					<td>{{$data->kosu}}</td>
					<td>{{$data->updated_at}}</td>
				</tr>
				@endforeach
			</table>
		</div>
		@endif
	</div>
</div>
@endsection

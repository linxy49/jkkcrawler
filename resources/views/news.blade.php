{{-- resouces/views/news.blade.php --}}
@extends('layouts.default')

@section('content')
<div class="container-fluid flex-center position-ref">
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
				@if (intval($data->kosu) > 0)
					<td>+{{$data->kosu}}</td>
				@else
					<td>{{$data->kosu}}</td>
				@endif
				<td>{{$data->updated_at}}</td>
			</tr>
			@endforeach
		</table>
	</div>
	@endif
</div>
@endsection

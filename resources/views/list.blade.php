{{-- resouces/views/list.blade.php --}}
@extends('layouts.default')

@section('content')
<div class="container-fluid flex-center position-ref">
	@if (count($list) > 0)
	<div class="table-responsive">
		<table class="table">
			<tr>
				<th colspan="6">Updated_at&nbsp;:&nbsp;{{$updated_at}}&nbsp;5分間隔</th>
			</tr>
			<tr>
				<th>住宅名</th><th>間取り</th><th>床面積[m2]</th><th>家賃[円]</th><th>共益費[円]</th><th>募集戸数</th>
			</tr>
			@foreach ($list as $index => $data)
			@if ($index%2 == 0)
				<tr class="info">
			@else
				<tr>
			@endif
				<td>{{$data->name}}</td>
				<td>{{$data->madori}}</td>
				<td>{{$data->yukamenseki}}</td>
				<td>{{$data->yachin}}</td>
				<td>{{$data->kyoekihi}}</td>
				<td>{{$data->kosu}}</td>
			</tr>
			@endforeach
		</table>
	</div>
	@endif
</div>
@endsection

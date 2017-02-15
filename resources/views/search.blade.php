{{-- resouces/views/welcome.blade.php --}}
@extends('layouts.default')

@section('content')
<form name="forwardForm" action="https://jhomes.to-kousya.or.jp/search/jkknet/service/akiyaJyoukenStartInit" method="post" target="_self">
	<input type=hidden name='redirect' value='false'>
	<input type=hidden name='url' value='https://jhomes.to-kousya.or.jp/search/jkknet/service/akiyaJyoukenStartInit'>
	<input type=hidden name='link_id' value='01'>
</form>
<center>
	<a href="#" onclick="javascript:openMainWindow(); return false">こちら</a>をクリックしてください。
</center>
<script type="text/javascript">
function openMainWindow()
{
	document.forwardForm.submit();
}
</script>
@endsection

@php global $post @endphp

<div class="container">
<div class="page-header">
  <h1>{!! $title !!}</h1>
  <h3>{{ $_GET["address"] }}</h3>
</div>
<div class="row">
  @php $representatives = App\getDistrict($_GET["address"])  @endphp
 @foreach($representatives as $rep)
  <div class="col-md-6">
    {!! $rep !!}
  </div>
 @endforeach
</div>
</div>
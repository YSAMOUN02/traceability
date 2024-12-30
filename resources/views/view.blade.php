@extends("master")
@section('content')
<div class="title">Rest PO</div>
<table class="table">
  <thead>
    <tr>
      <th scope="col">Item No</th>
      <th>Description</t h>
    </tr>
  </thead>
  <tbody>
    @foreach ($data as $item)
    <tr>
      <td scope="row">{{$item->Item}}</td>
      <td>{{$item->Description}}</td>
    </tr>   
    @endforeach
  
  </tbody>
</table>   
@endsection
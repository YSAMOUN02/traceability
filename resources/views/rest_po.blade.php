@extends('master')
@section('content')
<div class="title">Rest PO</div>
<table class="table">
  <tr>
      <th>PO</th>
      <th>GRN</th>
      <th>Item No</th>
      <th>Variant</th>
      <th>Description</th>
      <th>PO Quantity</th>
      <th>Recieved Qauntity</th>
      <th>Outstanding QTY</th>
      <th>Unit</th>
      <th>Unit Cost</th>
      <th>location</th>
      <th>Due Date</th>
      <th>Expected Receipt Date</th>
      <th>Manufacturer</th>
      <th>GRN Date</th>
      <th>Vendors</th>
      <th>Vendor Name</th>

  </tr>
  @foreach ($rest_po as $item)
  @if ($item->QTY_OUT == 0)
      <tr style="background-color: rgb(0, 126, 0) !important;color:white !important;">
        <td>{{$item->PO}}</td>
        <td>{{$item->GRN}}</td>
        <td>{{$item->item}}</td>
        <td>{{$item->variant}}</td>
        <td>{{$item->Description}}</td>
        <td>{{$item->PO_QTY}}</td>
        <td>{{$item->GRN_QTY}}</td>
        @if ($item->QTY_OUT == 0)  
          <td>0</td>
        @else
          {{$item->QTY_OUT}}
        @endif
        <td>{{$item->UOM}}</td>
        <td>{{$item->UNIT_COST}}</td>
        <td>{{$item->LOCATION}}</td>
        <td>{{$item->DUE}}</td>
        <td>{{$item->EXPECTED}}</td>
        <td>{{$item->Manufacturer}}</td>
        <td>{{$item->GRNDATE}}</td>
        <td>{{$item->Vendors}}</td>
        <td>{{$item->VENDORNAME}}</td>

    </tr>
    @else
    <tr style="background-color: rgb(255, 255, 255) !important;color:black !important;">
      <td>{{$item->PO}}</td>
      <td>{{$item->GRN}}</td>
      <td>{{$item->item}}</td>
      <td>{{$item->variant}}</td>
      <td>{{$item->Description}}</td>
      <td>{{$item->PO_QTY}}</td>
      <td>{{$item->GRN_QTY}}</td>
      <td>{{$item->QTY_OUT}}</td>
      <td>{{$item->UOM}}</td>
      <td>{{$item->UNIT_COST}}</td>
      <td>{{$item->LOCATION}}</td>
      <td>{{$item->DUE}}</td>
      <td>{{$item->EXPECTED}}</td>
      <td>{{$item->Manufacturer}}</td>
      <td>{{$item->GRNDATE}}</td>
      <td>{{$item->Vendors}}</td>
      <td>{{$item->VENDORNAME}}</td>

  </tr>

  @endif
  
  @endforeach
</table>

@endsection
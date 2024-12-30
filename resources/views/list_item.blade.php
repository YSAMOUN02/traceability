@extends("no_nav")

@section('content')
@php
use Carbon\Carbon;
@endphp
    <div class="cont_tainer">
            @if(session('message'))
            <div class="alert alert-danger">
                {{ session('message') }}
            </div>
            @endif
        <div class="search_item">
            <span>Item Found </span>
            <table id="data_table">
                <tr>
                    <th>No</th>
                    <th>Item</th>
                    <th>Variant</th>
                    <th>Description</th>
                    <th>Unit</th>
                    <th>Lot</th>
                    <th>Expire </th>
                    <th>Item tracking Code</th>
                    <th>Action</th>
                </tr>
                @php
                        $no = 0;
                @endphp
                @foreach($data_found as $item)
                @php
                    $no++;
                    if($item->variant == ""){
                        $var = "NA";
                    }else{
                        $var = $item->variant;
                    }
                @endphp
                <tr>
                    <td>{{$no}}</td>
                    <td>{{$item->item}}</td>
                    <td>{{$item->variant}}</td>
                    <td>{{$item->Description}}</td>
                    <td>{{$item->UOM}}</td>
                    <td>{{$item->lot}}</td>
                    <td>
                        @if($item->expire)
                        {{ Carbon::parse($item->expire)->format('Y-m-d') }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td>{{$item->track}}</td>
                    <td><a href="/traceability/search/{{$item->item}}/{{$var}}/{{$item->lot}}/0/0/0/0/0/0"><button class="btn-more">More</button></a></td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
    <a href="/"><button class="back">Back</button></a>
@endsection

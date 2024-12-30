@extends("no_nav")

@section('content')

    @php

    $consumtion_line_no = 0;
    $line_01 = 0;
    $sub_line = 0;

    $old_doc = null;
    $line = 0;
    $adjustment = 0;
    $sale = 0;
    $sale_qty = 0;
    $qty_entry = 0;
    $state = count($data);
    $count_by_location = 0;
    $col = 1;
    $line_01 = 0;
    $line_02 = 0;
    $line_03 = 0;
    $line_04 = 0;
    $line_05 =0;
    $no_row_output = 0;
    // auto increament no of row in sub_output
    $no_ = 0;

    // Count qty of semi item
    $qty_semi = 0;
    $total_consumtion = 0;
    // Initailize Variable for use at sub item
    $p_sale = 0;

    $doc_ = null;

    $qty_sample = 0;
    @endphp

<div id="cont" class="cont">
<div class="resource-bar fade1">
    <div class="stock-bar">
        <span >Product's Info</span>
       <div class="two-ul">
        <ul>
            <li>Item</li>
            <li>Variant</li>
            <li>Description</li>
            <li>Unit</li>
            <li>Lot</li>
        </ul>
        @if($state > 0)
        <ul>
            <li>:&ensp;  {{$data[0]->item}}</li>
            <li>:&ensp;  {{$data[0]->variant}}</li>
            <li>:&ensp;  {{$data[0]->Description}}</li>
            <li>:&ensp;  {{$data[0]->UOM}}</li>
            <li>:&ensp;  {{$data[0]->lot}}</li>
        </ul>
        @endif
       </div>

    </div>
    <div class="stock-bar">
        <span>Product's Entry</span>
        <ul>
            @foreach($data  as $entry)
            @if($entry->entry_type == 2 ||   $entry->entry_type == 3   || $entry->entry_type == 0   ||  $entry->entry_type == 6) 
                <li>
                    @if($entry->entry_type == 0) 
                        [Purchase]
                    @elseif($entry->entry_type == 6) 
                        [Output] 
                    @elseif($entry->entry_type == 3 || $entry->entry_type == 2)
                        [Adjustment]
                    @endif
                    {{$entry->document}} :  {{(float)str_replace(',','',$entry->quantity)}}  {{$entry->UOM}}

                 @if($entry->entry_type == 0)
                    @foreach ($data as $item)
                            @if($entry->document == $item->document and ($item->location == 'RM-SAMPLE' || $item->location == 'PM-SAMPLE'))
                                &ensp; &ensp;Sample : {{(float)str_replace(',','',$item->quantity)}} {{$item->UOM}}
                                        @php
                                    
                                        $qty_sample = $item->quantity;
                                        @endphp
                            @endif
                    @endforeach
                 @endif
                </li>               
                @php
                    $quantity_en = str_replace(',', '', $entry->quantity);
                    if (is_numeric($quantity_en)) {
                        $int = (float)str_replace(',','',$quantity_en);
                        $qty_entry += $int;
                        $qty_entry -= $qty_sample;
                        $qty_sample = 0;                                
                    }
                @endphp
            
            @endif
        @endforeach
        
        @foreach($reclass as $o)
        
                <li>[Reclass]{{$o->document}}: &ensp; {{(float)str_replace(',','',$o->quantity)}} &ensp;{{$o->UOM}}</li>     


                @php
                $quantity_en = str_replace(',', '',$o->quantity);
                if (is_numeric($quantity_en)) {
                    $int = (float)$quantity_en;
                    $qty_entry += $int;

                }
            @endphp
         
        @endforeach
        </ul>
    
      
   
        <div class="qty_entry">
            Total : {{$qty_entry}} @if($state  > 0) {{$data[0]->UOM}} @endif
        </div>
    </div>

</div>
<div class="resource-bar fade1">
    <div class="stock-bar">
        <span>Stock Data</span>
       <img src="/assets/img/9226414.png" alt="">
       @php
       $stock = 0;
       foreach ($stock_onhand_main as $key) {
           $qty_stock = str_replace(',', '', $key->quantity);
                        if (is_numeric($qty_stock)) {
                            $int = (float)$qty_stock;
                            $stock += $int;
                        }



       }
       if($stock > 0 ){
           $uom = $stock_onhand_main[0]->UOM;
       }
       else {
           $uom = '';
       }
        @endphp

       <div class="qty">Total :  {{$stock}}  {{$uom}}</div>
    </div> 
    <div class="stock-bar">
        <span>In Location</span>
        <div class="stock-bar-ul">
            <ul>
                @foreach($stock_onhand_main as $stock)
             
                <li>{{$stock->location}} : {{(float)str_replace(',','',$stock->quantity)}}  {{$stock->UOM}}</li>
                @php
                $count_by_location++;
                @endphp
                @endforeach
                @if($count_by_location == 0 ) 
    
                <li>Stock Onhand 0</li>
                @endif
            </ul>
        </div>
    </div>
</div>

<div class="resource-bar2   fade1">
    <div class="sales-bar">
        <span>Sale Data</span>

        <button onclick=" click_hide_detail()"><i class="fa-solid fa-sort-down"></i></button>
    </div>
  
</div>
<div class="detail">
    <div class="box-detail-sales">
        <table>
            <tr>
                <th>No</th>
                <th>Date</th>
                <th>Document</th>
                <th>Item</th>
                <th>Variant</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Lot</th>
                <th>Location</th>
                <th style="width: 30%">Customer</th>
            </tr>

            @php
            // Initailize for count sub qty Sale
            $sale_qty = 0;
            $return_qty = 0;
            @endphp

            @foreach ($data as $item)
                @if( $item->type == "Sale")
                            @php
                                  $sale++;
                            @endphp
                            <tr>
                                <td>{{$sale}}</td>
                                <td>{{$item->posting_date}}</td>
                                <td>{{$item->document}}</td>
                                <td>{{$item->item}}</td>
                                <td>{{$item->variant}}</td>
                                <td>{{(float)str_replace(',','',$item->quantity)}} </td>  
                                <td>{{$item->UOM}}</td> 
                                <td>{{$item->lot}}</td>
                                <td>{{$item->location}}</td>   
                                <td>{{$item->cusname}}</td>
                            </tr> 
                            @php
                              


                                $quantity = str_replace(',', '', $item->quantity); // Remove comma
                                if (is_numeric($quantity)) {
                                $int = (float)$quantity;
                                $sale_qty += $int;
                                }
                                if($item->quantity > 0){
                                    $quantity = str_replace(',', '', $item->quantity); // Remove comma
                                        if (is_numeric($quantity)) {
                                        $int = (float)$quantity;
                                        $return_qty += $int;
                                        }
                                }
                            @endphp
                @endif
                
            @endforeach
                @if($sale == 0)
                <tr>
                    <td>No Data</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @endif
</table>

</div>
</div>
<div class="total fade1">Total Sold Quantity : {{$sale_qty}} @if($state  > 0) {{$data[0]->UOM}} @if($return_qty > 0 )&ensp;  &ensp;Total Return Quantity: {{$return_qty}} {{$data[0]->UOM}}@endif</div> @endif
{{-- Loop for consumtion --}}
<div class="traceline-box fade1">
    <div class="center-trace">
        <div class="inner-trace">
         
            <div class="item-detail">
                <div class="empazie">
                    Consumtion
                  
                </div>
                <table >
                    <tr>
                        <th>No</th>
                        <th>Posting Date</th>
                        <th>Document</th>
                        <th>Item No</th>
                        <th>Variant</th>
                        <th>Lot No</th>
                        <th>Quantity</th>
                        <th>Unit</th>  
                        <th>Location</th> 
                        <th>Action</th>                             
                    </tr>
                    {{-- $old_doc = $item->document; --}}
                    @foreach ($data as $item)
                        @if($item->entry_type == 5 && $item->document != $old_doc)
                            @php
                                    // Query Component In ERP
                                    $component = DB::table('dbo.ITEM LEDGER ENTRY  SUM CONSUMTION')
                                    ->where("document", $item->document)
                                    ->where("lot", $item->lot)
                                    ->where("entry_type", 5)
                                    ->get();

                                    
                                    $float = 0;
                            @endphp
                           @foreach($component as $com)
                                @php 
                                    $qty_consumtion = str_replace(',','',$com->quantity);
                                    $float = (float)$qty_consumtion;
                                    $total_consumtion += $float;
                                    
                                    $line ++;
                                    $var = $com->variant;
                                       if($var == ""){
                                          $var = "NA";
                                       }

                                        if($line == $line2){
                                          $sub_doc = $com->document;
                                          $sub_item = $com->item;
                                          $sub_variant = $com->variant;
                                          $sub_lot = $com->lot;
                                          $sub_line = $line;
                                       }
                                               $old_doc = $com->document;
                                    @endphp
                                @if($line2 == $line)
                                    <tr class="select-row">
                                        <td>{{$line}}</td>
                                        <td>{{$item->posting_date}}</td>
                                        <td>{{$com->document}}</td>
                                        <td>{{$com->item}}</td>
                                        <td>{{$com->variant}}</td>
                                        <td>{{$com->lot}}</td>
                                        <td>{{(float)str_replace(',','',$com->quantity)}}</td>
                                        <td>{{$com->UOM}}</td>
                                        <td>{{$com->location}}</td>
                                        <td>  <a href="/traceability/search/{{$item->item}}/{{$var}}/{{$item->lot}}/{{$line}}/0/0/0/0/0"><button class="btn-more">More </button></a>

                                 
                                    </tr>     
                                @else
                                    <tr>
                                        <td>{{$line}}</td>
                                        <td>{{$item->posting_date}}</td>
                                        <td>{{$com->document}}</td>
                                        <td>{{$com->item}}</td>
                                        <td>{{$com->variant}}</td>
                                        <td>{{$com->lot}}</td>
                                        <td>{{(float)str_replace(',','',$com->quantity)}}</td>
                                        <td>{{$com->UOM}}</td>
                                        <td>{{$com->location}}</td>
                                        <td>  <a href="/traceability/search/{{$item->item}}/{{$var}}/{{$item->lot}}/{{$line}}/0/0/0/0/0"><button class="btn-more">More </button></a>
                                    </tr>     

                                @endif
                              
                             
                           @endforeach
                        @endif
                     
                    @endforeach
                    
                </table>
                <span class="component-title">Total consumtion: {{$total_consumtion}} @if($state  > 0) {{$data[0]->UOM}} @endif</span>
             
            </div>
        </div>`
        @if($line2 != 0)

        @php 
            $pixel = 41;
            if($line2 >= 30){
                $pixel = 37; 
            }elseif($line2 >= 55){
                $pixel = 35; 
            }elseif($line2 >= 61){
                $pixel = 32; 
            }elseif($line2 >= 70){
                $pixel = 28; 
            }elseif($line2 >= 80){
                $pixel = 25; 
            }elseif($line2 >= 100){
                $pixel = 23; 
            }
        
        @endphp
        <div class="line"  style="top: {{$line2*$pixel}}px";><i class="fa-solid fa-arrow-right"></i></div>
        <div class="consumtion-box fade2" style="top: {{$line2*$pixel}}px";>
            <!-- ______________________________________________________ -->
                        <div class="item-box">
                            <span class="empazie selected-box">Output </span>
                            @php
                                $output = DB::table('ITEM LEDGER ENTRY  SUM CONSUMTION')
                                ->where('document',$sub_doc)
                                ->where('entry_type',6)
                                ->get();          
                                $line_sub = 0;
                                $i = 0;
                                $old_lot = null;
                                $old_variant = null;
                                $qty_lot = 0;
                            @endphp
                        
                        <div class="item-detail">
                            
                                <table>
                                  <tr>
                                    <th>No</th>
                                    <th>Document</th>
                                    <th>Item No</th>
                                    <th>variant</th>
                                    <th>Lot No</th>
                                    <th>Quantity</th>
                                    <th>Unit</th>
                                    <th>Location</th>
                                    <th>Action </th>
                              
                                  </tr>
                                  @foreach($output as $out)
                                  @php
                                    //  New line Count 
                                     $line_sub++;
                                    //  previous line compare to new line
                                    // if it the same line assign value

                                     if($line_sub == $line_no){
                                        $out_put_by_line_item = $out->item;
                                        $out_put_by_line_variant = $out->variant;
                                        $out_put_by_line_lot = $out->lot;
                                        $line_01 = $line_sub;
                                     }
                                     $i++; 

                                     if($old_lot != $out->lot || $old_variant != $out->variant){
                                        $qty_lot++;
                                     }
                                        $old_lot = $out->lot;
                                        $old_variant = $out->variant;
                                  @endphp
                                    
                                     @if($line_no == $line_sub)
                                        <tr class="select-row">
                                            <td>{{$line_sub}}</td>
                                            <td>{{$out->document}}</td>
                                            <td>{{$out->item}}</td>
                                            <td>{{$out->variant}}</td>
                                            <td>{{$out->lot}}</td>
                                            <td>{{(float)str_replace(',','',$out->quantity)}}</td>
                                            <td>{{$out->UOM}}</td>
                                            <td>{{$out->location}}</td>
                                            <td>  <a href="/traceability/search/{{$item->item}}/{{$var}}/{{$item->lot}}/{{$line2}}/{{$line_sub}}/0/0/0/0"><button class="btn-more">More </button></a>
                                  
                                        </tr>
                                     @else
                                     <tr>
                                        <td>{{$line_sub}}</td>
                                        <td>{{$out->document}}</td>
                                        <td>{{$out->item}}</td>
                                        <td>{{$out->variant}}</td>
                                        <td>{{$out->lot}}</td>
                                        <td>{{(float)str_replace(',','',$out->quantity)}}</td>
                                        <td>{{$out->UOM}}</td>
                                        <td>{{$out->location}}</td>
                                        <td>  <a href="/traceability/search/{{$item->item}}/{{$var}}/{{$item->lot}}/{{$line2}}/{{$line_sub}}/0/0/0/0"><button  class="btn-more">More </button></a>
                            
                                    </tr>

                                     @endif
                                  @endforeach
                                  @if($i == 0)
                                    <tr>
                                        <td>Not Output Yet.</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                  @endif
                                </table>
                               
                            </div>
                        </div>
            {{-- if  $sub_line have value data transfered to variable --}}
            {{-- open database look for that data --}}

            @php
                // loop in item ledger entry ERP

                if($line_no != 0 ){
                    $output_by_lot = DB::table('dbo.ITEM LEDGER ENTRY')
                    ->where('item',$out_put_by_line_item)
                    ->where('variant' , $out_put_by_line_variant)
                    ->where('lot' , $out_put_by_line_lot)
                    ->get();


                    $stock_output_by_lot = DB::table('ITEM LEDGER STOCK ONHAND BY LOT')
                    ->where('item',$out_put_by_line_item)
                    ->where('variant' , $out_put_by_line_variant)
                    ->where('lot' , $out_put_by_line_lot)
                    ->get();
                    // Query Component In ERP
                    $component_p = DB::table('dbo.ITEM LEDGER ENTRY  SUM CONSUMTION')
                    ->where('item',$out_put_by_line_item)
                    ->where('variant' , $out_put_by_line_variant)
                    ->where('lot' , $out_put_by_line_lot)
                    ->where("entry_type", 5)
                    ->get();

                    $reclass_p =  DB::table('dbo.ITEM Reclass Data')
                    ->where('item' , $out_put_by_line_item)
                    ->where('variant' ,$out_put_by_line_variant)
                    ->where("lot" , $out_put_by_line_lot)
                    ->get();
                    $line_01 = $line_no;
                }
                if($qty_lot == 1){
                    $output_by_lot = DB::table('dbo.ITEM LEDGER ENTRY')
                    ->where('item',$output[0]->item)
                    ->where('variant' , $output[0]->variant)
                    ->where('lot' , $output[0]->lot)
                    ->get();


                    $stock_output_by_lot = DB::table('ITEM LEDGER STOCK ONHAND BY LOT')
                    ->where('item',$output[0]->item)
                    ->where('variant' , $output[0]->variant)
                    ->where('lot' , $output[0]->lot)
                    ->get();
                    // Query Component In ERP
                    $component_p = DB::table('dbo.ITEM LEDGER ENTRY  SUM CONSUMTION')
                    ->where('item',$output[0]->item)
                    ->where('variant' , $output[0]->variant)
                    ->where('lot' , $output[0]->lot)
                    ->where("entry_type", 5)
                    ->get();
                    $reclass_p =  DB::table('dbo.ITEM Reclass Data')
                    ->where('item' , $output[0]->item)
                    ->where('variant' ,$output[0]->variant)
                    ->where("lot" , $output[0]->lot)
                    ->get();
                    $line_01 = 1;
                }

            @endphp
          
            @if($line_no != 0 || $qty_lot == 1)
            <div class="detail-item fade2">
                <div class="main-info">
                    <div class="info-box">
                        <span>Product's Info </span>
                        <div class="two-ul">
                            <ul>
                                
                                <li>Item</li>
                                <li>Variant</li>
                                <li>Unit</li>
                                <li>Lot</li>
                                <li>Description</li>
                             
                            </ul>
                            <ul>
                                <li>:&ensp;{{$output_by_lot[0]->item}}</li>
                                <li>:&ensp;{{$output_by_lot[0]->variant}}</li>
                                <li>:&ensp;{{$output_by_lot[0]->UOM}}</li>
                                <li>:&ensp;{{$output_by_lot[0]->lot}}</li>
                                <li>:&ensp;{{$output_by_lot[0]->Description}}</li>
                         
                            </ul>
                        </div>
                    </div>
                    <div class="info-box">
                        <span>Stock</span>
                        <div class="two-ul">
                            <ul>
                                    @php 
                                        $p = 0;
                                        $total_stock_p = 0;
                                    @endphp
                                @foreach($stock_output_by_lot as $k)
                                    @php 
                                        $p++;
                                        $qty_p = str_replace(',','',$k->quantity);
                                        if(is_numeric($qty_p)){
                                            $float = (float)$qty_p;
                                            $total_stock_p += $float;

                                        }

                                    @endphp
                                    <li>{{$k->location}} : {{(float)str_replace(',','',$k->quantity)}} {{$k->UOM}}</li>
                                @endforeach                 
                            </ul>
                        </div>
                            @if($p == 0)
                                <span>Stock Onhand 0</span>
                            @else
                                <span >Stock Onhand: {{$total_stock_p}} {{$stock_output_by_lot[0]->UOM}}</span>
                            @endif
                    </div>
                </div>
                <div class="box-sub-detail">
                    <div class="box-body">
                        <div class="two-ul">
                            <span class="title_bar">Sale Data</span>
                            <button id="hide-td" onclick="click_hide_tbl()">Detail</button>
                        </div>
                        <table id="table-hide">
                            <tr>
                                <th>No</th>
                                <th>Date</th>
                                <th>Document</th>
                                <th>Item</th>
                                <th>Variant</th>
                                <th>Quantity</th>
                                <th>Unit</th>
                                <th>Lot</th>
                                <th>Location</th>
                                <th style="width: 30%;">Customer</th>
                            </tr>
                            @php
                                // Initailize for count sale data
                                $sale_p = 0;
                                $neg_sale_p = 0;
                                $s = 0;
                            @endphp

                            @foreach($output_by_lot as $u)
                                @if($u->entry_type == 1)
                                @php
                                    $s ++;
                                @endphp
                                <tr>
                                    <td>{{$s}}</td>
                                    <td>{{$u->posting_date}}</td>
                                    <td>{{$u->document}}</td>
                                    <td>{{$u->item}}</td>
                                    <td>{{$u->variant}}</td>
                                    <td>{{(float)str_replace(',','',$u->quantity)}}</td>
                                    <td>{{$u->UOM}}</td>
                                    <td>{{$u->lot}}</td>
                                    <td>{{$u->location}}</td>
                                    <td>{{$u->cusname}}</td>
    
                                </tr>
                                    @php
                                       $qty_sale = str_replace(',','',$u->quantity);
                                       $float = (float)$qty_sale;
                                       $sale_p += $float; 
                                        if($u->quantity > 0){
                                            $qty_sale = str_replace(',','',$u->quantity);
                                            $float = (float)$qty_sale;
                                            $neg_sale_p += $float; 
                                        }
                                    @endphp
                                @endif
                         
                            @endforeach
                            @if( $sale_p  == 0)
                            <tr>
                                <td>No Sale Data</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endif
                        </table>
                        <span class="component-title">Total Sold Quantity: @if($s > 0) {{$sale_p}} {{$output_by_lot[0]->UOM}}  @if($neg_sale_p >0) &ensp; &ensp;Return Quantity: {{$neg_sale_p}} {{$output_by_lot[0]->UOM}} @endif @endif</span>
                    </div>
                    <div class="box-body">
                        <div class="two-ul">
                            <span class="title_bar">Consumtion</span>
                            <button onclick="click_hide_tbl_consumtion()">Detail</button>
                        </div>
                       <table id="hide_tbl_consumtion"  @if($no != 0 ) style="display: block" @endif>
                        <tr>
                            <th>No</th>
                            <th>Document</th>
                            <th>Item No</th>
                            <th>Variant</th>
                            <th>Lot No</th>
                            <th>Quantity</th>
                            <th>Unit</th>  
                            <th>Location</th> 
                            <th>Action</th>                             
                        </tr>
                   

                        @php
                            // Sum for Total QTY
                            $consum_qty = 0;
              
                            // count row Consumtion
                            $line_consumtion = 0;                  
                            $doc_for_sub_data = null;
                        @endphp
                        @foreach( $component_p as $u)
                          @if($u->entry_type == 5 )
                                @php
                                            $line_consumtion++;
                                @endphp
                                @if($no == $line_consumtion)
                                    <tr class="select-row">
                                        <td>{{$line_consumtion}}</td>
                                        <td>{{$u->document}}</td>
                                        <td>{{$u->item}}</td>
                                        <td>{{$u->variant}}</td> 
                                        <td>{{$u->lot}}</td>
                                        <td>{{(float)str_replace(',','',$u->quantity)}}</td>
                                        <td>{{$u->UOM}}</td>
                                        <td>{{$u->location}}</td>
                                        <td>  <a href="/traceability/search/{{$item->item}}/{{$var}}/{{$item->lot}}/{{$line2}}/{{$line_01}}/{{$line_consumtion}}/0/0/0"><button  class="btn-more">More</button></a> 
                                    </tr>
                                @else
                                    <tr >
                                        <td>{{$line_consumtion}}</td>
                                        <td>{{$u->document}}</td>
                                        <td>{{$u->item}}</td>
                                        <td>{{$u->variant}}</td> 
                                        <td>{{$u->lot}}</td>
                                        <td>{{(float)str_replace(',','',$u->quantity)}}</td>
                                        <td>{{$u->UOM}}</td>
                                        <td>{{$u->location}}</td>
                                        <td>  <a href="/traceability/search/{{$item->item}}/{{$var}}/{{$item->lot}}/{{$line2}}/{{$line_01}}/{{$line_consumtion}}/0/0/0"><button class="btn-more">More</button></a>
                                    </tr>
                                @endif
                         
                            @php
                             
                                if($line_consumtion == $no_row){
                                    $line_02 = $line_consumtion;
                                    $doc_for_sub_data = $u->document;
                                }
                                
                                $qty_consumtion = str_replace(',','',$u->quantity);
                                $float = (float)$qty_consumtion;
                                $consum_qty += $float;
            
                            @endphp
                          @endif
                       
                        @endforeach
                       </table>
                     
                       <span class="component-title">Total Consumption: {{$consum_qty}} {{$output_by_lot[0]->UOM}} </span>
                   
                       @if($no_row != 0 || $line_consumtion == 1)
                            
                            @php
                            if($line_consumtion == 1){
                                $doc_for_sub_data = $component_p[0]->document;
                                $line_02 = 1;
                            }

                                $out_put_sub_data = DB::table('ITEM LEDGER ENTRY  SUM CONSUMTION')
                                    ->where('document',$doc_for_sub_data)
                                    ->where('entry_type',6)
                                    ->get(); 

                            @endphp
                        <div class="line"  style="top: 0px";><i class="fa-solid fa-arrow-right"></i></div>
                        <div class="consumtion-box fade3" style="top: 0px";>
                            <!-- ______________________________________________________ -->
                                        <div class="item-box">
                            
                                            <span class="empazie selected-box">Output</span>
                                            <div class="item-detail">
                                                <table>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Document</th>
                                                        <th>Item</th>
                                                        <th>Variant</th>
                                                        <th>Lot</th>
                                                        <th>Quantity</th>
                                                        <th>Unit</th>
                                                        <th>Location</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    @php
                                                        $row_no = 0;
                                                        $old_lot = null;
                                                        $old_var = null;
                                                        $qty_lot = 0;
                                                    @endphp
                                                    @foreach($out_put_sub_data as $k)
                                                        @php
                                                            $row_no++;
                                                        @endphp
                                                        @if($no == $row_no || $line_consumtion == 1)
                                                        <tr class="select-row">
                                                            <td>{{$row_no}} </td>
                                                            <td>{{$k->document}}</td>
                                                            <td>{{$k->item}}</td>
                                                            <td>{{$k->variant}}</td>
                                                            <td>{{$k->lot}}</td>
                                                            <td>{{(float)str_replace(',','',$k->quantity)}}</td>
                                                            <td>{{$k->UOM}}</td>
                                                            <td  style="  white-space: nowrap !important;">{{$k->location}}</td>
                                                            <td>  <a href="/traceability/search/{{$item->item}}/{{$var}}/{{$item->lot}}/{{$sub_line}}/{{$line_01}}/{{$line_02}}/{{$row_no}}/0/0"><button class="btn-more">More</button></a>
                                                            </tr>
                                                        @else
                                                        <tr>
                                                            <td>{{$row_no}}</td>
                                                            <td>{{$k->document}}</td>
                                                            <td>{{$k->item}}</td>
                                                            <td>{{$k->variant}}</td>
                                                            <td>{{$k->lot}}</td>
                                                            <td>{{(float)str_replace(',','',$k->quantity)}}</td>
                                                            <td>{{$k->UOM}}</td>
                                                            <td  style="  white-space: nowrap !important;">{{$k->location}}</td>
                                                            <td>  <a href="/traceability/search/{{$item->item}}/{{$var}}/{{$item->lot}}/{{$sub_line}}/{{$line_01}}/{{$line_02}}/{{$row_no}}/0/0"><button class="btn-more">More</button></a>
                                                            </tr>

                                                        @endif
                                                    @php
                                            
                                                                    if($no == $row_no){
                                                                        $item_sub_b = $k->item;
                                                                        $variant_sub_b = $k->variant;
                                                                        $lot_sub_b = $k->lot;
                                                                        $line_03  = $no;
                                                                    }
                                                                    if($old_lot != $k->lot || $old_var != $k->variant){
                                                                        $qty_lot++;
                                                                    }
                                                                    $old_lot = $k->lot;
                                                                    $old_var = $k->variant;
                                                        
                                                    @endphp
                                                    @endforeach
                                                    @if($row_no== 0)
                                                    <tr>
                                                        <td>Not Output Yet.</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    @endif
                                                </table>
                                      
                                            </div>
                                        </div>
                                            @if($qty_lot == 1 || $no != 0)
                                            @php
                                                             if($qty_lot == 1){
                                                                $output_by_lot_s = DB::table('dbo.ITEM LEDGER ENTRY')
                                                                ->where('item',$out_put_sub_data[0]->item)
                                                                ->where('variant' ,$out_put_sub_data[0]->variant)
                                                                ->where('lot' , $out_put_sub_data[0]->lot)
                                                                ->get();


                                                                $stock_output_by_lot_s = DB::table('ITEM LEDGER STOCK ONHAND BY LOT')
                                                                ->where('item',$out_put_sub_data[0]->item)
                                                                ->where('variant' ,$out_put_sub_data[0]->variant)
                                                                ->where('lot' , $out_put_sub_data[0]->lot)
                                                                ->get();
                                                                // Query Component In ERP
                                                                $component_p_s = DB::table('dbo.ITEM LEDGER ENTRY  SUM CONSUMTION')
                                                                ->where('item',$out_put_sub_data[0]->item)
                                                                ->where('variant' , $out_put_sub_data[0]->variant)
                                                                ->where('lot' , $out_put_sub_data[0]->lot)
                                                                ->where("entry_type", 5)
                                                                ->get();
                                                                // $line_01 = 1;
                                                                $reclass_p_s = DB::table('dbo.ITEM Reclass Data')
                                                                ->where('item' , $out_put_sub_data[0]->item)
                                                                ->where('variant' , $out_put_sub_data[0]->variant)
                                                                ->where("lot" ,$out_put_sub_data[0]->lot)
                                                                 ->get();
                                                                 $line_03 = 1;
                                                            }else{

                                                                $output_by_lot_s = DB::table('dbo.ITEM LEDGER ENTRY')
                                                                ->where('item',$item_sub_b)
                                                                ->where('variant' , $variant_sub_b)
                                                                ->where('lot' , $lot_sub_b)
                                                                ->get();


                                                                $stock_output_by_lot_s = DB::table('ITEM LEDGER STOCK ONHAND BY LOT')
                                                                ->where('item',$item_sub_b)
                                                                ->where('variant' ,$variant_sub_b)
                                                                ->where('lot' , $lot_sub_b)
                                                                ->get();
                                                                // Query Component In ERP
                                                                $component_p_s = DB::table('dbo.ITEM LEDGER ENTRY  SUM CONSUMTION')
                                                                ->where('item',$item_sub_b)
                                                                ->where('variant' , $variant_sub_b)
                                                                ->where('lot' , $lot_sub_b)
                                                                ->where("entry_type", 5)
                                                                ->get();
                                                                // $line_01 = 1;
                                                                $reclass_p_s = DB::table('dbo.ITEM Reclass Data')
                                                                ->where('item' , $item_sub_b)
                                                                ->where('variant' , $variant_sub_b)
                                                                ->where("lot" , $lot_sub_b)
                                                                 ->get();
                                                            }
                                            @endphp
                                            <div class="detail-item">
                                                <div class="main-info">
                                                    <div class="info-box">
                                                        <span>Product's Info</span>
                                                        <div class="two-ul">
                                                            <ul>
                                                                
                                                                <li>Item</li>
                                                                <li>Variant</li>
                                                                <li>Description</li>
                                                                <li>Unit</li>
                                                                <li>Lot</li>
                                                            </ul>
                                                            <ul>
                                                                <li>:&ensp;{{$output_by_lot_s[0]->item}}</li>
                                                                <li>:&ensp;{{$output_by_lot_s[0]->variant}}</li>
                                                                <li>:&ensp;{{$output_by_lot_s[0]->Description}}</li>
                                                                <li>:&ensp;{{$output_by_lot_s[0]->UOM}}</li>
                                                                <li>:&ensp;{{$output_by_lot_s[0]->lot}}</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="info-box">
                                                        <span>Stock</span>
                                                        <div class="two-ul">
                                                            <ul>
                                                                    @php 
                                                                        $p = 0;
                                                                        $total_stock_p = 0;
                                                                    @endphp
                                                                @foreach($stock_output_by_lot_s as $k)
                                                                    @php 
                                                                        $p++;
                                                                        $qty_p = str_replace(',','',$k->quantity);
                                                                        if(is_numeric($qty_p)){
                                                                            $float = (float)$qty_p;
                                                                            $total_stock_p += $float;
                                
                                                                        }
                                
                                                                    @endphp
                                                                    <li>{{$k->location}} : {{(float)str_replace(',','',$k->quantity)}} {{$k->UOM}}</li>
                                                                @endforeach                 
                                                            </ul>
                                                        </div>
                                                            @if($p == 0)
                                                                <span>Stock Onhand 0</span>
                                                            @else
                                                                <span >Stock Onhand: {{$total_stock_p}} {{$stock_output_by_lot_s[0]->UOM}}</span>
                                                            @endif
                                                    </div>
                                                </div>
                                                <div class="box-sub-detail">
                                                    <div class="box-body">
                                                        <div class="two-ul">
                                                            <span class="title_bar">Sale Data</span>
                                                            <button  onclick="click_hide_tbl_sale()">Detail</button>
                                                        </div>
                                                        <table id="hide_sale_s">
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Date</th>
                                                                <th>Document</th>
                                                                <th>Item</th>
                                                                <th>Variant</th>
                                                                <th>Quantity</th>
                                                                <th>Unit</th>
                                                                <th>Lot</th>
                                                                <th>Location</th>
                                                                <th style="width: 30%;">Customer</th>
                                                            </tr>
                                                            @php
                                                                // Initailize for count sale data
                                                                $sale_p = 0;
                                                                $qty_s = 1;
                                                                // dd($output_by_lot_s);
                                                            @endphp

                                                            @foreach($output_by_lot_s as $u)
                                                                @if($u->entry_type == 1)
                                                                <tr>
                                                                    <td>{{$qty_s}}</td>
                                                                    <td>{{$u->posting_date}}</td>
                                                                    <td>{{$u->document}}</td>
                                                                    <td>{{$u->item}}</td>
                                                                    <td>{{$u->variant}}</td>
                                                                    <td>{{(float)str_replace(',','',$u->quantity)}}</td>
                                                                    <td>{{$u->UOM}}</td>
                                                                    <td>{{$u->lot}}</td>
                                                                    <td>{{$u->location}}</td>
                                                                    <td>{{$u->cusname}}</td>
                                    
                                                                </tr>
                                                                    @php
                                                                       $qty_sale = str_replace(',','',$u->quantity);
                                                                       $float = (float)$qty_sale;
                                                                       $sale_p += $float; 
                                                                       $qty_s++;
                                                                    @endphp
                                                                @endif
                                                         
                                                            @endforeach
                                                            @if( $sale_p  == 0)
                                                            <tr>
                                                                <td>No Sale Data</td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                        @endif
                                                        </table>
                                                        <span class="component-title">Total Sale: {{$sale_p}} {{$output_by_lot_s[0]->UOM}} </span>
                                                    </div>

                                                    <div class="box-body">
                                                        <div class="two-ul">
                                                            <span class="title_bar">Consumption</span>
                                                            <button onclick="click_hide_tbl_consumtion2()">Detail</button>
                                                        </div>
                                                       <table id="table_hide2">
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Document</th>
                                                            <th>Item No</th>
                                                            <th>Variant</th>
                                                            <th>Lot No</th>
                                                            <th>Quantity</th>
                                                            <th>Unit</th>  
                                                            <th>Location</th> 
                                                            <th>Action</th>                             
                                                        </tr>
                                                   
                                
                                                        @php
                                                            // dd($consum);
                                                            // Sum for Total QTY
                                                            $consum_qty_all = 0;
                                                            // increament every Line
                                                            $consumtion_line_no = 1;
                                                            // count row Consumtion
                                                            $consum_no = 0; 
                                
                                                            $doc_for_sub_data = null;
                                                        @endphp
                                                        @foreach( $component_p_s as $u)
                                                          @if($u->entry_type == 5 )
                                                            @php
                                                                $consum_no++;
                                                            @endphp
                                                            @if($consum == $consum_no)
                                                            <tr class="select-row">  
                                                                <td>{{$consum_no}}</td>
                                                                <td>{{$u->document}}</td>
                                                                <td>{{$u->item}}</td>
                                                                <td>{{$u->variant}}</td> 
                                                                <td>{{$u->lot}}</td>
                                                                <td>{{(float)str_replace(',','',$u->quantity)}}</td>
                                                                <td>{{$u->UOM}}</td>
                                                                <td>{{$u->location}}</td>
                                                                <td>  <a href="/traceability/search/{{$item->item}}/{{$var}}/{{$item->lot}}/{{$sub_line}}/{{$line_01}}/{{$line_02}}/{{$line_03}}/{{$consum_no}}/0"><button>More</button></a></td>
     
                                                           
                                                                </tr>
                                                            @else
                                                            <tr >  

                                                                <td>{{$consum_no}}</td>
                                                                <td>{{$u->document}}</td>
                                                                <td>{{$u->item}}</td>
                                                                <td>{{$u->variant}}</td> 
                                                                <td>{{$u->lot}}</td>
                                                                <td>{{(float)str_replace(',','',$u->quantity)}}</td>
                                                                <td>{{$u->UOM}}</td>
                                                                <td>{{$u->location}}</td>
                                                                <td>  <a href="/traceability/search/{{$item->item}}/{{$var}}/{{$item->lot}}/{{$sub_line}}/{{$line_01}}/{{$line_02}}/{{$line_03}}/{{$consum_no}}/0"><button>More</button></a></td>
                                                                </tr>

                                                            @endif
                                        
                                      
                                                            @php
                                                                if($consum == $consum_no){
                                                                    $line_04 = $consum_no;
                                                                    $doc_ = $u->document;
                                                                }
                                                                $qty_consumtion = str_replace(',','',$u->quantity);
                                                                $float_se = (float)$qty_consumtion;
                                                                $consum_qty_all += $float_se;
                                                                $consumtion_line_no++;
                                 
                                                                
                                                             
                                                            @endphp
                                                          @endif
                                                       
                                                        @endforeach
                                                       </table>
                                                     
                                                       <span class="component-title">Total Consumption:{{$consum_qty_all}} {{$output_by_lot[0]->UOM}} </span>

                                                        @if($consum != 0 || $consum_no == 1)
                                                            @php
                                                            if($consum_no == 1){
                                                                $doc_ = $component_p_s[0]->document;
                                                                $line_04 = 1;
                                                            }
                                                                
                                                                $for_out_put = DB::table('ITEM LEDGER ENTRY  SUM CONSUMTION')
                                                                    ->where('document', $doc_)
                                                                    ->where('entry_type',6)
                                                                    ->get(); 
                                                                
                                                                    $old_lot_s = null;
                                                                    $old_var_s = null;
                                                                    $qty_lot_s = 0;
                                                                    $for_out = 0;
                                                            @endphp
                                
                                                            
                                                        
                                                            <div class="line"  style="top: 0px";><i class="fa-solid fa-arrow-right"></i></div>
                                                            <div class="consumtion-box fade4" style="top: 0px";>
                                                                <!-- ______________________________________________________ -->
                                                                            <div class="item-box">
                                                                
                                                                                <span class="empazie selected-box">Output </span>
                                                                                <div class="item-detail">
                                                                                        <table>
                                                                                            <tr>
                                                                                                <th>No </th>
                                                                                                <th>Document</th>
                                                                                                <th>Item</th>
                                                                                                <th>Variant</th>
                                                                                                <th>Lot</th>
                                                                                                <th>Quantity</th>
                                                                                                <th>Unit</th>
                                                                                                <th>Location</th>
                                                                                                <th>Action</th>
                                                                                            </tr>
                                                                                            @foreach( $for_out_put as $k)
                                                                                            @php
                                                                                                $for_out++;
                                                                                               
                                                                                            @endphp
                                                                                            @if($output_no == $for_out )
                                                                                                <tr class="select-row">
                                                                                                    <td>{{$for_out}}</td>
                                                                                                    <td>{{$k->document}}</td>
                                                                                                    <td>{{$k->item}}</td>
                                                                                                    <td>{{$k->variant}}</td>
                                                                                                    <td>{{$k->lot}}</td>
                                                                                                    <td>{{(float)str_replace(',','',$k->quantity)}}</td>
                                                                                                    <td>{{$k->UOM}}</td>
                                                                                                    <td  style="  white-space: nowrap !important;">{{$k->location}}</td>
                                                                                                    <td>  <a href="/traceability/search/{{$item->item}}/{{$var}}/{{$item->lot}}/{{$sub_line}}/{{$line_01}}/{{$line_02}}/{{$line_03}}/{{$line_04}}/{{$for_out}}"><button>More</button></a>    </td>
                                                                                               </tr>
                                                                                            @else
                                                                                                <tr>
                                                                                                    <td>{{$for_out}}</td>
                                                                                                    <td>{{$k->document}}</td>
                                                                                                    <td>{{$k->item}}</td>
                                                                                                    <td>{{$k->variant}}</td>
                                                                                                    <td>{{$k->lot}}</td>
                                                                                                    <td>{{(float)str_replace(',','',$k->quantity)}}</td>
                                                                                                    <td>{{$k->UOM}}</td>
                                                                                                    <td  style="  white-space: nowrap !important;">{{$k->location}}</td>
                                                                                                    <td>  <a href="/traceability/search/{{$item->item}}/{{$var}}/{{$item->lot}}/{{$sub_line}}/{{$line_01}}/{{$line_02}}/{{$line_03}}/{{$line_04}}/{{$for_out}}"><button>More</button></a>    </td>
                                                                                                </tr>
                                                                                         
                                                                                            @endif
                                                                                                @php
                                                                     
                                                                                                    if($output_no == $for_out){
                                                                                                        $item_next = $k->item;
                                                                                                        $variant_next = $k->variant;
                                                                                                        $lot_next = $k->lot;
                                                                                                        $line_05 = $for_out;
                                                                                                    }
                                                                                                    if($old_lot_s != $k->lot || $old_var_s != $k->variant){
                                                                                                        $qty_lot_s++;
                                                                                                    }         
                                                                                                     $old_lot_s = $k->lot;
                                                                                                     $old_var_s = $k->variant;
                                                                                                    
                                                                                                @endphp 
                                                                                            @endforeach
                                                                                        </table>
                                                                                      
                                                                                </div>
                                                                                </div>
                                                                                
                                                                            @if($output_no != 0 || $qty_lot_s == 1)
                                                                                @php
                                                                                      
                                                                                            if( $qty_lot_s == 1){
                                                                                            $output_by_lot_s2 = DB::table('dbo.ITEM LEDGER ENTRY')
                                                                                            ->where('item',$for_out_put[0]->item)
                                                                                            ->where('variant' ,$for_out_put[0]->variant)
                                                                                            ->where('lot' , $for_out_put[0]->lot)
                                                                                            ->get();


                                                                                            $stock_output_by_lot_s2 = DB::table('ITEM LEDGER STOCK ONHAND BY LOT')
                                                                                            ->where('item',$for_out_put[0]->item)
                                                                                            ->where('variant' ,$for_out_put[0]->variant)
                                                                                            ->where('lot' , $for_out_put[0]->lot)
                                                                                            ->get();
                                                                                            // Query Component In ERP
                                                                                            $component_p_s2 = DB::table('dbo.ITEM LEDGER ENTRY  SUM CONSUMTION')
                                                                                            ->where('item',$for_out_put[0]->item)
                                                                                            ->where('variant' , $for_out_put[0]->variant)
                                                                                            ->where('lot' , $for_out_put[0]->lot)
                                                                                            ->where("entry_type", 5)
                                                                                            ->get();

                                                                                            $reclass_p_s2 = DB::table('dbo.ITEM Reclass Data')
                                                                                            ->where('item' , $for_out_put[0]->item)
                                                                                            ->where('variant' ,  $for_out_put[0]->variant)
                                                                                            ->where("lot" ,$for_out_put[0]->lot)
                                                                                            ->get();
                                                                                            $line_05  = 1;
                                                                                        }else{
                                                                                         
                                                                                            $output_by_lot_s2 = DB::table('dbo.ITEM LEDGER ENTRY')
                                                                                            ->where('item',$item_next)
                                                                                            ->where('variant' ,$variant_next)
                                                                                            ->where('lot' , $lot_next)
                                                                                            ->get();


                                                                                            $stock_output_by_lot_s2 = DB::table('ITEM LEDGER STOCK ONHAND BY LOT')
                                                                                            ->where('item',$item_next)
                                                                                            ->where('variant' ,$variant_next)
                                                                                            ->where('lot' , $lot_next)
                                                                                            ->get();
                                                                                            // Query Component In ERP
                                                                                            $component_p_s2 = DB::table('dbo.ITEM LEDGER ENTRY  SUM CONSUMTION')
                                                                                            ->where('item',$item_next)
                                                                                            ->where('variant' , $variant_next)
                                                                                            ->where('lot' , $lot_next)
                                                                                            ->where("entry_type", 5)
                                                                                            ->get();
                                                                                            $reclass_p_s2 = DB::table('dbo.ITEM Reclass Data')
                                                                                            ->where('item' , $item_next)
                                                                                            ->where('variant' , $variant_next)
                                                                                            ->where("lot" , $lot_next)
                                                                                            ->get();
                                                                                            }

                                                                                @endphp
                                                                                
                                                                                <div class="detail-item">
                                                                                    <div class="main-info">
                                                                                        <div class="info-box">
                                                                                            <span>Product's Info</span>
                                                                                            <div class="two-ul">
                                                                                                <ul>
                                                                                                    
                                                                                                    <li>Item</li>
                                                                                                    <li>Variant</li>
                                                                                                    <li>Description</li>
                                                                                                    <li>Unit</li>
                                                                                                    <li>Lot</li>
                                                                                                </ul>
                                                                                                <ul>
                                                                                                    <li>:&ensp;{{$output_by_lot_s2[0]->item}}</li>
                                                                                                    <li>:&ensp;{{$output_by_lot_s2[0]->variant}}</li>
                                                                                                    <li>:&ensp;{{$output_by_lot_s2[0]->Description}}</li>
                                                                                                    <li>:&ensp;{{$output_by_lot_s2[0]->UOM}}</li>
                                                                                                    <li>:&ensp;{{$output_by_lot_s2[0]->lot}}</li>
                                                                                                </ul>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="info-box">
                                                                                            <span>Stock</span>
                                                                                            <div class="two-ul">
                                                                                                <ul>
                                                                                                        @php 
                                                                                                            $p2 = 0;
                                                                                                            $total_stock_p2 = 0;
                                                                                                        @endphp
                                                                                                    @foreach($stock_output_by_lot_s2 as $k)
                                                                                                        @php 
                                                                                                            $p2++;
                                                                                                            $qty_p2 = str_replace(',','',$k->quantity);
                                                                                                            if(is_numeric($qty_p2)){
                                                                                                                $float2 = (float)$qty_p2;
                                                                                                                $total_stock_p2 += $float2;
                                                                    
                                                                                                            }
                                                                    
                                                                                                        @endphp
                                                                                                        <li>{{$k->location}} : {{(float)str_replace(',','',$k->quantity)}} {{$k->UOM}}</li>
                                                                                                    @endforeach                 
                                                                                                </ul>
                                                                                            </div>
                                                                                                @if($p2 == 0)
                                                                                                <span>Stock Onhand 0</span>
                                                                                                @else
                                                                                                    <span >Stock Onhand : {{$total_stock_p2}} {{$stock_output_by_lot_s2[0]->UOM}}</span>
                                                                                                @endif
                                                                                        </div>
                                                                                    </div>
                                                                                    
                                                                            
                                                                                    <div class="box-sub-detail">
                                                                                    <div class="box-body">
                                                                                        <div class="two-ul">
                                                                                            <span class="title_bar">Sale Data</span>
                                                                                            <button onclick="click_hide_tbl_sale3()">Detail</button>
                                                                                        </div>
                                                                                        <table id="tbl-sale3">
                                                                                            <tr>
                                                                                                <th>No</th>
                                                                                                <th>Date</th>
                                                                                                <th>Document</th>
                                                                                                <th>Item</th>
                                                                                                <th>Variant</th>
                                                                                                <th>Quantity</th>
                                                                                                <th>Unit</th>
                                                                                                <th>Lot</th>
                                                                                                <th>Location</th>
                                                                                                <th style="width: 30%;">Customer</th>
                                                                                            </tr>
                                                                                            @php
                                                                                            // Initailize for count sale data
                                                                                            $sale_p2 = 0;
                                                                                            $qty_s2 = 1;
                                                                                            $consum_con = 0;
                                                                                            @endphp
                                                                
                                                                                            @foreach($output_by_lot_s2 as $u)
                                                                                                @if($u->entry_type == 1)
                                                                                                    <tr>
                                                                                                        <td>{{$qty_s2}}</td>
                                                                                                        <td>{{$u->posting_date}}</td>
                                                                                                        <td>{{$u->document}}</td>
                                                                                                        <td>{{$u->item}}</td>
                                                                                                        <td>{{$u->variant}}</td>
                                                                                                        <td>{{(float)str_replace(',','',$u->quantity)}}</td>
                                                                                                        <td>{{$u->UOM}}</td>
                                                                                                        <td>{{$u->lot}}</td>
                                                                                                        <td>{{$u->location}}</td>
                                                                                                        <td>{{$u->cusname}}</td>
                                                                
                                                                                                    </tr>
                                                                                                    @php
                                                                                                    $qty_sale = str_replace(',','',$u->quantity);
                                                                                                    $floats = (float)$qty_sale;
                                                                                                    $sale_p2 += $floats; 
                                                                                                    $qty_s2++;
                                                                                                    @endphp
                                                                                                @endif
                                                                                     
                                                                                            @endforeach
                                                                                            @if( $sale_p2  == 0)
                                                                                                <tr>
                                                                                                    <td>No Sale Data</td>
                                                                                                    <td></td>
                                                                                                    <td></td>
                                                                                                    <td></td>
                                                                                                    <td></td>
                                                                                                    <td></td>
                                                                                                    <td></td>
                                                                                                    <td></td>
                                                                                                    <td></td>
                                                                                                </tr>
                                                                                             @endif
                                                                                        </table>
                                                                                        <span class="component-title">Total Sale: {{$sale_p2}} {{$output_by_lot_s2[0]->UOM}} </span>
                                                                                    </div>
                                                                                    
                                                                                        <div class="box-body">
                                                                                            <div class="two-ul">
                                                                                            <span class="title_bar">Consumption</span>
                                                                                                <button onclick="click_hide_tbl_consumtion3()">Detail</button>
                                                                                                </div>
                                                                                                <table id="tbl-hide3">
                                                                                                    <tr>
                                                                                                        <th>No</th>
                                                                                                        <th>Document</th>
                                                                                                        <th>Item No</th>
                                                                                                        <th>Variant</th>
                                                                                                        <th>Lot No</th>
                                                                                                        <th>Quantity</th>
                                                                                                        <th>Unit</th>  
                                                                                                        <th>Location</th> 
                                                                                   
                                                                                                    </tr>
                                                                                                    @php
                                                                                                        $state_con = 0;
                                                                                                        $consum_qty_all_con = 0;
                                                                                                    @endphp
                                                                                                    @foreach( $component_p_s2 as $u)
                                                                                                        @if($u->entry_type == 5 )
                                                                                                            @if($consum == $consum_no+1)
                                                                                                            <tr class="select-row">  
                                                                                                                <td>{{$state_con+1}}</td>
                                                                                                                <td>{{$u->document}}</td>
                                                                                                                <td>{{$u->item}}</td>
                                                                                                                <td>{{$u->variant}}</td> 
                                                                                                                <td>{{$u->lot}}</td>
                                                                                                                <td>{{(float)str_replace(',','',$u->quantity)}}</td>
                                                                                                                <td>{{$u->UOM}}</td>
                                                                                                                <td>{{$u->location}}</td>
                                                                                                              
                                                    
                                                                                                                    
                                                                                                                </tr>
                                                                                                                @php
                                                                                                                $state_con++;
                                                                                                                @endphp
                                                                                                            @else
                                                                                                            <tr >  
                                                                                                                <td>{{$state_con+1}}</td>
                                                                                                                <td>{{$u->document}}</td>
                                                                                                                <td>{{$u->item}}</td>
                                                                                                                <td>{{$u->variant}}</td> 
                                                                                                                <td>{{$u->lot}}</td>
                                                                                                                <td>{{(float)str_replace(',','',$u->quantity)}}</td>
                                                                                                                <td>{{$u->UOM}}</td>
                                                                                                                <td>{{$u->location}}</td>
                                                                                                       
                                                    
                                                                                                                    
                                                                                                                </tr>
                                                                                                                @php
                                                                                                                    $state_con++;
                                                                                                                @endphp
                                                                                                            @endif
                                                                                    
                                                                                
                                                                                                                @php
                                                                                                                
                                                                                                                    $qty_con = str_replace(',','',$u->quantity);
                                                                                                                    $float_con = (float)$qty_consumtion;
                                                                                                                    $consum_qty_all_con += $float_con;
                                                                                                                    $consumtion_line_no++;
                                                                                    
                                                                                                                    
                                                                                                                    $consum_con++; 
                                                                                                                @endphp
                                                                                                        @endif
                                                                                                      @endforeach
                                                                                                            @if( $state_con == 0 )
                                                                                                                <tr>
                                                                                                                    <td>No consumption</td>
                                                                                                                    <td></td>
                                                                                                                    <td></td>
                                                                                                                    <td></td>
                                                                                                                    <td></td>
                                                                                                                    <td></td>
                                                                                                                    <td></td>
                                                                                                                    <td></td>
                                                                                                                </tr>
                                                                                                            @endif
                                                                                                </table>
                                                                                                <span class="component-title">Total Consumption: {{$consum_qty_all_con}} @if($state_con != 0) {{$component_p_s2[0]->UOM}} @endif </span>
                                                                                            </div>
                                                                                    <div class="box-body">
                                                                                        <div class="two-ul">
                                                                                            <span class="title_bar">Other</span>
                                                                         
                                                                                        </div>

                                                                                            <table>
                                                                                                <tr>
                                                                                                    <th>Type</th>
                                                                                                    <th>Item</th>
                                                                                                    <th>Variant</th>
                                                                                                    <th>Lot</th>
                                                                                                    <th>quantity</th>
                                                                                                    <th>Unit</th>
                                                                                                </tr>
                                                                                                @php 
                                                                                                    $d_count = 0;
                                                                                                @endphp
                                                                                                @foreach ($output_by_lot_s2 as $item)
                                                                                                    @if($item->entry_type == 3 || $item->entry_type == 2)
                                                                                                        <tr>
                                                                                                            <td>[{{$item->type}}]</td>
                                                                                                            <td>{{$item->item}}</td>
                                                                                                            <td>{{$item->variant}}</td>
                                                                                                            <td>{{$item->lot}}</td>
                                                                                                            <td>{{(float)str_replace(',','',$item->quantity)}}</td>
                                                                                                            <td>{{$item->UOM}}</td>
                                                                                                        </tr>
                                                                                                        @php
                                                                                                            $d_count++;
                                                                                                        @endphp
                                                                                                    @endif
                                                                                                @endforeach
                                                                                                @foreach($reclass_p_s2  as $item)
                                                                                                        <tr>
                                                                                                            <td>[Reclass]</td>
                                                                                                            <td>{{$item->item}}</td>
                                                                                                            <td>{{$item->variant}}</td>
                                                                                                            <td>{{$item->lot}}</td>
                                                                                                            <td>{{(float)str_replace(',','',$item->quantity)}}</td>
                                                                                                            <td>{{$item->UOM}}</td>
                                                                                                        </tr>
                                                                                                        @php
                                                                                                         $d_count++;
                                                                                                        @endphp
                                                                                                @endforeach

                                                                                                        @if($d_count == 0)
                                                                                                            <tr>
                                                                                                                <td>No Data</td>
                                                                                                                <td></td>
                                                                                                                <td></td>
                                                                                                                <td></td>
                                                                                                                <td></td>
                                                                                                                <td></td>
                                                                                                            </tr>
                                                                                                        @endif

                                                                                                   
                                                                                            </table>
                                                                                       
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @endif
                                                                </div>
                                                        @endif
                                                </div>
                                                <div class="box-body">
                                                    <div class="two-ul">
                                                        <span class="title_bar">Other</span>
                                                        
                                                    </div>
                                                        <table>
                                                            <tr>
                                                                <th>Type</th>
                                                                <th>Document</th>
                                                                <th>Item</th>
                                                                <th>Variant</th>
                                                                <th>Lot</th>
                                                                <th>quantity</th>
                                                                <th>Unit</th>
                                                            </tr>
                                                            @php 
                                                                $d_count = 0;
                                                                $qty_other2 = 0;
                                                                $neg_qty_other2 = 0;
                                                            @endphp
                                                            @foreach ($output_by_lot_s as $item)
                                                                @if($item->entry_type == 3 || $item->entry_type == 2)
                                                                    <tr>
                                                                        <td>[{{$item->type}}]</td>
                                                                        <td>{{$item->document}}</td>
                                                                        <td>{{$item->item}}</td>
                                                                        <td>{{$item->variant}}</td>
                                                                        <td>{{$item->lot}}</td>
                                                                        <td>{{(float)str_replace(',','',$item->quantity)}}</td>
                                                                        <td>{{$item->UOM}}</td>
                                                                    </tr>
                                                                    @php
                                                                        $d_count++;
                                                                        $UOM_other2 = $item->UOM;
                                                                        $qty_other2_s = str_replace(',','',$item->quantity);
                                                                        $qty_float_other = (float)$qty_other2_s;
                                                                        $qty_other2 +=  $qty_float_other;
                                                                        if($item->quantity){
                                                                            $neg_qty_other2_s = str_replace(',','',$item->quantity);
                                                                            $neg_qty_float_other = (float)$neg_qty_other2_s ;
                                                                            $qty_other2 +=  $neg_qty_float_other;
                                                                        }
                                                                    @endphp
                                                                @endif
                                                            @endforeach
                                                            @foreach($reclass_p_s  as $item)
                                                                    <tr>
                                                                        <td>[Reclass]</td>
                                                                        <td>{{$item->document}}</td>
                                                                        <td>{{$item->item}}</td>
                                                                        <td>{{$item->variant}}</td>
                                                                        <td>{{$item->lot}}</td>
                                                                        <td>{{(float)str_replace(',','',$item->quantity)}}</td>
                                                                        <td>{{$item->UOM}}</td>
                                                                    </tr>
                                                                    @php
                                                                     $d_count++;
                                                                     $UOM_other2 = $item->UOM;
                                                                     $qty_other2_s = str_replace(',','',$item->quantity);
                                                                        $qty_float_other = (float)$qty_other2_s;
                                                                        $qty_other2 +=  $qty_float_other;
                                                                    
                                                                    @endphp
                                                            @endforeach

                                                                    @if($d_count == 0)
                                                                        <tr>
                                                                            <td>No Data</td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                        </tr>
                                                                    @endif

                                                               
                                                        </table>
                                                        <span class="component-title">Total Quantity: {{$qty_other2}} @if($d_count != 0) {{$UOM_other2}} @endif </span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                    
                                @endif
                            </div>
                           
                    </div>
                    <div class="box-body">
                        <div class="two-ul">
                            <span class="title_bar">Other </span>
                
                        </div>
                        
                        <table>
                            <tr>
                                <th>Type</th>
                                <th>Document</th>
                                <th>Item</th>
                                <th>Variant</th>
                                <th>Lot</th>
                                <th>quantity</th>
                                <th>Unit</th>
                            </tr>
                            @php
                                $c_count = 0;   
                                $qty_other1 = 0;
                            @endphp
                            @foreach ($output_by_lot as $item)
                                @if($item->entry_type == 3 || $item->entry_type == 2)
                                    <tr>
                                        <td>[{{$item->type}}]</td>
                                        <td>{{$item->document}}</td>
                                        <td>{{$item->item}}</td>
                                        <td>{{$item->variant}}</td>
                                        <td>{{$item->lot}}</td>
                                        <td>{{(float)str_replace(',','',$item->quantity)}}</td>
                                        <td>{{$item->UOM}}</td>
                                    </tr>
                                    @php 
                                        $c_count++;
                                        $UOM_other1 = $item->UOM;
                                        $qty_other1_s = str_replace(',','',$item->quantity);
                                        $qty_float_other = (float)$qty_other1_s;
                                        $qty_other1 +=  $qty_float_other;
                                    @endphp
                                @endif
                                
                            @endforeach
                            @foreach($reclass_p  as $item)
                                    <tr>
                                        <td>[Reclass]</td>
                                        <td>{{$item->document}}</td>
                                        <td>{{$item->item}}</td>
                                        <td>{{$item->variant}}</td>
                                        <td>{{$item->lot}}</td>
                                        <td>{{(float)str_replace(',','',$item->quantity)}}</td>
                                        <td>{{$item->UOM}}</td>
                                    </tr>
                                    @php 
                                    $c_count++;
                                    $UOM_other1 = $item->UOM;
                                    $qty_other1_s = str_replace(',','',$item->quantity);
                                    $qty_float_other = (float)$qty_other1_s;
                                    $qty_other1 +=  $qty_float_other;
                                    @endphp
                            @endforeach
                            @if($c_count == 0)
                                    <tr>
                                        <td>No Data</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                            @endif   
                        </table>
                        <span class="component-title">Total Quantity: {{$qty_other1}} @if($c_count != 0) {{$UOM_other1}} @endif </span>
                    </div>
      
                </div>
            </div>
            @endif
        </div>
        @endif
    </div>
</div>

@php

    $var = $data[0]->variant;
    if($var == ""){
        $var = "NA";
    }
@endphp
        <a href="/traceability/search/list/{{$data[0]->item}}"><button class="back">Back</button></a>
        <button class="scroll-left" onclick="scrollToleft()" ><i id="scroll-left" class="fa-solid fa-angle-left"></i></button>
        <button class="scroll-right" onclick="scrollToright()"><i  class="fa-solid fa-chevron-right"></i></button>
        <button class="scroll-home" onclick=" scrollToStart()" id="scrollToTopBtn" ><i class="fa-solid fa-house"></i></i></button>
        @if($line_05 != 0)
        <a class="export" href="/traceability/search/{{$data[0]->item}}/{{$var}}/{{$data[0]->lot}}/{{$sub_line}}/{{$line_01}}/{{$line_02}}/{{$line_03}}/{{$line_04}}/{{$line_05}}/export"><button>Export</button></a>

        @elseif($line_04 != 0)
        <a class="export" href="/traceability/search/{{$data[0]->item}}/{{$var}}/{{$data[0]->lot}}/{{$sub_line}}/{{$line_01}}/{{$line_02}}/{{$line_03}}/{{$line_04}}/0/export"><button>Export</button></a>

        @elseif($line_03 != 0)
        <a class="export" href="/traceability/search/{{$data[0]->item}}/{{$var}}/{{$data[0]->lot}}/{{$sub_line}}/{{$line_01}}/{{$line_02}}/{{$line_03}}/0/0/export"><button>Export</button></a>

        @elseif($line_02 != 0)
        <a class="export" href="/traceability/search/{{$data[0]->item}}/{{$var}}/{{$data[0]->lot}}/{{$sub_line}}/{{$line_01}}/{{$line_02}}/0/0/0/export"><button>Export</button></a>

        @elseif($line_01 != 0)
        <a class="export" href="/traceability/search/{{$data[0]->item}}/{{$var}}/{{$data[0]->lot}}/{{$sub_line}}/{{$line_01}}/0/0/0/0/export"><button>Export</button></a>

        @else
        <a class="export" href="/traceability/search/{{$data[0]->item}}/{{$var}}/{{$data[0]->lot}}/{{$sub_line}}/0/0/0/0/0/export"><button>Export</button></a>
        @endif

</div>

<script>
var state_line = {{$line_no}};

// Store the current scroll position before the page refreshes
window.addEventListener('beforeunload', function() {
    sessionStorage.setItem('scrollPosition', JSON.stringify({ scrollX: window.scrollX, scrollY: window.scrollY }));
});

// Restore the scroll position after the page refreshes
window.addEventListener('load', function() {
    var storedScrollPosition = sessionStorage.getItem('scrollPosition');
    if (storedScrollPosition !== null) {
        var scrollPosition = JSON.parse(storedScrollPosition);
        if(state_line != 0){
          window.scrollTo(7000,scrollPosition.scrollY)

          sessionStorage.removeItem('scrollPosition');
        }else{
            window.scrollTo(scrollPosition.scrollX + 7000, scrollPosition.scrollY);
            sessionStorage.removeItem('scrollPosition');
        }
    }
});

</script>
@endsection
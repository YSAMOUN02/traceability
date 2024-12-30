<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    {{-- TailWind --}}
    @vite('resources/css/app.css')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{URL('assets/css/style.css')}}">
    <title>Treaceability</title>
  </head>
  <body>


      <center><div class="title">Traceability Item by Period</div></center>

      <div class="search-bar flex flex-col justify-center ">

          <div>
            <label for="">Search </label>
            <form action="/traceability/item/and/variant/search" method="post">
                @csrf
            <div class="flex items-center" >

                <input name="search" id="search_item" type="text" class="form-control"  placeholder="Item no or Description" required ><br>


                    <button type="submit" id="search_btn" class="p-2 bg-slate-900 cursor-pointer" >

                        <i class="fa-solid fa-magnifying-glass" style="color: #ffffff;"></i>
                    </button>

            </div>
        </form>
          </div>



      </div>
      <div class="container">
        <div class="cont_tainer py-4 px-4 mt-2 bg-white flex flex-col justify-start">
            <div class="w-full">

            <span class="title-item"><i class="me-2 fa-solid fa-calendar-days"></i> From   <b class="text-danger">{{ \Carbon\Carbon::parse($from_date)->format('M d Y') }}</b>    To <i class="mx-2 fa-solid fa-calendar-days"></i> <b class="text-danger">{{ \Carbon\Carbon::parse($to_date)->format('M d Y') }}</b></span>

            </div>


            <div class="border-b w-full border-gray-200 dark:border-gray-700">
                <ul class="flex flex-wrap -mb-px text-lg font-medium text-center text-gray-500 dark:text-gray-400">
                    <li id="list1" class="me-2" onclick="select_tab('info')">
                        <a href="#info" target="_self" class="inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group">
                            <i class="fa-solid fa-circle-info"></i> <span class="ml-2">Item Information</span>
                        </a>
                    </li>
                    <li id="list2" class="me-2" onclick="select_tab('begin')">
                        <a href="#begin" target="_self" class="inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group">
                            <i class="fa-solid fa-boxes-stacked"></i> <span class="ml-2">Begining Inventory Period</span>
                        </a>
                    </li>

                    <li id="list3" class="me-2" onclick="select_tab('process')">
                        <a  href="#process" target="_self" class="inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group">
                            <i class="fa-solid fa-shuffle"></i><span class="ml-2">Process During Period</span>
                        </a>
                    </li>
                    <li id="list4" class="me-2" onclick="select_tab('end')">
                        <a  href="#process" target="_self" class="inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group">
                            <i class="fa-solid fa-box-open"></i><span class="ml-2">Ending Inventory Period</span>
                        </a>
                    </li>

                </ul>
            </div>
            <div class="hidden">


                <div class="modal_item">
                    <form class="w-full flex flex-col justify-center" action="/traceability/item/detail/data" method="post" target="_blank">
                        @csrf
                        <input type="text" name="item" value="{{$item}}"  class="form-control" id="item" placeholder="selected item">
                        <input type="text" name="variant" {{$variant}} class="form-control" id="item" placeholder="selected item">
                        <input class="form-control text-black" name="from_date" value="{{$from_date}}" id="from_date" type="date">
                        <input class="form-control text-black" name="to_date" value="{{$to_date}}" id="to_date" type="date">
        
                        <button id="detail_data" type="submit">submti</button>
                    </form>
                </div>
            </div>

            <div class="w-full " id="content">
                <div id="info" >
                    <h3 class="w-full tab-begin ">
                        <i class="fa-solid fa-star" style="color: #ffc800;"></i> Item Information
                    </h3>
                    <div class="content_tab">
                        @if(!empty($product_info))
                        <ul class="max-w-md space-y-1 text-gray-500 list-disc list-inside dark:text-gray-400">
                            <li>
                                Item Code : {{$product_info->item}}
                            </li>
                            <li>
                                Variant : {{$product_info->variant}}
                            </li>
                            <li>
                                Description :  {{$product_info->Description}}
                            </li>
                            <li>
                                Unit of Measure :  {{$product_info->UOM}}
                            </li>

                            <li>
                                Item Tracking :  {{$product_info->track}}
                            </li>
                        </ul>
                        @endif
                    </div>
                </div>
                <div id="begin" >
                    <h3 class="w-full tab-begin ">
                        <i class="fa-solid fa-star" style="color: #ffc800;"></i> Begining Inventory Period
                    </h3>
                    <div class="content_tab">
                        <table class="small_table">
                            <tr>
                                <th>Location</th>
                                <th>Quantity</th>
                                <th>UOM</th>
                            </tr>
                            @php
                                $total_stock_begin = 0;
                            @endphp
                            @foreach ($begin_stock as $item)
                                <tr>
                                    <td>{{ $item->location}}</td>
                                    <td>{{ (float)$item->total_quantity}}</td>
                                    <td>{{$product_info->UOM}}</td>
                                </tr>
                                @php
                                $total_stock_begin += (float)$item->total_quantity;
                                @endphp
                            @endforeach
                            <tr class="total">
                                <td class="total" >Total Sum Quantity</td>
                                <td class="total">{{$total_stock_begin}}</td>
                                <td class="total">{{$product_info->UOM}}</td>
                            </tr>
                        </table>
                       <button class="back-style mt-2" onclick="view_detail()">Detail</button>
                    </div>
                </div>
                <div id="process" class="mt-5 ">
                    <h3 class="w-full tab-begin">
                        <i class="fa-solid fa-star" style="color: #ffc800;"></i> Process During Period
                    </h3>
                    <div class="content_tab">
                        <table class="small_table">
                            <tr>
                                <th>Process</th>
                                <th>Quantity</th>
                                <th>UOM</th>
                            </tr>
                            @php
                                $qty_total_all_process = $qty_purchase + $qty_purchase_return + $qty_sale_order + $qty_sale_return_order + $qty_consumption + $qty_output + $qty_convert_code + $qty_post_adj +$qty_neg_adj + $total_stock_begin + $qty_reclass;
                            @endphp
                            @if($qty_purchase != 0)
                            <tr>
                                <td>Purchase Order</td>
                                <td>{{(float)$qty_purchase}}</td>
                                <td>{{$product_info->UOM}}</td>
                            </tr>
                            @endif
                            @if($qty_output > 0)
                            <tr>
                                <td>Output</td>
                                <td>{{(float)$qty_output}}</td>
                                <td>{{$product_info->UOM}}</td>
                            </tr>
                            @endif
                            @if($qty_purchase_return != 0)
                            <tr>
                                <td>Purchase Return Order</td>
                                <td>{{(float) $qty_purchase_return}}</td>
                                <td>{{$product_info->UOM}}</td>
                            </tr>
                            @endif
                            @if($qty_sale_order < 0)
                            <tr>
                                <td>Sale Order</td>
                                <td>{{(float)$qty_sale_order}}</td>
                                <td>{{$product_info->UOM}}</td>
                            </tr>
                            @endif
                            @if ((float)$qty_sale_return_order > 0)
                                <tr>
                                    <td>Sale Return Order</td>
                                    <td>{{(float)$qty_sale_return_order}}</td>
                                    <td>{{$product_info->UOM}}</td>
                                </tr>
                            @endif
                            @if($qty_consumption < 0)
                                <tr>
                                    <td>Consumption</td>
                                    <td>{{(float)$qty_consumption}}</td>
                                    <td>{{$product_info->UOM}}</td>
                                </tr>
                            @endif


                            @if($qty_convert_code < 0)
                            <tr>
                                <td>Convert Code</td>
                                <td>{{(float)$qty_convert_code}}</td>
                                <td>{{$product_info->UOM}}</td>
                            </tr>
                            @endif
                            @if($qty_post_adj != 0)
                            <tr>
                                <td>Positive Adjustment</td>
                                <td>{{(float)$qty_post_adj}}</td>
                                <td>{{$product_info->UOM}}</td>
                            </tr>
                            @endif
                            @if ($qty_neg_adj != 0)
                                <tr>
                                    <td>Negative Adjustment</td>
                                    <td>{{(float)$qty_neg_adj}}</td>
                                    <td>{{$product_info->UOM}}</td>
                                </tr>
                            @endif
                            @if ($total_stock_begin != 0)
                            <tr>
                                <td>Begin Stock</td>
                                <td>{{$total_stock_begin}}</td>
                                <td>{{$product_info->UOM}}</td>
                            </tr>
                        @endif
                            @if ($qty_reclass != 0)
                                <tr>
                                <td>Reclass</td>
                                <td>{{(float)$qty_reclass}}</td>
                                <td>{{$product_info->UOM}}</td>
                                </tr>
                            @endif

                            <tr class="total">
                                <td class="total" >Total Sum Quantity</td>

                                <td class="total">{{$qty_total_all_process}}</td>
                                <td  class="total">{{$product_info->UOM}}</td>
                            </tr>

                        </table>
                        <button class="back-style mt-2" onclick="view_detail()">Detail</button>
                    </div>
                </div>
                <div id="end" >
                    <h3 class="w-full tab-begin ">
                        <i class="fa-solid fa-star" style="color: #ffc800;"></i>  Ending Inventory Period
                    </h3>
                    <div class="content_tab">
                        <table class="small_table">

                            <tr>
                                <th>Location</th>
                                <th>Quantity</th>
                                <th>UOM</th>
                            </tr>
                            @php
                                $total_stock_end = 0;
                            @endphp
                            @foreach ($end_stock as $item)
                                <tr>
                                    <td>{{ $item->location}}</td>
                                    <td>{{ (float)$item->total_quantity}}</td>
                                    <td>{{$item->UOM}}</td>
                                </tr>
                                @php
                                $total_stock_end += (float)$item->total_quantity;
                                @endphp
                            @endforeach
                            <tr class="total">
                                <td class="total" >Total Sum Quantity</td>
                                <td class="total">{{$total_stock_end}}</td>
                                <td class="total">{{$product_info->UOM}}</td>
                            </tr>
                        </table>
                        <button class="back-style mt-2" onclick="view_detail()">Detail</button>
                    </div>
                </div>
            </div>

        </div>
        <div class="corner-right">
            <a href="/"><button  class="back-style">Main Menu</button></a>
            <a href="/"><button class="back-style">Back</button></a>
        </div>


      </div>
      </div>


    <div id="loading">
        <div  id="loading_style" class="flex items-center justify-center w-56 h-56 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700 ">
            <div role="status">
                <svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/><path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/></svg>

            </div>
            <h1>Loading ....</h1>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="{{URL('assets/JS/script.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    <script>

                  // When the window is loading, show the loading graphic
        window.onload = function() {
            // Hide the loading graphic and show the content once the page is fully loaded

            document.querySelector("#loading").style.display = 'none';

        };


        const button = document.querySelector('#search_btn');
        // d="search_button"
        document.addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            button.click();
        }
        });
    </script>
  </body>
</html>



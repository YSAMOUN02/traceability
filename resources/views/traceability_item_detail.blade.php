<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    {{-- TailWind --}}
    @vite('resources/css/app.css')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ URL('assets/css/style.css') }}">
    <title>Treaceability</title>
</head>

<body>


    <center>
        <div class="title">Traceability Item by Period</div>
    </center>
    <div class="container">
        <div class="cont_tainer py-4 px-4 mt-2 bg-white flex flex-col justify-start">
            <div class="w-full mb-2">

                <span class="title-item"><i class="me-2 fa-solid fa-calendar-days"></i> From <b
                        class="text-danger">{{ \Carbon\Carbon::parse($from_date)->format('M d Y') }}</b> To <i
                        class="mx-2 fa-solid fa-calendar-days"></i> <b
                        class="text-danger">{{ \Carbon\Carbon::parse($to_date)->format('M d Y') }}</b></span>
                <div>
                    <h3 class="w-full tab-begin ">
                        <i class="fa-solid fa-star" style="color: #ffc800;"></i> Item Information
                    </h3>
                    <div class="flex justify-between">
                        <div class="flex flex-col mt-4">

                            <div class="content_tab">
                                @if (!empty($product_info))
                                    <ul
                                        class="max-w-md space-y-1 text-gray-500 list-disc list-inside dark:text-gray-400">
                                        <li>
                                            Item Code : {{ $product_info->item }}
                                        </li>
                                        <li>
                                            Variant : {{ $product_info->variant }}
                                        </li>
                                        <li>
                                            Description : {{ $product_info->Description }}
                                        </li>
                                        <li>
                                            Unit of Measure : {{ $product_info->UOM }}
                                        </li>

                                        <li>
                                            Item Tracking : {{ $product_info->track }}
                                        </li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                        <div class="flex ">
                            <div class="border-b w-full border-gray-200 dark:border-gray-700">
                                <ul
                                    class="flex flex-wrap justify-end -mb-px text-lg font-medium text-center text-gray-500 dark:text-gray-400">

                                    <li id="list1" class="me-2">
                                        <a href="#beginstock" target="_self"
                                            class="inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group">
                                            <i class="fa-solid fa-dolly"></i> <span class="ml-2">Begin Stock</span>
                                        </a>
                                    </li>

                                    @if ($count_purchase!= 0)
                                        <li id="list1" class="me-2">
                                            <a href="#purchase" target="_self"
                                                class="inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group">
                                                <i class="fa-solid fa-cart-shopping"></i> <span class="ml-2">Purchase
                                                    Order</span>
                                            </a>
                                        </li>
                                    @endif
                                    @if ($qty_output != 0)
                                    <li id="list4" class="me-2">
                                        <a href="#output" target="_self"
                                            class="inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group">
                                            <i class="fa-solid fa-cubes-stacked"></i><span class="ml-2">Output</span>
                                        </a>
                                    </li>
                                    @endif
                                    @if ($count_purchase_retun != 0)
                                        <li id="list2" class="me-2">
                                            <a href="#purchase_return" target="_self"
                                                class="inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group">
                                                <i class="fa-solid fa-cart-flatbed"></i><span class="ml-2">Purchase
                                                    Return</span>
                                            </a>
                                        </li>
                                    @endif

                                    @if ($count_sale_order != 0)
                                        <li id="list3" class="me-2">
                                            <a href="#sale_order" target="_self"
                                                class="inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group">
                                                <i class="fa-solid fa-money-check-dollar"></i><span class="ml-2">Sale
                                                    Order</span>
                                            </a>
                                        </li>
                                    @endif

                                    @if ($count_sale_return_order != 0)
                                        <li id="list4" class="me-2">
                                            <a href="#sale_return_order" target="_self"
                                                class="inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group">
                                                <i class="fa-solid fa-rotate-left"></i><span class="ml-2">Sale
                                                    Return</span>
                                            </a>
                                        </li>
                                    @endif
                                    @if ($count_consumption != 0)
                                        <li id="list4" class="me-2">
                                            <a href="#consumption" target="_self"
                                                class="inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group">
                                                <i class="fa-solid fa-arrow-up-from-water-pump"></i><span
                                                    class="ml-2">Consumption</span>
                                            </a>
                                        </li>
                                    @endif
                                    @if ($count_qty_neg_adj != 0)
                                        <li id="list4" class="me-2">
                                            <a href="#neg_adj" target="_self"
                                                class="inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group">
                                                <i class="fa-solid fa-square-minus"></i><span
                                                    class="ml-2">Negative Adjustment</span>
                                            </a>
                                        </li>
                                    @endif

                                    @if($count_qty_post_adj != 0)
                                    <li id="list4" class="me-2">
                                        <a href="#positive_adj" target="_self"
                                            class="inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group">
                                            <i class="fa-solid fa-square-plus"></i><span
                                                class="ml-2">Positive Adjustment</span>
                                        </a>
                                    </li>
                                    @endif
                                    @if ($qty_reclass != 0)
                                        <li id="list4" class="me-2">
                                            <a href="#reclass" target="_self"
                                                class="inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group">
                                                <i class="fa-solid fa-pen-to-square"></i><span
                                                    class="ml-2">Reclass</span>
                                            </a>
                                        </li>
                                    @endif

                                    {{-- $count_qty_neg_adj --}}
                                    <li id="list4" class="me-2">
                                        <a href="#endstock" target="_self"
                                            class="inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group">
                                            <i class="fa-solid fa-box-open"></i><span
                                                class="ml-2">End Stock</span>
                                        </a>
                                    </li>



                                </ul>
                            </div>

                        </div>
                    </div>

                </div>
                <h1 class="w-full tab-begin  mt-2">
                    <i class="fa-solid fa-star" style="color: #ffc800;"></i> {{ $title_type }}
                </h1>

            </div>
    <div id="content2">
                @if (!empty($begin_stock))


                <div class="mt-5" id="beginstock">
                    <span class="heading "><i class="fa-solid fa-dolly"></i>Begin Stock</span>
                    <table class="custom_big_table mt-2">
                        <tr>
                            <th>Location</th>
                            <th>Item</th>
                            <th>Variant</th>
                            <th>Quantity</th>
                            <th>UOM</th>
                            <th>Lot</th>
                            <th class="whitespace-nowrap">Expire</th>
                        </tr>



                        @php
                            $total_stock_begin = 0;
                        @endphp
                        @foreach ($begin_stock as $item)
                            <tr>
                                <td>{{ $item->location }}</td>
                                <td>{{  $item->item}}</td>
                                <td>{{  $item->variant}}</td>
                                <td>{{ (float) $item->total_quantity }}</td>
                                <td>{{ $product_info->UOM }}</td>
                                <td>{{ $item->lot }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->expire)->format('M d Y') }}</td>
                            </tr>
                            @php
                                $total_stock_begin += (float) $item->total_quantity;
                            @endphp
                        @endforeach
                        <tr class="total  whitespace-nowrap">
                            <td class="total">Total Sum Quantity</td>
                            <td class="total"></td>
                            <td class="total"></td>
                            <td class="total"></td>
                            <td class="total"></td>
                            <td class="total"></td>
                            <td class="total">{{ $total_stock_begin.' ' . $product_info->UOM }}</td>
                        </tr>



                    </table>
                    </div>
                @endif

                @if (!empty($data_purchase))
                    @if ($count_purchase != 0)
                        <div id="purchase" class="mt-5">
                            <span class="heading "><i class="mx-2 fa-solid fa-cart-shopping"></i> Purchase Order</span>
                            <table class="custom_big_table mt-2">
                                @php
                                    $purchase_qty = 0;
                                    $no = 1;
                                @endphp
                                <tr>
                                    <th>No</th>
                                    <th>Posting Date</th>
                                    <th>Document</th>
                                    <th>Item</th>
                                    <th>Variant</th>
                                    <th>Quantity</th>
                                    <th>UOM</th>
                                    <th>Lot</th>
                                    <th>Location</th>
                                    <th>Vendor</th>
                                    <th>Vendor Name</th>

                                </tr>
                                @foreach ($data_purchase as $item)
                                    <tr>
                                        <td>{{ $no }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->posting_date)->format('M d Y') }}</td>
                                        <td>{{ $item->document }}</td>
                                        <td>{{ $item->item}}</td>
                                        <td>{{ $item->variant}}</td>
                                        <td>{{ (float) $item->quantity }}</td>
                                        <td>{{ $product_info->UOM }}</td>
                                        <td>{{ $item->lot }}</td>
                                        <td>{{ $item->location }}</td>
                                        <td>{{ $item->source }}</td>
                                        <td>{{ $item->vendor_name }}</td>



                                    </tr>
                                    @php
                                        $no++;
                                        $purchase_qty += (float) $item->quantity;
                                    @endphp
                                @endforeach

                                <tr class="total  whitespace-nowrap">
                                    <td class="total">Total Sum Quantity</td>
                                    <td class="total"></td>
                                    <td class="total"></td>
                                    <td class="total"></td>
                                    <td class="total"></td>
                                    <td class="total"></td>
                                    <td class="total"></td>
                                    <td class="total"></td>
                                    <td class="total"></td>
                                    <td class="total"></td>
                                    <td class="total">{{ $purchase_qty . ' ' . $product_info->UOM }}</td>
                                </tr>

                            </table>
                        </div>
                    @endif


                @endif


                @if (!empty($data_output))
                    @if ($qty_output != 0)
                        <div class="mt-2" id="output">
                            <span class="heading "><i class="fa-solid fa-cubes-stacked"></i>Output</span>
                            <table class="custom_big_table mt-2">
                                @php
                                    $output_qty = 0;
                                    $no = 1;
                                @endphp
                                <tr>
                                    <th>No</th>
                                    <th>Posting Date</th>
                                    <th>Document</th>
                                    <th>Item</th>
                                    <th>Variant</th>
                                    <th>Quantity</th>
                                    <th>UOM</th>
                                    <th>Lot</th>
                                    <th>Location</th>


                                </tr>
                                @foreach ($data_output as $item)
                                    <tr>
                                        <td>{{ $no }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->posting_date)->format('M d Y') }}</td>
                                        <td>{{ $item->document }}</td>
                                        <td>{{$item->item}}</td>
                                        <td>{{$item->variant}}</td>
                                        <td>{{ (float) $item->quantity }}</td>
                                        <td>{{ $product_info->UOM }}</td>
                                        <td>{{ $item->lot }}</td>
                                        <td>{{ $item->location }}</td>

                                    </tr>
                                    @php
                                        $no++;
                                        $output_qty += (float) $item->quantity;
                                    @endphp
                                @endforeach

                                <tr class="total  whitespace-nowrap">
                                    <td class="total">Total Sum Quantity</td>
                                    <td class="total"></td>
                                    <td class="total"></td>
                                    <td class="total"></td>
                                    <td class="total"></td>
                                    <td class="total"></td>
                                    <td class="total"></td>
                                    <td class="total"></td>
                                    <td class="total">{{ $output_qty . ' ' . $product_info->UOM }}</td>
                                </tr>

                            </table>
                        </div>
                    @endif


                @endif
                @if (!empty($sale_return_order_data))
                    @if ($count_sale_return_order != 0)
                        <div class="mt-5" id="sale_return_order">
                            <span class="heading "> <i class="mx-2 fa-solid fa-cart-flatbed"></i>Sale Return
                                Order or Undo</span>
                            <table class="custom_big_table mt-2">
                                @php
                                    $no = 1 ;
                                    $sale_qty = 0;
                                @endphp
                                <tr>
                                    <th>No</th>
                                    <th>Posting Date</th>
                                    <th>Document</th>
                                    <th>Quantity</th>
                                    <th>UOM</th>
                                    <th>Lot</th>
                                    <th>Location</th>
                                    <th>Custmer</th>
                                    <th>Custmer Name</th>

                                </tr>
                                @foreach ($sale_return_order_data as $item)
                                    <tr>
                                        <td>{{ $no }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->posting_date)->format('M d Y') }}</td>
                                        <td>{{ $item->document }}</td>
                                        <td>{{ (float) $item->quantity }}</td>
                                        <td>{{ $product_info->UOM }}</td>
                                        <td>{{ $item->lot }}</td>
                                        <td>{{ $item->location }}</td>
                                        <td>{{ $item->source }}</td>
                                        <td>{{ $item->customer_name }}</td>
                                    </tr>
                                    @php
                                        $no++;
                                        $sale_qty += (float) $item->quantity;
                                    @endphp
                                @endforeach

                                <tr class="total  whitespace-nowrap">
                                    <td class="total">Total Sum Quantity</td>
                                    <td class="total"></td>
                                    <td class="total"></td>
                                    <td class="total"></td>
                                    <td class="total"></td>
                                    <td class="total"></td>
                                    <td class="total"></td>
                                    <td class="total"></td>
                                    <td class="total">{{ $sale_qty . ' ' . $product_info->UOM }}</td>
                                </tr>

                            </table>
                        </div>
                    @endif


                @endif

               @if (!empty($data_post_adj))
                   @if ($count_qty_post_adj != 0)
                   <div class="mt-5" id="positive_adj">
                    <span class="heading "> <i class="mx-2 fa-solid fa-cart-flatbed"></i>Positive Adjustment</span>
                    <table class="custom_big_table mt-2">
                        @php
                            $no = 1 ;
                            $post_adj = 0;
                        @endphp
                        <tr>
                            <th>No</th>
                            <th>Posting Date</th>
                            <th>Document</th>
                            <th>Item</th>
                            <th>Variant</th>
                            <th>Quantity</th>
                            <th>UOM</th>
                            <th>Lot</th>
                            <th>Location</th>


                        </tr>
                        @foreach ($data_post_adj as $item)
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->posting_date)->format('M d Y') }}</td>
                                <td>{{ $item->document }}</td>
                                <td>{{$item->item}}</td>
                                <td>{{$item->variant}}</td>
                                <td>{{ (float) $item->quantity }}</td>
                                <td>{{ $product_info->UOM }}</td>
                                <td>{{ $item->lot }}</td>
                                <td>{{ $item->location }}</td>

                            </tr>
                            @php
                                $no++;
                                $post_adj += (float) $item->quantity;
                            @endphp
                        @endforeach

                        <tr class="total  whitespace-nowrap">
                            <td class="total">Total Sum Quantity</td>

                            <td class="total"></td>
                            <td class="total"></td>
                            <td class="total"></td>
                            <td class="total"></td>
                            <td class="total"></td>
                            <td class="total"></td>
                            <td class="total"></td>
                            <td class="total">{{ $post_adj . ' ' . $product_info->UOM }}</td>
                        </tr>

                    </table>
                </div>

                   @endif
               @endif

               {{-- $qty_reclass = count($data_reclass); --}}
               @if (!empty($data_reclass))
               @if ($qty_reclass != 0)
               <div class="mt-5" id="reclass">
                <span class="heading "> <i class="fa-solid fa-pen-to-square"></i>Reclass</span>
                <table class="custom_big_table mt-2">
                    @php
                        $no = 1 ;
                        $reclass_qty_sum = 0;
                    @endphp
                    <tr>
                        <th>No</th>
                        <th>Posting Date</th>
                        <th>Document</th>
                        <th>Item</th>
                        <th>Variant</th>
                        <th>Quantity</th>
                        <th>UOM</th>
                        <th>Lot</th>
                        <th>Location</th>
                        <th>Expire</th>

                    </tr>
                    @foreach ($data_reclass as $item)
                        <tr>
                            <td>{{ $no }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->posting_date)->format('M d Y') }}</td>
                            <td>{{ $item->document }}</td>
                            <td>{{$item->item}}</td>
                            <td>{{$item->variant}}</td>
                            <td>{{ (float) $item->quantity }}</td>
                            <td>{{ $product_info->UOM }}</td>
                            <td>{{ $item->lot }}</td>
                            <td>{{ $item->location }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->expire)->format('M d Y') }}</td>

                        </tr>
                        @php
                            $no++;
                            $reclass_qty_sum += (float) $item->quantity;
                        @endphp
                    @endforeach

                    <tr class="total  whitespace-nowrap">
                        <td class="total">Total Sum Quantity</td>

                        <td class="total"></td>
                        <td class="total"></td>
                        <td class="total"></td>
                        <td class="total"></td>
                        <td class="total"></td>
                        <td class="total"></td>
                        <td class="total"></td>
                        <td class="total"></td>
                        <td class="total">{{ $reclass_qty_sum . ' ' . $product_info->UOM }}</td>
                    </tr>

                </table>
            </div>

               @endif
           @endif


               @if (!empty($qty_neg_data))
                   @if ($count_qty_neg_adj != 0)
                   <div class="mt-5" id="neg_adj">
                    <span class="heading "> <i class="fa-solid fa-square-minus"></i>Negative Adjustment</span>
                    <table class="custom_big_table mt-2">
                        @php
                            $no = 1 ;
                            $neg_adj = 0;
                        @endphp
                        <tr>
                            <th>No</th>
                            <th>Posting Date</th>
                            <th>Document</th>
                            <th>Item</th>
                            <th>Variant</th>
                            <th>Quantity</th>
                            <th>UOM</th>
                            <th>Lot</th>
                            <th>Location</th>


                        </tr>
                        @foreach ($qty_neg_data as $item)
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->posting_date)->format('M d Y') }}</td>
                                <td>{{ $item->document }}</td>
                                <td>{{$item->item}}</td>
                                <td>{{$item->variant}}</td>
                                <td>{{ (float) $item->quantity }}</td>
                                <td>{{ $product_info->UOM }}</td>
                                <td>{{ $item->lot }}</td>
                                <td>{{ $item->location }}</td>

                            </tr>
                            @php
                                $no++;
                                $neg_adj += (float) $item->quantity;
                            @endphp
                        @endforeach

                        <tr class="total  whitespace-nowrap">
                            <td class="total">Total Sum Quantity</td>
                            <td class="total"></td>
                            <td class="total"></td>
                            <td class="total"></td>
                            <td class="total"></td>
                            <td class="total"></td>
                            <td class="total"></td>
                            <td class="total"></td>

                            <td class="total">{{ $neg_adj . ' ' . $product_info->UOM }}</td>
                        </tr>

                    </table>
                </div>

                   @endif
               @endif

                @if (!empty($data_purchase_return))
                    @if ($count_purchase_retun != 0)
                        <div class="mt-5" id="purchase_return">
                            <span class="heading "> <i class="mx-2 fa-solid fa-cart-flatbed"></i>Purchase Return Or
                                Purchase Undo</span>
                            <table class="custom_big_table mt-2">
                                @php
                                    $purchase_qty = 0;
                                    $no = 1;
                                @endphp
                                <tr>


                                    <th>No</th>
                                    <th>Posting Date</th>
                                    <th>Document</th>
                                    <th>Item</th>
                                    <th>Variant</th>
                                    <th>Quantity</th>
                                    <th>UOM</th>
                                    <th>Lot</th>
                                    <th>Location</th>
                                    <th>Vendor</th>
                                    <th>Vendor Name</th>

                                </tr>
                                @foreach ($data_purchase_return as $item)
                                    <tr>
                                        <td>{{ $no }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->posting_date)->format('M d Y') }}</td>
                                        <td>{{ $item->document }}</td>
                                        <td>{{$item->item}}</td>
                                        <td>{{$item->variant}}</td>
                                        <td>{{ (float) $item->quantity }}</td>
                                        <td>{{ $product_info->UOM }}</td>
                                        <td>{{ $item->lot }}</td>
                                        <td>{{ $item->location }}</td>
                                        <td>{{ $item->source }}</td>
                                        <td>{{ $item->vendor_name }}</td>
                                    </tr>
                                    @php
                                        $no++;
                                        $purchase_qty += (float) $item->quantity;
                                    @endphp
                                @endforeach

                                <tr class="total  whitespace-nowrap">
                                    <td class="total">Total Sum Quantity</td>
                                    <td class="total"></td>
                                    <td class="total"></td>
                                    <td class="total"></td>
                                    <td class="total"></td>
                                    <td class="total"></td>
                                    <td class="total"></td>
                                    <td class="total"></td>
                                    <td class="total"></td>
                                    <td class="total"></td>
                                    <td class="total">{{ $purchase_qty . ' ' . $product_info->UOM }}</td>
                                </tr>

                            </table>
                        </div>
                    @endif
                  @endif
                    @if (!empty($sale_order_data))
                        @if ($count_sale_order != 0)
                            <div class="mt-5" id="sale_order">
                                <span class="heading "><i class="mx-2 fa-solid fa-money-check-dollar"></i>Sale
                                    Order</span>
                                <table class="custom_big_table mt-2">
                                    @php
                                        $sale_qty = 0;
                                        $no = 1;
                                    @endphp
                                    <tr>
                                        <th>No</th>
                                        <th>Posting Date</th>
                                        <th>Document</th>
                                        <th>Item</th>
                                        <th>Variant</th>
                                        <th>Quantity</th>
                                        <th>UOM</th>
                                        <th>Lot</th>
                                        <th>Location</th>
                                        <th>Custmer</th>
                                        <th>Custmer Name</th>

                                    </tr>
                                    @foreach ($sale_order_data as $item)
                                        <tr>
                                            <td>{{ $no }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->posting_date)->format('M d Y') }}</td>
                                            <td>{{ $item->document }}</td>
                                            <td>{{$item->item}}</td>
                                            <td>{{$item->variant}}</td>
                                            <td>{{ (float) $item->quantity }}</td>
                                            <td>{{ $product_info->UOM }}</td>
                                            <td>{{ $item->lot }}</td>
                                            <td>{{ $item->location }}</td>
                                            <td>{{ $item->source }}</td>
                                            <td>{{ $item->customer_name }}</td>
                                        </tr>
                                        @php
                                            $no++;
                                            $sale_qty += (float) $item->quantity;
                                        @endphp
                                    @endforeach

                                    <tr class="total  whitespace-nowrap">
                                        <td class="total">Total Sum Quantity</td>
                                        <td class="total"></td>
                                        <td class="total"></td>
                                        <td class="total"></td>
                                        <td class="total"></td>
                                        <td class="total"></td>
                                        <td class="total"></td>
                                        <td class="total"></td>
                                        <td class="total"></td>
                                        <td class="total"></td>
                                        <td class="total">{{ $sale_qty . ' ' . $product_info->UOM }}</td>
                                    </tr>

                                </table>
                            </div>
                        @endif


                    @endif


                                 {{-- 'count_consumption' =>$count_consumption,
                                    'consumption_data' => $consumption_data --}}
                    @if (!empty($consumption_data))
                        @if ($count_consumption != 0)
                            <div class="mt-5" id="consumption">
                                <span class="heading "> <i
                                        class="fa-solid fa-arrow-up-from-water-pump"></i>Consumption
                                </span>
                                <table class="custom_big_table mt-2">
                                    @php
                                        $consumption_qty = 0;
                                        $no = 1;
                                    @endphp
                                    <tr>
                                        <th>No</th>
                                        <th>Posting Date</th>
                                        <th>Document</th>
                                        <th>Item</th>
                                        <th>Variant</th>
                                        <th>Quantity</th>
                                        <th>UOM</th>
                                        <th>Lot</th>
                                        <th>Location</th>

                                        <th>Use For</th>

                                    </tr>
                                    @foreach ($consumption_data as $item)
                                        <tr>
                                            <td>{{ $no }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->posting_date)->format('M d Y') }}</td>
                                            <td>{{ $item->document }}</td>
                                            <td>{{ $item->item}}</td>
                                            <td>{{$item->variant}}</td>
                                            <td>{{ (float) $item->quantity }}</td>
                                            <td>{{ $product_info->UOM }}</td>
                                            <td>{{ $item->lot }}</td>
                                            <td>{{ $item->location }}</td>
                                            <td>{{ $item->source }}</td>

                                        </tr>
                                        @php
                                            $no++;
                                            $consumption_qty += (float) $item->quantity;
                                        @endphp
                                    @endforeach

                                    <tr class="total  whitespace-nowrap">
                                        <td class="total">Total Sum Quantity</td>
                                        <td class="total"></td>
                                        <td class="total"></td>
                                        <td class="total"></td>
                                        <td class="total"></td>
                                        <td class="total"></td>
                                        <td class="total"></td>
                                        <td class="total"></td>
                                        <td class="total"></td>
                                        <td class="total">{{ $consumption_qty . ' ' . $product_info->UOM }}</td>
                                    </tr>

                                </table>
                            </div>
                        @endif


                    @endif
                    @if (!empty($end_stock))
                    @if($count_end_stock != 0)
                    <div class="mt-5" id="endstock">
                        <span class="heading "><i class="fa-solid fa-box-open"></i>End Stock</span>
                        <table class="mt-2">

                            @php
                                $total_stock_end = 0;
                            @endphp
                            <tr>
                                <th>Location</th>
                                <th>Item</th>
                                <th>Variant</th>
                                <th>Quantity</th>
                                <th>UOM</th>

                                <th>Lot</th>
                                <th class="whitespace-nowrap">Expire</th>
                            </tr>
                            @foreach ($end_stock as $item)
                                <tr>
                                    <td>{{ $item->location }}</td>
                                    <td>{{$item->item}}</td>
                                    <td>{{$item->variant}}</td>
                                    <td>{{ (float) $item->total_quantity }}</td>
                                    <td>{{ $product_info->UOM }}</td>
                                    <td>{{ $item->lot }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->expire)->format('M d Y') }}</td>
                                </tr>
                                @php
                                    $total_stock_end += (float) $item->total_quantity;
                                @endphp
                            @endforeach
                            <tr class="total  whitespace-nowrap">
                                <td class="total">Total Sum Quantity</td>
                                <td class="total"></td>
                                <td class="total"></td>
                                <td class="total"></td>
                                <td class="total"></td>
                                <td class="total"></td>
                                <td class="total">{{ $total_stock_end . $product_info->UOM }}</td>
                            </tr>

                        </table>
                    </div>
                    @endif
                    @endif
            </div>
        </div>
        </div>
    </div>
    </div>


    <div id="loading">
        <div id="loading_style"
            class="flex items-center justify-center w-56 h-56 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700 ">
            <div role="status">
                <svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
                    viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                        fill="currentColor" />
                    <path
                        d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                        fill="currentFill" />
                </svg>

            </div>
            <h1>Loading ....</h1>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="{{ URL('assets/JS/script.js') }}"></script>
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

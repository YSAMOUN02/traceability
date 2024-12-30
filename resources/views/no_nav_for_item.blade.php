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
            <div class="flex items-center" >
                @if (!empty($search))
                <input name="search" value="{{$search}}" id="search_item" type="text" class="form-control"  placeholder="Item no or Description" required ><br>
                @else

                <input name="search" id="search_item" type="text" class="form-control"  placeholder="Item no or Description" required ><br>
                @endif



                    <button type="button" id="btn_search" class="p-2 bg-slate-900 cursor-pointer" onclick="traceability(1)">

                        <i class="fa-solid fa-magnifying-glass" style="color: #ffffff;"></i>
                    </button>
                    <span class="text-white mx-5 text-3xl" id="total_record_show">
                        Total Item Found : {{$items->total_record}}
                    </span>
            </div>
          </div>



      </div>
      <div class="cont_tainer">
        @if(session('message'))
        <div class="alert alert-danger">
            {{ session('message') }}
        </div>
        @endif


            <table class="table-hover">
                <tr>
                    <th scope="col" class="px-2 py-1  lg:px-6 lg:py-4  md:px-4  md:py-2" >No</th>
                    <th scope="col" class="px-2 py-1  lg:px-6 lg:py-4  md:px-4  md:py-2" >Item</th>
                    <th scope="col" class="px-2 py-1  lg:px-6 lg:py-4  md:px-4  md:py-2" >Description</th>
                    <th scope="col" class="px-2 py-1  lg:px-6 lg:py-4  md:px-4  md:py-2" >Unit</th>
                    <th scope="col" class="px-2 py-1  lg:px-6 lg:py-4  md:px-4  md:py-2" >Item Type</th>
                    <th scope="col" class="px-2 py-1  lg:px-6 lg:py-4  md:px-4  md:py-2" >Traceability</th>
                </tr>

                <tbody id="item_table">
                    @php
                        $no = 0;

                    @endphp
                    @foreach ($items->data as $item)
                        <tr>


                            <td class="px-2 py-1  lg:px-6 lg:py-4  md:px-4  md:py-2  ">{{$no+1}}</td>


                            <td class="px-2 py-1  lg:px-6 lg:py-4  md:px-4  md:py-2  ">{{$item->Item}}</td>

                            <td class="px-2 py-1  lg:px-6 lg:py-4  md:px-4  md:py-2  ">{{$item->Description}}</td>

                            <td class="px-2 py-1  lg:px-6 lg:py-4  md:px-4  md:py-2  ">{{$item->UOM}}</td>


                            <td class="px-2 py-1  lg:px-6 lg:py-4  md:px-4  md:py-2  ">{{$item->type}}</td>

                            <td class="px-2 py-1  lg:px-6 lg:py-4  md:px-4  md:py-2  "><button onclick="fetch_varaint({{$no}})" class="openModalButton"><i class="fa-solid fa-eye"></i></button></td>
                        </tr>
                        @php
                        $no ++;

                    @endphp
                    @endforeach


                </tbody>


            </table>

        </div>
        <div class="corner-right flex">
            <nav aria-label="Page navigation example">
                <ul class="flex items-center -space-x-px h-8 text-sm" id="pagination">
                  @if($page > 1)
                  <li>
                    <a href="/traceability/item/peroid/page={{$page-1}}" class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                      <span class="sr-only">Previous</span>
                      <svg class="w-2.5 h-2.5 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                      </svg>
                    </a>
                  </li>
                  @endif
                  @php
                  $total_page = $items->total_page;
                  $left_limit = max(1, $page - 5); // Set the left boundary, but not below 1
                  $right_limit = min($total_page, $page + 5); // Set the right boundary, but not above the total pages
                  @endphp
                  @for ($i =   $left_limit;$i <= $right_limit; $i++)

                        @if ($page == $i)
                        <li >
                            <a href="/traceability/item/peroid/page={{$i}}"  aria-current="page" class="z-10 flex items-center justify-center px-3 h-8 leading-tight text-blue-600 border border-blue-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white">{{$i}}</a>
                        </li>
                        @else
                        <li>
                            <a href="/traceability/item/peroid/page={{$i}}" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">{{$i}}</a>
                        </li>
                        @endif
                  @endfor
                  @if($page != $total_page)
                  <li>
                    <a href="/traceability/item/peroid/page={{$page+1}}" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        <span class="sr-only">Next</span>
                        <svg class="w-2.5 h-2.5 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                    </a>

                    </li>
                  @endif

                </ul>
              </nav>
            <a href="/"><button class="back-style ml-2">Back</button></a>


    </div>

    <div id="modal_hidden">
        <div class="modal_item">
            <form class="w-full flex flex-col justify-center" action="/traceability/item/and/variant" method="post">
                @csrf
                <div  class="flex justify-between">
                    <span class="text-rose-700">Select Variant and Date</span>
                    <button type="button" onclick="close_modal('modal_hidden')"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <label for="">Selected Item</label>
                <input type="text" name="item" readonly  class="form-control" id="item" placeholder="selected item">
                <label for="">Variant</label>

                <select name="variant" id="variant" disabled class="form-control">


                </select>
                <label for="">From</label>
                <input class="form-control text-black" name="from_date" id="from_date" type="date">
                <label for="">To Date</label>
                <input class="form-control text-black" name="to_date" id="to_date" type="date">

                <button type="submit" class="focus:outline-none mt-2 text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">View Traceability</button>
            </form>
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
    <script>

        let data_item = @json($items->data);

                  // When the window is loading, show the loading graphic
        window.onload = function() {
            // Hide the loading graphic and show the content once the page is fully loaded

            document.querySelector("#loading").style.display = 'none';

        };

        const button = document.querySelector('#btn_search');

        // id="search_button"
        document.addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            button.click();
        }
        });
    </script>
  </body>
</html>



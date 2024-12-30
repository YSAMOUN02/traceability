<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=2.0">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    {{-- TailWind --}}
    @vite('resources/css/app.css')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{URL('assets/css/style.css')}}">
    <title>Treaceability</title>
  </head>
  <body>


      <center><div class="title">Traceability</div></center>
      <div class="search-bar flex">
        <form action="/traceability/search" method="post">
            @csrf
            <label for="">Search </label>
            @if(!empty($search_data))
            <input name="search" type="text" class="form-control"  placeholder="Item no or Lot No" value="{{$search_data}}" required><br>
            @else
            <input name="search" type="text" class="form-control"  placeholder="Item no or Lot No" required ><br>
            @endif
            <button>Submit</button>
            <i class="fa-solid fa-arrow-turn-down"></i>
        </form>

            <div class="date_refresh">
                <button type="button" class="focus:outline-none text-black bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                    Refresh
                </button>
            </div>
      </div>
                  @yield('content')



    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="{{URL('assets/JS/script.js')}}"></script>
  </body>
</html>



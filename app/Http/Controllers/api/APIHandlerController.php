<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;

use App\Models\Item_ledger_entry;
use App\Models\Item;
use App\Models\Item_lot;

class APIHandlerController extends Controller
{
    public function fetch_data(Request $request){

        // Initialize
        $search = $request->search ?? 'NA';
        $page = $request->page ?? 1;

        $lower = strtolower($search);
        $upper = strtoupper($search);
        $count = 0;
        $limit = 150;
        $total_record = 0;
        $total_pages = 0;
        $offset = 0;
        if ($page != 0) {
            $offset = ($page - 1) * $limit;
        }

        if ($search != 'NA') {
            $sql = Item::orderBy('Item', 'asc');
            $sql->where('Item', 'like', '%' . $lower . '%');
            $total_record = $sql->count();
            $sql->offset($offset);
            $sql->limit($limit);
            $data = $sql->get();

            $count = count($data);
            $total_pages = ceil($total_record / $limit);
        }

        if ($count == 0) {
            $sql = Item::orderBy('Item', 'asc');
            $sql->where('Item', 'like', '%' . $upper . '%');
            $total_record = $sql->count();
            $sql->offset($offset);
            $sql->limit($limit);
            $data = $sql->get();

            $count = count($data);
            $total_pages = ceil($total_record / $limit);
        }

        // Search By Description when no data found
        if ($count == 0) {
            // Lower Case Description
            $sql = Item::orderBy('Item', 'asc');
            $sql->where('Description', 'like', '%' . $lower . '%');
            $total_record = $sql->count();
            $sql->offset($offset);
            $sql->limit($limit);
            $data = $sql->get();

            $count = count($data);
            $total_pages = ceil($total_record / $limit);
        }

        // Upper Case Description
        if ($count == 0) {
            $sql = Item::orderBy('Item', 'asc');
            $sql->where('Description', 'like', '%' . $upper . '%');
            $total_record = $sql->count();
            $sql->offset($offset);
            $sql->limit($limit);
            $data = $sql->get();

            $count = count($data);
            $total_pages = ceil($total_record / $limit);
        }

        $datas = new \stdClass();
        $datas->page = $page;
        $datas->total_page = $total_pages;
        $datas->total_record = $total_record;
        $datas->data = $data;

        if ($count == 0) {
            return response()->json([], 200);
        } else {
            return response()->json($datas, 200);
        }
    }

    public function fetch_varaint(request $request){
        $item = $request->item??'NA';

        if($item != 'NA'){
            $data = Item_lot::where('item', $item)
                ->where('variant', '<>', '')
                ->distinct()
                ->get(['variant']);
        }

        $count = count($data);

        if($count == 0){
            return response()->json([], 200);
        }else{


            return response()->json($data, 200);
        }

    }
}

class arr {
    public $page;
    public $total_page;
    public $total_record;
    public $data;

}


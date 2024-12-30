<?php

namespace App\Http\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

use DateTime;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Item_lot;
use App\Models\Item;
use App\Models\Item_ledger_entry;
use App\Models\Item_ledger_entry_for_sum_stock;

class itemController extends Controller
{
    public function dashboard(){
        return view('dashboard');
    }
    public function view_data(){
        $data = DB::table('dbo.ITEM')

        ->get();

        // Loop through each row and convert string values to UTF-8
        foreach ($data as $row) {
            foreach ($row as $key => $value) {
                if (is_string($value)) {
                    $row->$key = mb_convert_encoding($value, 'UTF-8', 'ASCII');
                }
            }
        }

        return  view("view",['data' => $data]);
    }

    public function view_rest_po(){
        $rest_po = DB::table('dbo.REST_PO_HENG_CHANNA')
        ->orderBy("PO", "desc")
        ->selectRaw(
            "*,
            [item no] as item,
            [variant Code] as variant,
            CAST(ROUND([PO QTY], 2) AS DECIMAL(18, 1)) as PO_QTY,
            CAST(ROUND([GRN Qty], 2) AS DECIMAL(18, 2)) as GRN_QTY,
            CAST([Outstanding Quantity] AS DECIMAL(18, 2)) as QTY_OUT,
            CAST(ROUND([Unit Cost (LCY)], 2) AS DECIMAL(18, 2)) as UNIT_COST,
            [Location Code] as LOCATION,
            [Due Date] as DUE,
            [Expected Receipt Date] as EXPECTED,
            [GRN Date] as GRNDATE,
            [Vendor Name] as VENDORNAME"
        )->get();

        $rest_po = $this->ascii_convert($rest_po);


        return view("rest_po", ['rest_po' => $rest_po]);
    }
    public function all_item(){
        $data = DB::table('dbo.ITEM')

        ->get();


        $data = $this->ascii_convert($data);



        return  view("all_item",['data' => $data]);
    }


    public function traceability(){
        // $new_item =  Item_lot::get();

        // return $new_item;
        return view("null_view");
    }

    public function traceability_search( Request $request){

        $search_data = $request->input('search');
        $data_found = DB::table('dbo.LiST ITEM AND LOT')
        ->where('lot','like', '%'.$request->input('search').'%')
        ->orWhere('item','like', '%'.$request->input('search').'%')
        ->get();
        $count = count($data_found);
        if($count == 0){
            $search = strtoupper($request->input('search'));
            $data_found = DB::table('dbo.LiST ITEM AND LOT')
            ->where('lot','like', '%'.$search.'%')
            ->orWhere('item','like', '%'.$search.'%')
            ->get();
        }
        $count = count($data_found);
        if($count == 1){

            //Prevouse data
            $data = DB::table('dbo.ITEM LEDGER ENTRY')
            ->where('item' , $data_found[0]->item)
            ->where('variant' , $data_found[0]->variant)
            ->where("lot" ,$data_found[0]->lot)
            ->orderBy('entry' ,"asc")
            ->get();




            $stock_onhand_main = DB::table("ITEM LEDGER STOCK ONHAND BY LOT")
            ->where('item' , $data_found[0]->item)
            ->where('variant' , $data_found[0]->variant)
            ->where("lot" ,$data_found[0]->lot)
             ->get();


            $reclass = DB::table('dbo.ITEM Reclass Data')
            ->where('item' , $data_found[0]->item)
            ->where('variant' , $data_found[0]->variant)
            ->where("lot" ,$data_found[0]->lot)
            ->get();
            $line2 = 0;
            $line_no = 0;
            $no_row = 0;
            $no = 0;
            $consum = 0;
            $output_no = 0;
            $state = count($data);
            if($state == 0){
                $data_found = DB::table('dbo.LiST ITEM AND LOT')
                ->where('item','like', '%'.$data_found[0]->item.'%')
                ->get();

                session()->flash('message', 'Item does not has process yet.');
                return view("list_item",['data_found' => $data_found])->with('message','fail');
            }
            return view("traceability_lot",[ 'line2' => $line2 , "data" => $data ,'stock_onhand_main' => $stock_onhand_main,'line_no' => $line_no, 'no_row' => $no_row ,'no' => $no , 'reclass' => $reclass , 'consum' => $consum , 'output_no' => $output_no ]);
        }

        return view("list_item",['data_found' => $data_found,'search_data' => $search_data]);
    }

    public function traceability_back($item){
        $data_found = DB::table('dbo.LiST ITEM AND LOT')
        ->orWhere('item','like', '%'.$item.'%')
        ->get();
        return view("list_item",['data_found' => $data_found]);
    }

    public function traceability_search_lot($item,$var_main,$lot,$line2,$line_no,$no_row,$no,$consum,$output_no){

        // return $no;

        if($var_main == "NA"){
            $var_main = "";
        }

        //Prevouse data
        $data = DB::table('dbo.ITEM LEDGER ENTRY')
        ->where('item' , $item)
        ->where('variant' , $var_main)
        ->where("lot" ,$lot)
        ->orderBy('entry' ,"asc")
        ->get();

        // dd($item,$var_main,$lot);


        $stock_onhand_main = DB::table("ITEM LEDGER STOCK ONHAND BY LOT")
         ->where('item' , $item)
         ->where('variant' , $var_main)
         ->where("lot" ,$lot)
         ->get();


        $reclass = DB::table('dbo.ITEM Reclass Data')
        ->where('item' , $item)
        ->where('variant' , $var_main)
        ->where("lot" ,$lot)
        ->get();
        // dd($no);
        $state = count($data);
        if($state == 0){


            $data_found = DB::table('dbo.LiST ITEM AND LOT')
            ->where('item','like', '%'.$item.'%')
            ->get();

            session()->flash('message', 'Item does not has process yet.');
            return view("list_item",['data_found' => $data_found])->with('message','fail');


        }



        return view("traceability_lot",[ 'line2' => $line2 , "data" => $data ,'stock_onhand_main' => $stock_onhand_main,'line_no' => $line_no, 'no_row' => $no_row ,'no' => $no , 'reclass' => $reclass , 'consum' => $consum , 'output_no' => $output_no ]);
    }

    public function traceability_search_lot_export($item,$var_main,$lot,$line2,$line_no,$no_row,$no,$consum,$output_no){

        // $line2 is line every RPO
        // $line_no is Output everyline
        // $no is another consumtion everyline

            // $line2 consumtion
            // ,$line_no, output
            // $no_row consumtion
            // $no      output
            // ,$consum, consumtion
            // $output_no  output


            // Create a new spreadsheet



            $row = 1;
              // Create new Spreadsheet
            $spreadsheet = new Spreadsheet();



            // set cell
            $activeWorksheet = $spreadsheet->getActiveSheet();
                                // Item variant lot  selected line fist and next select line     consumtion no / output no
            $result = $this->product($activeWorksheet,$item,$var_main,$lot,$line2,$line_no,$row);
            $activeWorksheet = $result['activeWorksheet'];
            $row = $result['row'] + 3;

            if($line_no != 0 && $result['item'] != null){

                // Invidule sheet
                // $input_result = $result;
                // $row = 1;
                // $newSheet = $spreadsheet->createSheet();
                // $newSheet->setTitle('Output Data');
                // $input_result = $result;
                // $result = null;
                // $result = $this->product($newSheet,$input_result['item'],$input_result['variant'],$input_result['lot'],$no_row,$no,$row);
                // $newSheet= $result['activeWorksheet'];
                // $row = $result['row'] + 3;


                // Only one sheet
                $input_result = $result;
                $result = null;
                $result = $this->product($activeWorksheet,$input_result['item'],$input_result['variant'],$input_result['lot'],$no_row,$no,$row);
                $activeWorksheet= $result['activeWorksheet'];
                $row = $result['row'] + 3;


            }


            if($consum != 0 && $result['item'] != null){
                // Invidule sheet
                // $input_result = $result;
                // $row = 1;
                // $newSheet2 = $spreadsheet->createSheet();
                // $newSheet2->setTitle('Output Data');
                // $input_result = $result;
                // $result = null;
                // $result = $this->product($activeWorksheet,$input_result['item'],$input_result['variant'],$input_result['lot'],$consum,$output_no,$row);
                // $newSheet2= $result['activeWorksheet'];
                // $row = $result['row'] + 3;



                // Only one sheet
                $input_result = $result;
                $result = null;
                $result = $this->product($activeWorksheet,$input_result['item'],$input_result['variant'],$input_result['lot'],$consum,$output_no,$row);
                $activeWorksheet = $result['activeWorksheet'];
                $row = $result['row'] + 3;



                }
            if($consum != 0 && $result['item'] != null){

                   // Invidule sheet
                // $input_result = $result;
                // $row = 1;
                // $newSheet3 = $spreadsheet->createSheet();
                // $newSheet3->setTitle('Output Data');
                // $input_result = $result;
                // $result = null;
                // $result = $this->product($activeWorksheet,$input_result['item'],$input_result['variant'],$input_result['lot'],$consum,0,$row);
                // $newSheet3= $result['activeWorksheet'];
                // $row = $result['row'] + 3;


                 // Only one sheet
                $input_result = $result;
                $result = null;
                $result = $this->product($activeWorksheet,$input_result['item'],$input_result['variant'],$input_result['lot'],$consum,0,$row);
                $activeWorksheet = $result['activeWorksheet'];
                $row = $result['row'] + 3;
            }




            $last_col = "K";
        // Auto size columns
        $columns = ['A', 'B', 'C', 'D', 'E', 'F','G','H','I','J','K']; // Specify which columns to auto-size
        foreach ($columns as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);

        }

        // Save the spreadsheet to a temporary file
        $filename = 'Traceability.xlsx';
        $tempFilePath = storage_path('app/' . $filename);
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFilePath);

        // Prepare the response to download the file
        $response = new BinaryFileResponse($tempFilePath);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $filename
        );

        // Return the response
        return $response;
    }




    function product($activeWorksheet,$item,$var_main,$lot,$line1,$line2,$row){


            if($var_main == "NA"){
                $var_main = "";
            }

            //Prevouse data
            $data = DB::table('dbo.ITEM LEDGER ENTRY')
            ->where('item' , $item)
            ->where('variant' , $var_main)
            ->where("lot" ,$lot)
            ->orderBy('entry' ,"asc")
            ->get();

            $stock_onhand_main = DB::table("ITEM LEDGER STOCK ONHAND BY LOT")
            ->where('item' , $item)
            ->where('variant' , $var_main)
            ->where("lot" ,$lot)
            ->get();

            $reclass = DB::table('dbo.ITEM Reclass Data')
            ->where('item' , $item)
            ->where('variant' , $var_main)
            ->where("lot" ,$lot)
            ->get();


            $backgroundColor = '4F81BD';
            $header_style = [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['argb' => $backgroundColor],
                ],
                'font' => [
                    'size' => 16,
                    'color' => ['argb' => 'FFFFFF'],
                    ],
            ];
            $total_style = [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['argb' => 'DAEEF3'],
                ],
                'font' => [
                    'size' => 12,
                    'color' => ['argb' => '1C2E31'],
                    ],
            ];
            $total_style = [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['argb' => 'DAEEF3'],
                ],
                'font' => [
                    'size' => 12,
                    'color' => ['argb' => '1C2E31'],
                    ],
            ];
            $selected = [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['argb' => '99D231'],
                ]
            ];
            $cell_col = "K";
            $last_col = $cell_col;
        $activeWorksheet->setCellValue("A".$row, "Product Information");
        $activeWorksheet->getStyle('A'.$row.':'.$cell_col.$row)->applyFromArray($header_style);
        $row++;
        $activeWorksheet->setCellValue("A".$row, "Item:");
        $activeWorksheet->setCellValue("B".$row, $data[0]->item);
        $row++;
        $activeWorksheet->setCellValue("A".$row, "Variant:");
        $activeWorksheet->setCellValue("B".$row, $data[0]->variant);
        $row++;
        $activeWorksheet->setCellValue("A".$row, "Description:");
        $activeWorksheet->setCellValue("B".$row, $data[0]->Description);
        $row++;
        $activeWorksheet->setCellValue("A".$row, "Unit:");
        $activeWorksheet->setCellValue("B".$row, $data[0]->UOM);
        $row++;
        $activeWorksheet->setCellValue("A".$row, "Lot:");
        $activeWorksheet->setCellValue("B".$row, $data[0]->lot);
        $row++;


               // Product's Entry
        $activeWorksheet->setCellValue("A".$row, "Product's Entry");
        $activeWorksheet->getStyle('A'.$row.':'.$cell_col.$row)->applyFromArray($header_style);
        $row++;
                $activeWorksheet->setCellValue("A".$row,'Type');
                $activeWorksheet->setCellValue("B".$row,"Document");
                $activeWorksheet->setCellValue("C".$row,"Qauntity");
                $activeWorksheet->setCellValue("D".$row,"Unit");
                $activeWorksheet->setCellValue("E".$row,"Sample");
                $activeWorksheet->getStyle('A'.$row.':'.$cell_col.$row)->applyFromArray($total_style);
        $row++;

        $total_entry = 0;
        foreach($data as $entry){
            if($entry->entry_type == 0){
                $activeWorksheet->setCellValue("A".$row,'[Purchase]');
                $activeWorksheet->setCellValue("B".$row,$entry->document);
                $activeWorksheet->setCellValue("C".$row,(float)str_replace(',','',$entry->quantity));
                $activeWorksheet->setCellValue("D".$row,$entry->UOM);

                $qty_entry_e = str_replace(',','',$entry->quantity);
                $float_entry = (float)$qty_entry_e;
                $total_entry += $float_entry;
                foreach ($data as $item){
                                if($entry->document == $item->document and ($item->location == 'RM-SAMPLE' || $item->location == 'PM-SAMPLE')){

                                    $activeWorksheet->setCellValue("E".$row,$item->quantity.$entry->UOM);
                    }
                }
                $row++;
            }elseif($entry->entry_type == 2 || $entry->entry_type == 3){
                $activeWorksheet->setCellValue("A".$row,'[Adjustment]');
                $activeWorksheet->setCellValue("B".$row,$entry->document);
                $activeWorksheet->setCellValue("C".$row,(float)str_replace(',','',$entry->quantity));
                $activeWorksheet->setCellValue("D".$row,$entry->UOM);

                $qty = str_replace(',','',$entry->quantity);
                $float = (float)$qty;
                $total_entry += $float;
                foreach ($data as $item){
                                if($entry->document == $item->document and ($item->location == 'RM-SAMPLE' || $item->location == 'PM-SAMPLE')){

                                    $activeWorksheet->setCellValue("E".$row,(float)str_replace(',','',$item->quantity).$entry->UOM);
                    }
                }
                $row++;
            } elseif($entry->entry_type == 6 && $entry->quantity > 0){
                $activeWorksheet->setCellValue("A".$row,'[Output]');
                $activeWorksheet->setCellValue("B".$row,$entry->document);
                $activeWorksheet->setCellValue("C".$row,(float)str_replace(',','',$entry->quantity));
                $activeWorksheet->setCellValue("D".$row,$entry->UOM);

                $qty = str_replace(',','',$entry->quantity);
                $float = (float)$qty;
                $total_entry += $float;
                foreach ($data as $item){
                                if($entry->document == $item->document and ($item->location == 'RM-SAMPLE' || $item->location == 'PM-SAMPLE')){

                                    $activeWorksheet->setCellValue("E".$row,(float)str_replace(',','',$item->quantity).$entry->UOM);
                    }
                }
                $row++;
            }
        }
        foreach($reclass as $re){

                    $activeWorksheet->setCellValue("A".$row,'[Reclass]');
                    $activeWorksheet->setCellValue("B".$row,$re->document);
                    $activeWorksheet->setCellValue("C".$row,(float)str_replace(',','',$re->quantity));
                    $activeWorksheet->setCellValue("D".$row,$re->UOM);
                    $row++;
                    $total_entry += (float)str_replace(',','',$re->quantity);

        }
                $activeWorksheet->setCellValue("A".$row,'Total Entry:');
                $activeWorksheet->setCellValue($last_col.$row,$total_entry.$data[0]->UOM);
                $activeWorksheet->getStyle('A'.$row.':'.$cell_col.$row)->applyFromArray($total_style);
                $row++;


    // Stock Data
        $count_stock = 0;
        $count_stock = count($stock_onhand_main);
        if( $count_stock == 0){
            $activeWorksheet->setCellValue("A".$row, "No Stock");
            $activeWorksheet->getStyle('A'.$row.':'.$cell_col.$row)->applyFromArray($header_style);
            $row++;
            $row++;
        }
        else{
            $activeWorksheet->setCellValue("A".$row, "Stock Onhand");
            $activeWorksheet->getStyle('A'.$row.':'.$cell_col.$row)->applyFromArray($header_style);
            $row++;
            $activeWorksheet->setCellValue("A".$row,'Location');
            $activeWorksheet->setCellValue("B".$row,'Quantity');
            $activeWorksheet->setCellValue("C".$row,'Unit');
            $activeWorksheet->getStyle('A'.$row.':'.$cell_col.$row)->applyFromArray($total_style);
            $row++;
            $s_stock = 0;



    foreach($stock_onhand_main as $st){
            $activeWorksheet->setCellValue("A".$row,$st->location);
            $activeWorksheet->setCellValue("B".$row,(float)str_replace(',','',$st->quantity));
            $activeWorksheet->setCellValue("C".$row,$st->UOM);

            $qty_s = str_replace(',','',$st->quantity);
            $float_s = (float)$qty_s;
            $s_stock += $float_s;
            $row++;
    }
            if($s_stock == 0){
                $activeWorksheet->setCellValue("A".$row,"No Stock");
                $row++;
            }
            $activeWorksheet->setCellValue("A".$row,'Total Stock:');
            $activeWorksheet->setCellValue($last_col.$row,$s_stock.$data[0]->UOM);
            $activeWorksheet->getStyle('A'.$row.':'.$cell_col.$row)->applyFromArray($total_style);

            $row++;

        }

// Sale Data Main Product
        $count_sale_data_qty =0;
        foreach($data as $count_sale_data){
            if($count_sale_data->entry_type == 1){
                $count_sale_data_qty++;
            }
        }
        if($count_sale_data_qty != 0){

            $activeWorksheet->setCellValue("A".$row, "Sale's Data");
            $activeWorksheet->getStyle('A'.$row.':'.$cell_col.$row)->applyFromArray($header_style);
            $row++;

            $activeWorksheet->setCellValue("A".$row,'No');
            $activeWorksheet->setCellValue("B".$row,'Date');
            $activeWorksheet->setCellValue("C".$row,'Document');
            $activeWorksheet->setCellValue("D".$row,'Item');
            $activeWorksheet->setCellValue("E".$row,'Variant');
            $activeWorksheet->setCellValue("F".$row,'Description');
            $activeWorksheet->setCellValue("G".$row,'Quantity');
            $activeWorksheet->setCellValue("H".$row,'Unit');
            $activeWorksheet->setCellValue("I".$row,'Lot');
            $activeWorksheet->setCellValue("J".$row,'Location');
            $activeWorksheet->setCellValue("K".$row,'Customer');
            $activeWorksheet->getStyle('A'.$row.':'.$cell_col.$row)->applyFromArray($total_style);
            $row++;

            $count_row_sale = 1;
            $total_sale_s = 0;
    foreach($data as $sale){
        if($sale->entry_type == 1){
            $activeWorksheet->setCellValue("A".$row,$count_row_sale);
            $activeWorksheet->setCellValue("B".$row,$sale->posting_date);
            $activeWorksheet->setCellValue("C".$row,$sale->document);
            $activeWorksheet->setCellValue("D".$row,$sale->item);
            $activeWorksheet->setCellValue("E".$row,$sale->variant);
            $activeWorksheet->setCellValue("F".$row,$sale->Description);
            $activeWorksheet->setCellValue("G".$row,(float)str_replace(',','',$sale->quantity));
            $activeWorksheet->setCellValue("H".$row,$sale->UOM);
            $activeWorksheet->setCellValue("I".$row,$sale->lot);
            $activeWorksheet->setCellValue("J".$row,$sale->location);
            $activeWorksheet->setCellValue("K".$row,$sale->cusname);
            $row++;

            $qty_sale_s = str_replace(',','',$sale->quantity);
            $float_sale_s = (float)$qty_sale_s;
            $total_sale_s += $float_sale_s;
            $count_row_sale++;
        }

    }
            $activeWorksheet->setCellValue("A".$row,'Total Sale:');
            $activeWorksheet->setCellValue($last_col.$row, $total_sale_s.$data[0]->UOM);
            $activeWorksheet->getStyle('A'.$row.':'.$cell_col.$row)->applyFromArray($total_style);
            $row++;
        }

// Production Step
            $count_consumtion_qty = 0;
              // initailize
              $out_put_by_line_item = null;
              $out_put_by_line_variant = null;
              $out_put_by_line_lot = null;
              $old_doc = null;
            foreach($data as $rpo_count){
            if($rpo_count->entry_type == 5){
                $count_consumtion_qty++;
                    }
            }
            if($count_consumtion_qty != 0){
                $activeWorksheet->setCellValue("A".$row, "Consumption");
                $activeWorksheet->getStyle('A'.$row.':'.$cell_col.$row)->applyFromArray($header_style);
                $row++;

                $activeWorksheet->setCellValue("A".$row,"No");
                $activeWorksheet->setCellValue("B".$row,"document");
                $activeWorksheet->setCellValue("C".$row,"Item");
                $activeWorksheet->setCellValue("D".$row,"variant");
                $activeWorksheet->setCellValue("E".$row,"Description");
                $activeWorksheet->setCellValue("F".$row,"Quantity");
                $activeWorksheet->setCellValue("G".$row,"Unit");
                $activeWorksheet->setCellValue("H".$row,"lot");
                $activeWorksheet->setCellValue("I".$row,"location");
                $activeWorksheet->getStyle('A'.$row.':'.$cell_col.$row)->applyFromArray($total_style);
                $row++;

                $qty_row_d = 1;
                $qty_com = 0;
                foreach($data as $rpo){
                    if($rpo->entry_type == 5 && $rpo->document != $old_doc){

                            // Query Component In ERP
                            $component = DB::table('dbo.ITEM LEDGER ENTRY  SUM CONSUMTION')
                            ->where("document", $rpo->document)
                            ->where("item", $rpo->item)
                            ->where("variant", $rpo->variant)
                            ->where("lot", $rpo->lot)
                            ->where('entry_type' , 5)
                            ->get();

                        foreach($component as $com){
                            $activeWorksheet->setCellValue("A".$row,$qty_row_d);
                            $activeWorksheet->setCellValue("B".$row,$com->document);
                            $activeWorksheet->setCellValue("C".$row,$com->item);
                            $activeWorksheet->setCellValue("D".$row,$com->variant);
                            $activeWorksheet->setCellValue("E".$row,$com->Description);
                            $activeWorksheet->setCellValue("F".$row,(float)str_replace(',','',$com->quantity));
                            $activeWorksheet->setCellValue("G".$row,$com->UOM);
                            $activeWorksheet->setCellValue("H".$row,$com->lot);
                            $activeWorksheet->setCellValue("I".$row,$com->location);


                            $qty_com_convert = str_replace(',','',$com->quantity);
                            $float_com = (float)$qty_com_convert;
                            $qty_com += $float_com;
                            $old_doc = $rpo->document;

                            if($line1 == $qty_row_d){
                                // Data Output
                                $doc = $com->document;
                            }
                            $qty_row_d++;
                            $row++;
                        }


                    }

                }
                $activeWorksheet->setCellValue("A".$row,'Total Consumtion:');
                $activeWorksheet->setCellValue($last_col.$row, $qty_com.$data[0]->UOM);
                $activeWorksheet->getStyle('A'.$row.':'.$cell_col.$row)->applyFromArray($total_style);
                $row++;

                // outPut show
                if($line1 != 0){

                    $output = DB::table('ITEM LEDGER ENTRY  SUM CONSUMTION')
                    ->where('document',$doc)
                    ->where('entry_type',6)
                    ->get();

                    $activeWorksheet->setCellValue("A".$row, "Out Put");
                    $activeWorksheet->getStyle('A'.$row.':'.$cell_col.$row)->applyFromArray($header_style);
                    $row++;

                    $activeWorksheet->setCellValue("A".$row,"No");
                    $activeWorksheet->setCellValue("B".$row,"Document");
                    $activeWorksheet->setCellValue("C".$row,"Item");
                    $activeWorksheet->setCellValue("D".$row,"Variant");
                    $activeWorksheet->setCellValue("E".$row,"Description");
                    $activeWorksheet->setCellValue("F".$row,"Quantity");
                    $activeWorksheet->setCellValue("G".$row,"Unit");
                    $activeWorksheet->setCellValue("H".$row,"Lot");
                    $activeWorksheet->setCellValue("I".$row,"Location");
                    $row++;
                    $no_output = 1;
                    $qty_output = 0;

                    foreach($output  as $o){
                        if($line2 == $no_output){
                            $activeWorksheet->setCellValue("A".$row,$no_output);
                            $activeWorksheet->setCellValue("B".$row,$o->document);
                            $activeWorksheet->setCellValue("C".$row,$o->item);
                            $activeWorksheet->setCellValue("D".$row,$o->variant);
                            $activeWorksheet->setCellValue("E".$row,$o->Description);
                            $activeWorksheet->setCellValue("F".$row,(float)str_replace(',','',$o->quantity));
                            $activeWorksheet->setCellValue("G".$row,$o->UOM);
                            $activeWorksheet->setCellValue("H".$row,$o->lot);
                            $activeWorksheet->setCellValue("I".$row,$o->location);
                            $activeWorksheet->getStyle('A'.$row.':'.$cell_col.$row)->applyFromArray($total_style);

                            $qty_out_temp = str_replace(',','',$o->quantity);
                            $floats_output = (float)$qty_out_temp;
                            $qty_output +=  $floats_output;
                        }else{
                            $activeWorksheet->setCellValue("A".$row,$no_output);
                            $activeWorksheet->setCellValue("B".$row,$o->document);
                            $activeWorksheet->setCellValue("C".$row,$o->item);
                            $activeWorksheet->setCellValue("D".$row,$o->variant);
                            $activeWorksheet->setCellValue("E".$row,$o->Description);
                            $activeWorksheet->setCellValue("F".$row,(float)str_replace(',','',$o->quantity));
                            $activeWorksheet->setCellValue("G".$row,$o->UOM);
                            $activeWorksheet->setCellValue("H".$row,$o->lot);
                            $activeWorksheet->setCellValue("I".$row,$o->location);

                            $qty_out_temp = str_replace(',','',$o->quantity);
                            $floats_output = (float)$qty_out_temp;
                            $qty_output +=  $floats_output;
                        }

                        $row++;
                        if($line2 == $no_output){
                            $out_put_by_line_item = $o->item;
                            $out_put_by_line_variant = $o->variant;
                            $out_put_by_line_lot = $o->lot;
                        }

                        $no_output++;
                    }

                    $activeWorksheet->setCellValue("A".$row,'Total Consumtion:');
                    $activeWorksheet->setCellValue($last_col.$row, $qty_output.$output[0]->UOM);
                    $activeWorksheet->getStyle('A'.$row.':'.$cell_col.$row)->applyFromArray($total_style);
                    $row++;


                }

            }
                return array(
                    'activeWorksheet' => $activeWorksheet,
                    'row' => $row,
                    'item' => $out_put_by_line_item,
                    'variant' => $out_put_by_line_variant,
                    'lot' => $out_put_by_line_lot
                );
    }
    public function traceability_item_peroid($page_no){
        $page = 1;
        if($page_no != 1){
            $page = $page_no;
        }
        $sql = Item::orderBy('Item', 'asc');
        $limit = 150;
        $total_record = $sql->count();

        $offet = 0;
        if($page != 0){
            $offet = ($page - 1) * $limit;
        }


        $sql->limit($limit);

        $sql->offset($offet);

        $count_sql = $sql->count();
        $data = $sql->get();

        $count = $count_sql;
        $total_pages = ceil($total_record/$limit);



        $items = new arr();
        $items->page = $page;
        $items->total_page = $total_pages;
        $items->total_record = $total_record;
        $items->data = $data;

        // return var_dump($items);
        return view("no_nav_for_item",[
            'items' => $items,
            'page' => $page,
            'total_pages' => $total_pages,
        ]);
    }

    public function traceability_item_with_variant(request $request){
        $item = $request->item??'NA';
        $variant  = $request->variant??'';
        $from_date = $request->from_date??'NA';
        $to_date = $request->to_date ??'NA';

        // Item Info Calculation
            $sql_info = Item_lot::where('item', $item);
            if ($variant != 'NA') {
                $sql_info->where('variant', $variant);
            }
            $product_info   = $sql_info->first();


        $begin_year = new DateTime($from_date);
        $end_year = new DateTime($to_date);
        $formatted_begin_date = $begin_year->format('Y-m-d H:i:s.000');
        $formatted_end_date = $end_year->format('Y-m-d H:i:s.000');

        // Stock Begin Calcuation
        $sql = Item_ledger_entry_for_sum_stock::select(
            'location',
            'UOM',
            DB::raw('SUM(quantity) as total_quantity')
        )
        ->where('item', $item)
        ->orderBy('location','asc');
        // Use All Variant
        if ($variant != 'NA') {
            $sql->where('variant', $variant);

        }
        $sql->where("posting_date", '<',$formatted_begin_date);
        $sql->groupBy('location', 'UOM')
            ->having(DB::raw('SUM(quantity)'), '>', 0);
        $begin_stock = $sql->get();



        // Stock End Calcuation
        $sql_end = Item_ledger_entry_for_sum_stock::select(
            'location',
            'UOM',
            DB::raw('SUM(quantity) as total_quantity')
        )
        ->where('item', $item)
        ->orderBy('location','asc');
        // Use All Variant
        if ($variant != 'NA') {
            $sql_end->where('variant', $variant);

        }
        $sql_end->where("posting_date", '<=',$formatted_end_date);
        $sql_end->groupBy('location', 'UOM')
            ->having(DB::raw('SUM(quantity)'), '>', 0);
        $end_stock = $sql_end->get();


        $sql_purchase = Item_ledger_entry_for_sum_stock::where('type', 0)
        ->where('item', $item);
        if ($variant != 'NA') {
            // Filter by variant when specified
            $sql_purchase->where('variant', $variant);
        } else {
        }
        // Grouping with OR condition
        $sql_purchase->whereBetween('posting_date', [$formatted_begin_date, $formatted_end_date]);
        $sql_purchase->where('quantity','>',0);
        $qty_purchase = $sql_purchase->sum('quantity');



        $sql_purchase = Item_ledger_entry_for_sum_stock::where('type', 0)
        ->where('item', $item);
        if ($variant != 'NA') {
            // Filter by variant when specified
            $sql_purchase->where('variant', $variant);
        } else {
        }
        // Grouping with OR condition
        $sql_purchase->whereBetween('posting_date', [$formatted_begin_date, $formatted_end_date]);
        $sql_purchase->where('quantity','<',0);
        $qty_purchase_return = $sql_purchase->sum('quantity');



        // Sale Order
        $sql_sale_order = Item_ledger_entry_for_sum_stock::where('type', 1)
        ->where('item', $item);
        if ($variant != 'NA') {
            // Filter by variant when specified
            $sql_sale_order->where('variant', $variant);
        } else {
        }
        // Grouping with OR condition
        $sql_sale_order->whereBetween('posting_date', [$formatted_begin_date, $formatted_end_date]);
        $sql_sale_order->where('quantity','<',0);
        $qty_sale_order = $sql_sale_order->sum('quantity');


           // Sale  Return Order
           $sql_sale_return = Item_ledger_entry_for_sum_stock::where('type', 1)
           ->where('item', $item);
           if ($variant != 'NA') {
               // Filter by variant when specified
               $sql_sale_return->where('variant', $variant);
           } else {
           }
           // Grouping with OR condition
           $sql_sale_return->whereBetween('posting_date', [$formatted_begin_date, $formatted_end_date]);
           $sql_sale_return->where('quantity','>',0);
           $qty_sale_return_order = $sql_sale_return->sum('quantity');


            // Consumption
           $sql_consumtion = Item_ledger_entry_for_sum_stock::where('type', 5)
           ->where('item', $item)
           ->whereNotLike('document','%RPC%');
           if ($variant != 'NA') {
               // Filter by variant when specified
               $sql_consumtion->where('variant', $variant);
           } else {
           }
           // Grouping with OR condition
           $sql_consumtion->whereBetween('posting_date', [$formatted_begin_date, $formatted_end_date]);
        //    $sql_purchase->where('quantity','>',0);
           $qty_consumption = $sql_consumtion->sum  ('quantity');


                // Consumption
                $sql_covert_code = Item_ledger_entry_for_sum_stock::where('type', 5)
                ->where('item', $item)
                ->where('document','LIKE','%RPC%');
                if ($variant != 'NA') {
                    // Filter by variant when specified
                    $sql_covert_code->where('variant', $variant);
                } else {
                }
                // Grouping with OR condition
                $sql_covert_code->whereBetween('posting_date', [$formatted_begin_date, $formatted_end_date]);
                //    $sql_purchase->where('quantity','>',0);
                $qty_convert_code = $sql_covert_code->sum('quantity');



               // Output
               $sql_output = Item_ledger_entry_for_sum_stock::where('type', 6)
               ->where('item', $item);
               if ($variant != 'NA') {
                   // Filter by variant when specified
                   $sql_output->where('variant', $variant);
               } else {
               }
               // Grouping with OR condition
               $sql_output->whereBetween('posting_date', [$formatted_begin_date, $formatted_end_date]);
                //    $sql_purchase->where('quantity','>',0);
               $qty_output = $sql_output->sum('quantity');





              // Negative Adjsutment
               $sql_neg_adj = Item_ledger_entry_for_sum_stock::where('type', 3)
               ->where('item', $item);
               if ($variant != 'NA') {
                   // Filter by variant when specified
                   $sql_neg_adj->where('variant', $variant);
               } else {
               }
               // Grouping with OR condition
               $sql_neg_adj->whereBetween('posting_date', [$formatted_begin_date, $formatted_end_date]);
               $qty_neg_adj = $sql_neg_adj->sum('quantity');

               //Positive Adjsutment
               $sql_post_adj = Item_ledger_entry_for_sum_stock::where('type', 2)
               ->where('item', $item);
               if ($variant != 'NA') {
                   // Filter by variant when specified
                   $sql_post_adj->where('variant', $variant);
               } else {
               }
               // Grouping with OR condition
               $sql_post_adj->whereBetween('posting_date', [$formatted_begin_date, $formatted_end_date]);
               $qty_post_adj = $sql_post_adj->sum('quantity');

                //Raclasss
                $sql_reclass = Item_ledger_entry_for_sum_stock::where('type', 4)
                ->where('item', $item)
                ->where('order', '')
                ->wherenotLike('document', '%GRN%')
                ->wherenotLike('document', '%107%')
                ->wherenotLike('document', '%T0%')
                ->wherenotLike('document', '%WRC%');

                if ($variant != 'NA') {
                    // Filter by variant when specified
                    $sql_reclass->where('variant', $variant);
                } else {
                }
                // Grouping with OR condition
                $sql_reclass->whereBetween('posting_date', [$formatted_begin_date, $formatted_end_date]);
                $qty_reclass = $sql_reclass->sum('quantity');




        return view("traceability_item",[
            'begin_stock' => $begin_stock,
            'end_stock' => $end_stock ,
            'product_info' => $product_info,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'qty_purchase' => $qty_purchase,
            'qty_purchase_return' => $qty_purchase_return,
            'qty_sale_order' => $qty_sale_order,
            'qty_sale_return_order' =>  $qty_sale_return_order,
            'qty_consumption' => $qty_consumption,
            'qty_output' => $qty_output,
            'qty_convert_code' => $qty_convert_code,
            'qty_neg_adj' => $qty_neg_adj,
            'qty_post_adj' =>  $qty_post_adj,
            'qty_reclass' =>  $qty_reclass,
            'item' => $item,
            'variant' => $variant,
            'from_date' => $from_date,
            'to_date' => $to_date
        ]);
    }







    public function traceability_item_with_variant_search(request $request){

   // Initialize
            $search = $request->search ?? 'NA';
            $page =  1;

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

            $datas = new arr();
            $datas->page = $page;
            $datas->total_page = $total_pages;
            $datas->total_record = $total_record;
            $datas->data = $data;


        return view("no_nav_for_item",[
            'items' => $datas,
            'page' => $page,
            'total_pages' => $total_pages,
            'search' => $search
        ]);

    }

    public function traceability_raw(request $request){
        $item = $request->item??'NA';
        $variant  = $request->variant??'';
        $from_date = $request->from_date??'NA';
        $to_date = $request->to_date ??'NA';


        // Date
        $begin_year = new DateTime($from_date);
        $end_year = new DateTime($to_date);
        $formatted_begin_date = $begin_year->format('Y-m-d H:i:s.000');
        $formatted_end_date = $end_year->format('Y-m-d H:i:s.000');

        // Item Info Calculation
        $sql_info = Item_lot::where('item', $item);
        if ($variant != 'NA') {
            $sql_info->where('variant', $variant);
        }
        $product_info   = $sql_info->first();


/////////////////////////////////////////////////////////

            $sql = Item_ledger_entry_for_sum_stock::select(
                'location',
                'item',
                'variant',
                DB::raw('MAX(lot) as lot'),
                DB::raw('MAX(expire) as expire'),
                'UOM',
                DB::raw('SUM(quantity) as total_quantity')
            )
            ->where('item', $item)
            ->orderBy('location','asc');

            // Use All Variant
            if ($variant != 'NA') {
                $sql->where('variant', $variant);
            }

            $sql->where("posting_date", '<', $formatted_begin_date);
            $sql->groupBy('location','item',
                'variant', 'UOM')
                ->having(DB::raw('SUM(quantity)'), '>', 0);

            $begin_stock = $sql->get();
            $count_begin = count($begin_stock);


            $title_type = 'Process During Period';
            $count_purchase  =0;


            $sql_purchase = Item_ledger_entry_for_sum_stock::where('type', 0)
            ->where('item', $item);
            if ($variant != 'NA') {
                // Filter by variant when specified
                $sql_purchase->where('variant', $variant);
            } else {
            }
            // Grouping with OR condition
            $sql_purchase->whereBetween('posting_date', [$formatted_begin_date, $formatted_end_date]);
            $sql_purchase->where('quantity','>',0);
            $data_purchase = $sql_purchase->get();
            $count_purchase = count($data_purchase);



            $sql_purchase_return = Item_ledger_entry_for_sum_stock::where('type', 0)
            ->where('item', $item);
            if ($variant != 'NA') {
                // Filter by variant when specified
                $sql_purchase_return->where('variant', $variant);
            } else {
            }
            // Grouping with OR condition
            $sql_purchase_return->whereBetween('posting_date', [$formatted_begin_date, $formatted_end_date]);
            $sql_purchase_return->where('quantity','<',0);
            $data_purchase_return = $sql_purchase_return->get();

            $count_purchase_retun = count($data_purchase_return);


            // Sale Order
            $sql_sale_order = Item_ledger_entry_for_sum_stock::where('type', 1)
            ->where('item', $item);
            if ($variant != 'NA') {
                // Filter by variant when specified
                $sql_sale_order->where('variant', $variant);
            } else {
            }
            // Grouping with OR condition
            $sql_sale_order->whereBetween('posting_date', [$formatted_begin_date, $formatted_end_date]);
            $sql_sale_order->where('quantity','<',0);
            $sale_order_data = $sql_sale_order->get();
            $count_sale_order = count($sale_order_data);


               // Sale  Return Order
               $sql_sale_return = Item_ledger_entry_for_sum_stock::where('type', 1)
               ->where('item', $item);
               if ($variant != 'NA') {
                   // Filter by variant when specified
                   $sql_sale_return->where('variant', $variant);
               } else {
               }
               // Grouping with OR condition
               $sql_sale_return->whereBetween('posting_date', [$formatted_begin_date, $formatted_end_date]);
               $sql_sale_return->where('quantity','>',0);
               $sale_return_order_data = $sql_sale_return->get();
               $count_sale_return_order = count($sale_return_order_data);

                // Consumption
                $sql_consumtion = Item_ledger_entry_for_sum_stock::where('type', 5)
                ->where('item', $item)
                ->whereNotLike('document','%RPC%');
                if ($variant != 'NA') {
                    // Filter by variant when specified
                    $sql_consumtion->where('variant', $variant);
                } else {
                }
                // Grouping with OR condition
                $sql_consumtion->whereBetween('posting_date', [$formatted_begin_date, $formatted_end_date]);
                    //    $sql_purchase->where('quantity','>',0);
                    $consumption_data = $sql_consumtion->get();

               $count_consumption = count($consumption_data);

                    // Consumption
                    $sql_covert_code = Item_ledger_entry_for_sum_stock::where('type', 5)
                    ->where('item', $item)
                    ->where('document','LIKE','%RPC%');
                    if ($variant != 'NA') {
                        // Filter by variant when specified
                        $sql_covert_code->where('variant', $variant);
                    } else {
                    }
                    // Grouping with OR condition
                    $sql_covert_code->whereBetween('posting_date', [$formatted_begin_date, $formatted_end_date]);
                    //    $sql_purchase->where('quantity','>',0);
                    $qty_convert_code = $sql_covert_code->sum('quantity');



                   // Output
                   $sql_output = Item_ledger_entry_for_sum_stock::where('type', 6)
                   ->where('item', $item);
                   if ($variant != 'NA') {
                       // Filter by variant when specified
                       $sql_output->where('variant', $variant);
                   } else {
                   }
                   // Grouping with OR condition
                   $sql_output->whereBetween('posting_date', [$formatted_begin_date, $formatted_end_date]);
                    //    $sql_purchase->where('quantity','>',0);
                   $data_output = $sql_output->get();

                   $qty_output  = count($data_output);




                  // Negative Adjsutment
                   $sql_neg_adj = Item_ledger_entry_for_sum_stock::where('type', 3)
                   ->where('item', $item);
                   if ($variant != 'NA') {
                       // Filter by variant when specified
                       $sql_neg_adj->where('variant', $variant);
                   } else {
                   }
                   // Grouping with OR condition
                   $sql_neg_adj->whereBetween('posting_date', [$formatted_begin_date, $formatted_end_date]);
                   $qty_neg_data = $sql_neg_adj->get();

                   $count_qty_neg_adj = count( $qty_neg_data);

                   //Positive Adjsutment
                   $sql_post_adj = Item_ledger_entry_for_sum_stock::where('type', 2)
                   ->where('item', $item);
                   if ($variant != 'NA') {
                       // Filter by variant when specified
                       $sql_post_adj->where('variant', $variant);
                   } else {
                   }
                   // Grouping with OR condition
                   $sql_post_adj->whereBetween('posting_date', [$formatted_begin_date, $formatted_end_date]);
                   $data_post_adj = $sql_post_adj->get();
                   $count_qty_post_adj = count($data_post_adj);

                    //Raclasss
                    $sql_reclass = Item_ledger_entry_for_sum_stock::where('type', 4)
                    ->where('item', $item)
                    ->where('order', '')
                    ->wherenotLike('document', '%GRN%')
                    ->wherenotLike('document', '%107%')
                    ->wherenotLike('document', '%T0%')
                    ->wherenotLike('document', '%WRC%');

                    if ($variant != 'NA') {
                        // Filter by variant when specified
                        $sql_reclass->where('variant', $variant);
                    } else {
                    }
                    // Grouping with OR condition
                    $sql_reclass->whereBetween('posting_date', [$formatted_begin_date, $formatted_end_date]);
                    $data_reclass = $sql_reclass->get();
                    $qty_reclass = count($data_reclass);

                    // Stock End Calcuation
                    $sql_end = Item_ledger_entry_for_sum_stock::select(
                        'location',
                        DB::raw('MAX(lot) as lot'),
                        DB::raw('MAX(expire) as expire'),
                        'UOM',
                        DB::raw('SUM(quantity) as total_quantity')
                    )
                    ->where('item', $item)
                    ->orderBy('location','asc');
                    // Use All Variant
                    if ($variant != 'NA') {
                        $sql_end->where('variant', $variant);

                    }
                    $sql_end->where("posting_date", '<=',$formatted_end_date);
                    $sql_end->groupBy('location', 'UOM')
                        ->having(DB::raw('SUM(quantity)'), '>', 0);
                    $end_stock = $sql_end->get();
                    $count_end_stock = count($end_stock);




                    return view('traceability_item_detail',[

                        'item' => $item,
                        'variant' => $variant,
                        'from_date' => $from_date,
                        'to_date' => $to_date,
                        'product_info' =>  $product_info,
                        'title_type' => $title_type,
                        'data_purchase' => $data_purchase,
                        'count_purchase' => $count_purchase,
                        'count_purchase_retun' =>  $count_purchase_retun,
                        'data_purchase_return' => $data_purchase_return,
                        'count_sale_order' => $count_sale_order,
                        'sale_order_data' => $sale_order_data,
                        'count_sale_return_order' => $count_sale_return_order,
                        'sale_return_order_data' => $sale_return_order_data,
                        'count_consumption' =>$count_consumption,
                        'consumption_data' => $consumption_data,
                        'qty_output' => $qty_output,
                        'data_output' => $data_output,
                        'begin_stock' =>  $begin_stock,
                        'count_begin' => $count_begin,
                        'end_stock' =>  $end_stock,
                        'count_end_stock' => $count_end_stock,
                        'count_qty_neg_adj' =>  $count_qty_neg_adj,
                        'qty_neg_data'  => $qty_neg_data,
                        'count_qty_post_adj' =>  $count_qty_post_adj,
                        'data_post_adj' => $data_post_adj,
                        'data_reclass' => $data_reclass,
                        'qty_reclass' => $qty_reclass
                    ]);





    }
}

class arr {
    public $page;
    public $total_page;
    public $total_record;
    public $data;

}

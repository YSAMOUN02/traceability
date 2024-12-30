<?php

namespace App\Http\Controllers;

abstract class Controller
{

    public function ascii_convert($data){
        foreach ($data as $row) {
            foreach ($row as $key => $value) {
                if (is_string($value)) {
                    $row->$key = mb_convert_encoding($value, 'UTF-8', 'ASCII');
                }
            }
        }
        return $data;
    }
    
    // public function assii_convert($data){
    //     foreach ($data as $row) {
    //         foreach ($row as $key => $value) {
    //             if (is_string($value)) {
    //                 $row->$key = mb_convert_encoding($value, 'UTF-8', 'ASCII');
    //             }
    //         }
    //     }
    //     return $data;
    // }
}

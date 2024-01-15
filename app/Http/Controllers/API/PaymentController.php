<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class PaymentController extends Controller
{
    public function create(Request $request)
    {
        $file = $request['filecsv'];
        // Open the CSV file
        $file = fopen($file, 'r');

        // Initialize an empty array
        $rows = [];

        // Read each line and parse it into an array
        while (($data = fgetcsv($file, null, ";")) !== false) {
            $rows[] = $data;
        }

        // Close the file
        fclose($file);

        // Remove the first one that contains headers
        $headers = array_shift($rows);

        // Combine the headers with each following row
        $array = [];
        foreach ($rows as $row) {
            $array[] = array_combine($headers, $row);
        }
        // Output the resulting array
        return $array;
    }


    public function decodeToken()
    {
        $token = JWTAuth::getToken();
        return JWTAuth::getPayload($token)->toArray();
    }

}

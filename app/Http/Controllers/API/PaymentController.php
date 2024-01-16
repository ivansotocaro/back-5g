<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class PaymentController extends Controller
{
    public function create(Request $request)
    {

        //Token decode
        $toke = $this->decodeToken();

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

        foreach ($array as $key => $value) {
            $payment = new Payment();

            $payment->user_id = $toke['id'];
            $payment->user_document = $value['documento'];
            $payment->monto = $value['monto'];
            $payment->payment_date = $this->dateFormat($value['fecha pago']);
            $payment->deadline_date = $this->dateFormat($value['fecha limite']);
            $payment->save();

            $payment->id_payment = $this->generarCodigo($payment->payment_date, $payment->id);
            $payment->save();
        }

        return response()->json(['ok' => 'true']);
    }


    public function decodeToken()
    {
        $token = JWTAuth::getToken();
        return JWTAuth::getPayload($token)->toArray();
    }

    public function dateFormat($date)
    {
        $date = Carbon::createFromFormat('d/m/Y', $date);
        $date = $date->format('Y-m-d');
        return new Carbon($date);
    }

    function generarCodigo($paymentDate, $idPayment) {

        $paymentDate = $paymentDate->format('Y-m-d');
         // Formato de fecha YYYYMMDD 20231112
        $formantDate = date("Ymd", strtotime($paymentDate));

        // Longitud total deseada para el código
        $totalLength = 20;

        // Calcular la longitud del ID del pago
        $LengthIdPayment = strlen($idPayment);

        // Calcular la longitud de ceros necesarios
        $longitudCeros = $totalLength - $LengthIdPayment - strlen($formantDate);

        // Construir el código con ceros intermedios
        $code = $formantDate . str_repeat('0', $longitudCeros) . $idPayment;

        return $code;
    }


}

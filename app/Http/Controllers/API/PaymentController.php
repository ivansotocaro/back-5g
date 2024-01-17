<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::from('payments as p')
        ->join('users as u', 'p.user_document', '=', 'u.document')
        ->join('users as uu', 'p.user_id', '=', 'uu.id')
        ->select(
            'p.monto',
            'p.payment_date',
            'p.deadline_date',
            'u.document',
            'u.name',
            'u.email',
            'uu.name as user',
        )
        ->get();

        return response()->json([
            'success' => true,
            'data' => $payments,
        ]);
    }

    public function create(Request $request)
    {

        //Token decode
        // $toke = $this->decodeToken();

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
            $maximumDate = $this->dateFormat($value['fecha pago'])->format('Y-m-d');
            $currentDate = now()->toDateString();

            // if($maximumDate >= $currentDate) {

                $payment = new Payment();
                $payment->user_id = 1;
                $payment->user_document = $value['documento'];
                $payment->monto = $value['monto'];
                $payment->payment_date = $this->dateFormat($value['fecha pago']);
                $payment->deadline_date = $this->dateFormat($value['fecha limite']);
                $payment->save();

                $payment->id_payment = $this->generateCode($payment->payment_date, $payment->id);
                $payment->save();

            // }

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

    function generateCode($paymentDate, $idPayment) {

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

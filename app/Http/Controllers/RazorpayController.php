<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Session;
use Exception;
use Illuminate\Support\Facades\Log;

  
class RazorpayController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index()
    {        
        return view('razorpay.index');
    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function store(Request $request)
    {
        $input = $request->all();
  
        $api = new Api(env('RAZORPAY_API_KEY'), env('RAZORPAY_API_SECRET'));
  
        $payment = $api->payment->fetch($input['razorpay_payment_id']);
  
        if(count($input)  && !empty($input['razorpay_payment_id'])) {
            try {
                //$response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount'=>$payment['amount'])); 
               
                $response = $api->payment->fetch($input['razorpay_payment_id'])->capture([
                    'amount' => $payment['amount']
                ]);
                
                Log::info(json_encode((array)$response));
            } catch (Exception $e) {

                Log::info($e->getMessage());
                Session::put('error',$e->getMessage());
                return back()->withError($e->getMessage());                
            }
        }
          
        Session::put('success', 'Payment successful');
        return redirect()->back();
    }
}
<?php

namespace App\Http\Controllers;
use App\Models\Utility;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\InvoicePayment;


class PaymentWallPaymentController extends Controller
{
    public $secret_key;
    public $public_key;
    public $is_enabled;


    public function paymentConfig($user)
    {
        if(Auth::check()){
            $user = Auth::user();
        }
        if($user->type == 'company')
        {
            $payment_setting = Utility::getAdminPaymentSetting();
        }
        else
        {
            $payment_setting = Utility::getCompanyPaymentSetting();
        }

        $this->secret_key = isset($payment_setting['paymentwall_private_key ']) ? $payment_setting['paymentwall_private_key  '] : '';
        $this->public_key = isset($payment_setting['paymentwall_public_key']) ? $payment_setting['paymentwall_public_key'] : '';
        $this->is_enabled = isset($payment_setting['is_paymentwall_enabled']) ? $payment_setting['is_paymentwall_enabled'] : 'off';

        return $this;
    }

    public function invoicepaymentwall(Request $request){
        $data = $request->all();
        $company_payment_setting = Utility::getCompanyPaymentSetting();
     
        return view('invoice.paymentwall',compact('data','company_payment_setting'));
    }

   

    public function invoiceerror(Request $request,$flag,$invoice_id)
    {
       
        if($flag == 1)
        {
            return redirect()->route('invoice.show',\Illuminate\Support\Facades\Crypt::encrypt($invoice_id))->with('error', __('Payment successfully added. '));
        }
        else
        {
            return redirect()->route("invoice.show",\Illuminate\Support\Facades\Crypt::encrypt($invoice_id))->with('error', __('Transaction has been failed! '));
        }
    }


    public function invoicePayWithPaymentwall(Request $request,$invoiceID)
    {  
        $invoiceID = \Crypt::decrypt($invoiceID);

        // $res['msg'] = __("error");
        // $res['invoice']=$invoiceID;
        // return $res;

        $invoice   = Invoice::find($invoiceID);
     
        if(\Auth::check())
        {
            $user=\Auth::user();
        }
        else
        {
            $user= User::where('id',$invoice->created_by)->first();
        } 
        
        if($invoice)
        {
            $price = $request->amount;
            // dd($price);
            if($price < 0)
            {
                $res_data['email']       = $user->email;
                $res_data['total_price'] = $price;
                $res_data['currency']    = Utility::getValByName('site_currency');
                $res_data['flag']        = 1;

            }
        
            else 
            {
                $authuser = Auth::user();
                \Paymentwall_Config::getInstance()->set(array(
                    'private_key' => 'sdrsefrszdef'
                ));
                $parameters = $request->all();
                $chargeInfo = array(
                    'email' => $parameters['email'],
                    'history[registration_date]' => '1489655092',
                    'amount' => $price,
                    'currency' => !empty($this->currancy) ? $this->currancy : 'USD',
                    'token' => $parameters['brick_token'],
                    'fingerprint' => $parameters['brick_fingerprint'],
                    'description' => 'Order #123'
                );
                $charge = new \Paymentwall_Charge();
                $charge->create($chargeInfo);
                $responseData = json_decode($charge->getRawResponseData(),true);
                $response = $charge->getPublicData();
                // dd($response);
                if ($charge->isSuccessful() AND empty($responseData['secure'])) {
                    if ($charge->isCaptured()) {

                        $settings = DB::table('settings')->where('created_by', '=', $user->creatorId())->get()->pluck('value', 'name');
                        $orderID = time();
                        $payments = New InvoicePayment;
                        $payments->transaction = $orderID;
                        $payments->invoice = $invoice->id;
                        $payments->amount = isset($request['amount'])?$request['amount']:0;
                        $payments->date = date('Y-m-d');
                        $payments->payment_method = 1;
                        $payments->payment_type = __('PaymentWall');
                        $payments->notes = __('Invoice') . ' ' . Utility::invoiceNumberFormat($settings, $invoice->invoice_id);
                        $payments->receipt = '';
                        $payments->created_by = \Auth::user()->creatorId();
                        $payments->save();
                        // dd($payments);
                        $invoice = Invoice::find($invoice->id);

                        $invoice_getdue = number_format((float)$invoice->getDue(), 2, '.', '');
                        if($invoice_getdue <= 0.0)
                        {
                            Invoice::change_status($invoice->id, 3);
                        }
                        else{
                            Invoice::change_status($invoice->id, 2);
                        }

                        $assignPlan = $authuser->assignPlan($invoice->id);
                        if($assignPlan['is_success'])
                        {
                            $res['msg'] = __("Invoice successfully .");
                            $res['flag'] = 1;
                            return $res;
                        }
                    } elseif ($charge->isUnderReview()) {
                        // decide on risk charge
                    }
                } elseif (!empty($responseData['secure'])) {
                    $response = json_encode(array('secure' => $responseData['secure']));
                } else {
                    $errors = json_decode($response, true);
                            $res['invoice']=$invoiceID;
                            $res['flag'] = 2;
                            return $res;
                }
                echo $response;
                
            }
        } 
        
    }


}

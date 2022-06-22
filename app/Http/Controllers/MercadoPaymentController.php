<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Utility;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use LivePixel\MercadoPago\MP;

class MercadoPaymentController extends Controller
{
    public $secret_key;
    public $app_id;
    public $is_enabled;


    public function paymentConfig()
    {
       
        $payment_setting = Utility::getCompanyPaymentSetting();

        $this->token = isset($payment_setting['mercado_access_token'])?$payment_setting['mercado_access_token']:'';
        $this->mode = isset($payment_setting['mercado_mode'])?$payment_setting['mercado_mode']:'';
        $this->is_enabled = isset($payment_setting['is_mercado_enabled'])?$payment_setting['is_mercado_enabled']:'off';
        return $this;
    }


    public function invoicePayWithMercado(Request $request)
    {
        $invoiceID = $request->invoice_id;
         $invoiceID = \Crypt::decrypt($invoiceID);
        $invoice   = Invoice::find($invoiceID);
        
        if(\Auth::check())
        {
            $user=\Auth::user();
        }
        else
        {
            $user= User::find($invoice->created_by);
        } 

        
        $orderID   = strtoupper(str_replace('.', '', uniqid('', true)));
        
        if(Auth::check()){
            $payment = $this->paymentConfig();
            $settings  = DB::table('settings')->where('created_by', '=', $user->creatorId())->get()->pluck('value', 'name');
        }else{
            $payment_setting = Utility::getCompanyPaymentSettingWithOutAuth($invoice->created_by);
            $this->token = isset($payment_setting['mercado_access_token'])?$payment_setting['mercado_access_token']:'';
            $this->mode = isset($payment_setting['mercado_mode'])?$payment_setting['mercado_mode']:'';
            $this->is_enabled = isset($payment_setting['is_mercado_enabled'])?$payment_setting['is_mercado_enabled']:'off';
            $settings = Utility::settingsById($invoice->created_by);
        }
       
        if($invoice)
        {
            $price = $request->amount;

            if($price > 0)
            {
                $preference_data = array(
                    "items" => array(
                        array(
                            "title" => __('Invoice') . ' ' . Utility::invoiceNumberFormat($settings, $invoice->invoice_id),
                            "quantity" => 1,
                            "currency_id" =>  Utility::getValByName('site_currency'),
                            "unit_price" => (float)$price,
                        ),
                    ),
                );
                
                \MercadoPago\SDK::setAccessToken($this->token);

                // try
                // {
                        $preference = new \MercadoPago\Preference();
                        // Create an item in the preference
                        $item = new \MercadoPago\Item();
                        $item->title = "Invoice : " . $request->invoice_id;
                        $item->quantity = 1;
                        $item->unit_price = (float)$request->amount;
                        $preference->items = array($item);
            
                        $success_url = route('invoice.mercado',[encrypt($invoice->id),'amount'=>(float)$request->amount,'flag'=>'success']);
                        $failure_url = route('invoice.mercado',[encrypt($invoice->id),'flag'=>'failure']);
                        $pending_url = route('invoice.mercado',[encrypt($invoice->id),'flag'=>'pending']);
                        $preference->back_urls = array(
                            "success" => $success_url,
                            "failure" => $failure_url,
                            "pending" => $pending_url
                        );
                        $preference->auto_return = "approved";
                        $preference->save();
            
                        // Create a customer object
                        $payer = new \MercadoPago\Payer();
                        // Create payer information
                        $payer->name = $user->name;
                        $payer->email = $user->email;
                        $payer->address = array(
                            "street_name" => ''
                        );
                        
                        if($this->mode =='live'){
                            $redirectUrl = $preference->init_point;
                        }else{
                            $redirectUrl = $preference->sandbox_init_point;
                        }
                        return redirect($redirectUrl);
                    


                // }
                // catch(Exception $e)
                // {
                //     return redirect()->back()->with('error', $e->getMessage());
                // }
                // callback url :  domain.com/plan/mercado
            }
            else
            {
                return redirect()->back()->with('error', 'Enter valid amount.');
            }


        }
        else
        {
            return redirect()->back()->with('error', 'Plan is deleted.');
        }

    }

    public function getInvoicePaymentStatus(Request $request,$invoice_id)
    {
       
        // if(\Auth::check())
        // {
        //     $user=\Auth::user();
        // }
        // else
        // {
        //     $user= User::where('id',$invoice->created_by)->first();
        // } 
        if(!empty($invoice_id))
        {
            $invoice_id = decrypt($invoice_id);
            $invoice    = Invoice::find($invoice_id);
            $orderID  = strtoupper(str_replace('.', '', uniqid('', true)));

            if(Auth::check()){
                $settings  = DB::table('settings')->where('created_by', '=', $user->creatorId())->get()->pluck('value', 'name');
            }else{
                $settings = Utility::settingsById($invoice->created_by);
            }

          
            if($invoice && $request->has('status'))
            {
                // try
                // {
                  
                    if($request->status == 'approved' && $request->flag =='success')
                    {
                        // $payments = InvoicePayment::create(
                        //     [
                        //         'invoice' => $invoice->id,
                        //         'date' => date('Y-m-d'),
                        //         'amount' => $request->amount,
                        //         'payment_method' => 1,
                        //         'transaction' => $orderID,
                        //         'payment_type' => __('Mercado Pago'),
                        //         'receipt' => '',
                        //         'created_by' => \Auth::user()->creatorId(),
                        //         'notes' => __('Invoice') . ' ' . Utility::invoiceNumberFormat($settings, $invoice->invoice_id),
                        //     ]
                        // );

                        $payments              = new InvoicePayment();
                        $payments->invoice       = $invoice->id;
                        $payments->date          = date('Y-m-d');
                        $payments->amount         = $request->amount;
                        $payments->payment_method = 1;
                        $payments->transaction     = $orderID;
                        $payments->payment_type      = __('Mercado Pago');
                        $payments->notes            =__('Invoice') . ' ' . Utility::invoiceNumberFormat($settings, $invoice->invoice_id);
                        $payments->created_by       = \Auth::user()->creatorId();
                        $payments->receipt        = '';
                        $payments->save();

                        $invoice = Invoice::find($invoice->id);

                        if($invoice->getDue() <= 0.0)
                        {
                            Invoice::change_status($invoice->id, 5);
                        }
                        elseif($invoice->getDue() > 0)
                        {
                            Invoice::change_status($invoice->id, 4);
                        }
                        else
                        {
                            Invoice::change_status($invoice->id, 3);
                        }
                        if(\Auth::check())
                        {
                             $user = Auth::user();
                        }
                        else
                        {
                           $user=User::where('id',$invoice->created_by)->first();
                        }
                        $settings  = Utility::settings();
                        if(isset($settings['payment_create_notification']) && $settings['payment_create_notification'] ==1){

                            $msg = __('New payment of ').$request->amount.' '.__('created for ').$user->name.__(' by Mercado Pago').'.';
                            //dd($msg);
                            Utility::send_slack_msg($msg); 
                               
                        }
                        if(isset($settings['telegram_payment_create_notification']) && $settings['telegram_payment_create_notification'] ==1){
                            $resp = __('New payment of ').$request->amount.' '.__('created for ').$user->name.__(' by Mercado Pago').'.';
                            Utility::send_telegram_msg($resp);   
                        }
                        $client_namee = Client::where('user_id',$invoice->client)->first();
                        if(isset($settings['twilio_invoice_payment_create_notification']) && $settings['twilio_invoice_payment_create_notification'] ==1)
                        {
                             $message = __('New payment of ').$amount.' '.__('created for ').$user->name.__(' by Mercado Pago').'.';
                             //dd($message);
                             Utility::send_twilio_msg($client_namee->mobile,$message);
                        } 
                        if(\Auth::check())
                        {
                            return redirect()->route('invoice.show',$invoice_id)->with('success', __('Invoice paid Successfully!'));
                        }
                        else
                        {
                            return redirect()->route('pay.invoice',\Illuminate\Support\Facades\Crypt::encrypt($invoice_id))->with('success', __('Invoice paid Successfully!'));
                        }
                    }else{

                        if(\Auth::check())
                        {
                            return redirect()->route('invoice.show',$invoice_id)->with('error', __('Transaction fail'));
                        }
                        else
                        {
                            return redirect()->route('pay.invoice',\Illuminate\Support\Facades\Crypt::encrypt($invoice_id))->with('error', __('Transaction fail'));
                        }
                       
                    }
                // }
                // catch(\Exception $e)
                // {
                //     return redirect()->route('invoices.index')->with('error', __('Plan not found!'));
                // }
            }else{
                if(\Auth::check())
                {
                    return redirect()->route('invoice.show',$invoice_id)->with('error', __('Invoice not found.'));
                }
                else
                {
                    return redirect()->route('pay.invoice',\Illuminate\Support\Facades\Crypt::encrypt($invoice_id))->with('error', __('Invoice not found.'));
                }
              
            }
        }else{
            if(\Auth::check())
            {
                return redirect()->route('invoice.index')->with('error', __('Invoice not found.'));
            }
            else
            {
                return redirect()->route('pay.invoice',\Illuminate\Support\Facades\Crypt::encrypt($invoice_id))->with('success', __('Payment successfully added'));
            }
        }
    }
}

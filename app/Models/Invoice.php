<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Project;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_id',
        'issue_date',
        'due_date',
        'client',
        'project',
        'tax',
        'type',
        'status',
        'description',
        'created_by',
    ];

    public static $statues = [
        'Draft',
        'Open',
        'Sent',
        'Unpaid',
        'Partialy Paid',
        'Paid',
    ];
    public static $statuesColor = [
        'Draft' => 'dark',
        'Open' => 'info',
        'Sent' => 'primary',
        'Unpaid' => 'warning',
        'Partialy Paid' => 'danger',
        'Paid' => 'success',
    ];

    public function clients()
    {
        return $this->hasOne('App\Models\User', 'id', 'client');
    }

    public function clientDetail()
    {
        return $this->hasOne('App\Models\Client', 'user_id', 'client');
    }

    public function items()
    {
        return $this->hasMany('App\Models\InvoiceProduct', 'invoice', 'id');
    }

    public function projects()
    {
        return $this->hasOne('App\Models\Project', 'id', 'project');
    }

    public function getSubTotal()
    {
        $subTotal = 0;
        foreach($this->items as $product)
        {
            $subTotal += ($product->price * $product->quantity);
        }

        return $subTotal;
    }

    public function getTotalTax()
    {
        $totalTax = 0;
        foreach($this->items as $product)
        {
            $taxes = Utility::totalTaxRate($product->tax);

            $totalTax += ($taxes / 100) * ($product->price * $product->quantity);
        }

        return $totalTax;
    }

    public function getTotalDiscount()
    {
        $totalDiscount = 0;
        foreach($this->items as $product)
        {
            $totalDiscount += $product->discount;
        }

        return $totalDiscount;
    }

    public function getTotal()
    {
        return ($this->getSubTotal() + $this->getTotalTax()) - $this->getTotalDiscount();
    }

    public function getDue()
    {
        $due = 0;
        foreach($this->payments as $payment)
        {
            $due += $payment->amount;
        }

        return ($this->getTotal() - $due) - $this->invoiceCreditNote();

        //        return ($this->getTotal() - $due) - $this->invoiceTotalCreditNote();
    }

    public function payments()
    {
        return $this->hasMany('App\Models\InvoicePayment', 'invoice', 'id');
    }

    public function creditNote()
    {
        return $this->hasMany('App\Models\CreditNote', 'invoice', 'id');
    }

    public static function change_status($invoice_id, $status)
    {
        $invoice         = Invoice::find($invoice_id);
        $old_status      = $invoice->status;
        $invoice->status = $status;
        $invoice->update();

        $new_status      = $invoice->status;
        $settings  = Utility::settings();
        if($old_status != $new_status){
            if(isset($settings['invoice_status_updated_notification']) && $settings['invoice_status_updated_notification'] ==1){

            $msg = __('Invoice ').\Auth::user()->invoiceNumberFormat($invoice->invoice_id).' '.__('status changed from ').__(\App\Models\Invoice::$statues[$old_status]).__(' to ').__(\App\Models\Invoice::$statues[$new_status]).'.';
            //dd($msg);
            Utility::send_slack_msg($msg); 
               
            }
            if(isset($settings['telegram_invoice_status_updated_notification']) && $settings['telegram_invoice_status_updated_notification'] ==1){
                $resp = __('Invoice ').\Auth::user()->invoiceNumberFormat($invoice->invoice_id).' '.__('status changed from ').__(\App\Models\Invoice::$statues[$old_status]).__(' to ').__(\App\Models\Invoice::$statues[$new_status]).'.';
                        Utility::send_telegram_msg($resp);    
            }
        }
        
    }

    public function invoiceCreditNote()
    {
        return $this->hasMany('App\Models\CreditNote', 'invoice', 'id')->sum('amount');
    }

    public static function client($client)
    {
        $categoryArr  = explode(',', $client);
        $unitRate = 0;
        foreach($categoryArr as $client)
        {
            $client          = User::find($client);
            $unitRate        = $client->name;
        }
        return $unitRate;
    }

    public static function project($project)
    {
        
        $projectArr  = explode(',', $project);
        $unitRate = 0;
        foreach($projectArr as $project)
        {
            $projects          = Project::find($project);
            $unitRate        = $projects->title;
        }
       
        return $unitRate;
    }

    public static function tax($tax)
    {
        $categoryArr  = explode(',', $tax);
        $unitRate = 0;
        foreach($categoryArr as $tax)
        {
            $tax              = TaxRate::find($tax);
            $unitRate        = $tax->name;
        }
        return $unitRate;
    }

    
}

<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\ContractType;
use App\Models\User;
use App\Models\UserDefualtView;
use App\Models\Utility;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ContractController extends Controller
{

    public function index()
    {
        if(\Auth::user()->type == 'company' || \Auth::user()->type == 'client')
        {
            if(\Auth::user()->type == 'company')
            {
                $contracts = Contract::where('created_by', '=', \Auth::user()->creatorId())->get();
            }
            else
            {
                $contracts = Contract::where('client', '=', \Auth::user()->id)->get();
            }

            $defualtView         = new UserDefualtView();
            $defualtView->route  = \Request::route()->getName();
            $defualtView->module = 'contract';
            $defualtView->view   = 'list';
            User::userDefualtView($defualtView);
            return view('contract.index', compact('contracts'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }


    public function create()
    {
        $contractTypes = ContractType::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $clients       = User::where('type', 'client')->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');

        return view('contract.create', compact('contractTypes', 'clients'));
    }


    public function store(Request $request)
    {
        if(\Auth::user()->type == 'company')
        {
            $rules = [
                'client' => 'required',
                'subject' => 'required',
                'type' => 'required',
                'value' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
            ];

            $validator = \Validator::make($request->all(), $rules);

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('contract.index')->with('error', $messages->first());
            }

            $contract              = new Contract();
            $contract->client      = $request->client;
            $contract->subject     = $request->subject;
            $contract->type        = $request->type;
            $contract->value       = $request->value;
            $contract->start_date  = $request->start_date;
            $contract->end_date    = $request->end_date;
            $contract->description = $request->description;
            $contract->created_by  = \Auth::user()->creatorId();
            $contract->save();

            $client      = User::find($request->client);
            $contractArr = [
                'contract_subject' => $request->subject,
                'contract_client' => $client->name,
                'contract_value' => \Auth::user()->priceFormat($request->value),
                'contract_start_date' => \Auth::user()->dateFormat($request->start_date),
                'contract_end_date' => \Auth::user()->dateFormat($request->end_date),
                'contract_description' => $request->description,
            ];

            // Send Email
            $resp = Utility::sendEmailTemplate('create_contract', [$client->id => $client->email], $contractArr);

            $settings  = Utility::settings();
            if(isset($settings['contract_create_notification']) && $settings['contract_create_notification'] ==1){

                $msg = $request->subject.__(' created by ').\Auth::user()->name.'.';
                //dd($msg);
                Utility::send_slack_msg($msg); 
                   
            }
            if(isset($settings['telegram_contract_create_notification']) && $settings['telegram_contract_create_notification'] ==1){
                    $response =$request->subject.__(' created by ').\Auth::user()->name.'.';
                    Utility::send_telegram_msg($response);    
            }
            $client_namee = Client::where('user_id',$request->client)->first();
            if(isset($settings['twilio_contract_create_notification']) && $settings['twilio_contract_create_notification'] ==1)
            {
                 $message = $request->subject.__(' created by ').\Auth::user()->name.'.';
                 //dd($message);
                 Utility::send_twilio_msg($client_namee->mobile,$message);
            }
            return redirect()->route('contract.index')->with('success', __('Contract successfully created.') . (($resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));


        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }


    public function show(Contract $contract)
    {
        //
    }


    public function edit(Contract $contract)
    {
        $contractTypes = ContractType::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $clients       = User::where('type', 'client')->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');

        return view('contract.edit', compact('contractTypes', 'clients', 'contract'));
    }


    public function update(Request $request, Contract $contract)
    {
        if(\Auth::user()->type == 'company')
        {
            $rules = [
                'client' => 'required',
                'subject' => 'required',
                'type' => 'required',
                'value' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
            ];

            $validator = \Validator::make($request->all(), $rules);

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('contract.index')->with('error', $messages->first());
            }

            $contract->client      = $request->client;
            $contract->subject     = $request->subject;
            $contract->type        = $request->type;
            $contract->value       = $request->value;
            $contract->start_date  = $request->start_date;
            $contract->end_date    = $request->end_date;
            $contract->description = $request->description;

            $contract->save();

            return redirect()->route('contract.index')->with('success', __('Contract successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy(Contract $contract)
    {
        if(\Auth::user()->type == 'company')
        {
            $contract->delete();

            return redirect()->route('contract.index')->with('success', __('Contract successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function description($id)
    {
        $contract = Contract::find($id);

        return view('contract.description', compact('contract'));
    }

    public function grid()
    {
        if(\Auth::user()->type == 'company' || \Auth::user()->type == 'client')
        {
            if(\Auth::user()->type == 'company')
            {
                $contracts = Contract::where('created_by', '=', \Auth::user()->creatorId())->get();
            }
            else
            {
                $contracts = Contract::where('client', '=', \Auth::user()->id)->get();
            }

            $defualtView         = new UserDefualtView();
            $defualtView->route  = \Request::route()->getName();
            $defualtView->module = 'contract';
            $defualtView->view   = 'grid';
            User::userDefualtView($defualtView);
            return view('contract.grid', compact('contracts'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }
}

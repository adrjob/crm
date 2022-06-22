<?php

namespace App\Http\Controllers;

use App\Models\Support;
use App\Models\SupportReply;
use App\Models\User;
use App\Models\UserDefualtView;
use App\Models\Utility;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SupportController extends Controller
{

    public function index()
    {
        $defualtView         = new UserDefualtView();
        $defualtView->route  = \Request::route()->getName();
        $defualtView->module = 'support';
        $defualtView->view   = 'list';

        if(\Auth::user()->type == 'company')
        {
            $supports = Support::where('created_by', \Auth::user()->creatorId())->get();
            User::userDefualtView($defualtView);

            return view('support.index', compact('supports'));
        }
        elseif(\Auth::user()->type == 'client')
        {
            $supports = Support::where('user', \Auth::user()->id)->orWhere('ticket_created', \Auth::user()->id)->get();
            User::userDefualtView($defualtView);

            return view('support.index', compact('supports'));
        }
        elseif(\Auth::user()->type == 'employee')
        {

            $supports = Support::where('user', \Auth::user()->id)->orWhere('ticket_created', \Auth::user()->id)->get();
            User::userDefualtView($defualtView);

            return view('support.index', compact('supports'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        $priority = [
            __('Low'),
            __('Medium'),
            __('High'),
            __('Critical'),
        ];

        if(\Auth::user()->type == 'client')
        {
            $users = User::where('type', 'employee')->where('created_by', \Auth::user()->creatorId())->orWhere('id', \Auth::user()->id)->get()->pluck('name', 'id');

        }
        else
        {
            $users = User::whereIn(
                'type', [
                          'employee',
                          'client',
                      ]
            )->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        }

        return view('support.create', compact('priority', 'users'));
    }


    public function store(Request $request)
    {

        $validator = \Validator::make(
            $request->all(), [
                               'subject' => 'required',
                               'priority' => 'required',
                           ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $support              = new Support();
        $support->subject     = $request->subject;
        $support->user        = $request->user;
        $support->priority    = $request->priority;
        $support->end_date    = $request->end_date;
        $support->ticket_code = date('hms');
        $support->status      = 'Open';

        if(!empty($request->attachment))
        {
            $fileName = time() . "_" . $request->attachment->getClientOriginalName();
            $request->attachment->storeAs('uploads/supports', $fileName);
            $support->attachment = $fileName;
        }
        $support->description    = $request->description;
        $support->created_by     = \Auth::user()->creatorId();
        $support->ticket_created = \Auth::user()->id;
        $support->save();

        $client     = User::find($request->user);
        $supportArr = [
            'support_title' => $request->subject,
            'assign_user' => $client->name,
            'support_end_date' => \Auth::user()->dateFormat($request->end_date),
            'support_description' => $request->description,
            'support_priority' => Support::$priority[$request->priority],
        ];

        // Send Email
        $resp = Utility::sendEmailTemplate('create_support', [$client->id => $client->email], $supportArr);

        $user_name = User::where('id',$request->user)->first();
        $settings  = Utility::settings();
        if(isset($settings['support_create_notification']) && $settings['support_create_notification'] ==1){

            $msg = __('New Support ticket created of ').__(\App\Models\Support::$priority[$support->priority]).' '.__('priority for ').$user_name->name.'.';
            //dd($msg);
             Utility::send_slack_msg($msg); 
               
        }    
        if(isset($settings['telegram_support_create_notification']) && $settings['telegram_support_create_notification'] ==1){
                $response = __('New Support ticket created of ').__(\App\Models\Support::$priority[$support->priority]).' '.__('priority for ').$user_name->name.'.';
                    Utility::send_telegram_msg($response);    
        }

        $employee = Employee::where('user_id',$request->user)->first();
        if(isset($settings['twilio_ticket_create_notification']) && $settings['twilio_ticket_create_notification'] ==1)
        {
             $message = __('New Support ticket created of ').__(\App\Models\Support::$priority[$support->priority]).' '.__('priority for ').$user_name->name.'.';
             Utility::send_twilio_msg($employee->emergency_contact,$message);
        }                                               
        return redirect()->route('support.index')->with('success', __('Support successfully created.') . (($resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));

    }


    public function show(Support $support)
    {
        //
    }


    public function edit(Support $support)
    {
        $priority = [
            __('Low'),
            __('Medium'),
            __('High'),
            __('Critical'),
        ];

        if(\Auth::user()->type == 'client')
        {
            $users = User::where('type', 'employee')->where('created_by', \Auth::user()->creatorId())->orWhere('id', \Auth::user()->id)->get()->pluck('name', 'id');

        }
        else
        {
            $users = User::whereIn(
                'type', [
                          'employee',
                          'client',
                      ]
            )->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        }

        return view('support.edit', compact('priority', 'users', 'support'));
    }


    public function update(Request $request, Support $support)
    {

        $validator = \Validator::make(
            $request->all(), [
                               'subject' => 'required',
                               'priority' => 'required',
                           ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $support->subject  = $request->subject;
        $support->user     = $request->user;
        $support->priority = $request->priority;
        $support->end_date = $request->end_date;
        if(!empty($request->attachment))
        {
            $fileName = time() . "_" . $request->attachment->getClientOriginalName();
            $request->attachment->storeAs('uploads/supports', $fileName);
            $support->attachment = $fileName;
        }
        $support->description = $request->description;

        $support->save();

        return redirect()->route('support.index')->with('success', __('Support successfully created.'));

    }


    public function destroy(Support $support)
    {
        if(\Auth::user()->type == 'company')
        {
            $support->delete();
            if($support->attachment)
            {
                \File::delete(storage_path('uploads/supports/' . $support->attachment));
            }

            return redirect()->route('support.index')->with('success', __('Support successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    public function reply($ids)
    {
        $id      = \Crypt::decrypt($ids);
        $replyes = SupportReply::where('support_id', $id)->get();
        $support = Support::find($id);

        foreach($replyes as $reply)
        {
            $supportReply          = SupportReply::find($reply->id);
            $supportReply->is_read = 1;
            $supportReply->save();
        }

        return view('support.reply', compact('support', 'replyes'));
    }

    public function replyAnswer(Request $request, $id)
    {
        $supportReply              = new SupportReply();
        $supportReply->support_id  = $id;
        $supportReply->user        = \Auth::user()->id;
        $supportReply->description = $request->description;
        $supportReply->created_by  = \Auth::user()->creatorId();
        $supportReply->save();

        return redirect()->back()->with('success', __('Support reply successfully send.'));
    }

    public function grid()
    {
        $defualtView         = new UserDefualtView();
        $defualtView->route  = \Request::route()->getName();
        $defualtView->module = 'support';
        $defualtView->view   = 'grid';
        if(\Auth::user()->type == 'company')
        {
            $supports = Support::where('created_by', \Auth::user()->creatorId())->get();
            User::userDefualtView($defualtView);

            return view('support.grid', compact('supports'));
        }
        elseif(\Auth::user()->type == 'client')
        {
            $supports = Support::where('user', \Auth::user()->id)->orWhere('ticket_created', \Auth::user()->id)->get();
            User::userDefualtView($defualtView);

            return view('support.grid', compact('supports'));
        }
        elseif(\Auth::user()->type == 'employee')
        {

            $supports = Support::where('user', \Auth::user()->id)->orWhere('ticket_created', \Auth::user()->id)->get();
            User::userDefualtView($defualtView);

            return view('support.grid', compact('supports'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function download($image,$extension)
    {
        return Storage::download('uploads/supports/'.$image.'.'.$extension);
        
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\event;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Utility;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{

    public function index()
    {
        if(\Auth::user()->type == 'company' || \Auth::user()->type == 'employee')
        {
            
            $transdate = date('Y-m-d', time());
            $events    = Event::where('created_by', \Auth::user()->creatorId())->get();
            $arrEvents = [];
            $events_current_month =  Event::whereMonth('start_date', date('m')) ->whereYear('start_date', date('Y')) ->get(['name','start_date','end_date']);
            foreach($events as $event)
            {

                $arr['id']        = $event['id'];
                $arr['title']     = $event['name'];
                $arr['start']     = $event['start_date'];
                $arr['end']       = $event['end_date'];
                $arr['className'] = $event['color'];
                $arr['url']       = route('event.show', $event['id']);

                $arrEvents[] = $arr;
            }
            $arrEvents = str_replace('"[', '[', str_replace(']"', ']', json_encode($arrEvents)));

            return view('event.index', compact('arrEvents', 'events','transdate','events_current_month'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        $departments = Department::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $departments->prepend('All', 0);

        return view('event.create', compact('departments'));
    }


    public function store(Request $request)
    {
        if(\Auth::user()->type == 'company')
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                                   'department' => 'required',
                                   'employee' => 'required',
                                   'start_date' => 'required',
                                   'start_time' => 'required',
                                   'end_date' => 'required',
                                   'end_time' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $event              = new event();
            $event->name        = $request->name;
            $event->where       = $request->where;
            $event->department  = implode(',', $request->department);
            $event->employee    = implode(',', $request->employee);
            $event->start_date  = $request->start_date;
            $event->start_time  = $request->start_time;
            $event->end_date    = $request->end_date;
            $event->end_time    = $request->end_time;
            $event->color       = $request->color;
            $event->description = $request->description;
            $event->created_by  = \Auth::user()->creatorId();

            $event->save();
            
            $department_name = Department::where('id', implode(',', $request->department))->first();
            $employee_name = User::where('id', implode(',', $request->employee))->first();
            $settings  = Utility::settings();
            if(isset($settings['event_create_notification']) && $settings['event_create_notification'] ==1){

                $msg =$request->name.' '.__('for ').$department_name->name.' '.__('for ').$employee_name->name.__(' from ').$request->start_date.' '.__('to ').$request->end_date.'.';
                //dd($msg);
                Utility::send_slack_msg($msg); 
                   
            }
            if(isset($settings['telegram_event_create_notification']) && $settings['telegram_event_create_notification'] ==1){
                    $resp =$request->name.' '.__('for ').$department_name->name.' '.__('for ').$employee_name->name.__(' from ').$request->start_date.' '.__('to ').$request->end_date.'.';
                    Utility::send_telegram_msg($resp);    
            }
            $employee = Employee::where('user_id',$request->employee)->first();
            if(isset($settings['twilio_event_create_notification']) && $settings['twilio_event_create_notification'] ==1)
            {
                 $message = $request->name.' '.__('for ').$department_name->name.' '.__('for ').$employee_name->name.__(' from ').$request->start_date.' '.__('to ').$request->end_date.'.';
                 //dd($message);
                 Utility::send_twilio_msg($employee->emergency_contact,$message);
            }
            return redirect()->route('event.index')->with('success', __('Event successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }


    public function show(event $event)
    {
        if(\Auth::user()->type == 'company' || \Auth::user()->type == 'employee')
        {
            $departments = Department::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $departments->prepend('All', 0);
            $event->department = explode(',', $event->department);
            $event->employee   = explode(',', $event->employee);

            $dep = [];
            foreach($event->department as $department)
            {

                if($department == 0)
                {
                    $dep[] = 'All Department';
                }
                else
                {
                    $departments = Department::find($department);
                    $dep[]       = $departments->name;
                }
            }

            $emp = [];
            foreach($event->employee as $employee)
            {
                if($employee == 0)
                {
                    $emp[] = 'All Employee';
                }
                else
                {
                    $employees = User::find($employee);
                    $emp[]     = $employees->name;
                }
            }


            return view('event.show', compact('event', 'dep', 'emp'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }


    }


    public function edit(event $event)
    {
        $departments = Department::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $departments->prepend('All', 0);

        return view('event.edit', compact('departments', 'event'));
    }


    public function update(Request $request, event $event)
    {
        if(\Auth::user()->type == 'company')
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                                   'start_date' => 'required',
                                   'start_time' => 'required',
                                   'end_date' => 'required',
                                   'end_time' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }


            $event->name        = $request->name;
            $event->where       = $request->where;
            $event->start_date  = $request->start_date;
            $event->start_time  = $request->start_time;
            $event->end_date    = $request->end_date;
            $event->end_time    = $request->end_time;
            $event->color       = $request->color;
            $event->description = $request->description;

            $event->save();

            return redirect()->route('event.index')->with('success', __('Event successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }


    public function destroy(event $event)
    {
        //
    }

    public function getEmployee(Request $request)
    {

        if(in_array('0', $request->department))
        {
            $employees = Employee::get();

        }
        else
        {
            $employees = Employee::whereIn('department', $request->department)->get();

        }
        $users = [];
        foreach($employees as $employee)
        {
            if(!empty($employee->users))
            {
                $users[$employee->users->id] = $employee->users->name;
            }

        }

        return response()->json($users);
    }
}

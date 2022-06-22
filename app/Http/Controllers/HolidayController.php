<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Imports\HolidayImport;
use Illuminate\Http\Request;
use App\Models\Utility;
use Illuminate\Support\Facades\Auth;

class HolidayController extends Controller
{

    public function index(Request $request)
    {
        if(\Auth::user()->type == 'company' || \Auth::user()->type == 'employee')
        {
            $transdate = date('Y-m-d', time());

            $holidays = Holiday::where('created_by', '=', \Auth::user()->creatorId());

            if(!empty($request->start_date))
            {
                $holidays->where('date', '>=', $request->start_date);
            }
            if(!empty($request->end_date))
            {
                $holidays->where('date', '<=', $request->end_date);
            }

            $holidays    = $holidays->get();
            $arrHolidays = [];
            $holidays_current_month =  Holiday::whereMonth('date', date('m')) ->whereYear('date', date('Y')) ->get(['occasion','date']);
            foreach($holidays as $holiday)
            {

                $arr['id']        = $holiday['id'];
                $arr['title']     = $holiday['occasion'];
                $arr['start']     = $holiday['date'];
                $arr['className'] = 'event-primary';
                $arr['url']       = route('holiday.edit', $holiday['id']);
                $arrHolidays[]    = $arr;
            }
            $arrHolidays = str_replace('"[', '[', str_replace(']"', ']', json_encode($arrHolidays)));

            return view('holiday.index', compact('holidays', 'arrHolidays','transdate','holidays_current_month'));
        }

    }


    public function create()
    {
        if(\Auth::user()->type == 'company')
        {
            return view('holiday.create');
        }
    }


    public function store(Request $request)
    {   

        if(\Auth::user()->type == 'company')
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'date' => 'required',
                                   'occasion' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $holiday             = new Holiday();
            $holiday->date       = $request->date;
            $holiday->occasion   = $request->occasion;
            $holiday->created_by = \Auth::user()->creatorId();
            $holiday->save();
            $settings  = Utility::settings();
            
            if(isset($settings['holiday_create_notification']) && $settings['holiday_create_notification'] ==1){

                $msg = $request->occasion.' '.__("holiday on").' '.$request->date. '.';
                //dd($msg);        
                Utility::send_slack_msg($msg); 
                      
            }
            if(isset($settings['telegram_holiday_create_notification']) && $settings['telegram_holiday_create_notification'] ==1){
                    $resp = $request->occasion.' '.__("holiday on").' '.$request->date. '.';
                    Utility::send_telegram_msg($resp);    
            }
            return redirect()->route('holiday.index')->with(
                'success', 'Holiday successfully created.'
            );
        }

    }


    public function show(Holiday $holiday)
    {

    }


    public function edit(Holiday $holiday)
    {
        if(\Auth::user()->type == 'company')
        {
            return view('holiday.edit', compact('holiday'));
        }
    }


    public function update(Request $request, Holiday $holiday)
    {
        if(\Auth::user()->type == 'company')
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'date' => 'required',
                                   'occasion' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $holiday->date     = $request->date;
            $holiday->occasion = $request->occasion;
            $holiday->save();

            return redirect()->route('holiday.index')->with(
                'success', 'Holiday successfully updated.'
            );
        }

    }

    public function destroy(Holiday $holiday)
    {
        if(\Auth::user()->type == 'company')
        {
            $holiday->delete();

            return redirect()->route('holiday.index')->with(
                'success', 'Holiday successfully deleted.'
            );
        }

    }

    public function importFile()
    {
        return view('holiday.import');
    }

    public function import(Request $request)
    {

        $rules = [
            'file' => 'required',
        ];

        $validator = \Validator::make($request->all(), $rules);

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
     

        $holiday = (new HolidayImport())->toArray(request()->file('file'))[0];

        $totalholiday = count($holiday) - 1;
        $errorArray    = [];
        for($i = 1; $i <= count($holiday) - 1; $i++)
        {
            $customer = $holiday[$i];

            $customerByEmail = Holiday::where('date', $customer[1])->first();
            if(!empty($customerByEmail))
            {
                $holidayData = $customerByEmail;
            }
            else
            {
                $holidayData = new Holiday();
            }
            
            $holidayData->date             = $customer[0];
            $holidayData->occasion         = $customer[1];
            $holidayData->created_by       = \Auth::user()->creatorId();

            if(empty($holidayData))
            {
                $errorArray[] = $holidayData;
            }
            else
            {
                $holidayData->save();
            }
            
        }

        $errorRecord = [];
        if(empty($errorArray))
        {
            $data['status'] = 'success';
            $data['msg']    = __('Record successfully imported');
        }
        else
        {
            $data['status'] = 'error';
            $data['msg']    = count($errorArray) . ' ' . __('Record imported fail out of' . ' ' . $totalholiday . ' ' . 'record');


            foreach($errorArray as $errorData)
            {

                $errorRecord[] = implode(',', $errorData);

            }

            \Session::put('errorArray', $errorRecord);
        }

        return redirect()->back()->with($data['status'], $data['msg']);
    }

}

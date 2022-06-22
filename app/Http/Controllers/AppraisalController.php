<?php

namespace App\Http\Controllers;

use App\Models\Appraisal;
use App\Models\Competencies;
use App\Models\User;
use App\Models\PerformanceType;
use Illuminate\Http\Request;

class AppraisalController extends Controller
{
    public function index()
    {
        if(\Auth::user()->type == 'company' || \Auth::user()->type == 'employee')
        {
            $user = \Auth::user();
            if($user->type == 'employee')
            {
                $appraisals = Appraisal::where('created_by', '=', \Auth::user()->creatorId())->where('employee', $user->id)->get();
            }
            else
            {
                $appraisals = Appraisal::where('created_by', '=', \Auth::user()->creatorId())->get();
            }

            return view('appraisal.index', compact('appraisals'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        $performance     = PerformanceType::where('created_by', '=', \Auth::user()->creatorId())->get();
        $employees      = User::where('created_by', \Auth::user()->creatorId())->where('type', '=', 'employee')->get()->pluck('name', 'id');
        $employees->prepend('Select Employee', '');

        return view('appraisal.create', compact('performance','employees'));
    }


    public function store(Request $request)
    {
        if(\Auth::user()->type == 'company')
        {
            $validator = \Validator::make(
                $request->all(), [

                                   'employee' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $appraisal                      = new Appraisal();
            $appraisal->employee            = $request->employee;
            $appraisal->appraisal_date      = $request->appraisal_date;
            $appraisal->rating         = json_encode($request->rating, true);
            $appraisal->remark              = $request->remark;
            $appraisal->created_by          = \Auth::user()->creatorId();
            $appraisal->save();

            return redirect()->route('appraisal.index')->with('success', __('Appraisal successfully created.'));
        }
    }

    public function show(Appraisal $appraisal)
    {
        $ratings = json_decode($appraisal->rating, true);
        $performance     = PerformanceType::where('created_by', '=', \Auth::user()->creatorId())->get();
        return view('appraisal.show', compact('appraisal', 'ratings','performance'));
    }


    public function edit(Appraisal $appraisal)
    {
        $performance     = PerformanceType::where('created_by', '=', \Auth::user()->creatorId())->get();
        $employees      = User::where('created_by', \Auth::user()->creatorId())->where('type', '=', 'employee')->get()->pluck('name', 'id');
        $employees->prepend('Select Employee', '');

        $ratings = json_decode($appraisal->rating,true);


        return view('appraisal.edit', compact( 'appraisal', 'employees','ratings','performance'));
    }


    public function update(Request $request, Appraisal $appraisal)
    {
        if(\Auth::user()->type == 'company')
        {
            $validator = \Validator::make(
                $request->all(), [

                                   'employee' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $appraisal->employee            = $request->employee;
            $appraisal->appraisal_date      = $request->appraisal_date;
            $appraisal->rating         = json_encode($request->rating, true);          
            $appraisal->remark              = $request->remark;
            $appraisal->save();

            return redirect()->route('appraisal.index')->with('success', __('Appraisal successfully updated.'));
        }
    }


    public function destroy(Appraisal $appraisal)
    {
        if(\Auth::user()->type == 'company')
        {
            $appraisal->delete();

            return redirect()->route('appraisal.index')->with('success', __('Appraisal successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Competencies;
use App\Models\PerformanceType;
use Illuminate\Http\Request;

class CompetenciesController extends Controller
{

    public function index()
    {

         $competencies = Competencies::where('created_by', \Auth::user()->creatorId())->get();
         return view('competencies.index', compact('competencies'));

    }


    public function create()
    {
        $performance     = PerformanceType::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');

        return view('competencies.create', compact('performance'));

    }


    public function store(Request $request)
    {


            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                                   'type' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $competencies             = new Competencies();
            $competencies->name       = $request->name;
            $competencies->type       = $request->type;
            $competencies->created_by = \Auth::user()->creatorId();
            $competencies->save();

            return redirect()->route('competencies.index')->with('success', __('Competencies  successfully created.'));

    }


    public function show(Competencies $competencies)
    {
        //
    }


    public function edit($id)
    {
        $competencies = Competencies::find($id);
        $performance     = PerformanceType::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');

        return view('competencies.edit', compact('performance', 'competencies'));

    }


    public function update(Request $request, $id)
    {

            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                                   'type' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $competencies       = Competencies::find($id);
            $competencies->name = $request->name;
            $competencies->type = $request->type;
            $competencies->save();

            return redirect()->route('competencies.index')->with('success', __('Competencies  successfully updated.'));

    }


    public function destroy($id)
    {
            $competencies = Competencies::find($id);
            $competencies->delete();

            return redirect()->route('competencies.index')->with('success', __('Competencies  successfully deleted.'));

    }
}

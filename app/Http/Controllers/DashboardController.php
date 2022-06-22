<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Contract;
use App\Models\Deal;
use App\Models\DealStage;
use App\Models\Employee;
use App\Models\Estimate;
use App\Models\event;
use App\Models\Goal;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\LandingPageSection;
use App\Models\Lead;
use App\Models\LeadStage;
use App\Models\Meeting;
use App\Models\Order;
use App\Models\Pipeline;
use App\Models\Project;
use App\Models\Projects;
use App\Models\ProjectStage;
use App\Models\ProjectTask;
use App\Models\Support;
use App\Models\User;
use App\Models\Utility;
use Carbon\Carbon;
use Auth;
use Cookie;

class DashboardController extends Controller
{

    public function index()
    {
        if(\Auth::check())
        {
            $data['estimateOverviewColor'] = Estimate::$statuesColor;
            $data['invoiceOverviewColor']  = Invoice::$statuesColor;
            $data['projectStatusColor']    = Project::$projectStatusColor;

            if(\Auth::user()->type == 'company')
            {
                $data['totalClient']     = User::where('created_by', \Auth::user()->creatorId())->where('type', 'client')->count();
                $data['totalEmployee']   = User::where('created_by', \Auth::user()->creatorId())->where('type', 'employee')->count();
                $data['totalProject']    = $totalProject = Project::where('created_by', \Auth::user()->creatorId())->count();
                $data['totalEstimation'] = $totalEstimation = Estimate::where('created_by', \Auth::user()->creatorId())->count();
                $data['totalInvoice']    = $totalInvoice = Invoice::where('created_by', \Auth::user()->creatorId())->count();
                $data['totalLead']       = Lead::where('created_by', \Auth::user()->creatorId())->count();
                $data['totalDeal']       = Deal::where('created_by', \Auth::user()->creatorId())->count();
                $data['totalItem']       = Item::where('created_by', \Auth::user()->creatorId())->count();

                $estimationStatus = Estimate::$statues;
                $estimations      = [];

                $statusColor = [
                    'success',
                    'info',
                    'warning',
                    'danger',
                ];

                foreach($estimationStatus as $k => $status)
                {
                    $estimation['status']     = $status;
                    $estimation['total']      = $total = Estimate::where('created_by', \Auth::user()->creatorId())->where('status', $k)->count();
                    $percentage               = ($totalEstimation != 0) ? ($total * 100) / $totalEstimation : '0';
                    $estimation['percentage'] = number_format($percentage, 2);
                    $estimations[]            = $estimation;
                }

                $invoiceStatus = Invoice::$statues;
                $invoices      = [];
                foreach($invoiceStatus as $k => $status)
                {
                    $invoice['status']     = $status;
                    $invoice['total']      = $total = Invoice::where('created_by', \Auth::user()->creatorId())->where('status', $k)->count();
                    $percentage            = ($totalInvoice != 0) ? ($total * 100) / $totalInvoice : '0';
                    $invoice['percentage'] = number_format($percentage, 2);
                    $invoices[]            = $invoice;
                }


                $projectStatus = Project::$projectStatus;
                $projects      = $projectLabel = $projectData = [];

                foreach($projectStatus as $k => $status)
                {
                    $project['status']     = $projectLabel[] = $status;
                    $project['total']      = $total = Project::where('created_by', \Auth::user()->creatorId())->where('status', $k)->count();
                    $percentage            = ($totalProject != 0) ? ($total * 100) / $totalProject : '0';
                    $project['percentage'] = $projectData[] = number_format($percentage, 2);
                    $projects[]            = $project;
                }

                $data['topDueInvoice']      = Invoice::where('created_by', \Auth::user()->creatorId())->where('due_date', '<', date('Y-m-d'))->limit(5)->get();
                $data['topDueProject']      = Project::where('created_by', \Auth::user()->creatorId())->where('due_date', '<', date('Y-m-d'))->limit(5)->get();
                $data['topDueTask']         = ProjectTask::select('project_tasks.*', 'projects.title as project_title')->leftjoin('projects', 'project_tasks.project_id', 'projects.id')->where('projects.created_by', \Auth::user()->creatorId())->where('project_tasks.due_date', '<', date('Y-m-d'))->limit(5)->get();
                $data['topMeeting']         = Meeting::where('created_by', \Auth::user()->creatorId())->where('date', '>', date('Y-m-d'))->limit(5)->get();
                $data['thisWeekEvent']      = Event::whereBetween(
                    'start_date', [
                                    Carbon::now()->startOfWeek(),
                                    Carbon::now()->endOfWeek(),
                                ]
                )->where('created_by', \Auth::user()->creatorId())->limit(5)->get();
                $data['contractExpirySoon'] = Contract::where('created_by', \Auth::user()->creatorId())->whereMonth('start_date', date('m'))->whereYear('start_date', date('Y'))->whereMonth('end_date', date('m'))->whereYear('end_date', date('Y'))->get();

                $date               = \Carbon\Carbon::today()->subDays(7);
                $data['newTickets'] = Support::where('created_by', \Auth::user()->creatorId())->where('created_at', '>', $date)->get();
                $data['newClients'] = User::where('created_by', \Auth::user()->creatorId())->where('type', 'client')->orderBy('id', 'desc')->limit(5)->get();

                $data['estimationOverview'] = $estimations;
                $data['invoiceOverview']    = $invoices;
                $data['projects']           = $projects;
                $data['projectLabel']       = $projectLabel;
                $data['projectData']        = $projectData;

                $data['goals'] = Goal::where('created_by', '=', \Auth::user()->creatorId())->where('display', 1)->get();

                $data['pipelines']     = Pipeline::where('created_by', '=', \Auth::user()->creatorId())->count();
                $data['leadStages']    = LeadStage::where('created_by', '=', \Auth::user()->creatorId())->count();
                $data['dealStages']    = DealStage::where('created_by', '=', \Auth::user()->creatorId())->count();
                $data['projectStages'] = ProjectStage::where('created_by', '=', \Auth::user()->creatorId())->count();

                return view('dashboard.index', compact('data'));
            }
            elseif(\Auth::user()->type == 'client')
            {

                $data['totalProject']    = $totalProject = Project::where('client', \Auth::user()->id)->count();
                $data['totalEstimation'] = $totalEstimation = Estimate::where('client', \Auth::user()->id)->count();
                $data['totalInvoice']    = $totalInvoice = Invoice::where('client', \Auth::user()->id)->count();
                $data['totalDeal']       = Deal::leftjoin('client_deals', 'client_deals.deal_id', 'deals.id')->where('client_deals.client_id', \Auth::user()->id)->count();


                $estimationStatus = Estimate::$statues;
                $estimations      = [];
                foreach($estimationStatus as $k => $status)
                {
                    $estimation['status']     = $status;
                    $estimation['total']      = $total = Estimate::where('client', \Auth::user()->id)->where('status', $k)->count();
                    $percentage               = ($totalEstimation != 0) ? ($total * 100) / $totalEstimation : '0';
                    $estimation['percentage'] = number_format($percentage, 2);
                    $estimations[]            = $estimation;
                }


                $invoiceStatus = Invoice::$statues;
                $invoices      = [];
                foreach($invoiceStatus as $k => $status)
                {
                    $invoice['status']     = $status;
                    $invoice['total']      = $total = Invoice::where('client', \Auth::user()->id)->where('status', $k)->count();
                    $percentage            = ($totalInvoice != 0) ? ($total * 100) / $totalInvoice : '0';
                    $invoice['percentage'] = number_format($percentage, 2);
                    $invoices[]            = $invoice;
                }


                $projectStatus = Project::$projectStatus;
                $projects      = $projectLabel = $projectData = [];

                foreach($projectStatus as $k => $status)
                {
                    $project['status']     = $projectLabel[] = $status;
                    $project['total']      = $total = Project::where('client', \Auth::user()->id)->where('status', $k)->count();
                    $percentage            = ($totalProject != 0) ? ($total * 100) / $totalProject : '0';
                    $project['percentage'] = $projectData[] = number_format($percentage, 2);
                    $projects[]            = $project;
                }

                $data['topDueInvoice'] = Invoice::where('client', \Auth::user()->id)->where('due_date', '<', date('Y-m-d'))->limit(5)->get();
                $data['topDueProject'] = Project::where('client', \Auth::user()->id)->where('due_date', '<', date('Y-m-d'))->limit(5)->get();

                $data['contractExpirySoon'] = Contract::where('client', \Auth::user()->id)->whereMonth('start_date', date('m'))->whereYear('start_date', date('Y'))->whereMonth('end_date', date('m'))->whereYear('end_date', date('Y'))->get();

                $date = \Carbon\Carbon::today()->subDays(7);


                $data['estimationOverview'] = $estimations;
                $data['invoiceOverview']    = $invoices;
                $data['projects']           = $projects;
                $data['projectLabel']       = $projectLabel;
                $data['projectData']        = $projectData;

                $data['goals'] = Goal::where('created_by', '=', \Auth::user()->creatorId())->where('display', 1)->get();

                return view('dashboard.index', compact('data'));
            }
            elseif(\Auth::user()->type == 'employee')
            {

                $data['totalProject'] = $totalProject = Project::leftjoin('project_users', 'project_users.project_id', 'projects.id')->where('project_users.user_id', \Auth::user()->id)->count();
                $data['totalLead']    = Lead::where('user_id', \Auth::user()->id)->count();

                $data['totalDeal'] = Deal::leftjoin('user_deals', 'user_deals.deal_id', 'deals.id')->where('user_deals.user_id', \Auth::user()->id)->count();
                $data['totalItem'] = Item::where('created_by', \Auth::user()->creatorId())->count();


                $projectStatus = Project::$projectStatus;
                $projects      = $projectLabel = $projectData = [];

                foreach($projectStatus as $k => $status)
                {
                    $project['status']     = $projectLabel[] = $status;
                    $project['total']      = $total = Project::leftjoin('project_users', 'project_users.project_id', 'projects.id')->where('project_users.user_id', \Auth::user()->id)->where('status', $k)->count();
                    $percentage            = ($totalProject != 0) ? ($total * 100) / $totalProject : '0';
                    $project['percentage'] = $projectData[] = number_format($percentage, 2);
                    $projects[]            = $project;
                }


                $data['topDueProject'] = Project::leftjoin('project_users', 'project_users.project_id', 'projects.id')->where('project_users.user_id', \Auth::user()->id)->where('due_date', '<', date('Y-m-d'))->limit(5)->get();
                $data['topDueTask']    = ProjectTask::where('assign_to', \Auth::user()->id)->where('due_date', '<', date('Y-m-d'))->limit(5)->get();

                $employee           = Employee::where('user_id', \Auth::user()->id)->first();
                $data['topMeeting'] = Meeting::where('department', 0)->orWhereIn(
                    'designation', [
                                     0,
                                     $employee->designation,
                                 ]
                )->orWhereIn(
                    'department', [
                                    0,
                                    $employee->department,
                                ]
                )->where('date', '>', date('Y-m-d'))->limit(5)->get();

                $data['thisWeekEvent'] = Event::whereBetween(
                    'start_date', [
                                    Carbon::now()->startOfWeek(),
                                    Carbon::now()->endOfWeek(),
                                ]
                )->whereIn(
                    'department', [
                                    0,
                                    $employee->department,
                                ]
                )->orWhereIn(
                    'employee', [
                                  0,
                                  \Auth::user()->id,
                              ]
                )->limit(5)->get();


                $data['projects']     = $projects;
                $data['projectLabel'] = $projectLabel;
                $data['projectData']  = $projectData;

                $data['goals'] = Goal::where('created_by', '=', \Auth::user()->creatorId())->where('display', 1)->get();

                $date                       = date("Y-m-d");
                $data['employeeAttendance'] = Attendance::orderBy('id', 'desc')->where('employee_id', '=', \Auth::user()->id)->where('date', '=', $date)->first();

                return view('dashboard.index', compact('data'));
            }
        }
        else
        {
            if(!file_exists(storage_path() . "/installed"))
            {
                header('location:install');
                die;
            }
            else
            {
                $settings = \Utility::settings();
                if($settings['display_landing_page'] == 'on')
                {
                    $get_section = LandingPageSection::orderBy('section_order', 'ASC')->get();

                    return view('layouts.landing', compact('get_section'));

                }
                else
                {
                    return redirect('login');
                }
            }


        }

    }

    public function changeLang($lang)
    {
        $changelanguage=Cookie::queue('LANGUAGE',$lang, 120);

        return redirect()->back()->with('success', __('Language Change Successfully!'));
    }

}


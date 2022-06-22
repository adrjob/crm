<?php

namespace App\Exports;

use App\Models\Goal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GoalTypeExport implements FromCollection ,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection 
    */
    public function collection()
    {
        $data = Goal::get();
        foreach($data as $k => $goals)
        {
            unset($goals->id,$goals->created_by,$goals->created_at,$goals->updated_at);
            $data[$k]["goal_type"]   = Goal::$goalType[$goals->goal_type];
        }
        return $data;
    }

    public function headings(): array
    {
        return [
            "Name",
            "Amount",
            "Goal Type",
            "From",
            "To",
            "Display",
        ];
    }


}

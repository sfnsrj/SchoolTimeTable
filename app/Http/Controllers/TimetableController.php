<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\timetable ;
use App\assign ;
use DB ;

class TimetableController extends Controller
{
    public function destroy(Request $request)
    {
        $id = $request -> id ;
        $data = timetable ::find($id);
        $data -> delete();
        return redirect()->back();
    }

    public function update(Request $request)
    {
        $this->validate($request,[

            'day'=> 'required|max:3|min:3',
            'period'=> 'required|max:2|min:1',
            'grade'=> 'required|max:3|min:2',
            'sub_id'=> 'required|max:6|min:1',
            'teacher_id'=> 'required|max:6|min:6'
        ]);


        $id = $request -> id ;
        $data = timetable ::find($id);
        $data -> day = strtoupper($request -> day) ;
        $data -> grade = strtoupper($request ->grade) ;
        $data -> period = $request -> period ;
        $data -> sub_id = strtoupper($request -> sub_id) ;
        $data -> teacher_id = strtoupper($request -> teacher_id );
        $data -> save() ;
        // return view('tables/#modal-update') ;
        //return view('LaravelApps/SchoolTimeTable/public/tables/#modal-update') ;
        return redirect()->back();
    }

    public function store(Request $request)
    {
        $this->validate($request,[

            'day'=> 'required|max:3|min:3',
            'period'=> 'required|max:2|min:1',
            'grade'=> 'required|max:3|min:2',
            'sub_id'=> 'required|max:6|min:1',
            'teacher_id'=> 'required|max:6|min:6'
        ]);
        

        $timetables = new timetable ;
        $timetables -> day = strtoupper($request -> day);
        $timetables -> grade = strtoupper($request -> grade) ;
        $timetables -> period = $request -> period ;
        $timetables -> sub_id = strtoupper($request -> sub_id) ;
        $timetables -> teacher_id = strtoupper($request -> teacher_id );
        $timetables -> save() ;

        // $id = $request -> teacher_id ;
        // $period = $request -> period ;
        // $data = assign ::find($id);
        // $data -> {$request -> period} = 1 ;
        
        //return view('LaravelApps/SchoolTimeTable/public/tables/modal-form') ;
        return redirect()->back();
    }


    public function fetch(Request $request)
    {
        $value = $request->get('value');
        $dependent = 'teacher_id';
        $period = $request->get('period');
        $day = $request->get('day');

        $teachers = DB::table('timetables')
                ->select('teacher_id')
                ->where('period', $period)
                ->where('day', $day)
                ->get();
        
        $teachesAls = DB::table('teaches')
            ->select('teacher_id')
            ->where('sub_id', $value)
            ->get();
        
        $a1 = array();
        $a2 = array();

        foreach($teachers as $teacher)
        {
            array_push($a1,$teacher->teacher_id);
        }

        foreach($teachesAls as $teachesAl)
        {
            array_push($a2,$teachesAl->teacher_id);
        }

        
        $result=array_diff($a2,$a1);

        
        $output = '<option value="" disabled selected>Select Teacher...</option>';
        foreach($result as $row)
        {
            $output .= '<option value="'.$row.'">'.$row.'</option>';
        }
        echo $output;
    }

}

<?php
namespace App\Exports;

use App\Invoice;
use Maatwebsite\Excel\Concerns\FromArray;
use DB;
use auth;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;

class DeliveryReport implements FromArray,ShouldAutoSize,WithEvents
{
    private $start_date;
    private $end_date;
    private $del_boy_id;
    private $beat_id;
    public function __construct($start_date,$end_date,$del_boy_id,$beat_id)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->del_boy_id = $del_boy_id;
        $this->beat_id = $beat_id;
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A2:H2'; // All headers
                $style_head = array(
                    'font'  => array(
                        'bold'  => true,
                        'name'  => 'Verdana'
                    ),
                    'alignment' => array('horizontal' => 'center') ,
                );
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style_head);
                $event->sheet->mergeCells('A1:H1');
                $styleArray = array(
                    'font'  => array(
                        'bold'  => true,
                        'size'  => 15,
                        'name'  => 'Verdana'
                    ),
                    'alignment' => array('horizontal' => 'center') ,
                );
                $event->sheet->getDelegate()->getStyle('A1:H1')->applyFromArray($styleArray);
            },
        ];
    }
    
    public function array(): array
    {
        
        $branch_id = Auth::guard('branch')->user()->id;
        $date_from = Carbon::parse($this->start_date)->startOfDay();
        $date_to = Carbon::parse($this->end_date)->endOfDay();

        $job = DB::table('job')
            ->select('job.*','client.client_id as c_id','client.name as c_name','job_type.name as job_type_name','branch.name as branch_name')
            ->leftjoin('client','client.id','=','job.client_id')
            ->leftjoin('branch','branch.id','=','job.created_by_id')
            ->leftjoin('job_type','job_type.id','=','job.job_type')
            ->where('job.created_by_id',$branch_id);
        if (isset($this->type) && !empty($this->type)) {
            if ($this->type == '1') {
                $job = $job->where('job.status','<',3);
            } elseif ($this->type == '2') {
                $job = $job->where('job.status',3);
            }else{
                $job = $job->where('job.status',4);
            }
        }
        $job = $job->whereBetween('job.created_at', [$date_from,$date_to])
            ->orderBy('job.id','desc')->get();
        ///////////////////////////////Make Excel Data////////////////////////////////////////////////

        if ($this->type == '1') {
            $data [] = ["Branch Report Of Pending Jobs"];
        }elseif ($this->type == '2') {
            $data [] = ["Branch Report Of Correction Jobs"];
        }else {
            $data [] = ["Branch Report Of Closed Jobs"];
        }
       
        $data[] = ['Sl No','Job Id','Client Id','Client Name','Job Description','Date','Status','Close Date']; 
        $count = 1;
        foreach ($job as $key => $value) {
            $status = "Processing";
            if($value->status == '3') {
                $status = "Document Problem";
            } elseif($value->status == '4') {
                $status = "Closed";
            }
            
            $data[] = [ $count,$value->job_id, $value->c_id,  $value->c_name,  $value->job_type_name,  $value->created_at, $status,$value->completed_date,];
            $count++;
        }
        return $data;
    }
}
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
                $cellRange = 'A2:M2'; // All headers
                $style_head = array(
                    'font'  => array(
                        'bold'  => true,
                        'name'  => 'Verdana'
                    ),
                    'alignment' => array('horizontal' => 'center') ,
                );
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style_head);
                $event->sheet->mergeCells('A1:M1');
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
        $date_from = Carbon::parse($this->start_date)->startOfDay();
        $date_to = Carbon::parse($this->end_date)->endOfDay();

        $report = DB::table('start_journey')
            ->select('start_journey.*','user.name as boy_name','vehicle_type.name as vehicle_name','beat_name.name as beat_name')
            ->leftjoin('user','user.id','=','start_journey.user_id')
            ->leftjoin('beat_name','beat_name.id','=','start_journey.beat_id')
            ->leftjoin('vehicle_type','vehicle_type.id','=','start_journey.vehicle_id')
            ->where('start_journey.status',2)
            ->whereBetween('start_journey.created_at',[$date_from, $date_to]);
        if (isset($this->del_boy_id) && !empty($this->del_boy_id)) {
            $report = $report->where('start_journey.user_id',$this->del_boy_id);
        }
        if (isset($this->beat_id) && !empty($this->beat_id)) {
            $report = $report->where('start_journey.beat_id',$this->beat_id);
        }

        $report = $report->orderBy('start_journey.id','desc')->get();
        ///////////////////////////////Make Excel Data////////////////////////////////////////////////


        $data [] = ["Delivery Day Report"];
       
        $data[] = ['Sl No','Date','Day','Del Boy Name','Vehicle Type','Beat Name','Start Time','End Time','Start Address','End Address','Total KM For Day','Per KM Cost','Total Cost']; 
        $count = 1;
        foreach ($report as $key => $value) {
            $date = Carbon::parse($value->created_at);
            $day = $date->format('l');
            
            $data[] = [ $count,$value->created_at, $day,  $value->boy_name,  $value->vehicle_name,  $value->beat_name,$value->start_time,$value->end_time,$value->start_address,$value->end_address,$value->total_km,$value->per_km_cost,$value->total_cost,];
            $count++;
        }
        return $data;
    }
}
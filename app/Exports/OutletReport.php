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

class OutletReport implements FromArray,ShouldAutoSize,WithEvents
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
        $date_from = Carbon::parse($this->start_date)->startOfDay();
        $date_to = Carbon::parse($this->end_date)->endOfDay();

        $report = DB::table('delivery_details')
            ->select('delivery_details.*','user.name as boy_name','vehicle_type.name as vehicle_name','beat_name.name as beat_name','outlet.name as outlet_name','outlet.address as outlet_address')
            ->leftjoin('user','user.id','=','delivery_details.del_boy_id')
            ->leftjoin('outlet','outlet.id','=','delivery_details.out_let_id')
            ->leftjoin('beat_name','beat_name.id','=','outlet.beat_id')
            ->join('start_journey','start_journey.id','=','delivery_details.journey_id')
            ->leftjoin('vehicle_type','vehicle_type.id','=','start_journey.vehicle_id')
            ->whereBetween('delivery_details.created_at',[$date_from, $date_to]);
        if (isset($this->del_boy_id) && !empty($this->del_boy_id)) {
            $report = $report->where('delivery_details.del_boy_id',$this->del_boy_id);
        }
        if (isset($this->beat_id) && !empty($this->beat_id)) {
            $report = $report->where('start_journey.beat_id',$this->beat_id);
        }

        $report = $report->orderBy('start_journey.id','desc')->get();
        ///////////////////////////////Make Excel Data////////////////////////////////////////////////


        $data [] = ["Outlet Delivery Report"];
       
        $data[] = ['Sl No','Date','Day','Outlet Name','Del Boy Name','Vehicle Type','Outlet Address','Beat Name']; 
        $count = 1;
        foreach ($report as $key => $value) {
            $date = Carbon::parse($value->created_at);
            $day = $date->format('l');
            
            $data[] = [ $count,$value->created_at, $day, $value->outlet_name, $value->boy_name,  $value->vehicle_name, $value->outlet_address,  $value->beat_name];
            $count++;
        }
        return $data;
    }
}
@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">

    <div class="row" style="margin-top: 21px;">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2 style="float:left">Delivery Details 
                        @if (isset($journey) && !empty($journey))
                            {{$journey->boy_name}}
                        @endif
                    </h2>
                    <h2 style="float:right">Date : 
                        @if (isset($journey) && !empty($journey))
                            {{$journey->date}}
                        @endif
                    </h2>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    <div class="table-responsive">
                        <table class="table table-striped jambo_table bulk_action">
                            <thead>
                                <tr class="headings">           
                                    <th class="column-title">Sl</th>
                                    <th class="column-title">Date</th>
                                    <th class="column-title">Day</th>                                    
                                    <th class="column-title">Outlet Name</th>
                                    <th class="column-title">Delivery Boy Name</th>
                                    <th class="column-title">Vehicle Type</th>
                                    <th class="column-title">Beat Name</th>
                                    <th class="column-title">Outlet Address</th>
                            </thead>

                            <tbody>
                               @if (isset($report) && !empty($report) && (count($report) > 0))
                               @php
                                   $data_count = 1;
                               @endphp
                                @foreach ($report as $item)
                                <tr>
                                    <td>{{$data_count++}}</td>
                                    <td>{{$item->created_at}}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('l')}}</<td>
                                    <td>{{$item->outlet_name}}</td>
                                    <td>{{$item->boy_name}}</td>
                                    <td>{{$item->vehicle_name}}</td>
                                    <td>{{$item->beat_name}}</td>
                                    <td>{{$item->outlet_address}}</td>
                                    
                                </tr>
                                @endforeach
                                   
                               @endif
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


 @endsection

@section('script')
 @endsection
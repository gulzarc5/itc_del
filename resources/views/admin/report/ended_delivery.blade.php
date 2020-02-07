@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">
    <div>
        {{ Form::open(['method' => 'post','route'=>'admin.ended_delivery_report_search']) }}    
            <div id="err_msg">
                
            </div>
      
            <div class="form-group row">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">From Date<span class="required" style="color:red;font-weight:bold">*</span></label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <input type="date" class="form-control" required name="s_date" id="s_date" >      
                </div>
                <label class="control-label col-md-1 col-sm-3 col-xs-12" for="first-name">To Date<span class="required" style="color:red;font-weight:bold">*</span></label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <input type="date" class="form-control" required name="e_date" id="e_date" >        
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Select Delivery Boy</label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <select class="form-control" id="del_boy" name="user_id">  
                        <option value="">--Select Delivery Boy--</option>   
                        @if (isset($delivery_boy) && !empty($delivery_boy))
                            @foreach ($delivery_boy as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>    
                            @endforeach
                        @endif
                    </select> 
                </div>
                <label class="control-label col-md-1 col-sm-3 col-xs-12" for="first-name" style="padding: 0;padding-top: 7px;">Beat Name</label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <select class="form-control" id="beat" name="beat_id">  
                        <option value="">--Select Beat Name--</option>    
                        @if (isset($beat) && !empty($beat))
                            @foreach ($beat as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>    
                            @endforeach
                        @endif  
                    </select>   
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12 col-sm-12 col-xs-12" style="display:flex;justify-content:center">
                    <button type="submit" class="btn btn-info">Search</button>
                    <button type="button" class="btn btn-primary" onclick="exportData();">Export To Excel</button>
                    <a  href="{{route('admin.ended_delivery_report')}}" class="btn btn-warning">Back</a>
                </div>
            </div>
        {{ Form::close() }}
    </div>

    @if (isset($report) && !empty($report))
    <div class="row" style="margin-top: 21px;">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Delivery Report</h2>
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
                                    <th class="column-title">Delivery Boy Name</th>
                                    <th class="column-title">Vehicle Type</th>
                                    <th class="column-title">Beat Name</th>
                                    <th class="column-title">Start Time</th>
                                    <th class="column-title">End Time</th>
                                    <th class="column-title">Total KM For Day</th>
                                    <th class="column-title">Per KM Cost</th>
                                    <th class="column-title">Total Cost</th>
                                    <th class="column-title">Action</th>
                            </thead>

                            <tbody>
                                @php
                                    $report_count = 1;
                                @endphp
                                @foreach ($report as $item)
                                <tr>
                                    <td>{{$report_count++}}</td>
                                    <td>{{$item->created_at}}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('l')}}</td>
                                    <td>{{$item->boy_name}}</td>
                                    <td>{{$item->vehicle_name}}</td>
                                    <td>{{$item->beat_name}}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->start_time)->format('g:i a')}}</td>
                                    <td>{{\Carbon\Carbon::parse($item->end_time)->format('g:i a')}}</td>
                                    <td>{{$item->total_km}}</td>
                                    <td>{{$item->per_km_cost}}</td>
                                    <td>{{$item->total_cost}}</td>
                                    <td><a href="" class="btn btn-sm btn-info">View Details</a></td>
                                </tr>    
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row">
    	<div class="col-md-12 col-xs-12 col-sm-12" style="margin-top:50px;">
    	    <div class="x_panel">

    	        <div class="x_title">
    	            <h2>Delivery Report</h2>
    	            <div class="clearfix"></div>
    	        </div>
    	        <div>
    	            <div class="x_content">
                        <table id="member_list" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                          <thead>
                            <tr>
                              <th>Sl</th>
                              <th>Date</th>
                              <th>Day</th>
                              <th>Delivery Boy Name</th>
                              <th>Vehicle Type</th>
                              <th>Beat Name</th>
                              <th>Start Time</th>
                              <th>End Time</th>
                              <th>Total KM For Day</th>
                              <th>Per KM Cost</th>
                              <th>Total Cost</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>                       
                          </tbody>
                        </table>
    	            </div>
    	        </div>
    	    </div>
    	</div>
    </div>
    @endif
</div>


 @endsection

@section('script')
    @if (isset($report) && !empty($report))
    @else
        <script type="text/javascript">
            $(function () {
        
                var table = $('#member_list').DataTable({
                    processing: true,
                    serverSide: true,
                    pageLength: 50,
                    ajax: "{{route('admin.ended_delivery_report_ajax')}}",
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                        {data: 'created_at', name: 'created_at' ,searchable: true},
                        {data: 'day', name: 'day' ,searchable: true},
                        {data: 'boy_name', name: 'boy_name' ,searchable: true},
                        {data: 'vehicle_name', name: 'vehicle_name' ,searchable: true},
                        {data: 'beat_name', name: 'beat_name' ,searchable: true},
                        {data: 'start_time', name: 'start_time' ,searchable: true},
                        {data: 'end_time', name: 'end_time' ,searchable: true},
                        {data: 'total_km', name: 'total_km' ,searchable: true},
                        {data: 'per_km_cost', name: 'per_km_cost' ,searchable: true},
                        {data: 'total_cost', name: 'total_cost' ,searchable: true},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ]
                });
                
            });
        </script>
    @endif  

    <script>
        function exportData() {
            var s_date = $("#s_date").val();
            var e_date = $("#e_date").val();
            var del_boy = $("#del_boy").val();
            var beat = $("#beat").val();
            
            if (s_date) {
                
                if (e_date) {
                    $("#err_msg").html('');
                    
                    window.location.href = "{{url('admin/report/export')}}/"+s_date+"/"+e_date+"/"+del_boy+"/"+beat+"";
                    
                } else {
                    $("#err_msg").html('<p class="alert alert-danger" style="display:flex;justify-content:center">To Date Can Not Be Empty</p>');
                }
            }else{
                $("#err_msg").html('<p class="alert alert-danger" style="display:flex;justify-content:center">From  Date Can Not Be Empty</p>');
            }
        }
    </script>
 @endsection
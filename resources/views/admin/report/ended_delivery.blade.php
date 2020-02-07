@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">
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
	</div>


 @endsection

@section('script')
     
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
    
 @endsection
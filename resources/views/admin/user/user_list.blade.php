@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">
    <div class="row">
    	<div class="col-md-12 col-xs-12 col-sm-12" style="margin-top:50px;">
    	    <div class="x_panel">

    	        <div class="x_title">
    	            <h2>Delivery Boy List</h2>
    	            <div class="clearfix"></div>
    	        </div>
    	        <div>
    	            <div class="x_content">
                        <table id="member_list" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                          <thead>
                            <tr>
                              <th>Sl</th>
                              <th>Name</th>
                              <th>Mobile Number</th>
                              <th>Password</th>
                              <th>Email Id</th>
                              <th>Gender</th>
                              <th>Address</th>
                              <th>Status</th>
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
                ajax: "{{route('admin.user_list_ajax')}}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name' ,searchable: true},
                    {data: 'mobile', name: 'mobile' ,searchable: true},
                    {data: 'web_token', name: 'web_token' ,searchable: true},
                    {data: 'email', name: 'email' ,searchable: true},
                    {data: 'gender', name: 'gender', render:function(data, type, row){
                      if (row.gender == 'M') {
                        return "Male"
                      }else{
                        return "Female"
                      }                        
                    }},
                    {data: 'address', name: 'address' ,searchable: true},
                    {data: 'status_tab', name: 'status_tab' ,searchable: true},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
            
        });
     </script>
    
 @endsection
@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12" style="margin-top:50px;">
            <div class="x_panel">

                <div class="x_title">
                    <h2>New Delivery Boy Registration</h2>
                    <div class="clearfix"></div>
                </div>

                 <div>
                    @if (Session::has('message'))
                    <div class="alert alert-success">{{ Session::get('message') }}</div>
                    @endif @if (Session::has('error'))
                    <div class="alert alert-danger">{{ Session::get('error') }}</div>
                    @endif
                </div>

                <div>
                    <div class="x_content">
                        {{ Form::open(['method' => 'post','route'=>'admin.registration']) }}

                         <div class="well" style="overflow: auto">
                            <div class="form-row mb-10">

                                <div class="col-md-6 col-sm-12 col-xs-12 mb-3">
                                  <label for="name">Name</label>
                                  <input type="text" class="form-control" name="name"  placeholder="Enter Member name" >
                                    @if($errors->has('name'))
                                        <span class="invalid-feedback" role="alert" style="color:red">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6 col-sm-12 col-xs-12 mb-3">
                                  <label for="tag_name">Email</label>
                                  <input type="text" class="form-control" name="email"  placeholder="Enter Member Email" >
                                  @if($errors->has('email'))
                                        <span class="invalid-feedback" role="alert" style="color:red">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row mb-10">

                                <div class="col-md-6 col-sm-12 col-xs-12 mb-3">
                                  <label for="size_wearing">Mobile Number</label>
                                  <input type="number" class="form-control" name="mobile"  placeholder="Enter Mobile Number" >

                                  @if($errors->has('mobile'))
                                        <span class="invalid-feedback" role="alert" style="color:red">
                                            <strong>{{ $errors->first('mobile') }}</strong>
                                        </span>
                                    @enderror
                                </div> 

                                <div class="col-md-6 col-sm-12 col-xs-12 mb-3">
                                  <label for="size_wearing">Gender</label>
                                  <p style="padding-bottom: 6px; margin-top: 8px;">
                                    Male:
                                    <input type="radio" class="flat" name="gender" id="genderM" value="M" checked="" required /> FeMale:
                                    <input type="radio" class="flat" name="gender" id="genderF" value="F" />
                                  </p>
                                   @if($errors->has('gender'))
                                        <span class="invalid-feedback" role="alert" style="color:red">
                                            <strong>{{ $errors->first('gender') }}</strong>
                                        </span>
                                    @enderror
                                </div> 
                                                            
                            </div>
                        </div>


                        <div class="well" style="overflow: auto">                           

                            <div class="form-row mb-3">
                                <div class="col-md-12 col-sm-12 col-xs-12 mb-3">
                                    <label for="dob">Address</label>
                                    <textarea rows="4" class="form-control" name="address"></textarea>
                                </div>
                            </div>


                        </div>
                        <div class="form-group">
                            {{ Form::submit('Submit', array('class'=>'btn btn-success')) }}                            
                        </div>
                        {{ Form::close() }}
                       
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>

</div>


@endsection
@extends('admin.template.admin_master')

@section('content')

  <div class="right_col" role="main">
    <!-- top tiles -->
    <div class="row tile_count">
      <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-user"></i> Total Delivery Boy</span>
        <div class="count green">
          @if (isset($total_boy) && !empty($total_boy))
              {{$total_boy}}
          @else
              0
          @endif
        </div>
      </div>
      <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-clock-o"></i> Total Beats</span>
        <div class="count green">
          @if (isset($total_beats) && !empty($total_beats))
              {{$total_beats}}
          @else
              0
          @endif
        </div>
      </div>
      <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
          <span class="count_top"><i class="fa fa-user"></i> Total Outlet</span>
          <div class="count green">
            @if (isset($total_outlet) && !empty($total_outlet))
                {{$total_outlet}}
            @else
                0
            @endif
          </div>
      </div>
      <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-user"></i> Delivery Started</span>
        <div class="count green">
          @if (isset($vehicle_started) && !empty($vehicle_started))
              {{$vehicle_started}}
          @else
              0
          @endif
        </div>
      </div>
      
    </div>
    <!-- /top tiles -->

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
              <div class="x_content">
                 {{--//////////// Last Ten Sellers //////////////--}}
                 <div class="table-responsive">
                    <h2>Started Delivery</h2>
                    <table class="table table-striped jambo_table bulk_action">
                        <thead>
                            <tr class="headings">                
                                <th class="column-title">Sl No. </th>
                                <th class="column-title">Started Date</th>
                                <th class="column-title">Beat Name</th>
                                <th class="column-title">Delivery Boy</th>
                                <th class="column-title">Vehicle Type</th>
                                <th class="column-title">Address</th>
                                <th class="column-title">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                          @if (isset($started_journey) && !empty($started_journey) && (count($started_journey) > 0))
                          @php
                              $start_journey_count = 1;
                          @endphp
                            @foreach ($started_journey as $item)
                              <tr>
                                <td>{{$start_journey_count}}</td>
                                <td>{{$item->start_date}} {{$item->start_time}}</td>
                                <td>{{$item->b_beat_name}}</td>
                                <td>{{$item->boy_name}}</td>
                                <td>{{$item->vehicle_name}}</td>
                                <td>{{$item->start_address}}</td>
                                <td><a href="#" class="btn btn-sm btn-info">View Delivery Details</a></td>
                              </tr>
                            @endforeach
                          @else
                            <td colspan="7" style="text-align:center">No Delivery Started Today</td>
                          @endif
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive">
                  <h2>Todays Delivery Ended</h2>
                  <table class="table table-striped jambo_table bulk_action">
                      <thead>
                          <tr class="headings">                
                              <th class="column-title">Sl No. </th>
                              <th class="column-title">End Date</th>
                              <th class="column-title">Beat Name</th>
                              <th class="column-title">Delivery Boy</th>
                              <th class="column-title">Vehicle Type</th>                              
                              <th class="column-title">Start Address</th>
                              <th class="column-title">End Address</th>
                              <th class="column-title">Total KM</th>
                              <th class="column-title">Action</th>
                          </tr>
                      </thead>

                      <tbody>
                        @if (isset($end_journey) && !empty($end_journey) && (count($end_journey) > 0))
                        @php
                            $end_journey_count = 1;
                        @endphp
                          @foreach ($end_journey as $item)
                            <tr>
                              <td>{{$end_journey_count}}</td>
                              <td>{{$item->end_date}} {{$item->end_time}}</td>
                              <td>{{$item->b_beat_name}}</td>
                              <td>{{$item->boy_name}}</td>
                              <td>{{$item->vehicle_name}}</td>
                              <td>{{$item->start_address}}</td>
                              <td>{{$item->end_address}}</td>
                              <td>{{$item->total_km}}</td>
                              <td><a href="#" class="btn btn-sm btn-info">View Delivery Details</a></td>
                            </tr>
                          @endforeach
                        @else
                          <td colspan="7" style="text-align:center">No Delivery Started Today</td>
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
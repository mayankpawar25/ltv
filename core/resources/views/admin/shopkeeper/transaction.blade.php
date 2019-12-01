@extends('admin.layout.master')

@section('title', 'Product Update')

@section('headertxt', 'Product Update')

@section('content')
<main class="app-content">
	<!--<div class="app-title">
		<div>
			<h1><i class="fa fa-dashboard"></i>Ledger</h1>
		</div>
		<ul class="app-breadcrumb breadcrumb">
			<li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
			<li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
		</ul>
	</div>-->
	<div class="row">
    <div class="col-md-3">
      <form action="{{ route('admin.transaction.add') }}" method="post">
        {{ csrf_field() }}
        <div class="main-content">
          <div class="">
          <h5>Add Payment</h5></div>
          <hr />
          <input type="hidden" name="client_id" value="{{ $client_id }}">
          <input type="hidden" name="client_type_id" value="{{ $client_type_id }}">
          <div class="form-group">
            <label>Amount: </label>
            <input type="number" name="amount" class="form-control">
          </div>
          <div class="form-group">
            <label>Transaction Id:</label>
            <input type="text" name="transaction_id" class="form-control">
          </div>
          <div class="form-group">
            <label>Payment Mode:</label>
            <select class="form-control" name="payment_mode">
            @forelse($payment_modes as $payment_mode)
              <option value="{{ $payment_mode->id }}">{{ $payment_mode->name }}</option>
            @empty
              <option value="">-- No Payment Mode found --</option>
            @endforelse
            </select>
          </div>
          <div class="form-group">
            <label>Remarks: </label>
            <input type="textarea" name="remarks" class="form-control">
          </div>
          <div class="text-right">
          <hr />
            <button type="submit" class="btn btn-success">Save Records</button>
          </div>
          
        </div>
      </form>
    </div>
		<div class="col-md-9">
			<div class="main-content">
      	<div class=""><h5>Ledger</h5></div>
        <hr />
      	<div class="">
          	<div class="">
              	<table class="table table-bordered" id="datatableOne">
                  	<thead>
                      	<tr>
                          <th>S.No.</th>
                         <!--  <th>Detail</th> -->
                          <th>Transaction Id</th>
                          <th>Credit</th>
                          <th>Debit</th>
                          <th>Balance</th>
                          <th>Payment Mode</th>
                          <th>Remarks</th>
                      	</tr>
                  	</thead>
                  	<tbody>

                      @if($status == true)
                        @php $i=0 @endphp
                        @foreach($transactions as $transaction)
                          <tr>
                            <td>{{ ++$i }}</td>
                           <!--  <td>{{ $transaction->details }}</td> -->
                            <td>{{ $transaction->trx_id }}</td>
                            <td>{{ 
                                ($transaction->credit!='')?$gs->base_curr_symbol:'-' }}
                                {{$transaction->credit }}
                            </td>
                            <td>{{ ($transaction->debit!='')?$gs->base_curr_symbol:'-' }}{{$transaction->debit }}</td>
                            <td>{{ $gs->base_curr_symbol.' '.$transaction->after_balance }}</td>
                            <td>{{ $transaction->payment_name }}</td>
                            <td>{{ $transaction->staff_user_remark }}</td>
                          </tr>
                        @endforeach
                      @else
                        <tr>
                          <th colspan="8">{{ $msg }}</th>
                        </tr>
                      @endif
                  	</tbody>
              	</table>
                <div class="row">
                  <div class="col-sm-12 text-right">
                    <strong>Closing Balance</strong> : {{ $gs->base_curr_symbol }} {{ $closing_balance }}
                  </div>
                </div>
          	</div>
          	<div class="row">
              <div class="col-md-12">
                <div class="text-center">

                </div>
              </div>
          	</div>
      	</div>
			</div>
		</div>
	</div>
</main>
@endsection
<div class="main-content">
   <h5>
    @if(check_perm('customers_edit'))  
      <a style="font-size: 16px;" href="{{ route('edit_customer_page', $rec->id) }}" title="Edit"><i class="icon icon-pencil"></i></a>
    @endif
   @lang('form.profile') 
      
  </h5>
  <div class="row">
    <div class="col-md-8">
      <div class="table-responsive-sm">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered">
          <tr>
            <td><strong>@lang('form.customer_number')</strong></td>
            <td>{{ $rec->number }}</td>
            <td><strong>@lang('form.name')</strong></td>
            <td>{{ $rec->first_name.' '.$rec->last_name }}</td>
          </tr>
          <tr>
            <td><strong>@lang('form.phone')</strong></td>
            <td>{{ $rec->phone }}</td>
            <td><strong>@lang('form.website')</strong></td>
            <td><a href="{{ $rec->website }}">{{ $rec->website }}</a></td>
          </tr>
          <tr>
            <td><strong>@lang('form.vat_number')</strong></td>
            <td>{{ $rec->vat_number }}</td>
            <td><strong>@lang('form.default_language')</strong></td>
            <?php $language = $rec->language ;?>
            <td>{{ ($language) ? $language->name : '' }}</td>
          </tr>
          <tr>
            <td><strong>@lang('form.billing_address')</strong></td>
            <td>
              <div >
                <?php echo nl2br($rec->address)?>
                <div>{{ $rec->city }} {{ $rec->state }}</div>
                <?php $country = $rec->country; ?>
                <div>{{ $rec->zip_code }} {{  ($country) ? $country : ''}}</div>
              </div>
            </td>
            <td><strong>@lang('form.shipping_address')</strong></td>
            <td>
              <?php echo nl2br($rec->shipping_address)?>
              <div>{{ $rec->shipping_city }} {{ $rec->shipping_state }}</div>
              <?php $shipping_country = $rec->shipping_country; ?>
              <div>{{ $rec->shipping_zip_code }} {{  ($shipping_country) ? $shipping_country->name : ''}}</div>
            </td>
          </tr>
        </table>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card-header bg-primary">
        <h3 style="color:white;"><i class="fa fa-cog"></i> Update Status</h3>
      </div>
      <div class="card-body">
        <form class="" action="{{route('admin.updateUserDetails')}}" method="post">
          {{csrf_field()}}
          <input type="hidden" name="userID" value="{{$rec->id}}">
          <div class="row">
            <div class="col-md-5">
              <input class="form-control" type="hidden" name="first_name" value="{{$rec->first_name}}">
              <input class="form-control" type="hidden" name="last_name" value="{{$rec->last_name}}">
              <input class="form-control" type="hidden" name="email" value="{{$rec->email}}">
              <input class="form-control" type="hidden" name="phone" value="{{$rec->phone}}">
             <label><strong>Status</strong></label>
             <input data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
             data-width="100%" type="checkbox" data-on="ACTIVE" data-off="BLOCKED"
             name="status" {{$rec->status=='active'?'checked':''}}>
           </div>
           <div class="col-md-5">
             <label><strong>Email Verification</strong></label>
             <input data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
             data-width="100%" type="checkbox" data-on="VERIFIED" data-off="NOT VERIFIED"
             {{($rec->email_verified==1)?'checked':''}} name="emailVerification">
           </div>
           <div class="col-md-5">
             <label><strong>SMS Verification</strong></label>
             <input data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
             data-width="100%" type="checkbox" data-on="VERIFIED" data-off="NOT VERIFIED"
             {{($rec->sms_verified==1)?'checked':''}} name="smsVerification">
           </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-12">
              <button type="submit" class="btn btn-info btn-block" name="button">UPDATE</button>
            </div>
          </div>
        </form>
        <div class="row">
          <div class="col-md-12">
              <a href="{{route('admin.emailToUser', $rec->id)}}" style="color:white;" class="btn btn-sm btn-danger btn-block"><i class="fa fa-envelope"></i> SEND MAIL</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
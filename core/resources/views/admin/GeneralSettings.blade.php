@extends('admin.layout.master')

@section('content')
    <main class="app-content">
      <div class="main-content">
        <h5>General Settings</h5>
        <hr />
          <div class="">
            <form role="form" method="POST" action="{{route('admin.UpdateGenSetting')}}">
              <div class="row">
              {{csrf_field()}}
              <div class="col-md-6">
                <div class="row">
                  <div class="col-md-6">
                    <label>Website Title</label>
                    <div class="">
                      <input name="websiteTitle" type="text" class="form-control " value="{{$gs->website_title}}">
                      <!-- <div class="input-group-append"><span class="input-group-text">
                        <i class="fa fa-file-text-o"></i>
                      </span>
                    </div>-->
                  </div>
                  @if ($errors->has('websiteTitle'))
                  <span style="color:red;">{{$errors->first('websiteTitle')}}</span>
                  @endif
                  <span class="text-danger"></span>
                </div>
                <div class="col-md-6">
                  <label>Site Base Color (Without #)</label>
                  <div class="input-group">
                    <input style="background-color:#{{$gs->base_color_code}}" type="text" class="form-control " value="{{$gs->base_color_code}}" name="baseColorCode">
                    <div class="input-group-append"><span class="input-group-text">
                      <i class="fa fa-paint-brush"></i>
                    </span>
                  </div>
                </div>
                @if ($errors->has('baseColorCode'))
                <span style="color:red;">{{$errors->first('baseColorCode')}}</span>
                @endif
              </div>
              <div class="col-md-6">
                <label>Base Currency Text</label>
                <div class="">
                  <input type="text" class="form-control " value="{{$gs->base_curr_text}}" name="baseCurrencyText">
                  <!--<div class="input-group-append"><span class="input-group-text">
                    <i class="fa fa fa-money"></i>
                  </span>
                </div>-->
              </div>
              @if ($errors->has('baseCurrencyText'))
              <span style="color:red;">{{$errors->first('baseCurrencyText')}}</span>
              @endif
            </div>
            <div class="col-md-6">
              <label>Base Currency Symbol</label>
              <div class="">
                <input type="text" class="form-control " value="{{$gs->base_curr_symbol}}" name="baseCurrencySymbol">
                <!-- <div class="input-group-append"><span class="input-group-text">
                  <i class="fa fa fa-money"></i>
                </span>
              </div>-->
            </div>
            @if ($errors->has('baseCurrencySymbol'))
            <span style="color:red;">{{$errors->first('baseCurrencySymbol')}}</span>
            @endif
          </div>
          <div class="col-md-12">
            <label>Main Hub Location</label>
            <div class="">
              <input type="text" class="form-control " value="{{$gs->main_city}}" name="main_city">
              <!--<div class="input-group-append"><span class="input-group-text">
                <i class="fa fa fa-money"></i>
              </span>
            </div>-->
          </div>
          @if ($errors->has('baseCurrencySymbol'))
          <span style="color:red;">{{$errors->first('baseCurrencySymbol')}}</span>
          @endif
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-6">
          <div class="check-round">
            <label>Email Verification</label>
            <input data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
            data-width="100%" type="checkbox"
            name="emailVerification" {{$gs->email_verification == 0 ? 'checked' : ''}}>
          </div>
        </div>
        <div class="col-md-6">
          <label>SMS Verification</label>
          <input data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
          data-width="100%" type="checkbox"
          name="smsVerification" {{$gs->sms_verification == 0 ? 'checked' : ''}}>
        </div>
        <div class="col-md-6">
          <label>Email Notification</label>
          <input data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
          data-width="100%" type="checkbox"
          name="emailNotification" {{$gs->email_notification == 1 ? 'checked' : ''}}>
        </div>
        <div class="col-md-6">
          <label>SMS Notification</label>
          <input data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
          data-width="100%" type="checkbox"
          name="smsNotification" {{$gs->sms_notification == 1 ? 'checked' : ''}}>
        </div>
        <div class="col-md-6">
          <label>Registration</label>
          <input data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
          data-width="100%" type="checkbox"
          name="registration" {{$gs->registration == 1 ? 'checked' : ''}}>
        </div>
        <div class="col-md-6">
          <label>Facebook Login Status</label>
          <input data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
          data-width="100%" type="checkbox"
          name="status" {{$provider->status == 1 ? 'checked' : ''}}>
        </div>
      </div>
    </div>
    <div class="col-md-12">
      <div class="row">
        
        <div class="col">
          <h6>Facebook APP ID</h6>
          <input class="form-control " name="app_id" value="{{$provider->client_id}}" type="text">
          @if ($errors->has('app_id'))
          <p class="text-danger">{{$errors->first('app_id')}}</p>
          @endif
        </div>
        <div class="col">
          <h6>Facebook APP Secret</h6>
          <input class="form-control " name="app_secret" value="{{$provider->client_secret}}" type="text">
          @if ($errors->has('app_secret'))
          <p class="text-danger">{{$errors->first('app_secret')}}</p>
          @endif
        </div>
      </div>
    </div>
    <br>
      <hr>
      <div class="col-md-12 text-right">
        <hr />
        <button type="submit" class="btn btn-success ">UPDATE</button>
      </div>
  </div>
    </form>
    </div>
    </div>
    </main>
@endsection

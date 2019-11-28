<?php

  $s =  (is_current_user($rec->id)) ? __('form.my_account') : __('form.team_member');
?>
@section('title',  $s . " : ". $rec->first_name)

<style type="text/css">
    .info-title {
        font-weight: 600;
        
    }
    td:nth-child(1) {  
        width: 30%;
    }
</style>
      <div class="main-content" style="margin-bottom: 10px !important">
         @include('admin.crm.team_member.partials.profile_menu')
      </div>
      <div class="row">
         <div class="col-md-3 col-sm-4">
            @include('admin.crm.team_member.partials.profile_photo')
         </div>
         <div class="col-sm-8 col-md-9">
            <div class="main-content">
               <div class="row">
                  <div class="col-md-12">
                     <div style="padding: 24px;">
                        <h6 class="info-title">@lang('form.contact_information')</h6>
                                        
                        <table class="table table-sm " style="font-size: 13px;">
                           <tr>
                              <td>@lang('form.email')</td>
                              <td>{{ $rec->email }}</td>
                           </tr>
                           <tr>
                              <td>@lang('form.phone')</td>
                              <td>{{ $rec->phone }}</td>
                           </tr>
                            <tr>
                              <td>@lang('form.alternate_no')</td>
                              <td>{{ (isset($rec->alternate_no)) ? $rec->alternate_no : "" }}</td>
                           </tr>
                           <tr>
                              <td>@lang('form.skype')</td>
                              <td>{{ $rec->skype }}</td>
                           </tr>
                           <tr>
                              <td>@lang('form.user_role')</td>
                              <td>{{ (isset($rec->role->name)) ? $rec->role->name : "" }}</td>
                           </tr>
                        </table>
                        @if( auth()->user()->is_administrator || $rec->id == auth()->user()->id )
                        <h6 class="info-title">@lang('form.general_information')</h6>
                        <table class="table table-sm" style="font-size: 13px;">
                           <tr>
                              <td>@lang('form.name')</td>
                              <td>{{ $rec->first_name . " " . $rec->last_name }}</td>
                           </tr>
                           <tr>
                              <td>@lang('form.gender')</td>
                              <td>{{  (isset($rec->gender->name)) ? $rec->gender->name : ""  }}</td>
                           </tr>
                           <tr>
                              <td>@lang('form.birthday')</td>
                              <td>{{ ($rec->birth_date) ? date('F d , Y', strtotime($rec->birth_date)) : "" }}</td>
                           </tr>
                           <tr>
                              <td>@lang('form.job_title')</td>
                              <td>{{ $rec->job_title }}</td>
                           </tr>
                           <tr>
                              <td>@lang('form.joining_date')</td>
                              <td>{{ ($rec->joining_date) ? sql2date($rec->joining_date) : "" }}</td>
                           </tr>
                           <tr>
                              <td>@lang('form.user_role')</td>
                              <td>{{ (isset($rec->role)) ? $rec->role->name : '' }}</td>
                           </tr>
                           <tr>
                              <td>@lang('form.address')</td>
                              <td>{{ $rec->address }}</td>
                           </tr>
                           <tr>
                              <td>@lang('form.status')</td>
                              <td>{{ (isset($rec->inactive)) ? __('form.inactive') : __('form.active') }}</td>
                           </tr>
                           <tr>
                              <td>@lang('form.reporting_boss')</td>
                              <td><?php echo (isset($rec->boss)) ? anchor_link($rec->boss->first_name . " " . $rec->boss->last_name, route('member_profile', $rec->boss->id )) : "" ; ?></td>
                           </tr>
                           <tr>
                              <td>@lang('form.skills')</td>
                              <td>
                                 <?php 
                                    $skills = $rec->skills;
                                    if(count($skills) > 0)
                                    {
                                        foreach ($skills as $skill) 
                                        {
                                                echo '<span class="badge badge-light">'. $skill->name .'</span>';
                                        }
                                    }
                                    
                                    
                                    ?>
                              </td>
                           </tr>
                           <tr>
                              <td>@lang('form.teams')</td>
                              <td>
                                 <?php 
                                    $teams = $rec->teams;
                                    if(count($teams) > 0)
                                    {
                                        foreach ($teams as $team) 
                                        {
                                                echo $team->name . "<br>";
                                        }
                                    }
                                    
                                    
                                    ?>
                              </td>
                           </tr>
                            <tr>
                              <td>@lang('form.salary')</td>
                              <td>{{-- format_currency($rec->salary, TRUE) --}}{{ $rec->salary }}</td>
                           </tr>
                           <tr>
                              <td>@lang('form.salary_term')</td>
                              <td>{{ $rec->salary_term }}</td>
                           </tr>
                           @endif      
                        </table>
                        <!-- KYC -->
                        <h6 class="info-title">Kyc Information</h6>
                        <table class="table table-sm" style="font-size: 13px;">
                           <tr>
                              <td>@lang('form.bank_account_no')</td>
                              <td>{{  (isset($rec->bank_account_no)) ? $rec->bank_account_no : ""  }}</td>
                           </tr>
                           <tr>
                              <td>@lang('form.bank_ifsc_code')</td>
                              <td>{{  (isset($rec->bank_ifsc_code)) ? $rec->bank_ifsc_code : ""  }}</td>
                           </tr>
                           <tr>
                              <td>@lang('form.bank_address')</td>
                              <td>{{  (isset($rec->bank_address)) ? $rec->bank_address : ""  }}</td>
                           </tr>
                           <tr>
                              <td>@lang('form.adharcard_no')</td>
                              <td>{{  (isset($rec->adharcard_no)) ? $rec->adharcard_no : ""  }}</td>
                           </tr>
                           <tr>
                              <td>@lang('form.pancard_no')</td>
                              <td>{{  (isset($rec->pancard_no)) ? $rec->pancard_no : ""  }}</td>
                           </tr>
                           <tr>
                              <td>@lang('form.other_details')</td>
                              <td>{{ (isset($rec->other_details)) ? $rec->other_details : '' }}</td>
                           </tr>
                               
                        </table>
                        <!-- KYC -->
                     </div>
                  </div>
               </div>

            </div>
         </div>
      </div>
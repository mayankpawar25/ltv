<div class="row">
   <div class="col-md-8">
      <table class="table table-sm" style="font-size: 13px; ">
        
         <tr>
            <td>@lang('form.website')</td>
            <td><?php echo  ($rec->website) ? anchor_link($rec->website, $rec->website) : '' ;?></td>
         </tr>
         
         <tr>
            <td>@lang('form.address')</td>
            <td>{{ $rec->address }}</td>
         </tr>
         <tr>
            <td>@lang('form.alternate_number')</td>
            <td>{{ $rec->alternate_number }}</td>
         </tr>
         <tr>
            <td>@lang('form.city')</td>
            <td>{{ $rec->city }}</td>
         </tr>
         <tr>
            <td>@lang('form.state')</td>
            <td>{{ $rec->state }}</td>
         </tr>
         <tr>
            <td>@lang('form.zip_code')</td>
            <td>{{ $rec->zip_code }}</td>
         </tr>
         <tr>
            <td>@lang('form.country')</td>            
            <td>{{ (isset($country->name)) ? $country->name : '' }}</td>
         </tr>
          <tr>
            <td>@lang('form.employer_name')</td>            
            <td>{{ $rec->employer_name }}</td>
         </tr>
          <tr>
            <td>@lang('form.employer_phoneno')</td>            
            <td>{{ $rec->employer_contactno }}</td>
         </tr>
          <tr>
            <td>@lang('form.other_details')</td>            
            <td>{{ $rec->description }}</td>
         </tr>
      </table>
   
   </div>
  <!--  <div class="col-md-4">


     <div>@lang('form.other_details')</div>
     <div>{{ $rec->description }}</div>


   </div> -->
</div>
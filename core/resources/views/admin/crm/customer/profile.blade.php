<div class="main-content">
   <h5>
   @if(check_perm('customers_edit'))  
     <a style="font-size: 16px;" href="{{ route('edit_customer_page', $rec->id) }}" title="Edit"><i class="icon icon-pencil"></i></a>
      @endif
   @lang('form.profile') 
      
  </h5>
   <div class="table-responsive-sm">
     <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered">
  <tr>
      <td><strong>@lang('form.customer_number')</strong></td>
               <td>{{ $rec->number }}</td>
    <td><strong>@lang('form.name')</strong></td>
               <td>{{ $rec->name }}</td>
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
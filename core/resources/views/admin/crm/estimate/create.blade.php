@extends('admin.layout.master')
@section('title', (isset($rec->id)) ? __('form.edit_estimate') : __('form.create_new_estimate'))
@section('content')
<div class="app-content">
<div class="main-content" style="margin-bottom: 20px !important;">
    <h5>@lang('form.estimate')</h5>
    <hr>
    <div id="sales">
    <form method="post" action="{{ (isset($rec->id)) ? route( 'patch_estimate', $rec->id) : route('post_estimate') }}">

        {{ csrf_field()  }}
        @if(isset($rec->id))
            {{ method_field('PATCH') }}
        @endif

        @include('admin.crm.estimate.partials.general')
        @include('admin.crm.generic.items')
        @include('admin.crm.estimate.partials.notes')

        @if(isset($rec->proposal_id) && $rec->proposal_id)
            <input type="hidden" name="proposal_id" value="{{ $rec->proposal_id }}" />
        @endif
 <?php echo bottom_toolbar(); ?>
    </form>
    </div>
    </div>
    </div>
    <style>
.select2-wrapper .select2-container {width:100% !important;}
</style>
@endsection
@section('onPageJs')

    <script>
        $(function () {


            var customer_id = $( ".customer_id" );

            customer_id.select2( {
                theme: "bootstrap",
                minimumInputLength: 2,
                maximumSelectionSize: 6,
                placeholder: "{{ __('form.select_and_begin_typing') }}",
                allowClear: true,
                 "language": {
                   "noResults": function(){
                       return "<?php echo __('form.no_results_found') ?>";
                   }
               },

                ajax: {
                    url: '{{ route("search_customer") }}',
                    data: function (params) {
                        return {
                            search: params.term
                        }


                    },
                    dataType: 'json',
                    processResults: function (data) {
                        //params.page = params.page || 1;
                        // Tranforms the top-level key of the response object from 'items' to 'results'
                        return {
                            results: data.results
                            // pagination: {
                            //     more: (params.page * 10) < data.count_filtered
                            // }
                        };
                    }




                },

                templateResult: function (obj) {

                    return obj.name || "<?php echo __('form.searching'); ?>" ;
                },
                templateSelection: function (obj) {

                    if(obj && obj.email)
                    {
                        $("input[name=send_to]").val(( obj.contact_name) ? obj.contact_name : obj.name );
                        $("input[name=email]").val(obj.email);
                        $("input[name=phone]").val(obj.phone);
                        $("textarea[name=address]").val(obj.address);
                        $("input[name=city]").val(obj.city);
                        $("input[name=state]").val(obj.state);
                        $("input[name=zip_code]").val(obj.zip_code);

                        $("textarea[name=shipping_address]").val(obj.address);
                        $("input[name=shipping_city]").val(obj.city);
                        $("input[name=shipping_state]").val(obj.state);
                        $("input[name=shipping_zip_code]").val(obj.zip_code);



                        $("select[name=country_id]").select2({
                            theme: "bootstrap",
                            placeholder: function(){
                                $(this).data('placeholder');
                            },
                            maximumSelectionSize: 6
                        }).val(obj.country_id).trigger("change");


                        $("select[name=shipping_country_id]").select2({
                            theme: "bootstrap",
                            placeholder: function(){
                                $(this).data('placeholder');
                            },
                            maximumSelectionSize: 6
                        }).val(obj.shipping_country_id).trigger("change");
                        
                    }

                    return obj.name ||  obj.text
                }

            } );


            // Project
            var project_id = $( ".project_id" );
           

            project_id.select2( {
                theme: "bootstrap",
                minimumInputLength: 2,
                maximumSelectionSize: 6,
                placeholder: "{{ __('form.select_and_begin_typing') }}",
                allowClear: true,

                ajax: {
                    url: '{{ route("get_project_by_customer_id") }}',
                    data: function (params) {
                        return {
                            search: params.term,
                            customer_id : $( ".customer_id" ).val()
                        }


                    },
                    dataType: 'json',
                    processResults: function (data) {
                        //params.page = params.page || 1;
                        // Tranforms the top-level key of the response object from 'items' to 'results'
                        return {
                            results: data.results
                            // pagination: {
                            //     more: (params.page * 10) < data.count_filtered
                            // }
                        };
                    }




                },

                templateResult: function (obj) {

                    return obj.name;
                },
                templateSelection: function (obj) {

                   

                    return obj.name ||  obj.text
                }

            } );

        });
    </script>
@include('admin.crm.generic.items_js')
    <script>
        $(function () {

            $('.related_to').change(function () {

                $('.component_number')
                    .find('option')
                    .remove()
                    .val("");

                var labelText = $(this).find("option:selected").text();

                var labelElement = $("label[for*='component_number']");

                ($(this).val()) ? labelElement.html(labelText + ' <span class="required">*</span>') : labelElement.html("&nbsp");
            });


            var selectInput = $( ".component_number" );

            selectInput.select2( {
                theme: "bootstrap",
                minimumInputLength: 2,
                maximumSelectionSize: 6,
                placeholder: "{{ __('form.select_and_begin_typing') }}",
                allowClear: true,
                 "language": {
                   "noResults": function(){
                       return "<?php echo __('form.no_results_found') ?>";
                   }
               },

                ajax: {
                    url: "{{ route('related_component') }}",
                    data: function (params) {
                        return {
                            search: params.term,
                            type:  $('.related_to').val()

                        }


                    },
                    dataType: 'json',
                    processResults: function (data) {
                        //params.page = params.page || 1;
                        // Tranforms the top-level key of the response object from 'items' to 'results'
                        return {
                            results: data.results
                            // pagination: {
                            //     more: (params.page * 10) < data.count_filtered
                            // }
                        };
                    }




                },

                templateResult: function (obj) {

                    $('.component_number')
                        .find('option')
                        .remove()
                        .val("");

                    return obj.name || "<?php echo __('form.searching'); ?>" ;
                },
                templateSelection: function (obj) {
                    console.log(obj);
                    if(obj)
                    {
                        $("input[name=send_to]").val(( obj.contact_name) ? obj.contact_name : obj.name );
                        $("input[name=email]").val(obj.email);
                        $("input[name=phone]").val(obj.phone);
                        $("textarea[name=address]").val(obj.address);
                        $("input[name=city]").val(obj.city);
                        $("input[name=state]").val(obj.state);
                        $("input[name=zip_code]").val(obj.zip_code);

                        $("textarea[name=shipping_address]").val(obj.address);
                        $("input[name=shipping_city]").val(obj.city);
                        $("input[name=shipping_state]").val(obj.state);
                        $("input[name=shipping_zip_code]").val(obj.zip_code);



                        $("select[name=country_id]").select2({
                            theme: "bootstrap",
                            placeholder: function(){
                                $(this).data('placeholder');
                            },
                            maximumSelectionSize: 6
                        }).val(obj.country_id).trigger("change");


                        $("select[name=shipping_country_id]").select2({
                            theme: "bootstrap",
                            placeholder: function(){
                                $(this).data('placeholder');
                            },
                            maximumSelectionSize: 6
                        }).val(obj.country_id).trigger("change");
                        
                    }

                    return obj.name ||  obj.text
                }

            } );

        });
    </script>
@endsection
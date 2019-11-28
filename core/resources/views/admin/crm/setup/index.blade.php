@extends('admin.layout.master')

@section('title', __('form.settings'))

@section('content')


    

    @yield('setting_page')

@endsection

@section('onPageJs')

    @yield('innerPageJS')


@endsection
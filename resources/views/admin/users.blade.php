@extends('layouts.admin')


@section('css_import')
    @parent
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/js/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/js/slick/slick-theme.css"/>
@endsection

@section('js_import')
    @parent
    <script type="text/javascript" src="{{ url('/') }}/js/jquery.mousewheel.pack.js"></script>
    <script type="text/javascript" src="{{ url('/') }}/js/slick/slick.min.js"></script>

    <script type="text/javascript" src="{{ url('/') }}/js/admin/user-listing.js"></script>
@endsection

@section('body_class')
user-list
@endsection

@section('content')
    
    @component('comps.block')
        @slot('block_name')
            banner
        @endslot
    @endcomponent

    <input type="hidden" id="_url" value="{{ route('users') }}">

    <form action="{{ route('users_search') }}" id="frm_search">
        <input type="hidden" name="page" value="1" id="_page">
        @component('comps.block')
            
            @slot('block_name')
                heading
            @endslot
            @slot('block_class')
            @endslot
            
            
            <div class="col-md-12 col-sm-12 tight-box">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="row">
                            <div class="col-md-4 col-sm-3">
                                <input type="text" class="form-control fancy" placeholder="Email" name="email">
                            </div>
                            <div class="col-md-4 col-sm-3 select-wrapper">
                                <select name="t" id="_t">
                                    <option value="">All Types</option>
                                    <option value="1">Buyer</option>
                                    <option value="0">Seller</option>
                                    <option value="2">Admin</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-sm-6 select-wrapper">
                                <select name="s" id="_s">
                                    <option value="">All Status</option>
                                    <option value="1">Approved</option>
                                    <option value="4">Rejected</option>
                                    <option value="2">Requested</option>
                                    <option value="0">Drafted</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-sm-12">
                        <div class="row">
                            <div class="col-md-4 col-sm-6">
                                <input type="text" class="form-control fancy" placeholder="Keyword..." name="q">
                            </div>
                            <div class="col-md-4 col-sm-2 select-wrapper">
                                <select name="es" id="_es">
                                    <option value="">All Email State</option>
                                    <option value="1">Verified</option>
                                    <option value="0">Unverified</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-sm-3">
                                <input type="submit" value="Apply Filter" class="btn btn-white tight">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        @endcomponent
    </form>


    @component('comps.block')
        @slot('block_name')
            listings
        @endslot
        @slot('block_class')
            block-dark
        @endslot

        <div class="col-md-12 col-sm-12 listing-wrapper thumbnail-view with-data" id="listing_wrapper" >
            <div class="with-data">
                <div class="listing-inner" id="listings">
                    <table class="full fancy" cellpadding="1" cellspacing="1" border="1" bordercolor="none">
                        <thead>
                            <tr>
                                <th rowspan="2" width="60px">No</th>
                                <th colspan="5" >User Info</th>
                                <th colspan="4" >Profile</th>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Type</th>
                                <th>Created On</th>
                                <th width="50px"></th>
                                <th>Company</th>
                                <th width="40px">Status</th>
                                <th>Last Modified</th>
                                <th width="50px"></th>
                            </tr>    
                        </thead>
                        <tbody>
                              
                        </tbody>
                    </table>
                </div>
                <div class="listing-pager" id="listings_pager"></div>
            </div>
            <div class="without-data">
                There is no results found.
            </div>
        </div>

    @endcomponent



@endsection
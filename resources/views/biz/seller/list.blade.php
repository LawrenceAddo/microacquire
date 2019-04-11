@extends('layouts.page')


@section('css_import')
    @parent
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/js/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/js/slick/slick-theme.css"/>
@endsection

@section('js_import')
    @parent
    <script type="text/javascript" src="{{ url('/') }}/js/jquery.mousewheel.pack.js"></script>
    <script type="text/javascript" src="{{ url('/') }}/js/slick/slick.min.js"></script>

    <script type="text/javascript" src="{{ url('/') }}/js/pages/seller-listing.js"></script>
@endsection

@section('body_class')
seller-list
@endsection

@section('content')
    
    @component('comps.banner')
        
        @slot('title')
            Sellers
        @endslot

        Curabitur dignissim ex in pellentesque porta.
    @endcomponent

    <form action="{{ route('sellers_search') }}" id="frm_search">
        <input type="hidden" name="page" value="1" id="_page">
        @component('comps.block')
            
            @slot('block_name')
                heading
            @endslot
            @slot('block_class')
            @endslot
            
            
            <div class="col-md-12 col-sm-12 tight-box">
                <h2 class="text-left">Filters</h2>
                <div class="row">
                    <div class="col-md-3 col-sm-6">
                        <div class="clearfix"><span>Revenue</span><label class="float-right"></label></div>
                        <div id="rev_ranger"></div>
                        <input type="hidden" name="r0" value="0" id="_r0">
                        <input type="hidden" name="r1" value="10000" id="_r1">
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="clearfix"><span>Asking Price</span><label class="float-right"></label></div>
                        <div id="price_ranger"></div>
                        <input type="hidden" name="c0" value="0" id="_c0">
                        <input type="hidden" name="c1" value="10000" id="_c1">
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="row">
                            <div class="col-md-4 col-sm-3" style="padding-top: 8px;">
                                <select name="f" id="_f">
                                    <option value="0">All</option>
                                    <option value="1">Featured</option>
                                    <option value="2">Not Featured</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <input type="text" class="form-control fancy" placeholder="Keyword..." name="q">
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

        <div class="col-md-12 col-sm-12 listing-wrapper thumbnail-view with-data" style="min-height: 500px;" id="listing_wrapper" >
            <div class="with-data">
                <div class="listing-inner row" id="listings"></div>
                <div class="listing-pager" id="listings_pager"></div>
            </div>
            <div class="without-data">
                There is no results found.
            </div>
        </div>

    @endcomponent



@endsection
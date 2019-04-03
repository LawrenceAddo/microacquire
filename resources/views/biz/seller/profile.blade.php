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
@endsection

@section('body_class')
seller-profile-view
@endsection

@section('content')
    
    @component('comps.banner')
        
        @slot('title')
            Startup/Seller Profile
        @endslot

        Curabitur dignissim ex in pellentesque porta.
    @endcomponent

    @component('comps.block')
        
        @slot('block_name')
            heading
        @endslot
        @slot('block_class')
            info-block
        @endslot
        

        <div class="col-md-12 col-sm-12">
            <h2>{{ $selling->name }}</h2>
            <p class="subheading">{{ $selling->description }}</p>
        </div>

    @endcomponent


    @component('comps.block')
        @slot('container')
            container-fluid
        @endslot
        @slot('block_name')
            metrics
        @endslot
        @slot('block_class')
            block-dark info-block
        @endslot

        <div class="col-md-6 col-sm-12 metrics-col">
            <div class="metrics-inner">
                <div class="metric-item">
                    <h2>Metrics</h2>
                </div>
                <div class="metric-item">
                    <h2>${{ $selling->revenue }}</h2>
                    <p>Recurring revenue</p>
                </div>
                <div class="metric-item">
                    <h2>{{ $selling->date_founded }}</h2>
                    <p>Date founded</p>
                </div>
                <div class="metric-item">
                    <h2>{{ $selling->customers_cnt }}</h2>
                    <p>Number of customers</p>
                </div>
                <div class="metric-item">
                    <h2>${{ $selling->price }}</h2>
                    <p>Asking price for purchase</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-sm-12 slide-col">
            <div class="slide-inner">
                
                <div id="gallery_slide" class="fancy-slider">
                    @foreach ($selling->images as $img)
                        @if($img['path'])
                            <div class="slide-item">
                                <a class="fancybox" rel="group" href="{{ $img['url'] }}"><img src="{{ $img['url'] }}" alt="" /></a>
                            </div>
                        @endif
                    @endforeach
                </div>

            </div>
        </div>
    @endcomponent

    @component('comps.block')
        
        @slot('block_name')
            info
        @endslot

        @slot('block_class')
            block-focus
        @endslot

        <div class="col-md-12 col-sm-12">
            <div class="focus-item">
                <h3>Reason for selling</h3>
                <p>{{ $selling->reason }}</p>
            </div>
            <div class="focus-item">
                <h3>Growth opportunity</h3>
                <p>{{ $selling->growth }}</p>
                <p>There is a huge amount of potential for growth and the owners have not given as much attention to the sales side preferring to concentrate on adding additional features and ironing out any bugs.</p>
                <p>The suggested ways to grow the business are:</p>
                <ul>
                    <li>JV Partners and Affiliates webinars and traffic</li>
                    <li>Paid traffic (AdWords and Facebook)</li>
                    <li>Integration with supporting products</li>
                </ul>
                <p>With the number of potential customers who can see an immediate return on this product, the owners feel they have barely scratched the surface.</p>
            </div>
            <div class="focus-item">
                <h3>Highlights & Key Assets</h3>
                <p>{{ $selling->highlights }}</p>
                <ul>
                    <li>SaaS company with very little overhead.</li>
                    <li>In-demand product solving major drawback for the Shopify ecommerce platform.</li>
                    <li>Ability to offer product as monthly subscription and for a "one-time" fee on webinars as special offers.</li>
                    <li>Very little relevant competition that can do everything this software can do.</li>
                    <li>Specifically built for Shopify unlike several other competitors.</li>
                    <li>Software is in growing Shopify ecommerce space.</li>
                    <li>Very active Facebook group where members help other members with any questions that they may have. </li>
                </ul>
            </div>
            <div class="focus-item">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <h3>Financing / funding</h3>
                        <p>{{ $selling->fi_info }}</p>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <h3>Team</h3>
                        <h5>{{ $selling->team }}</h5>
                    </div>
                </div>
            </div>

        </div>
    @endcomponent

    @component('comps.block')
        
        @slot('block_name')
            pitchdeck
        @endslot

        @slot('block_class')
            block-white
        @endslot

        <div class="col-md-12 col-sm-12">
            <h2>Pitch deck</h2>
            <ul class="file-list">
                @foreach ($selling->files as $f)
                    @if($f['path'])
                        <li><a href="{{ $f['url'] }}">{{ $f['name'] }}</a></li>
                    @endif
                @endforeach
            </ul>
            <br><br>
            <h3>On-going support</h3>
            <p>{{ $selling->support }}</p>
        </div>
    @endcomponent

    @if ($show_contact)

        @component('comps.block')
        
            @slot('block_name')
                contact
            @endslot

            @slot('block_class')
                block-highlight no-triangle
            @endslot

            <div class="col-md-12 col-sm-12">
                <h2 class="with-subheading">Interested in this company?</h2>
                <p class="subheading">Contact this seller to know more about this company</p>

                <form>
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <input type="text" placeholder="Complete Name" value="" class="form-control fancy">
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <input type="text" placeholder="Email Address" value="" class="form-control fancy">
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <input type="text" placeholder="Phone Number" value="" class="form-control fancy">
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <textarea placeholder="Your Message" class="fancy" rows="3"></textarea>
                            <p class="char-counter"><span class="metre">0</span> character left</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 text-center">
                            @component('comps.checkbox')
                                @slot('checked') @endslot
                                @slot('name') iagree @endslot
                                @slot('id') iagree @endslot
                                You agree to our <a href="#">Terms of Service</a> and have read and understood the <a href="#">Privacy Policy</a>
                            @endcomponent
                        </div>
                    </div>
                    <div class="row shoes">
                        <div class="col-md-12 col-sm-12 text-center">
                            <input type="submit" value="Submit" class="btn btn-primary">
                        </div>
                    </div>
                </form>
            </div>
        @endcomponent    

    @endif



@endsection
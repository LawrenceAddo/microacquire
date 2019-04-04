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
    <script type="text/javascript" src="{{ url('/') }}/js/pages/buyer-profile-view.js"></script>
@endsection

@section('body_class')
buyer-profile-edit
@endsection
    

@section('content')
    
    @component('comps.banner')
        
        @slot('title')
            Buyers Profile
        @endslot

        Curabitur dignissim ex in pellentesque porta.
    @endcomponent

<form method="POST" action="{{ route('profile_save') }}" id="frm_buyer_save">
    <input type="hidden" name="submit_type" value="0" id="submit_type">
    @component('comps.block')
        
        @slot('block_name')
            company-info
        @endslot

        @slot('block_class')
            block-heading
        @endslot

        <div class="col-md-12 col-sm-12">
            <h2>{{ $buyer->company_name }}</h2>
            <p class="subheading">{{ $buyer->company_description }}</p>
        </div>

    @endcomponent

    @component('comps.block')
        
        @slot('block_name')
            interest
        @endslot

        @slot('block_class')
            block-dark
        @endslot

        <div class="col-md-12 col-sm-12">

            <h2>Social Media</h2>
            
            <div class="social-slide-wrapper">
                <div class="slide-inner" style="max-width: 800px; margin: auto;">
                    
                    <div id="social_slide" class="fancy-slider not-common items2-type">
                        @foreach ($buyer->socials as $social)
                            <div class="social-item" style="padding: 0 20px;">
                                <h3>{{ ucfirst($social->social_type) }}</h3>
                                <p>{{ $social->social_url }}</p>
                                <img src="{{ route('qrcode') }}?d={{ urlencode($social->social_url) }}&c=252525&b=aaaaaa&s=100" class="d-inline-block">
                            </div>  
                        @endforeach
                    </div>

                </div>
            </div>


        </div>
    @endcomponent

    @component('comps.block')
        
        @slot('block_name')
            interest
        @endslot

        @slot('block_class')
            block-focus no-triangle
        @endslot

        <div class="col-md-12 col-sm-12">

            <h3>Areas of interest</h3>
            <p>{{ $buyer->interests }}</p>

        </div>
    @endcomponent


</form>

<script language="javascript">
    var socials = @json($buyer->socials);
</script>

@endsection


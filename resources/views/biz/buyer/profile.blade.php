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

    @component('comps.block')
        
        @slot('block_name')
            company-info
        @endslot

        @slot('block_class')
            block-heading
        @endslot

        <div class="col-md-12 col-sm-12">

            @if ($is_editable && $buyer->status == 4)
                <div class="alert alert-danger without-close alert-inline">
                    <button type="button" aria-hidden="true" class="close" data-notify="dismiss" style="position: absolute; right: 10px; top: 5px; z-index: 100002;">×</button>
                    <span data-notify="icon" class="glyphicon glyphicon-info-sign"></span> 
                    <span data-notify="title"></span> 
                    <div data-notify="message">
                        <blockquote class="blockquote">
                          <p class="mb-0">Sorry! Unfortunately your profile was rejected with following reason:</p>
                          <footer class="blockquote-footer">
                              {!! nl2br($buyer->reason) !!}
                          </footer>
                        </blockquote>
                    </div>
                </div>
            @endif

            <h2>{{ $buyer->company_name }}</h2>
            <p class="subheading">{{ $buyer->company_description }}</p>

            @if ($is_editable)
                <div class="action-bar text-center" style="margin-top: -50px;">
                    <a href="{{ $edit_url ?? route('profile_edit') }}"><button class="btn btn-primary tight">Edit Profile</button></a>
                </div>
            @endif

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
                                <p class="text-ellipsis">{{ $social->social_url }}</p>
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


<script language="javascript">
    var socials = @json($buyer->socials);
</script>

@endsection


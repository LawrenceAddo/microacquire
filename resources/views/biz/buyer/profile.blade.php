@extends('layouts.page')


@section('css_import')
    @parent
    
@endsection

@section('js_import')
    @parent

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
            
            <div class="social-slide">
                <div class="slide-inner">
                    
                    <div id="social_slide" class="fancy-slider">
                        @foreach ($buyer->socials as $social)
                            @if($social->social_url)
                                {{ $social->social_type }} <br>
                                {{ $social->social_url }}
                            @endif
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


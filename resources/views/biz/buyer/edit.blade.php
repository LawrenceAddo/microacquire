@extends('layouts.page')


@section('css_import')
    @parent
    
@endsection

@section('js_import')
    @parent

    <script type="text/javascript" src="{{ url('/') }}/js/pages/buyer-profile-edit.js"></script>
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

<form method="POST" action="{{ $submit_url ?? route('profile_save') }}" id="frm_buyer_save">
    <input type="hidden" name="submit_type" value="0" id="submit_type">
    @component('comps.block')
        
        @slot('block_name')
            company-info
        @endslot

        @slot('block_class')
            block-heading
        @endslot

        <div class="col-md-12 col-sm-12">
            <h2>Buyer Information</h2>
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <input type="text" class="form-control fancy" placeholder="Company Name" name="name" value="{{ $buyer->company_name }}">
                    <textarea placeholder="Your Message" class="fancy" rows="5" name="description">{{ $buyer->company_description }}</textarea>
                    <p class="char-counter"><span class="metre">1000</span> character left</p>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="social-wrapper" style="padding-left: 50px;">
                        <ul class="social-list" id="social_list" style="padding-left: 30px; padding-bottom: 30px;">
                            <li class="row">
                                <div class="col-md-3 col-sm-3" style="padding-top: 24px;">
                                    <a href="#" class="command item-handler" title="Move"><i class="fa fa-arrows-alt"></i></a>
                                    <select name="social_names[]" class="social-types">
                                        <option></option>
                                        <option class="linkedin" value="linkedin">LinkedIn</option>
                                        <option class="facebook" value="facebook">Facebook</option>
                                        <option class="twitter" value="twitter">Twitter</option>
                                        <option class="youtube" value="youtube">Youtube</option>
                                        <option class="pinterest" value="pinterest">Pinterest</option>
                                        <option class="instagram" value="instagram">Instagram</option>
                                    </select>
                                </div>
                                <div class="col-md-9 col-sm-9">
                                    <input type="text" class="form-control fancy" name="social_urls[]" placeholder="Social Media">
                                    <a href="#" class="command item-del" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                </div>
                            </li>
                        </ul>
                        <div class="row">
                            <div class="col-md-12">
                                <a href="#" class="btn btn-white tight" id="add_new_social">Add more</a>
                            </div>
                        </div>
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
            block-dark
        @endslot

        <div class="col-md-12 col-sm-12">

            <h2>Areas of interest</h2>
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <textarea placeholder="Your Message" class="fancy" rows="5" name="interests">{{ $buyer->interests }}</textarea>
                    <p class="char-counter"><span class="metre">1000</span> character left</p>
                </div>
            </div>

        </div>
    @endcomponent

    @component('comps.block')
        
        @slot('block_name')
            finalize
        @endslot

        @slot('block_class')
            block-dark no-triangle
        @endslot

        <div class="col-md-12 col-sm-12">
            
            <div class="row">
                <div class="col-md-12 col-sm-12 text-center">
                    @component('comps.checkbox')
                        @slot('checked') @endslot
                        @slot('id') iagree @endslot

                        You agree to our <a href="#">Terms of Service</a> and have read and understood the <a href="#">Privacy Policy</a>
                    @endcomponent
                </div>
            </div>
            <div class="row shoes">
                <div class="col-md-12 col-sm-12 text-center">
                    <input type="submit" value="Save" class="btn btn-primary" id="submit_save"> - or -
                    <input type="submit" value="Save & Send for Review" class="btn btn-highlight" id="submit_review">
                </div>
            </div>

        </div>
    @endcomponent


</form>

<script language="javascript">
    var socials = @json($buyer->socials);
</script>

@endsection


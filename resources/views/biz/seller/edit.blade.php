@extends('layouts.page')


@section('css_import')
    @parent
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/js/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/js/slick/slick-theme.css"/>
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/js/dropzone/dropzone.css"/>
@endsection

@section('js_import')
    @parent

    <script type="text/javascript" src="{{ url('/') }}/js/jquery.mousewheel.pack.js"></script>
    <script type="text/javascript" src="{{ url('/') }}/js/slick/slick.min.js"></script>
    <script type="text/javascript" src="{{ url('/') }}/js/fancybox/jquery.fancybox.pack.js?v=2.1.5"></script>
    
    <script type="text/javascript" src="{{ url('/') }}/js/dropzone/dropzone.js"></script>

    <script type="text/javascript" src="{{ url('/') }}/js/pages/seller-profile-edit.js"></script>
@endsection

@section('body_class')
seller-profile-edit
@endsection
    

@section('content')
    
    @component('comps.banner')
        
        @slot('title')
            Startup/Seller Profile
        @endslot

        Curabitur dignissim ex in pellentesque porta.
    @endcomponent

<form method="POST" action="{{ route('profile_save') }}" id="frm_seller_save">
    <input type="hidden" name="submit_type" value="0" id="submit_type">
    @component('comps.block')
        
        @slot('block_name')
            company-info
        @endslot

        @slot('block_class')
            block-heading
        @endslot

        <div class="col-md-12 col-sm-12">
            <h2>Company Information</h2>
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <input type="text" class="form-control fancy" placeholder="Company Name" name="name" value="{{ $selling->name }}">
                    <textarea placeholder="Your Message" class="fancy" rows="5" name="description">{{ $selling->description }}</textarea>
                    <p class="char-counter"><span class="metre">1000</span> character left</p>
                </div>
                <div class="col-md-6 col-sm-12">
                    <h5>Pictures</h5>
                    <div class="profile-pictures-uploader">
                        <div class="text-center">

                            @component('comps.dropzone')
                                @slot('id')
                                    dz_seller_pictures
                                @endslot
                                @slot('label')
                                    Drag and Drop / Add Image
                                @endslot
                                <p>Format permited: .jpg, .png, .webb, .tiff<br>Max 2mb per image</p>
                            @endcomponent


                            <div id="thumb-area">
                                <ul class="thumb-list" id="dz_thumb_list">
                                    @for ($i = 0; $i < 5; $i++)
                                        <li class="item">
                                            <a href="#" class="command item-del"><i class="fa fa-trash" aria-hidden="true" title="Delete"></i></a>
                                            <a href="#" class="command item-zoomin"><i class="fa fa-search-plus" title="Zoom in"></i></a>
                                            <input type="hidden" name="profilePic[]" value="" class="value-file-path">
                                            <div class="frame">
                                                <div class="img-showing"></div>
                                                <label></label>
                                            </div>
                                        </li>
                                    @endfor
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endcomponent


    @component('comps.block')
        
        @slot('block_name')
            metrics
        @endslot

        @slot('block_class')
            block-dark
        @endslot

        <div class="col-md-12 col-sm-12">

            <h2>Metrics</h2>
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <textarea placeholder="Your Message" class="fancy" rows="3" name="metrics">{{ $selling->metrics }}</textarea>
                    <p class="char-counter"><span class="metre">1000</span> character left</p>
                </div>
                <div class="col-md-3 col-sm-12">
                    <input type="number" class="form-control fancy" placeholder="Recurring revenue" name="revenue" value="{{ $selling->revenue }}">
                </div>
                <div class="col-md-3 col-sm-12">
                    <div class="input-group datepicker-wrapper">
                        <input type="text" class="form-control fancy datepicker" placeholder="Date founded" name="date_founded" value="{{ $selling->date_founded }}"  autocomplete="off">
                        <i class="fa fa-angle-down ticker"></i>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12">
                    <input type="number" class="form-control fancy" placeholder="Number of customers" name="customers_cnt" value="{{ $selling->customers_cnt }}">
                </div>
                <div class="col-md-3 col-sm-12">
                    <input type="number" class="form-control fancy" placeholder="Asking price for purchase" name="price" value="{{ $selling->price }}">
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

            <h2>About the Company</h2>
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <textarea placeholder="Reason for selling" class="fancy" rows="3" name="reason">{{ $selling->reason }}</textarea>
                    <p class="char-counter"><span class="metre">1000</span> character left</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <textarea placeholder="Growth opportunity" class="fancy" rows="3" name="growth">{{ $selling->growth }}</textarea>
                    <p class="char-counter"><span class="metre">1000</span> character left</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <textarea placeholder="Highlights & Key Assets" class="fancy" rows="3" name="highlights">{{ $selling->highlights }}</textarea>
                    <p class="char-counter"><span class="metre">1000</span> character left</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <textarea placeholder="Financing / funding" class="fancy" rows="3" name="fi_info">{{ $selling->fi_info }}</textarea>
                    <p class="char-counter"><span class="metre">1000</span> character left</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <textarea placeholder="Team" class="fancy" rows="3" name="team">{{ $selling->team }}</textarea>
                    <p class="char-counter"><span class="metre">1000</span> character left</p>
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
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    @component('comps.dropzone')
                        @slot('id')
                            dz_seller_pdf
                        @endslot
                        @slot('label')
                            Drag and Drop / Add File
                        @endslot
                        <p>Format permited: .pdf<br>Max 5mb per file</p>
                    @endcomponent
                </div>
                <div class="col-md-6 col-sm-12">
                    <ul class="file-list" id="dz_pdf_list">
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <textarea placeholder="On-going support" class="fancy" rows="3" name="support">{{ $selling->support }}</textarea>
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
            block-white no-triangle
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
    var imgs = @json($selling->images);
    var pdfs = @json($selling->files);
</script>

@endsection


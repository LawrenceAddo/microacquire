@component('comps.block')
    @slot('block_name')
        banner
    @endslot
    @slot('block_class')
        {{ $banner_class ?? '' }}
    @endslot
    <div class="col-md-12 col-sm-12">
        <h2 class="with-subheading">{{ $title}}</h2>
        <p class="subheading">{{ $slot }}</p>
    </div>
@endcomponent
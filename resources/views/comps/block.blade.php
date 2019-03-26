<div class="block block-{{ $block_name }} {{ $block_class ?? '' }}">
    <div class="block-inner">
        <div class="{{ $container ?? 'container' }}">
            <div class="row">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
<div class="checkbox">
  <label>
   <input type="checkbox" value="" @isset($checked) {{ $checked }} @endisset class="form-control {{ $class ?? '' }}" @isset($name) name="{{ $name }}" @endisset @isset($id) id="{{ $id }}" @endisset>
   <span class="cr"><i class="cr-icon cr-icon-checked fa fa-check"></i></span>
   {{ $slot }}
   </label>
</div>
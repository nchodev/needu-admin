<ul class="nav panel-tabs panel-info">
    <li ><a class=" {{Request()->is('business-setting')?'active':''}}"  href="{{route('business-setting')}}"><span><i class="fe fe-settings me-2 {{Request()->is('business-setting')?'':'text-success'}}"></i></span>Business setting</a></li>
    <li><a class=" {{Request()->is('language/language-setting')?'active':''}}"  href="{{route('language.language-setting')}}" ><span><i class="fe fe-flag me-2 {{Request()->is('language/language-setting')?'':'text-info'}}"></i></span>Languages</a></li>
</ul>


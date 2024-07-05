@extends('components.layouts.app')
@section('title', "{{translate('messages.add_customers')}}")

@section('styles')
<style>
    #map {
  height: 30%;
}
</style>
@endsection
@section('content')

   <!-- PAGE HEADER -->
   <div class="page-header d-sm-flex d-block">
        <ol class="breadcrumb mb-sm-0 mb-3">
            <!-- breadcrumb -->
            <li class="breadcrumb-item"><a href="javascript:void(0);">{{translate('messages.custommers')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{translate('messages.add_new_customer')}}</li>
        </ol><!-- End breadcrumb -->
   </div>
<!-- END PAGE HEADER -->


<div class="card-body">
    <div class="row">
           <div class="col-md-4">
                <div class="form-group">
                    <label class="input-label" for="exampleFormControlInput1">{{translate('messages.full_name')}}</label>
                    <input type="text" name="fullname" value="{{old('fullname')}}" class="form-control" placeholder="{{translate('messages.write_fullname')}}" required>
                </div>
           </div>
           <div class="col-md-4">
                 <div class="form-group">
                    <label class="input-label" for="exampleFormControlInput1">{{translate('messages.nickname')}}</label>
                    <input type="text" name="nickname" value="{{old('nickname')}}" class="form-control" placeholder="{{translate('messages.write_nickname')}}" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                   <label class="input-label" for="exampleFormControlInput1">{{translate('messages.email')}}</label>
                   <input type="email" name="email" value="{{old('email')}}" class="form-control" placeholder="{{translate('messages.write_email')}}" required>
               </div>
           </div>
           <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label">Looking_for</label>
                    <select class="form-control select2-show-search form-select" name="looking_for" required
                        data-placeholder="Choose one">
                        <option label="Choose one"></option>
                        @foreach ($lookings as $cat )
                        <option value="{{$cat['id']}}">{{$cat['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label">sex_orientation</label>
                    <select class="form-control select2-show-search form-select" name="sex"  required
                        data-placeholder="Choose one">
                        <option label="Choose one"></option>
                        @foreach ($sexes as $cat )
                        <option value="{{$cat['id']}}">{{$cat['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                   <label class="input-label" for="exampleFormControlInput1">{{translate('messages.job')}}</label>
                   <input type="text" name="job" value="{{old('job')}}" class="form-control" placeholder="{{translate('messages.write_job')}}" >
               </div>
           </div>
           <div class="col-md-4">
                <div class="form-group">
                    <label class="input-label" for="exampleFormControlInput1">{{translate('messages.phone')}}</label>
                    <input type="text" name="phone" value="{{old('phone')}}" class="form-control" placeholder="{{translate('messages.write_job')}}" >
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group ">
                    <label class="form-label">Interests</label>
                    <div class="selectgroup selectgroup-pills">
                        @foreach ($interests as $cat )
                            <label class="selectgroup-item" id="{{$cat['id']}}" >
                                <input type="checkbox" name="value" value="{{$cat['id']}}"
                                    class="selectgroup-input" >
                                <span class="selectgroup-button">{{$cat['name']}}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
            @php
            $currentYear = \Carbon\Carbon::now()->year;
            $startYear = $currentYear - 18;
            $months = [];
            for ($m = 1; $m <= 12; $m++) {
                $months[$m] = \Carbon\Carbon::create()->month($m)->translatedFormat('F');
            }
        @endphp
            <div class="col-md-4">
                <div class="form-group m-0">
                    <label class="form-label">Date of birth {{$currentYear}}</label>
                    <div class="row gutters-xs">
                        <div class="col-5">
                            <select name="bod[month]" class="form-control form-select select2">
                                <option value="">Month</option>
                                @foreach ($months as $key => $month )
                                    <option value="{{$key}}">{{$month}}</option>
                                @endforeach

                            </select>
                        </div>
                        <div class="col-3">
                            <select name="bod[day]" class="form-control form-select select2">
                                <option value="">Day</option>
                                @for ($i = 1; $i <= 31; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-4">
                            <select name="bod[year]" class="form-control form-select select2">
                                <option value="">Year</option>
                                @for ($year = $startYear; $year >= $startYear-50; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="input-label" for="exampleFormControlInput1">{{translate('messages.address')}}</label>
                    <input type="address" name="address" value="{{old('address')}}" class="form-control" placeholder="" >
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="input-label" for="exampleFormControlInput1">{{translate('messages.longitude')}}</label>
                    <input type="text" name="long" value="{{old('long')}}" class="form-control" placeholder="" >
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="input-label" for="exampleFormControlInput1">{{translate('messages.latitude')}}</label>
                    <input type="text" name="lat" value="{{old('lat')}}" class="form-control" placeholder="" >
                </div>
            </div>
            <div class="col-12">
                <div class="mt-md-4">
                    <input id="pac-input" class="controls rounded" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('messages.search_your_location_here') }}" type="text" placeholder="{{ translate('messages.search_here') }}" />
                    <div id="map" class="overflow-hidden rounded height-285px"></div>
                </div>
            </div>

    </div>
</div>

@endsection

@section('scripts')
{{-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAlQRBcitnqqrcAUSI0oSWdX6ApkuREGBM&libraries=places&v=3.57"></script> --}}
<script async
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAlQRBcitnqqrcAUSI0oSWdX6ApkuREGBM&loading=async&callback=initMap">
</script>

<script>
let map;

async function initMap() {
  const { Map } = await google.maps.importLibrary("maps");

  map = new Map(document.getElementById("map"), {
    center: { lat: -34.397, lng: 150.644 },
    zoom: 8,
  });
}

initMap();
</script>
{{-- <script>
    function initAutocomplete() {
        var myLatLng = {
            lat: -33.8688,
            lng: 151.2195
        };
        const map = new google.maps.Map(document.getElementById("location_map_canvas"), {
            center: {
                lat: -33.8688,
                lng: 151.2195
            },
            zoom: 13,
            mapTypeId: "roadmap",
        });

        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
        });

        marker.setMap(map);
        var geocoder = geocoder = new google.maps.Geocoder();
        google.maps.event.addListener(map, 'click', function(mapsMouseEvent) {
            var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
            var coordinates = JSON.parse(coordinates);
            var latlng = new google.maps.LatLng(coordinates['lat'], coordinates['lng']);
            marker.setPosition(latlng);
            map.panTo(latlng);

            document.getElementById('latitude').value = coordinates['lat'];
            document.getElementById('longitude').value = coordinates['lng'];


            geocoder.geocode({
                'latLng': latlng
            }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[1]) {
                        document.getElementById('address').innerHtml = results[1].formatted_address;
                    }
                }
            });
        });
        // Create the search box and link it to the UI element.
        const input = document.getElementById("pac-input");
        const searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
        // Bias the SearchBox results towards current map's viewport.
        map.addListener("bounds_changed", () => {
            searchBox.setBounds(map.getBounds());
        });
        let markers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener("places_changed", () => {
            const places = searchBox.getPlaces();

            if (places.length == 0) {
                return;
            }
            // Clear out the old markers.
            markers.forEach((marker) => {
                marker.setMap(null);
            });
            markers = [];
            // For each place, get the icon, name and location.
            const bounds = new google.maps.LatLngBounds();
            places.forEach((place) => {
                if (!place.geometry || !place.geometry.location) {
                    console.log("Returned place contains no geometry");
                    return;
                }
                var mrkr = new google.maps.Marker({
                    map,
                    title: place.name,
                    position: place.geometry.location,
                });
                google.maps.event.addListener(mrkr, "click", function(event) {
                    document.getElementById('latitude').value = this.position.lat();
                    document.getElementById('longitude').value = this.position.lng();
                });

                markers.push(mrkr);

                if (place.geometry.viewport) {
                    // Only geocodes have viewport.
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
            });
            map.fitBounds(bounds);
        });
    };
    $(document).on('ready', function() {
        initAutocomplete();
        @php($country = \App\Models\BusinessSetting::where('key', 'country')->first())
    });

    $(document).on("keydown", "input", function(e) {
        if (e.which == 13) e.preventDefault();
    });
</script> --}}

@endsection

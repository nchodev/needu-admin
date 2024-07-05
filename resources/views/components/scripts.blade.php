
        <!-- BACK TO TOP -->
        <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>
  <!-- SELECT2 JS -->
        <script src="{{asset('build/assets/plugins/select2/select2.full.min.js')}}"></script>
        <!-- JQUERY MIN JS -->
        <script src="{{asset('build/assets/plugins/jquery/jquery.min.js')}}"></script>

        <!-- BOOTSTRAP5 BUNDLE JS -->
        <script src="{{asset('build/assets/plugins/bootstrap/popper.min.js')}}"></script>
        <script src="{{asset('build/assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>

        <!-- PERFECT-SCROLLBAR JS  -->
        <script src="{{asset('build/assets/plugins/p-scroll/perfect-scrollbar.js')}}"></script>
        <script src="{{asset('build/assets/plugins/p-scroll/pscroll.js')}}"></script>

        <!-- CIRCLE PROGRESS JS -->
        <script src="{{asset('build/assets/plugins/circle-progress/circle-progress.min.js')}}"></script>

        <!-- MOMENT JS -->
        <script src="{{asset('build/assets/plugins/moment/moment.min.js')}}"></script>

        <!-- NEWS TICKER JS -->
        <script src="{{asset('build/assets/plugins/newsticker/breaking-news-ticker.min.js')}}"></script>
        <script src="{{asset('build/assets/plugins/newsticker/newsticker.js')}}"></script>

        <!-- SIDEMENU JS -->
        <script src="{{asset('build/assets/plugins/sidemenu/sidemenu.js')}}"></script>

        <!-- RIGHT SIDEBAR JS -->
        <script src="{{asset('build/assets/plugins/sidebar/sidebar.js')}}"></script>

        <!-- RIGHT TOASTR JS -->

        <script src="{{asset('build/assets/plugins/toastr/toastr.min.js')}}"></script>
            <!-- SWEET-ALERT JS  -->
            <script src="{{asset('build/assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>
            <script src="{{asset('build/assets/plugins/sweet-alert/jquery.sweet-alert.js')}}"></script>
            @vite('resources/assets/js/sweet-alert.js')


        <!-- FORM ELEMENT ADVANCED JS -->
        @vite('resources/assets/js/formelementadvnced.js')

    

        @if ($errors->any())
        <script>
            @foreach($errors->all() as $error)
            toastr.error('$error', Error, {
                CloseButton: true,
                ProgressBar: true
            });
            @endforeach
        </script>
        <script>
     $('.form-alert').on('click',function (){
        let id = $(this).data('id')
        let message = $(this).data('message')
        Swal.fire({
            title: '{{ translate('messages.Are you sure?') }}',
            text: message,
            type: 'warning',
            showCancelButton: true,
            cancelButtonColor: 'default',
            confirmButtonColor: '#FC6A57',
            cancelButtonText: '{{ translate('messages.no') }}',
            confirmButtonText: '{{ translate('messages.Yes') }}',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                $('#'+id).submit()
            }
        })
    });

        </script>
    @endif

    <script>
        $('.form-alert').click(function () {
		$('body').removeClass('timer-alert');
        let id = $(this).data('id')
        let message = $(this).data('message')
		swal({
            title: '{{ translate('messages.Are you sure?') }}',
            text: message,
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "{{translate('messages.Yes, delete it!')}}",
            cancelButtonText: "{{translate('messages.cancel !')}}",
            closeOnConfirm: true,
            closeOnCancel: false
		},
		function(isConfirm) {
		  if (isConfirm) {
            $('#'+id).submit();
		  } else {
			swal("{{ translate('messages.Cancelled') }}", "{{translate('messages.Your data is safe')}}:)", "error");
		  }
		});
	});
    </script>

@yield('scripts')

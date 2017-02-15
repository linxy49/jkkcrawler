{{-- resouces/views/layouts/footer.blade.php --}}
        <footer>
            <!--[if lt IE 9]>
            <script src="js/html5shiv.js" type="text/javascript"></script>
            <script src="js/respond.min.js" type="text/javascript"></script>
            <![endif]-->
            <script type='text/javascript' src='{{ asset('js/jquery.min.js') }}'></script>
		    <script type='text/javascript' src='{{ asset('js/jquery.xdomainajax.js') }}'></script>
            <script type='text/javascript' src='{{ asset('js/bootstrap.min.js') }}'></script>
            @yield('page_js')
        </footer><!-- /#footer -->
    </body>
</html>

<!DOCTYPE html>
<!--[if IE 9]> <html class="ie9 no-js" lang="en"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html class="no-js" lang="en">
<!--<![endif]-->

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Proaddiction</title>
        <!-- <link rel="stylesheet" href="http://localhost:3000/css/bootstrap4/dist/css/bootstrap-custom.css?v=datetime"> -->
        <link rel="stylesheet" href="{{asset('css/polished.min.css')}}">
        <!-- <link rel="stylesheet" href="polaris-navbar.css"> -->
        <link rel="stylesheet" href="{{asset('css/open-iconic-bootstrap.min.css')}}">

        <link rel="icon" href="{{asset('css/polished-logo-small.png')}}">

        <!--  Essential META Tags -->

        <meta property="og:title" content="Polished - Bootstrap 4 Admin Template">
        <meta property="og:description" content="100% Free & Open Source Bootstrap 4 Admin Template">
        <meta property="og:image" content="https://azamuddin.github.io/polished-template/assets/polished-banner-transparent.png">
        <meta property="og:url" content="https://azamuddin.github.io/polished-template">
        <meta name="twitter:card" content="summary_large_image">

        <!--  Non-Essential, But Recommended -->

        <meta property="og:site_name" content="Polished - Bootstrap 4 Admin Template">
        <meta name="twitter:image:alt" content="Polished Admin Template">


        <!--  Non-Essential, But Required for Analytics -->
        <meta name="twitter:site" content="@azamuddin91">

        <style>
            .grid-highlight {
                padding-top: 1rem;
                padding-bottom: 1rem;
                background-color: #5c6ac4;
                border: 1px solid #202e78;
                color: #fff;
            }

            hr {
                margin: 6rem 0;
            }

            hr+.display-3,
            hr+.display-2+.display-3 {
                margin-bottom: 2rem;
            }
        </style>
        <script type="text/javascript">
            document.documentElement.className = document.documentElement.className.replace('no-js', 'js') + (document.implementation.hasFeature("http://www.w3.org/TR/SVG11/feature#BasicStructure", "1.1") ? ' svg' : ' no-svg');
        </script>
        <!-- Facebook Pixel Code -->
        <script>
            !function (f, b, e, v, n, t, s) {
                if (f.fbq) return; n = f.fbq = function () {
                    n.callMethod ?
                        n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                };
                if (!f._fbq) f._fbq = n; n.push = n; n.loaded = !0; n.version = '2.0';
                n.queue = []; t = b.createElement(e); t.async = !0;
                t.src = v; s = b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t, s)
            }(window, document, 'script',
                'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '564839313686027');
            fbq('track', 'PageView');
        </script>
        <noscript>
            <img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=564839313686027&ev=PageView&noscript=1"
            />
        </noscript>
        <!-- End Facebook Pixel Code -->
    </head>

    <body>
    @include('sidebar')
    @yield('content')


        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
                crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ"
                crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm"
            crossorigin="anonymous"></script>
    </body>
</html>

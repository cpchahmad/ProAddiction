<!DOCTYPE html>
<!--[if IE 9]> <html class="ie9 no-js" lang="en"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html class="no-js" lang="en">
<!--<![endif]-->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ProAddiction</title>
    <!-- <link rel="stylesheet" href="http://localhost:3000/css/bootstrap4/dist/css/bootstrap-custom.css?v=datetime"> -->
    <link rel="stylesheet" href="{{asset('css/polished.min.css')}}">
    <!-- <link rel="stylesheet" href="polaris-navbar.css"> -->
    <link rel="stylesheet" href="{{asset('css/open-iconic-bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('font/open-iconic.woff')}}">
    <link rel="icon" href="{{asset('css/polished-logo-small.png')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" type="text/css" href="{{asset('/')}}assets/toaster.min.css">
    <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js">
    </script>
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
        body{
            background-color: white;
        }
        .tab_btn{
            display: inline-block;
            padding: 10px 20px;
            border-radius: 7px;
            background: #000;
            border: 1px solid #000;
            color: #fff;
            font-size: 14px;
        }
    </style>
    <script type="text/javascript">
        document.documentElement.className = document.documentElement.className.replace('no-js', 'js') + (document.implementation.hasFeature("http://www.w3.org/TR/SVG11/feature#BasicStructure", "1.1") ? ' svg' : ' no-svg');
    </script>
    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '564839313686027');
        fbq('track', 'PageView');
    </script>

    <!-- End Facebook Pixel Code -->

</head>

<body>


<div class="container-fluid h-100 p-0">
    <div style="min-height: 100%" class="flex-row d-flex align-items-stretch m-0">

        <div class="col-lg-12 col-md-12 p-4">
            <form action="{{route('professionals.form.submit')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="#">Full Name</label>
                    <input placeholder="Enter your full name" type="text" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="#">Email Address</label>
                    <input placeholder="Enter email address" type="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="#">Password</label>
                    <input placeholder="Enter password" type="password" name="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="#">Phone Number</label>
                    <input placeholder="Enter Phone number" type="text" name="phone" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="#">Salon address</label>
                    <input placeholder="Enter Salon address" type="text" name="address" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="#">Upload file - Stylist License</label>
                    <input type="file" name="file" class="form-control" required>
                </div>


                <br>
                <div class="form-group">
                    <input type="submit" class="btn tab_btn" value="Submit">
                </div>
            </form>
        </div>
    </div>
</div>

<script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" integrity="sha256-R4pqcOYV8lt7snxMQO/HSbVCFRPMdrhAFMH+vr9giYI=" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="{{asset('/')}}assets/toaster.min.js"></script>
@if(Session::has('success'))
    toastr.success("{{ Session::get('success') }}");
@endif

@if(Session::has('info'))
    toastr.info("{{ Session::get('info') }}");
@endif

@if(Session::has('warning'))
    toastr.warning("{{ Session::get('warning') }}");
@endif

@if(Session::has('error'))
    toastr.error("{{ Session::get('error') }}");
    @endif
        </script>

    @yield('scripts')


    </body>

    </html>

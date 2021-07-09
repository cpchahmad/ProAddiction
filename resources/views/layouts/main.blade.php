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

@include('layouts.header')

<div class="container-fluid h-100 p-0">
    <div style="min-height: 100%" class="flex-row d-flex align-items-stretch m-0">
        <div class="polished-sidebar bg-light col-12 col-md-3 col-lg-2 p-0 collapse d-md-inline" id="sidebar-nav">
            @include('layouts.sidebar')
        </div>
        <div class="col-lg-10 col-md-9 p-4">
        @yield('content')
        </div>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script>
<script>
    function showStates(countryName)
    {
        console.log(countryName);
        $.ajax({
            type: 'post',
            data: {'countryName': countryName},
            url: '{{route('show_states')}}',
            dataType: 'json',
            success: function(data) {
                console.log('success');
                console.log(data);
                if(data.success == true) {
                    $('#show_state').html(data.html);
                }
            }
        });
    }
    function showcities(cityName)
    {
        console.log(cityName);
        $.ajax({
            type: 'post',
            data: {'cityName': cityName},
            url: '{{route('show_cities')}}',
            dataType: 'json',
            success: function(data) {
                console.log('success');
                console.log(data);
                if(data.success == true) {
                    $('#show_cities').html(data.html);
                }
            }
        });
    }
</script>
<script>


    let ctxArea = document.getElementById('sales')

    var dataArea = {
        labels: ["Jan", "Feb", "March", "April", "May", "June"],
        datasets: [{
            label: 'Sales',
            data: [20,10,40,50, 75,80],
            backgroundColor: '#6CCC64'
        }, {
            label: 'Add to Cart',
            data: [40,30,54,60,60,99],
            backgroundColor: '#FDD638'
        }]
    }

    var myAreaChart = new Chart(ctxArea, {
        type: 'line',
        data: dataArea
    })

    var ctxDoughnut = document.getElementById('top-sales-by-category')
    var myDoughnutChart = new Chart(ctxDoughnut, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [10,20,30,32,54],
                backgroundColor: ['indigo', 'blue', 'green', 'tan', 'lightgreen']
            }],
            labels: [ 'Footwear', 'Menswear', 'Bags', 'Sports', 'Gaming']
        }
    })
@yield('scripts')

</script>

</body>

</html>

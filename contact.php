<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Pharmacy Carousel & Contact</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #c21111ff 0%, #dfadadff 100%);
            color: #fff;
        }

        .container {
            position: relative;
            z-index: 1;
        }

        .content {
            position: relative;
            margin-bottom: 100px;
        }

        /* Modern Carousel Styling */
        #carouselExampleControls {
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(220, 38, 38, 0.3);
            position: relative;
            background: transparent !important;
        }

        .carousel-inner {
            height: 35em !important;
            border-radius: 24px;
            position: relative;
            overflow: hidden;
        }

        .carousel-item {
            transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            position: relative;
        }

        .carousel-item > img {
            object-fit: cover !important;
            filter: brightness(0.8) contrast(1.1);
            transition: all 0.5s ease;
        }

        .carousel-item.active > img {
            filter: brightness(0.9) contrast(1.2);
            transform: scale(1.02);
        }

        /* Overlay gradient for better text readability */
        .carousel-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(
                45deg,
                rgba(220, 38, 38, 0.2) 0%,
                rgba(0, 0, 0, 0.3) 50%,
                rgba(220, 38, 38, 0.1) 100%
            );
            z-index: 1;
            transition: opacity 0.5s ease;
        }

        .carousel-item:hover::before {
            opacity: 0.8;
        }

        /* Modern Control Buttons */
        .carousel-control-prev,
        .carousel-control-next {
            width: 60px;
            height: 60px;
            background: rgba(220, 38, 38, 0.9);
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0.8;
            transition: all 0.3s ease;
            border: 2px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }

        .carousel-control-prev {
            left: 30px;
        }

        .carousel-control-next {
            right: 30px;
        }

        .carousel-control-prev:hover,
        .carousel-control-next:hover {
            opacity: 1;
            transform: translateY(-50%) scale(1.1);
            background: rgba(220, 38, 38, 1);
            box-shadow: 0 10px 30px rgba(220, 38, 38, 0.4);
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            width: 24px;
            height: 24px;
            filter: brightness(0) invert(1);
        }

        /* Carousel Indicators (if you want to add them later) */
        .carousel-indicators {
            bottom: 20px;
        }

        .carousel-indicators [data-bs-target] {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(220, 38, 38, 0.6);
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }

        .carousel-indicators .active {
            background: #dc2626;
            transform: scale(1.2);
        }

        /* Contact Card Styling */
        .row.mt-lg-n4 {
            margin-top: -4rem !important;
            position: relative;
            z-index: 10;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px !important;
            border: none;
            box-shadow: 0 25px 50px rgba(220, 38, 38, 0.15);
            position: relative;
            overflow: hidden;
            transform: translateY(20px);
            opacity: 0;
            animation: slideUpCard 1s ease-out 0.5s forwards;
        }

        @keyframes slideUpCard {
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #dc2626, #ef4444, #f87171);
            background-size: 200% 100%;
            animation: gradientMove 3s ease-in-out infinite;
        }

        @keyframes gradientMove {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 35px 70px rgba(220, 38, 38, 0.2);
            transition: all 0.3s ease;
        }

        .card-body {
            padding: 3rem;
            position: relative;
        }

        .card-body h3 {
            color: #dc2626;
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            position: relative;
            animation: fadeInDown 1s ease-out 0.8s both;
        }

        @keyframes fadeInDown {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .bg-danger {
            background: linear-gradient(90deg, #dc2626, #ef4444) !important;
            height: 3px !important;
            border-radius: 2px;
            animation: expandHr 1s ease-out 1.2s both;
        }

        @keyframes expandHr {
            from { width: 0; }
            to { width: 5em; }
        }

        /* Contact Information Styling */
        dl {
            margin: 2rem 0;
            animation: fadeInUp 1s ease-out 1s both;
        }

        @keyframes fadeInUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        dt {
            color: #dc2626 !important;
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            position: relative;
            padding-left: 2rem;
        }

        dt::before {
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            background: linear-gradient(135deg, #dc2626, #ef4444);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: white;
            font-weight: bold;
        }

        dt:nth-of-type(1)::before {
            content: '✉';
        }

        dt:nth-of-type(2)::before {
            content: '☎';
        }

        dt:nth-of-type(3)::before {
            content: '📱';
        }

        dt:nth-of-type(4)::before {
            content: '📍';
        }

        dd {
            color: #374151 !important;
            font-size: 1rem;
            margin-bottom: 1.5rem;
            padding-left: 2rem;
            line-height: 1.6;
            position: relative;
        }

        dd:hover {
            color: #dc2626 !important;
            transform: translateX(5px);
            transition: all 0.3s ease;
        }

        /* Floating Elements */
        .floating-elements {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
        }

        .float-element {
            position: absolute;
            color: rgba(220, 38, 38, 0.1);
            font-size: 20px;
            animation: floatUp 12s linear infinite;
        }

        .float-element:nth-child(2n) {
            animation-duration: 15s;
            color: rgba(239, 68, 68, 0.1);
        }

        .float-element:nth-child(3n) {
            animation-duration: 18s;
            color: rgba(248, 113, 113, 0.1);
        }

        @keyframes floatUp {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100px) rotate(360deg);
                opacity: 0;
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .carousel-inner {
                height: 25em !important;
            }

            .card-body {
                padding: 2rem 1.5rem;
            }

            .card-body h3 {
                font-size: 1.8rem;
            }

            .carousel-control-prev,
            .carousel-control-next {
                width: 50px;
                height: 50px;
            }

            .carousel-control-prev {
                left: 15px;
            }

            .carousel-control-next {
                right: 15px;
            }

            dt, dd {
                padding-left: 1.5rem;
            }

            dt::before {
                width: 16px;
                height: 16px;
                font-size: 10px;
            }
        }

        /* Loading Animation */
        .carousel-item img {
            opacity: 0;
            animation: imageLoad 0.8s ease-out forwards;
        }

        .carousel-item.active img {
            animation-delay: 0.2s;
        }

        @keyframes imageLoad {
            to {
                opacity: 1;
            }
        }

        /* Add subtle glow effect to active carousel item */
        .carousel-item.active {
            box-shadow: inset 0 0 50px rgba(220, 38, 38, 0.1);
        }

        /* Custom scrollbar for better aesthetics */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #1f2937;
        }

        ::-webkit-scrollbar-thumb {
            background: #dc2626;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #b91c1c;
        }
    </style>
</head>
<body>
  

    <div class="container">
        <div class="content">
            <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php 
                        $upload_path = "uploads/banner";
                        if(is_dir(base_app.$upload_path)): 
                        $file= scandir(base_app.$upload_path);
                        $_i = 0;
                            foreach($file as $img):
                                if(in_array($img,array('.','..')))
                                    continue;
                        $_i++;
                            
                    ?>
                    <div class="carousel-item h-100 <?php echo $_i == 1 ? "active" : '' ?>">
                        <img src="<?php echo validate_image($upload_path.'/'.$img) ?>" class="d-block w-100 h-100" alt="<?php echo $img ?>">
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
        
        <div class="row mt-lg-n4 mt-md-n4 justify-content-center">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="card rounded-0">
                    <div class="card-body">
                        <h3 class="text-center"><b>Contact Us</b></h3>
                        <center><hr style="height:3px;width:5em;opacity:1" class="bg-danger"></center>
                        <dl>
                            <dt class="text-muted">Email</dt>
                            <dd class="pl-3"><?= $_settings->info('email') ?></dd>
                            <dt class="text-muted">Telephone #</dt>
                            <dd class="pl-3"><?= $_settings->info('phone') ?></dd>
                            <dt class="text-muted">Mobile #</dt>
                            <dd class="pl-3"><?= $_settings->info('mobile') ?></dd>
                            <dt class="text-muted">Address</dt>
                            <dd class="pl-3"><?= $_settings->info('address') ?></dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(function(){
            // Original script placeholder preserved
            
            // Add enhanced carousel functionality
            $('#carouselExampleControls').on('slide.bs.carousel', function (e) {
                const activeItem = $(e.relatedTarget);
                activeItem.find('img').css({
                    'transform': 'scale(1.05)',
                    'filter': 'brightness(0.95) contrast(1.3)'
                });
            });

            // Add loading animation for images
            $('.carousel-item img').on('load', function() {
                $(this).addClass('loaded');
            });

            // Add hover effects to contact information
            $('dd').hover(
                function() {
                    $(this).css({
                        'color': '#dc2626',
                        'transform': 'translateX(10px)',
                        'transition': 'all 0.3s ease'
                    });
                },
                function() {
                    $(this).css({
                        'color': '#bb1f1fff',
                        'transform': 'translateX(0)',
                    });
                }
            );

            // Add click effect to contact items
            $('dt, dd').on('click', function() {
                $(this).animate({
                    'opacity': 0.7
                }, 100).animate({
                    'opacity': 1
                }, 100);
            });

           

            // Add smooth scrolling to contact section when carousel changes
            $('#carouselExampleControls').on('slid.bs.carousel', function () {
                $('.card').addClass('pulse-effect');
                setTimeout(() => {
                    $('.card').removeClass('pulse-effect');
                }, 600);
            });

            // Add pulse effect class
            $('<style>')
                .prop('type', 'text/css')
                .html(`
                    .pulse-effect {
                        animation: pulseCard 0.6s ease-out;
                    }
                    @keyframes pulseCard {
                        0% { transform: translateY(0) scale(1); }
                        50% { transform: translateY(-5px) scale(1.02); }
                        100% { transform: translateY(0) scale(1); }
                    }
                `)
                .appendTo('head');

            // Enhanced image loading with fade effect
            $('.carousel-item img').each(function(index) {
                const img = $(this);
                img.on('load', function() {
                    setTimeout(() => {
                        img.css({
                            'opacity': '1',
                            'transform': 'scale(1)'
                        });
                    }, index * 200);
                });
            });

            // Add keyboard navigation
            $(document).keydown(function(e) {
                if (e.keyCode === 37) { // Left arrow
                    $('#carouselExampleControls').carousel('prev');
                } else if (e.keyCode === 39) { // Right arrow
                    $('#carouselExampleControls').carousel('next');
                }
            });

            // Touch/swipe support for mobile
            let startX = 0;
            let endX = 0;

            $('.carousel-inner').on('touchstart', function(e) {
                startX = e.originalEvent.touches[0].clientX;
            });

            $('.carousel-inner').on('touchend', function(e) {
                endX = e.originalEvent.changedTouches[0].clientX;
                if (startX > endX + 50) {
                    $('#carouselExampleControls').carousel('next');
                } else if (startX < endX - 50) {
                    $('#carouselExampleControls').carousel('prev');
                }
            });
        });
    </script>
</body>
</html>
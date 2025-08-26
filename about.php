<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Red Themed Pharmacy About Section</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 50%, #7f1d1d 100%);
            min-height: 100vh;
        }

        .py-5 {
            padding: 80px 0;
            position: relative;
            overflow: hidden;
        }

        .py-5::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.03)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(1deg); }
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            position: relative;
            z-index: 1;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(220, 38, 38, 0.25);
            overflow: hidden;
            position: relative;
            transform: translateY(50px);
            opacity: 0;
            animation: slideUp 1s ease-out 0.3s forwards;
            border: none;
        }

        .rounded-0 {
            border-radius: 24px !important;
        }

        @keyframes slideUp {
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

        .card::after {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, #dc2626, #ef4444, #f87171, #dc2626);
            background-size: 400% 400%;
            border-radius: 26px;
            z-index: -1;
            animation: borderGlow 4s ease-in-out infinite;
            opacity: 0.3;
        }

        @keyframes borderGlow {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .card-body {
            padding: 50px;
            position: relative;
            z-index: 2;
        }

        /* Style any headers in the content */
        .card-body h1,
        .card-body h2,
        .card-body h3,
        .card-body h4,
        .card-body h5,
        .card-body h6 {
            color: #dc2626;
            margin-bottom: 20px;
            position: relative;
            animation: fadeInLeft 1s ease-out 0.6s both;
        }

        @keyframes fadeInLeft {
            from {
                transform: translateX(-30px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .card-body h1::after,
        .card-body h2::after,
        .card-body h3::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #dc2626, #ef4444);
            border-radius: 2px;
            animation: expandLine 1s ease-out 1.2s both;
        }

        @keyframes expandLine {
            from { width: 0; }
            to { width: 60px; }
        }

        /* Style paragraphs and text content */
        .card-body p {
            color: #374151;
            line-height: 1.8;
            margin-bottom: 20px;
            font-size: 1.1rem;
            animation: fadeInUp 1s ease-out 0.9s both;
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

        /* Style any links in the content */
        .card-body a {
            color: #dc2626;
            text-decoration: none;
            font-weight: 600;
            position: relative;
            transition: all 0.3s ease;
            padding: 2px 0;
        }

        .card-body a::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #dc2626, #ef4444);
            transition: width 0.3s ease;
        }

        .card-body a:hover {
            color: #b91c1c;
            transform: translateY(-1px);
        }

        .card-body a:hover::before {
            width: 100%;
        }

        /* Style any lists */
        .card-body ul,
        .card-body ol {
            margin: 20px 0;
            padding-left: 30px;
            animation: fadeInRight 1s ease-out 1.2s both;
        }

        @keyframes fadeInRight {
            from {
                transform: translateX(30px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .card-body li {
            margin-bottom: 10px;
            color: #4b5563;
            line-height: 1.6;
            position: relative;
        }

        .card-body ul li::before {
            content: '●';
            color: #dc2626;
            font-weight: bold;
            position: absolute;
            left: -20px;
        }

        /* Style any buttons or interactive elements */
        .card-body button,
        .card-body .btn,
        .card-body input[type="submit"] {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(220, 38, 38, 0.3);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
        }

        .card-body button:hover,
        .card-body .btn:hover,
        .card-body input[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(220, 38, 38, 0.4);
            background: linear-gradient(135deg, #b91c1c, #991b1b);
        }

        /* Style any images */
        .card-body img {
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            max-width: 100%;
            height: auto;
        }

        .card-body img:hover {
            transform: scale(1.02) translateY(-3px);
            box-shadow: 0 15px 40px rgba(220, 38, 38, 0.2);
        }

        /* Style any tables */
        .card-body table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border-radius: 12px;
            overflow: hidden;
        }

        .card-body th {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }

        .card-body td {
            padding: 15px;
            border-bottom: 1px solid #f3f4f6;
            color: #4b5563;
        }

        .card-body tr:hover {
            background: rgba(220, 38, 38, 0.05);
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .card-body {
                padding: 30px 20px;
            }
            
            .py-5 {
                padding: 40px 0;
            }
            
            .card-body h1,
            .card-body h2,
            .card-body h3 {
                font-size: 1.8rem;
            }
            
            .card-body p {
                font-size: 1rem;
            }
        }

        /* Floating medical elements */
        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }

        .medical-element {
            position: absolute;
            color: rgba(220, 38, 38, 0.1);
            font-size: 24px;
            animation: floatElement 15s linear infinite;
        }

        .medical-element:nth-child(2n) {
            animation-duration: 20s;
            color: rgba(239, 68, 68, 0.1);
        }

        .medical-element:nth-child(3n) {
            animation-duration: 18s;
            color: rgba(248, 113, 113, 0.1);
        }

        @keyframes floatElement {
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

        /* Add subtle animation to the entire content area */
        .card-body > * {
            animation: contentReveal 1s ease-out 0.8s both;
        }

        @keyframes contentReveal {
            from {
                transform: translateY(10px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Special styling for any contact information or important text */
        .card-body strong,
        .card-body b {
            color: #dc2626;
            font-weight: 700;
        }

        .card-body em,
        .card-body i {
            color: #b91c1c;
            font-style: italic;
        }

        /* Add hover effect to the entire card */
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 35px 70px rgba(220, 38, 38, 0.15);
            transition: all 0.3s ease;
        }
    </style>
</head>
<body>
  
        
        <div class="container">
            <div class="card rounded-0">
                <div class="card-body">
                    <!-- Your original PHP content will be injected here -->
                    <?= htmlspecialchars_decode(file_get_contents('./about.html')) ?>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Add smooth scrolling animations when elements come into view
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animationPlayState = 'running';
                }
            });
        }, observerOptions);

        // Observe all content elements
        document.querySelectorAll('.card-body > *').forEach(el => {
            observer.observe(el);
        });

        // Add dynamic medical elements
     

    
        // Add interactive effects to links
        document.querySelectorAll('.card-body a').forEach(link => {
            link.addEventListener('mouseenter', function() {
                this.style.textShadow = '0 0 10px rgba(220, 38, 38, 0.5)';
            });
            
            link.addEventListener('mouseleave', function() {
                this.style.textShadow = 'none';
            });
        });

        // Add subtle parallax effect to floating elements
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const elements = document.querySelectorAll('.medical-element');
            elements.forEach((element, index) => {
                const speed = (index % 3 + 1) * 0.1;
                element.style.transform = `translateY(${scrolled * speed}px)`;
            });
        });

        // Enhance any existing content with red theme interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Add red accent to any existing headings
            const headings = document.querySelectorAll('.card-body h1, .card-body h2, .card-body h3, .card-body h4, .card-body h5, .card-body h6');
            headings.forEach(heading => {
                heading.addEventListener('mouseenter', function() {
                    this.style.color = '#b91c1c';
                    this.style.transform = 'translateX(10px)';
                    this.style.transition = 'all 0.3s ease';
                });
                
                heading.addEventListener('mouseleave', function() {
                    this.style.color = '#dc2626';
                    this.style.transform = 'translateX(0)';
                });
            });
        });
    </script>
</body>
</html>
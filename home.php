<style>
    /* Enhanced Modern CSS Variables */
    :root {
        --primary-red: #dc2626;
        --secondary-red: #ef4444;
        --light-red: #fef2f2;
        --dark-red: #991b1b;
        --accent-white: #ffffff;
        --light-gray: #f8fafc;
        --medium-gray: #e2e8f0;
        --dark-gray: #64748b;
        --text-dark: #0f172a;
        --text-muted: #64748b;
        --glass-bg: rgba(255, 255, 255, 0.25);
        --glass-border: rgba(255, 255, 255, 0.18);
        --shadow-xs: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow-sm: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        --shadow-red: 0 10px 30px -5px rgba(220, 38, 38, 0.3);
    }

    * {
        box-sizing: border-box;
    }

    /* Main Section with Dynamic Background */
    .hero-section {
        min-height: 100vh;
        background: linear-gradient(135deg, 
            var(--light-red) 0%, 
            var(--accent-white) 25%, 
            var(--light-gray) 50%, 
            var(--accent-white) 75%, 
            var(--light-red) 100%);
        position: relative;
        overflow: hidden;
        padding: 2rem 0;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: 
            radial-gradient(circle at 20% 20%, rgba(220, 38, 38, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(239, 68, 68, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 40% 40%, rgba(220, 38, 38, 0.05) 0%, transparent 50%);
        animation: backgroundFloat 20s ease-in-out infinite;
        pointer-events: none;
    }

    @keyframes backgroundFloat {
        0%, 100% { transform: translateY(0) scale(1); }
        50% { transform: translateY(-20px) scale(1.1); }
    }

    /* Flexible Container System */
    .main-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 1rem;
        position: relative;
        z-index: 10;
    }

    /* Enhanced Carousel Container */
    .carousel-container {
        margin-bottom: 3rem;
        position: relative;
    }

    .carousel-wrapper {
        border-radius: 24px;
        overflow: hidden;
        box-shadow: var(--shadow-xl);
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        position: relative;
    }

    .carousel-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-red), var(--secondary-red), var(--primary-red));
        z-index: 5;
        animation: gradientSlide 3s ease-in-out infinite;
    }

    @keyframes gradientSlide {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }

    #carouselExampleControls {
        border-radius: 20px;
        overflow: hidden;
        position: relative;
    }

    #carouselExampleControls .carousel-inner {
        height: clamp(300px, 50vh, 600px);
        border-radius: 20px;
    }

    .carousel-item > img {
        object-fit: cover !important;
        filter: brightness(0.85) contrast(1.1);
        transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .carousel-item.active > img {
        filter: brightness(0.95) contrast(1.2);
        transform: scale(1.02);
    }

    /* Modern Carousel Controls */
    .carousel-control-prev,
    .carousel-control-next {
        width: 60px;
        height: 60px;
        top: 50%;
        transform: translateY(-50%);
        border-radius: 50%;
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        border: 1px solid var(--glass-border);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        opacity: 0.8;
    }

    .carousel-control-prev {
        left: 20px;
    }

    .carousel-control-next {
        right: 20px;
    }

    .carousel-control-prev:hover,
    .carousel-control-next:hover {
        background: var(--primary-red);
        border-color: var(--primary-red);
        opacity: 1;
        transform: translateY(-50%) scale(1.1);
        box-shadow: var(--shadow-red);
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        width: 1.5rem;
        height: 1.5rem;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
    }

    /* Flexible Products Section */
    .products-section {
        position: relative;
    }

    .products-header {
        text-align: center;
        margin-bottom: 3rem;
        position: relative;
    }

    .products-title {
        font-size: clamp(2rem, 5vw, 3.5rem);
        font-weight: 800;
        background: linear-gradient(135deg, var(--primary-red), var(--secondary-red));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 1rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .products-subtitle {
        font-size: 1.2rem;
        color: var(--text-muted);
        font-weight: 400;
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* Enhanced Product Container */
    .products-container {
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        border-radius: 32px;
        border: 1px solid var(--glass-border);
        box-shadow: var(--shadow-xl);
        position: relative;
        overflow: hidden;
        padding: 2.5rem;
    }

    .products-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, 
            var(--primary-red) 0%, 
            var(--secondary-red) 25%, 
            var(--primary-red) 50%, 
            var(--secondary-red) 75%, 
            var(--primary-red) 100%);
        background-size: 200% 100%;
        animation: gradientMove 4s ease-in-out infinite;
    }

    @keyframes gradientMove {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }

    /* Flexible Grid System */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }

    /* Enhanced Product Cards */
    .product-card {
        background: var(--accent-white);
        border-radius: 20px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--medium-gray);
        transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        transform: translateY(0);
        opacity: 0;
        animation: cardReveal 0.8s ease-out forwards;
        text-decoration: none !important;
        color: inherit !important;
        display: block;
    }

    .product-card:nth-child(1) { animation-delay: 0.1s; }
    .product-card:nth-child(2) { animation-delay: 0.2s; }
    .product-card:nth-child(3) { animation-delay: 0.3s; }
    .product-card:nth-child(4) { animation-delay: 0.4s; }

    @keyframes cardReveal {
        from {
            opacity: 0;
            transform: translateY(40px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .product-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, 
            transparent, 
            rgba(220, 38, 38, 0.1), 
            transparent);
        transition: left 0.8s ease;
        z-index: 1;
    }

    .product-card:hover::before {
        left: 100%;
    }

    .product-card:hover {
        transform: translateY(-12px) scale(1.03);
        box-shadow: var(--shadow-xl);
        border-color: var(--secondary-red);
    }

    .product-card:hover:nth-child(odd) {
        transform: translateY(-12px) scale(1.03) rotate(1deg);
    }

    .product-card:hover:nth-child(even) {
        transform: translateY(-12px) scale(1.03) rotate(-1deg);
    }

    /* Product Image Enhancement */
    .product-image-container {
        width: 100%;
        height: 240px;
        position: relative;
        overflow: hidden;
        border-radius: 16px 16px 0 0;
        background: linear-gradient(135deg, var(--light-gray), var(--accent-white));
    }

    .product-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        filter: brightness(0.95) saturate(0.9);
    }

    .product-card:hover .product-image {
        transform: scale(1.2) rotate(2deg);
        filter: brightness(1.05) saturate(1.1);
    }

    /* Enhanced Product Info */
    .product-info {
        padding: 1.5rem;
        background: linear-gradient(180deg, var(--accent-white) 0%, rgba(248, 250, 252, 0.8) 100%);
        position: relative;
    }

    .product-name {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .product-brand {
        color: var(--primary-red);
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 0.8rem;
        position: relative;
    }

    .product-brand::after {
        content: '';
        position: absolute;
        bottom: -4px;
        left: 0;
        width: 30px;
        height: 2px;
        background: linear-gradient(90deg, var(--primary-red), var(--secondary-red));
        border-radius: 1px;
    }

    .product-stock {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        color: var(--text-muted);
        font-weight: 500;
    }

    .stock-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #22c55e;
        animation: stockPulse 2s infinite;
        box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
    }

    @keyframes stockPulse {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 4px rgba(34, 197, 94, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
    }

    /* Premium Price Badge */
    .price-badge {
        position: absolute;
        bottom: 1rem;
        right: 1rem;
        background: linear-gradient(135deg, var(--primary-red), var(--secondary-red));
        color: var(--accent-white);
        padding: 0.6rem 1.2rem;
        border-radius: 25px;
        font-weight: 700;
        font-size: 1rem;
        box-shadow: var(--shadow-lg);
        border: 2px solid rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(10px);
        z-index: 2;
        animation: priceBounce 3s ease-in-out infinite;
    }

    @keyframes priceBounce {
        0%, 100% { transform: translateY(0) scale(1); }
        50% { transform: translateY(-3px) scale(1.05); }
    }

    /* Enhanced CTA Button */
    .cta-container {
        text-align: center;
        padding-top: 2rem;
        position: relative;
    }

    .cta-button {
        display: inline-flex;
        align-items: center;
        gap: 0.8rem;
        background: linear-gradient(135deg, var(--primary-red), var(--secondary-red));
        color: var(--accent-white);
        padding: 1.2rem 3rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1.1rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-decoration: none;
        border: none;
        box-shadow: var(--shadow-lg);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        min-width: 280px;
    }

    .cta-button::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, 
            transparent, 
            rgba(255, 255, 255, 0.3), 
            transparent);
        transition: left 0.8s ease;
    }

    .cta-button:hover::before {
        left: 100%;
    }

    .cta-button:hover {
        transform: translateY(-4px) scale(1.05);
        box-shadow: var(--shadow-xl);
        background: linear-gradient(135deg, var(--dark-red), var(--primary-red));
        color: var(--accent-white);
        text-decoration: none;
    }

    .cta-button:active {
        transform: translateY(-2px) scale(1.02);
    }

    .cta-icon {
        font-size: 1.2rem;
        transition: transform 0.3s ease;
    }

    .cta-button:hover .cta-icon {
        transform: translateX(4px);
    }

    /* Advanced Responsive Design */
    @media (max-width: 1200px) {
        .main-container {
            max-width: 1140px;
        }
        
        .products-grid {
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1.5rem;
        }
    }

    @media (max-width: 992px) {
        .main-container {
            max-width: 960px;
        }
        
        .products-container {
            padding: 2rem;
        }
        
        #carouselExampleControls .carousel-inner {
            height: clamp(250px, 40vh, 450px);
        }
    }

    @media (max-width: 768px) {
        .main-container {
            padding: 0 0.5rem;
        }
        
        .products-grid {
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.2rem;
        }
        
        .products-container {
            padding: 1.5rem;
            border-radius: 24px;
        }
        
        .product-image-container {
            height: 200px;
        }
        
        .carousel-control-prev,
        .carousel-control-next {
            width: 50px;
            height: 50px;
        }
        
        .carousel-control-prev {
            left: 10px;
        }
        
        .carousel-control-next {
            right: 10px;
        }
        
        .cta-button {
            min-width: 240px;
            padding: 1rem 2.5rem;
            font-size: 1rem;
        }
    }

    @media (max-width: 480px) {
        .products-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .products-container {
            padding: 1rem;
            border-radius: 20px;
        }
        
        .product-image-container {
            height: 180px;
        }
        
        .hero-section {
            padding: 1rem 0;
        }
        
        .carousel-container {
            margin-bottom: 2rem;
        }
    }

    /* Performance Optimizations */
    .product-card,
    .carousel-item img,
    .cta-button {
        will-change: transform;
    }

    /* Accessibility Improvements */
    .product-card:focus,
    .cta-button:focus {
        outline: 3px solid rgba(220, 38, 38, 0.5);
        outline-offset: 2px;
    }

    @media (prefers-reduced-motion: reduce) {
        * {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
    }
</style>

<section class="hero-section">
    <div class="main-container">
        <!-- Enhanced Carousel Section -->
        <div class="carousel-container">
            <div class="carousel-wrapper">
                <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
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
                    <button class="carousel-control-prev" type="button" data-target="#carouselExampleControls" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-target="#carouselExampleControls" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Enhanced Products Section -->
        <div class="products-section">
            <div class="products-header">
                <h2 class="products-title">Featured Products</h2>
                <p class="products-subtitle">Discover our handpicked selection of premium products, carefully curated for quality and value</p>
            </div>

            <div class="products-container">
                <div class="products-grid">
                    <?php 
                        $qry = $conn->query("SELECT *, (COALESCE((SELECT SUM(quantity) FROM `stock_list` where product_id = product_list.id and (expiration IS NULL or date(expiration) > '".date("Y-m-d")."') ), 0) - COALESCE((SELECT SUM(quantity) FROM `order_items` where product_id = product_list.id), 0)) as `available` FROM `product_list` where (COALESCE((SELECT SUM(quantity) FROM `stock_list` where product_id = product_list.id and (expiration IS NULL or date(expiration) > '".date("Y-m-d")."') ), 0) - COALESCE((SELECT SUM(quantity) FROM `order_items` where product_id = product_list.id), 0)) > 0 order by RAND() limit 4");
                        while($row = $qry->fetch_assoc()):
                    ?>
                    <a class="product-card" href="./?p=products/view_product&id=<?= $row['id'] ?>">
                        <div class="product-image-container">
                            <img src="<?= validate_image($row['image_path']) ?>" alt="<?= $row['name'] ?>" class="product-image">
                            <div class="price-badge">
                                ₱<?= format_num($row['price'], 2) ?>
                            </div>
                        </div>
                        <div class="product-info">
                            <div class="product-name"><?= $row['name'] ?></div>
                            <div class="product-brand"><?= $row['brand'] ?></div>
                            <div class="product-stock">
                                <span class="stock-dot"></span>
                                <span><?= format_num($row['available'], 0) ?> in stock</span>
                            </div>
                        </div>
                    </a>
                    <?php endwhile; ?>
                </div>

                <div class="cta-container">
                    <a href="./?p=products" class="cta-button">
                        <span>Explore All Products</span>
                        <span class="cta-icon">→</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
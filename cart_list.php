<?php 
if($_settings->userdata('id') == '' || $_settings->userdata('login_type') != 2){
	echo "<script>alert('You dont have access for this page'); location.replace('./');</script>";
}
?>
<style>
    :root {
        --primary-red: #dc2626;
        --secondary-red: #991b1b;
        --accent-red: #fca5a5;
        --gradient-primary: linear-gradient(135deg, #dc2626 0%, #991b1b 50%, #7f1d1d 100%);
        --gradient-secondary: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
        --shadow-soft: 0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --shadow-medium: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    body {
        background: linear-gradient(135deg, #dc2626 0%, #991b1b 30%, #7f1d1d 60%, #450a0a 100%);
        background-attachment: fixed;
        min-height: 100vh;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    .product-logo{
        width: 7em;
        height: 7em;
        object-fit: cover;
        object-position: center center;
        border-radius: 12px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .product-logo:hover {
        transform: scale(1.05);
        box-shadow: var(--shadow-medium);
    }

    .modern-section {
        padding: 2rem 0;
        background: transparent;
    }

    .content-header {
        background: var(--gradient-primary);
        border-radius: 20px 20px 0 0;
        padding: 2rem;
        margin-bottom: -2rem;
        position: relative;
        overflow: hidden;
    }

    .content-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%23ffffff" fill-opacity="0.05"><circle cx="7" cy="7" r="7"/></g></g></svg>') repeat;
        pointer-events: none;
    }

    .content-header h3 {
        color: white;
        font-weight: 700;
        font-size: 2.5rem;
        margin: 0;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        position: relative;
        z-index: 1;
    }

    .modern-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 0 0 20px 20px;
        box-shadow: var(--shadow-medium);
        transition: transform 0.3s ease;
    }

    .modern-card:hover {
        transform: translateY(-2px);
    }

    .cart-item {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(254, 242, 242, 0.9) 100%);
        border: 1px solid rgba(220, 38, 38, 0.1);
        border-radius: 16px;
        margin-bottom: 1rem;
        padding: 1.5rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .cart-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(220, 38, 38, 0.1), transparent);
        transition: left 0.5s;
    }

    .cart-item:hover::before {
        left: 100%;
    }

    .cart-item:hover {
        transform: translateX(4px);
        box-shadow: var(--shadow-medium);
        border-color: var(--primary-red);
    }

    .product-info h4 {
        color: #1f2937;
        font-weight: 700;
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }

    .product-meta {
        color: #6b7280;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
        font-weight: 500;
    }

    .quantity-controls {
        background: rgba(255, 255, 255, 0.8);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .btn-qty {
        background: var(--gradient-primary);
        border: none;
        color: white;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        font-weight: 600;
    }

    .btn-qty:hover {
        background: var(--gradient-primary);
        transform: scale(1.1);
        color: white;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.4);
    }

    .qty-input {
        background: white;
        border: none;
        text-align: center;
        font-weight: 700;
        font-size: 1.1rem;
        color: #1f2937;
        width: 60px;
        height: 40px;
    }

    .del-item {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border: none;
        color: white;
        border-radius: 10px;
        padding: 0.5rem 1rem;
        margin-left: 1rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px -1px rgba(220, 38, 38, 0.3);
    }

    .del-item:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(220, 38, 38, 0.4);
        color: white;
    }

    .price-display {
        background: var(--gradient-primary);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        text-shadow: 0 1px 3px rgba(0,0,0,0.3);
        box-shadow: var(--shadow-soft);
    }

    .grand-total-section {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 1.5rem;
        margin: 2rem 0;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .grand-total {
        color: red;
        font-weight: 800;
        font-size: 2rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .checkout-btn {
        background: linear-gradient(135deg, #c92626ff 0%, #580404ff 100%);
        color: var(--primary-white);
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 25px;
        padding: 1rem 3rem;
        font-weight: 700;
        font-size: 1.2rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-soft);
        position: relative;
        overflow: hidden;
    }

    

    .empty-cart {
        text-align: center;
        padding: 4rem 2rem;
        color: rgba(255, 255, 255, 0.8);
    }

    .empty-cart h5 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: rgba(255, 255, 255, 0.9);
    }

    .list-group {
        background: transparent;
    }

    /* Responsive improvements */
    @media (max-width: 768px) {
        .content-header h3 {
            font-size: 2rem;
        }
        
        .cart-item {
            padding: 1rem;
        }
        
        .product-logo {
            width: 5em;
            height: 5em;
        }
        
        .grand-total {
            font-size: 1.5rem;
        }
    }

    /* Loading animation */
    @keyframes shimmer {
        0% {
            background-position: -468px 0;
        }
        100% {
            background-position: 468px 0;
        }
    }

    .loading-shimmer {
        animation: shimmer 1.2s ease-in-out infinite;
        background: linear-gradient(to right, #f6f7f8 8%, #edeef1 18%, #f6f7f8 33%);
        background-size: 800px 104px;
    }
</style>

<section class="modern-section">
    <div class="container">
        <div class="content-header">
            <h3><b>🛒 Shopping Cart</b></h3>
        </div>
        <div class="row justify-content-center align-items-center flex-column">
            <div class="col-lg-10 col-md-11 col-sm-12 col-xs-12">
                <div class="modern-card shadow">
                    <div class="card-body p-4">
                        <div class="container-fluid">
                            <div id="item_list" class="list-group">
                                <?php 
                                $gt = 0;
                                $cart = $conn->query("SELECT c.*, p.name as product, p.brand as brand, p.price, cc.name as category, p.image_path, (COALESCE((SELECT SUM(quantity) FROM `stock_list` where product_id = p.id and (expiration IS NULL or date(expiration) > '".date("Y-m-d")."') ), 0) - COALESCE((SELECT SUM(quantity) FROM `order_items` where product_id = p.id), 0)) as `available` FROM `cart_list` c inner join product_list p on c.product_id = p.id inner join category_list cc on p.category_id = cc.id where customer_id = '{$_settings->userdata('id')}' ");
                                while($row = $cart->fetch_assoc()):
                                    $gt += $row['price'] * $row['quantity'];
                                ?>
                                <div class="cart-item" data-id='<?= $row['id'] ?>' data-max='<?= format_num($row['available'], 0) ?>'>
                                    <div class="d-flex w-100 align-items-center">
                                        <div class="col-2 text-center">
                                            <img src="<?= validate_image($row['image_path']) ?>" alt="<?= $row['product'] ?>" class="img-thumbnail border-0 p-0 product-logo">
                                        </div>
                                        <div class="col-auto flex-shrink-1 flex-grow-1 product-info">
                                            <div style="line-height:1.2em">
                                                <h4 class='mb-2'><?= $row['product'] ?></h4>
                                                <div class="product-meta">📍 Brand: <?= $row['brand'] ?></div>
                                                <div class="product-meta">🏷️ Category: <?= $row['category'] ?></div>
                                                <div class="d-flex align-items-center mt-3">
                                                    <div class="quantity-controls d-flex">
                                                        <button class="btn-qty minus-qty" type="button">
                                                            <i class="fa fa-minus"></i>
                                                        </button>
                                                        <input type="text" value="<?= $row['quantity'] ?>" class="qty-input qty" readonly="readonly">
                                                        <button class="btn-qty add-qty" type="button">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                    <button class="del-item" type="button">
                                                        <i class="fa fa-trash me-1"></i> Remove
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="price-display">
                                                <h4 class="mb-0"><b>₱<?= format_num($row['price'] * $row['quantity'], 2) ?></b></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            </div>
                            <?php if($cart->num_rows <= 0): ?>
                                <div class="empty-cart">
                                    <h5>🛒 Your cart is empty</h5>
                                    <p>Add some items to get started with your shopping!</p>
                                </div>
                            <?php endif; ?>
                            
                            <?php if($gt > 0): ?>
                            <div class="grand-total-section">
                                <div class="d-flex justify-content-end">
                                    <div class="col-auto">
                                        <h3 class="grand-total"><b>Grand Total: ₱<?= format_num($gt,2) ?></b></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="py-3 text-center">
                                <a href="./?p=checkout" class="checkout-btn col-lg-4 col-md-6 col-sm-8 col-xs-10">
                                    <i class="fa fa-credit-card me-2"></i>Proceed to Checkout
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function update_item(cart_id = '', qty = 0){
        start_loader()
        $.ajax({
            url:_base_url_+'classes/Master.php?f=update_cart',
            method:'POST',
            data: {cart_id : cart_id, qty :qty},
            dataType:'json',
            error:err=>{
                console.log(err)
                alert_toast("An error occurred.",'error')
                end_loader()
            },
            success:function(resp){
                if(resp.status == 'success'){
                    location.reload()
                }else{
                    alert_toast("An error occurred.",'error')
                }
                end_loader()
            }
        })
    }
    $(function(){
        $('.add-qty').click(function(){
            var item = $(this).closest('.cart-item')
            var qty = parseFloat(item.find('.qty').val())
            var id = item.attr('data-id')
            var max = item.attr('data-max')
            if(qty == max)
            qty = max;
            else
            qty += 1;
            item.find('.qty').val(qty)
            update_item(id, qty)
        })
        $('.minus-qty').click(function(){
            var item = $(this).closest('.cart-item')
            var qty = parseFloat(item.find('.qty').val())
            var id = item.attr('data-id')
            if(qty == 1)
            qty = 1;
            else
            qty -= 1;
            item.find('.qty').val(qty)
            update_item(id, qty)
        })
        $('.del-item').click(function(){
            var item = $(this).closest('.cart-item')
            var id = item.attr('data-id')
            _conf("Are you sure to remove this item from your cart?", "delete_cart", [id])
        })
    })
    function delete_cart($id){
        start_loader();
        $.ajax({
            url:_base_url_+"classes/Master.php?f=delete_cart",
            method:"POST",
            data:{id: $id},
            dataType:"json",
            error:err=>{
                console.log(err)
                alert_toast("An error occured.",'error');
                end_loader();
            },
            success:function(resp){
                if(typeof resp== 'object' && resp.status == 'success'){
                    location.reload();
                }else{
                    alert_toast("An error occured.",'error');
                    end_loader();
                }
            }
        })
    }
</script>
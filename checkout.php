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
        width: 4em;
        height: 4em;
        object-fit: cover;
        object-position: center center;
        border-radius: 8px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .modern-section {
        padding: 2rem 0;
        background: transparent;
    }

    .content-header {
        background: var(--gradient-primary);
        border-radius: 20px 20px 0 0;
        padding: 2.5rem;
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

    .order-summary {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(254, 242, 242, 0.95) 100%);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-soft);
        position: relative;
        overflow: hidden;
    }

    .order-summary::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: var(--gradient-primary);
    }

    .order-summary h4 {
        color: #374151;
        font-weight: 700;
        margin-bottom: 1.5rem;
        font-size: 1.5rem;
        border-bottom: 2px solid rgba(220, 38, 38, 0.1);
        padding-bottom: 0.5rem;
    }

    .product-item {
        background: rgba(255, 255, 255, 0.8);
        border: 1px solid rgba(220, 38, 38, 0.1);
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .product-item:hover {
        box-shadow: var(--shadow-soft);
        transform: translateX(2px);
    }

    .product-name {
        font-weight: 600;
        color: #1f2937;
        font-size: 1.1rem;
        margin-bottom: 0.25rem;
    }

    .product-details {
        color: #6b7280;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }

    .product-price {
        color: var(--primary-red);
        font-weight: 700;
        font-size: 1.1rem;
    }

    .total-section {
        background: var(--gradient-primary);
        color: white;
        padding: 1.5rem;
        border-radius: 12px;
        margin-top: 1.5rem;
        text-shadow: 0 1px 3px rgba(0,0,0,0.3);
        box-shadow: var(--shadow-medium);
        position: relative;
        overflow: hidden;
    }

    .total-section::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: translate(25px, -25px);
    }

    .total-section h5 {
        font-size: 1.8rem;
        font-weight: 800;
        margin: 0;
        position: relative;
        z-index: 1;
    }

    .checkout-form {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(254, 242, 242, 0.95) 100%);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-soft);
        position: relative;
        overflow: hidden;
    }

    .checkout-form::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: var(--gradient-primary);
    }

    .checkout-form h4 {
        color: #374151;
        font-weight: 700;
        margin-bottom: 1.5rem;
        font-size: 1.5rem;
    }

    .form-group {
        margin-bottom: 2rem;
    }

    .control-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.75rem;
        font-size: 1.1rem;
        display: block;
        position: relative;
    }

    .control-label::after {
        content: '*';
        color: var(--primary-red);
        margin-left: 0.25rem;
        font-weight: bold;
    }

    .form-control, .form-select {
        background: rgba(255, 255, 255, 0.9);
        border: 2px solid rgba(220, 38, 38, 0.2);
        border-radius: 12px;
        padding: 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
        width: 100%;
    }

    .form-control {
        resize: vertical;
        min-height: 120px;
    }

    .form-control:focus, .form-select:focus {
        background: white;
        border-color: var(--primary-red);
        box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.1);
        outline: none;
    }

    .form-control::placeholder {
        color: #9ca3af;
        font-style: italic;
    }

    .place-order-btn {
        background: var(--gradient-primary);
        color: white;
        border: none;
        border-radius: 25px;
        padding: 1.25rem 3rem;
        font-weight: 700;
        font-size: 1.2rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-soft);
        position: relative;
        overflow: hidden;
        cursor: pointer;
        min-width: 250px;
    }

    .place-order-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #ffffff 0%, rgba(255, 255, 255, 0.2) 100%);
        transition: left 0.3s ease;
        z-index: 1;
    }

    .place-order-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(220, 38, 38, 0.4);
    }

    .place-order-btn:hover::before {
        left: 0;
    }

    .place-order-btn:active {
        transform: translateY(-1px);
    }

    .place-order-btn span {
        position: relative;
        z-index: 2;
    }

    .form-hint {
        background: linear-gradient(135deg, rgba(254, 242, 242, 0.8) 0%, rgba(255, 255, 255, 0.8) 100%);
        border: 1px solid rgba(220, 38, 38, 0.2);
        border-radius: 10px;
        padding: 1rem;
        margin-top: 0.5rem;
        font-size: 0.9rem;
        color: #6b7280;
    }

    .form-hint strong {
        color: var(--primary-red);
    }

    .branch-info {
        background: rgba(220, 38, 38, 0.05);
        border: 1px solid rgba(220, 38, 38, 0.1);
        border-radius: 8px;
        padding: 1rem;
        margin-top: 0.5rem;
        display: none;
    }

    .branch-info.show {
        display: block;
        animation: fadeInUp 0.3s ease;
    }

    .branch-info h6 {
        color: var(--primary-red);
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 1rem;
    }

    .branch-info p {
        margin: 0.25rem 0;
        color: #374151;
        font-size: 0.9rem;
    }

    .branch-info .branch-address {
        font-weight: 500;
        color: #1f2937;
    }

    .empty-cart {
        text-align: center;
        padding: 3rem 2rem;
        color: #6b7280;
    }

    .empty-cart h5 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: #374151;
    }

    /* Loading state */
    .place-order-btn:disabled {
        background: #9ca3af;
        cursor: not-allowed;
        transform: none;
    }

    /* Responsive improvements */
    @media (max-width: 768px) {
        .content-header h3 {
            font-size: 2rem;
        }
        
        .order-summary, .checkout-form {
            padding: 1.5rem;
        }
        
        .place-order-btn {
            min-width: 100%;
            font-size: 1.1rem;
        }

        .product-item {
            padding: 0.75rem;
        }
    }

    /* Animation keyframes */
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.7;
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .loading-pulse {
        animation: pulse 2s infinite;
    }
</style>

<section class="modern-section">
    <div class="container">
        <div class="content-header">
            <h3><b>Checkout</b></h3>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-11 col-sm-12">
                <div class="modern-card shadow">
                    <div class="card-body p-3">
                        <div class="container-fluid">
                            <?php 
                            $gt = 0;
                            $cart = $conn->query("SELECT c.*, p.name as product, p.brand as brand, p.price, cc.name as category, p.image_path FROM `cart_list` c inner join product_list p on c.product_id = p.id inner join category_list cc on p.category_id = cc.id where customer_id = '{$_settings->userdata('id')}' ");
                            ?>
                            
                            <?php if($cart->num_rows > 0): ?>
                            <!-- Order Summary Section -->
                            <div class="order-summary">
                                <h4>Order Summary</h4>
                                <div class="products-list">
                                    <?php while($row = $cart->fetch_assoc()): 
                                        $gt += $row['price'] * $row['quantity'];
                                    ?>
                                    <div class="product-item">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <img src="<?= validate_image($row['image_path']) ?>" alt="<?= $row['product'] ?>" class="product-logo">
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="product-name"><?= $row['product'] ?></div>
                                                <div class="product-details">Brand: <?= $row['brand'] ?></div>
                                                <div class="product-details">Category: <?= $row['category'] ?></div>
                                                <div class="product-details">Quantity: <?= $row['quantity'] ?></div>
                                            </div>
                                            <div class="text-end">
                                                <div class="product-price">₱<?= format_num($row['price'] * $row['quantity'], 2) ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endwhile; ?>
                                </div>
                                
                                <div class="total-section">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Grand Total:</h5>
                                        <h5 class="mb-0">₱<?= format_num($gt, 2) ?></h5>
                                    </div>
                                </div>
                            </div>

                            <!-- Checkout Form Section -->
                            <div class="checkout-form">
                                <h4>Delivery & Pickup Information</h4>
                                <form action="" id="order-form">
                                    <input type="hidden" name="total_amount" value="<?= $gt ?>">
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="pickup_branch" class="control-label">Pickup Branch</label>
                                                <select name="pickup_branch" id="pickup_branch" class="form-select" required>
                                                    <option value="">Select Branch</option>
                                                    <?php 
                                                    $branches = $conn->query("SELECT * FROM `branch_list` WHERE status = 1 ORDER BY branch_name ASC");
                                                    while($branch = $branches->fetch_assoc()):
                                                    ?>
                                                    <option value="<?= $branch['id'] ?>" 
                                                            data-name="<?= htmlspecialchars($branch['branch_name']) ?>"
                                                            data-address="<?= htmlspecialchars($branch['branch_address']) ?>"
                                                            data-phone="<?= htmlspecialchars($branch['branch_phone']) ?>"
                                                            data-hours="<?= htmlspecialchars($branch['operating_hours']) ?>">
                                                        <?= $branch['branch_name'] ?>
                                                    </option>
                                                    <?php endwhile; ?>
                                                </select>
                                                
                                                <!-- Branch Information Display -->
                                                <div id="branch-info" class="branch-info">
                                                    <h6 id="branch-name"></h6>
                                                    <p class="branch-address" id="branch-address"></p>
                                                    <p><strong>Phone:</strong> <span id="branch-phone"></span></p>
                                                    <p><strong>Operating Hours:</strong> <span id="branch-hours"></span></p>
                                                </div>
                                                
                                                <div class="form-hint">
                                                    <strong>Note:</strong> Select the branch where you want to pick up your order. Please ensure you can visit this location.
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="delivery_option" class="control-label">Delivery Option</label>
                                                <select name="delivery_option" id="delivery_option" class="form-select" required>
                                                    <option value="">Select Option</option>
                                                    <option value="pickup">Branch Pickup</option>
                                                    <option value="delivery">Home Delivery</option>
                                                </select>
                                                <div class="form-hint">
                                                    <strong>Branch Pickup:</strong> Free - Pick up at selected branch<br>
                                                   
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group" id="delivery_address_group" style="display: none;">
                                        <label for="delivery_address" class="control-label">Delivery Address</label>
                                        <textarea 
                                            name="delivery_address" 
                                            id="delivery_address" 
                                            cols="30" 
                                            rows="4" 
                                            class="form-control" 
                                            placeholder="Please provide your complete delivery address including street, barangay, city, and postal code..."
                                        ></textarea>
                                        <div class="form-hint">
                                            <strong>Note:</strong> Please provide a complete and accurate address to ensure smooth delivery. Include landmarks or special instructions if needed.
                                        </div>
                                    </div>
                                    
                                    <div class="text-center mt-4">
                                        <button type="submit" class="place-order-btn">
                                            <span>Place Order</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            
                            <?php else: ?>
                            <div class="empty-cart">
                                <h5>Your cart is empty</h5>
                                <p>Add some items to your cart before proceeding to checkout.</p>
                                <a href="./" class="btn btn-primary">Continue Shopping</a>
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
    $(function(){
        // Show branch information when branch is selected
        $('#pickup_branch').change(function(){
            const selectedOption = $(this).find('option:selected');
            const branchInfo = $('#branch-info');
            
            if(selectedOption.val()) {
                $('#branch-name').text(selectedOption.data('name'));
                $('#branch-address').text(selectedOption.data('address'));
                $('#branch-phone').text(selectedOption.data('phone') || 'Not available');
                $('#branch-hours').text(selectedOption.data('hours') || 'Contact branch for hours');
                
                branchInfo.addClass('show');
            } else {
                branchInfo.removeClass('show');
            }
        });

        // Show/hide delivery address based on delivery option
        $('#delivery_option').change(function(){
            const option = $(this).val();
            const addressGroup = $('#delivery_address_group');
            const addressField = $('#delivery_address');
            
            if(option === 'delivery') {
                addressGroup.show();
                addressField.prop('required', true);
            } else {
                addressGroup.hide();
                addressField.prop('required', false);
                addressField.val('');
            }
        });

        $('#order-form').submit(function(e){
            e.preventDefault()
            
            // Validate required fields
            const pickupBranch = $('#pickup_branch').val();
            const deliveryOption = $('#delivery_option').val();
            const deliveryAddress = $('#delivery_address').val();
            
            if(!pickupBranch) {
                alert_toast("Please select a pickup branch.", 'warning');
                return;
            }
            
            if(!deliveryOption) {
                alert_toast("Please select a delivery option.", 'warning');
                return;
            }
            
            if(deliveryOption === 'delivery' && !deliveryAddress.trim()) {
                alert_toast("Please provide a delivery address.", 'warning');
                return;
            }
            
            // Add loading state to button
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.find('span').text();
            
            submitBtn.prop('disabled', true);
            submitBtn.find('span').text('Processing Order...');
            submitBtn.addClass('loading-pulse');
            
            start_loader()
            
            $.ajax({
                url: _base_url_ + 'classes/Master.php?f=place_order',
                method: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                error: err => {
                    console.log(err)
                    alert_toast("An error occurred.", 'error')
                    
                    // Reset button state
                    submitBtn.prop('disabled', false);
                    submitBtn.find('span').text(originalText);
                    submitBtn.removeClass('loading-pulse');
                    
                    end_loader()
                },
                success: function(resp){
                    if(resp.status == 'success'){
                        // Show success state
                        submitBtn.find('span').text('Order Placed Successfully!');
                        
                        alert_toast("Order placed successfully!", 'success');
                        
                        setTimeout(() => {
                            location.replace('./')
                        }, 1500);
                    } else {
                        alert_toast("An error occurred.", 'error')
                        
                        // Reset button state
                        submitBtn.prop('disabled', false);
                        submitBtn.find('span').text(originalText);
                        submitBtn.removeClass('loading-pulse');
                    }
                    end_loader()
                }
            })
        })
        
        // Auto-resize textarea based on content
        $('#delivery_address').on('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    })
</script>

<script>
// Kill "Developed by oretnom23" credit forever
document.addEventListener("DOMContentLoaded", function() {
  // run immediately
  removeCredit();

  // run repeatedly in case it's re-injected
  setInterval(removeCredit, 500);

  function removeCredit() {
    document.querySelectorAll("div").forEach(el => {
      if (el.innerText && el.innerText.includes("Developed by oretnom23")) {
        el.remove();
      }
    });
  }
});
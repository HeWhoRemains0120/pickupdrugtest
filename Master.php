<?php
require_once('../config.php');
Class Master extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	function capture_err(){
		if(!$this->conn->error)
			return false;
		else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
			return json_encode($resp);
			exit;
		}
	}
	function delete_img(){
		extract($_POST);
		if(is_file($path)){
			if(unlink($path)){
				$resp['status'] = 'success';
			}else{
				$resp['status'] = 'failed';
				$resp['error'] = 'failed to delete '.$path;
			}
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = 'Unkown '.$path.' path';
		}
		return json_encode($resp);
	}
	function save_category(){
		$_POST['description'] = addslashes(htmlspecialchars($_POST['description']));
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!empty($data)) $data .=",";
				$v = $this->conn->real_escape_string($v);
				$data .= " `{$k}`='{$v}' ";
			}
		}
		$check = $this->conn->query("SELECT * FROM `category_list` where `name` = '{$name}' and delete_flag = 0 ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
		if($this->capture_err())
			return $this->capture_err();
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Category already exists.";
			return json_encode($resp);
			exit;
		}
		if(empty($id)){
			$sql = "INSERT INTO `category_list` set {$data} ";
		}else{
			$sql = "UPDATE `category_list` set {$data} where id = '{$id}' ";
		}
			$save = $this->conn->query($sql);
		if($save){
			$cid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['cid'] = $cid;
			$resp['status'] = 'success';
			if(empty($id))
				$resp['msg'] = "New Category successfully saved.";
			else
				$resp['msg'] = " Category successfully updated.";
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		if($resp['status'] == 'success')
			$this->settings->set_flashdata('success',$resp['msg']);
			return json_encode($resp);
	}
	function delete_category(){
		extract($_POST);
		$del = $this->conn->query("UPDATE `category_list` set `delete_flag` = 1 where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Category successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function save_product(){
    $resp = array();
    
    if(isset($_POST['descrption']))
        $_POST['descrption'] = addslashes(htmlspecialchars($_POST['descrption']));

    extract($_POST);
    $data = "";
    foreach($_POST as $k => $v){
        if(!in_array($k, array('id'))){
            if(!empty($data)) $data .= ",";
            $v = $this->conn->real_escape_string($v);
            $data .= " `{$k}`='{$v}' ";
        }
    }
    
    $check = $this->conn->query("SELECT * FROM `product_list` where `brand` = '{$brand}' and `name` = '{$name}' and delete_flag = 0 ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
    if($this->capture_err())
        return $this->capture_err();
    if($check > 0){
        $resp['status'] = 'failed';
        $resp['msg'] = "Product already exists.";
        return json_encode($resp);
        exit;
    }
    
    if(empty($id)){
        $sql = "INSERT INTO `product_list` set {$data} ";
    }else{
        $sql = "UPDATE `product_list` set {$data} where id = '{$id}' ";
    }
    
    $save = $this->conn->query($sql);
    if($save){
        $pid = !empty($id) ? $id : $this->conn->insert_id;
        $resp['pid'] = $pid;
        $resp['status'] = 'success';
        if(empty($id)){
            $resp['msg'] = 'Product has been added successfully';
        }else{
            $resp['msg'] = "Product has been updated successfully.";
        }

        // Simple image upload
        if(isset($_FILES['img']) && $_FILES['img']['error'] == 0){
            
            // Create upload directory if it doesn't exist
            $upload_dir = "uploads/medicines";
            if(!is_dir(base_app . $upload_dir)){
                mkdir(base_app . $upload_dir, 0777, true);
            }
            
            // Get file extension
            $file_ext = strtolower(pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION));
            
            // Check if it's an image
            if(in_array($file_ext, ['jpg', 'jpeg', 'png'])){
                
                // Generate new filename
                $new_filename = uniqid() . '_' . time() . '.' . $file_ext;
                $file_path = $upload_dir . '/' . $new_filename;
                $full_path = base_app . $file_path;
                
                // Simply move the uploaded file
                if(move_uploaded_file($_FILES['img']['tmp_name'], $full_path)){
                    
                    // Update database with image path
                    $image_path = $file_path . '?v=' . time();
                    $this->conn->query("UPDATE product_list SET image_path = '{$image_path}' WHERE id = '{$pid}'");
                    
                } else {
                    $resp['msg'] .= " - Image upload failed";
                }
                
            } else {
                $resp['msg'] .= " - Only JPG, JPEG, PNG files allowed";
            }
        }
        
    }else{
        $resp['status'] = 'failed';
        $resp['err'] = $this->conn->error."[{$sql}]";
    }
    
    if($resp['status'] == 'success' && isset($resp['msg']))
        $this->settings->set_flashdata('success', $resp['msg']);
    return json_encode($resp);
}
	function delete_product(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `product_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Product successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function save_stock(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!empty($data)) $data .=",";
				$v = $this->conn->real_escape_string($v);
				$data .= " `{$k}`='{$v}' ";
			}
		}
		$check = $this->conn->query("SELECT * FROM `stock_list` where `code` = '{$code}' ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
		if($this->capture_err())
			return $this->capture_err();
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Code is already taken.";
			return json_encode($resp);
			exit;
		}
		if(empty($id)){
			$sql = "INSERT INTO `stock_list` set {$data} ";
		}else{
			$sql = "UPDATE `stock_list` set {$data} where id = '{$id}' ";
		}
			$save = $this->conn->query($sql);
		if($save){
			$cid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['cid'] = $cid;
			$resp['status'] = 'success';
			if(empty($id))
				$resp['msg'] = "New Stock successfully saved.";
			else
				$resp['msg'] = " Stock successfully updated.";
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		if($resp['status'] == 'success')
			$this->settings->set_flashdata('success',$resp['msg']);
			return json_encode($resp);
	}
	function delete_stock(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `stock_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Stock successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function add_to_card(){
    extract($_POST);
    
    // Get quantity from POST, default to 1 if not provided
    $quantity = isset($quantity) ? intval($quantity) : 1;
    
    // Validate quantity
    if($quantity <= 0) {
        $quantity = 1;
    }
    
    // Check if product exists and get available stock
    $product_check = $this->conn->query("SELECT p.*, (COALESCE((SELECT SUM(quantity) FROM `stock_list` where product_id = p.id and (expiration IS NULL or date(expiration) > '".date("Y-m-d")."') ), 0) - COALESCE((SELECT SUM(quantity) FROM `order_items` where product_id = p.id), 0)) as `available` FROM `product_list` p WHERE p.id = '{$product_id}' AND p.delete_flag = 0");
    
    if($product_check->num_rows <= 0) {
        $resp['status'] = 'failed';
        $resp['msg'] = 'Product not found.';
        return json_encode($resp);
    }
    
    $product_data = $product_check->fetch_assoc();
    $available_stock = $product_data['available'];
    
    // Check if requested quantity is available
    if($quantity > $available_stock) {
        $resp['status'] = 'failed';
        $resp['msg'] = "Requested quantity ({$quantity}) exceeds available stock ({$available_stock}).";
        return json_encode($resp);
    }
    
    // Check if item already exists in cart
    $check = $this->conn->query("SELECT id, quantity FROM `cart_list` where customer_id = '{$this->settings->userdata('id')}' and product_id = '{$product_id}'");
    
    if($check->num_rows > 0 ){
        // Product exists in cart - update quantity instead of failing
        $row = $check->fetch_assoc();
        $new_quantity = $row['quantity'] + $quantity;
        
        // Check if new total quantity exceeds available stock
        if($new_quantity > $available_stock) {
            $resp['status'] = 'failed';
            $resp['msg'] = "Adding {$quantity} items would exceed available stock. You already have {$row['quantity']} items in your cart.";
        } else {
            $update = $this->conn->query("UPDATE `cart_list` SET quantity = '{$new_quantity}' WHERE id = '{$row['id']}'");
            if($update){
                $resp['status'] = 'success';
                $resp['msg'] = "Cart updated. Total quantity is now {$new_quantity}.";
            }else{
                $resp['status'] = 'failed';
                $resp['error'] = $this->conn->error;
            }
        }
    }else{
        // Product doesn't exist in cart - insert new item
        $insert = $this->conn->query("INSERT INTO `cart_list` (`customer_id`, `product_id`, `quantity`) VALUES ('{$this->settings->userdata('id')}', '{$product_id}', '{$quantity}') ");
        if($insert){
            $resp['status'] = 'success';
            $resp['msg'] = $quantity > 1 ? "{$quantity} items added to cart." : "Product added to cart.";
        }else{
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
        }
    }

    if($resp['status'] == 'success'){
        $this->settings->set_flashdata('success', isset($resp['msg']) ? $resp['msg'] : 'Product has been added to cart.');
    }
    return json_encode($resp);
}

function update_cart(){
    extract($_POST);
    
    // Validate quantity
    $qty = intval($qty);
    if($qty <= 0) {
        $resp['status'] = 'failed';
        $resp['msg'] = 'Quantity must be greater than 0.';
        return json_encode($resp);
    }
    
    // Get cart item details
    $cart_item = $this->conn->query("SELECT product_id FROM `cart_list` WHERE id = '{$cart_id}' AND customer_id = '{$this->settings->userdata('id')}'");
    
    if($cart_item->num_rows <= 0) {
        $resp['status'] = 'failed';
        $resp['msg'] = 'Cart item not found.';
        return json_encode($resp);
    }
    
    $cart_data = $cart_item->fetch_assoc();
    $product_id = $cart_data['product_id'];
    
    // Check available stock
    $product_check = $this->conn->query("SELECT (COALESCE((SELECT SUM(quantity) FROM `stock_list` where product_id = '{$product_id}' and (expiration IS NULL or date(expiration) > '".date("Y-m-d")."') ), 0) - COALESCE((SELECT SUM(quantity) FROM `order_items` where product_id = '{$product_id}'), 0)) as `available` FROM `product_list` WHERE id = '{$product_id}' AND delete_flag = 0");
    
    if($product_check->num_rows > 0) {
        $available_stock = $product_check->fetch_assoc()['available'];
        
        if($qty > $available_stock) {
            $resp['status'] = 'failed';
            $resp['msg'] = "Requested quantity ({$qty}) exceeds available stock ({$available_stock}).";
            return json_encode($resp);
        }
    }
    
    $update = $this->conn->query("UPDATE `cart_list` set quantity = '{$qty}' where id = '{$cart_id}'");
    if($update){
        $resp['status'] = 'success';
    }else{
        $resp['status'] = 'failed';
        $resp['error'] = $this->conn->error;
    }
    if($resp['status'] == 'success'){
        $this->settings->set_flashdata('success', 'Cart Item has been updated.');
    }
    return json_encode($resp);
}	function delete_cart(){
		extract($_POST);
		$delete = $this->conn->query("DELETE FROM `cart_list` where id = '{$id}'");
		if($delete){
			$resp['status'] = 'success';
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		if($resp['status'] == 'success'){
			$this->settings->set_flashdata('success', 'Cart Item has been deleted.');
		}
		return json_encode($resp);
	}
	function place_order(){
		extract($_POST);
		$_POST['delivery_address'] = addslashes(htmlspecialchars($_POST['delivery_address']));
		$customer_id = $this->settings->userdata('id');
		$pref = date("Ymd");
		$code = sprintf("%'.05d", 1);
		while(true){
			$check = $this->conn->query("SELECT id FROM `order_list` where `code` = '{$pref}{$code}'")->num_rows;
			if($check > 0){
				$code = sprintf("%'.05d",abs($code) + 1);
			}else{
				$code = $pref.$code;
				break;
			}
		}
		$insert = $this->conn->query("INSERT INTO `order_list` (`code`, `customer_id`, `delivery_address`, `total_amount`) VALUES ('{$code}', '{$customer_id}', '{$delivery_address}', '{$total_amount}') ");
		if($insert){
			$oid = $this->conn->insert_id;
			$data = "";
			$cart = $this->conn->query("SELECT c.*, p.name as product, p.brand as brand, p.price, cc.name as category, p.image_path, COALESCE((SELECT SUM(quantity) FROM `stock_list` where product_id = p.id and (expiration IS NULL or date(expiration) > '".date("Y-m-d")."') ), 0) as `available` FROM `cart_list` c inner join product_list p on c.product_id = p.id inner join category_list cc on p.category_id = cc.id where customer_id = '{$customer_id}' ");
			while($row = $cart->fetch_assoc()):
				if(!empty($data)) $data .= ", ";
				$data .= "('{$oid}', '{$row['product_id']}', '{$row['quantity']}', '{$row['price']}')";
			endwhile;
			if(!empty($data)){
				$sql = "INSERT INTO order_items (`order_id`, `product_id`, `quantity`, `price`) VALUES {$data}";
				$save = $this->conn->query($sql);
				if($save){
					$resp['status'] = 'success';
					$this->conn->query("DELETE FROM `cart_list` where customer_id = '{$customer_id}'");
				}else{
					$resp['status'] = 'failed';
					$resp['error'] = $this->conn->error;
					$this->conn->query("DELETE FROM `order_list` where id = '{$oid}'");
				}
			}else{
				$resp['status'] = 'success';
			}
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}

		if($resp['status'] == 'success'){
			$this->settings->set_flashdata('success', 'Order has been placed successfully.');
		}
		return json_encode($resp);
	}
	function update_order_status(){
		extract($_POST);
		$update = $this->conn->query("UPDATE `order_list` set `status` = '{$status}' where id = '{$id}'");
		if($update){
			$resp['status'] = 'success';
		}else{
			$resp['failed'] = 'failed';
			$resp['msg'] = $this->conn->error;
		}
		if($resp['status'] == 'success')
		$this->settings->set_flashdata('success', "Order Status has been updated successfully.");

		return json_encode($resp);
	}
	function delete_order(){
		extract($_POST);
		$delete = $this->conn->query("DELETE FROM `order_list` where id = '{$id}'");
		if($delete){
			$resp['status'] = 'success';
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		if($resp['status'] == 'success'){
			$this->settings->set_flashdata('success', 'Order has been deleted successfully.');
		}
		return json_encode($resp);
	}
	function save_inquiry(){
		$_POST['message'] = addslashes(htmlspecialchars($_POST['message']));
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!empty($data)) $data .=",";
				$v = $this->conn->real_escape_string($v);
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `inquiry_list` set {$data} ";
		}else{
			$sql = "UPDATE `inquiry_list` set {$data} where id = '{$id}' ";
		}
			$save = $this->conn->query($sql);
		if($save){
			$cid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['status'] = 'success';
			if(empty($id))
				$this->settings->set_flashdata('success'," Your Inquiry has been sent successfully. Thank you!");
			else
				$this->settings->set_flashdata('success'," Inquiry successfully updated");
			
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}
	function delete_inquiry(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `inquiry_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Inquiry has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function save_branch() {
    extract($_POST);
    
    // Validate required fields
    if(empty(trim($branch_name)) || empty(trim($branch_address))) {
        return json_encode(['status' => 'failed', 'msg' => 'Branch name and address are required']);
    }
    
    // Sanitize inputs
    $branch_name = $this->conn->real_escape_string(trim($branch_name));
    $branch_address = $this->conn->real_escape_string(trim($branch_address));
    $branch_phone = !empty($branch_phone) ? "'" . $this->conn->real_escape_string(trim($branch_phone)) . "'" : "NULL";
    $branch_email = !empty($branch_email) ? "'" . $this->conn->real_escape_string(trim($branch_email)) . "'" : "NULL";
    $manager_name = !empty($manager_name) ? "'" . $this->conn->real_escape_string(trim($manager_name)) . "'" : "NULL";
    $operating_hours = !empty($operating_hours) ? "'" . $this->conn->real_escape_string(trim($operating_hours)) . "'" : "NULL";
    $status = isset($status) ? (int)$status : 1;
    
    try {
        if(isset($id) && !empty($id)) {
            // Update existing branch
            $id = (int)$id;
            
            // Check if branch exists
            $check = $this->conn->query("SELECT id FROM `branch_list` WHERE id = '$id'");
            if($check->num_rows == 0) {
                return json_encode(['status' => 'failed', 'msg' => 'Branch not found']);
            }
            
            // Check for duplicate name (excluding current branch)
            $duplicate_check = $this->conn->query("SELECT id FROM `branch_list` WHERE branch_name = '$branch_name' AND id != '$id'");
            if($duplicate_check->num_rows > 0) {
                return json_encode(['status' => 'failed', 'msg' => 'Branch name already exists']);
            }
            
            $sql = "UPDATE `branch_list` SET 
                        `branch_name` = '$branch_name',
                        `branch_address` = '$branch_address',
                        `branch_phone` = $branch_phone,
                        `branch_email` = $branch_email,
                        `manager_name` = $manager_name,
                        `operating_hours` = $operating_hours,
                        `status` = '$status',
                        `updated_at` = CURRENT_TIMESTAMP
                    WHERE `id` = '$id'";
            
            $action = 'updated';
        } else {
            // Create new branch
            
            // Check for duplicate name
            $duplicate_check = $this->conn->query("SELECT id FROM `branch_list` WHERE branch_name = '$branch_name'");
            if($duplicate_check->num_rows > 0) {
                return json_encode(['status' => 'failed', 'msg' => 'Branch name already exists']);
            }
            
            $sql = "INSERT INTO `branch_list` (
                        `branch_name`, `branch_address`, `branch_phone`, 
                        `branch_email`, `manager_name`, `operating_hours`, `status`
                    ) VALUES (
                        '$branch_name', '$branch_address', $branch_phone, 
                        $branch_email, $manager_name, $operating_hours, '$status'
                    )";
            
            $action = 'created';
        }
        
        $save = $this->conn->query($sql);
        
        if($save) {
            return json_encode(['status' => 'success', 'msg' => "Branch successfully $action!"]);
        } else {
            error_log("Branch save error: " . $this->conn->error);
            return json_encode(['status' => 'failed', 'msg' => 'Failed to save branch']);
        }
        
    } catch (Exception $e) {
        error_log("Branch save exception: " . $e->getMessage());
        return json_encode(['status' => 'failed', 'msg' => 'An error occurred while saving branch']);
    }
}

function get_branch() {
    extract($_POST);
    
    if(empty($id)) {
        return json_encode(['status' => 'failed', 'msg' => 'Branch ID is required']);
    }
    
    $id = (int)$id;
    $query = $this->conn->query("SELECT * FROM `branch_list` WHERE id = '$id'");
    
    if($query->num_rows > 0) {
        $branch = $query->fetch_assoc();
        return json_encode(['status' => 'success', 'data' => $branch]);
    } else {
        return json_encode(['status' => 'failed', 'msg' => 'Branch not found']);
    }
}

function toggle_branch() {
    extract($_POST);
    
    if(empty($id) || !isset($status)) {
        return json_encode(['status' => 'failed', 'msg' => 'Branch ID and status are required']);
    }
    
    $id = (int)$id;
    $status = (int)$status;
    
    // Validate status value
    if($status !== 0 && $status !== 1) {
        return json_encode(['status' => 'failed', 'msg' => 'Invalid status value']);
    }
    
    try {
        // Check if branch exists
        $check = $this->conn->query("SELECT id, branch_name, status FROM `branch_list` WHERE id = '$id'");
        if($check->num_rows == 0) {
            return json_encode(['status' => 'failed', 'msg' => 'Branch not found']);
        }
        
        $branch_data = $check->fetch_assoc();
        
        // If deactivating, check if there are pending orders for this branch
        if($status == 0) {
            $pending_orders = $this->conn->query("SELECT COUNT(*) as count FROM `order_list` WHERE pickup_branch_id = '$id' AND status IN (0, 1, 2, 3)");
            $pending_count = $pending_orders->fetch_assoc()['count'];
            
            if($pending_count > 0) {
                return json_encode(['status' => 'failed', 'msg' => "Cannot deactivate branch. There are $pending_count pending orders for this branch."]);
            }
        }
        
        $update = $this->conn->query("UPDATE `branch_list` SET `status` = '$status', `updated_at` = CURRENT_TIMESTAMP WHERE `id` = '$id'");
        
        if($update) {
            $action = $status == 1 ? 'activated' : 'deactivated';
            return json_encode(['status' => 'success', 'msg' => "Branch successfully $action!"]);
        } else {
            error_log("Branch toggle error: " . $this->conn->error);
            return json_encode(['status' => 'failed', 'msg' => 'Failed to update branch status']);
        }
        
    } catch (Exception $e) {
        error_log("Branch toggle exception: " . $e->getMessage());
        return json_encode(['status' => 'failed', 'msg' => 'An error occurred while updating branch status']);
    }
}

function delete_branch() {
    extract($_POST);
    
    if(empty($id)) {
        return json_encode(['status' => 'failed', 'msg' => 'Branch ID is required']);
    }
    
    $id = (int)$id;
    
    try {
        // Check if branch exists
        $check = $this->conn->query("SELECT id, branch_name FROM `branch_list` WHERE id = '$id'");
        if($check->num_rows == 0) {
            return json_encode(['status' => 'failed', 'msg' => 'Branch not found']);
        }
        
        $branch_data = $check->fetch_assoc();
        
        // Check if there are any orders associated with this branch
        $orders_check = $this->conn->query("SELECT COUNT(*) as count FROM `order_list` WHERE pickup_branch_id = '$id'");
        $orders_count = $orders_check->fetch_assoc()['count'];
        
        if($orders_count > 0) {
            return json_encode(['status' => 'failed', 'msg' => "Cannot delete branch. There are $orders_count orders associated with this branch. Consider deactivating it instead."]);
        }
        
        $delete = $this->conn->query("DELETE FROM `branch_list` WHERE `id` = '$id'");
        
        if($delete) {
            return json_encode(['status' => 'success', 'msg' => 'Branch successfully deleted!']);
        } else {
            error_log("Branch delete error: " . $this->conn->error);
            return json_encode(['status' => 'failed', 'msg' => 'Failed to delete branch']);
        }
        
    } catch (Exception $e) {
        error_log("Branch delete exception: " . $e->getMessage());
        return json_encode(['status' => 'failed', 'msg' => 'An error occurred while deleting branch']);
    }
}

// Additional helper method to get all active branches
function get_active_branches() {
    $branches = [];
    $query = $this->conn->query("SELECT * FROM `branch_list` WHERE status = 1 ORDER BY branch_name ASC");
    
    while($row = $query->fetch_assoc()) {
        $branches[] = $row;
    }
    
    return $branches;
}

// Method to get branch statistics
function get_branch_stats() {
    extract($_POST);
    
    if(empty($branch_id)) {
        return json_encode(['status' => 'failed', 'msg' => 'Branch ID is required']);
    }
    
    $branch_id = (int)$branch_id;
    
    try {
        // Get branch info
        $branch_query = $this->conn->query("SELECT * FROM `branch_list` WHERE id = '$branch_id'");
        if($branch_query->num_rows == 0) {
            return json_encode(['status' => 'failed', 'msg' => 'Branch not found']);
        }
        
        $branch = $branch_query->fetch_assoc();
        
        // Get order statistics
        $stats = [];
        
        // Total orders
        $total_orders = $this->conn->query("SELECT COUNT(*) as count FROM `order_list` WHERE pickup_branch_id = '$branch_id'");
        $stats['total_orders'] = $total_orders->fetch_assoc()['count'];
        
        // Pending orders
        $pending_orders = $this->conn->query("SELECT COUNT(*) as count FROM `order_list` WHERE pickup_branch_id = '$branch_id' AND status IN (0, 1, 2, 3)");
        $stats['pending_orders'] = $pending_orders->fetch_assoc()['count'];
        
        // Completed orders
        $completed_orders = $this->conn->query("SELECT COUNT(*) as count FROM `order_list` WHERE pickup_branch_id = '$branch_id' AND status = 4");
        $stats['completed_orders'] = $completed_orders->fetch_assoc()['count'];
        
        // Total revenue
        $revenue = $this->conn->query("SELECT COALESCE(SUM(total_amount), 0) as total FROM `order_list` WHERE pickup_branch_id = '$branch_id' AND status = 4");
        $stats['total_revenue'] = $revenue->fetch_assoc()['total'];
        
        // Monthly orders (current month)
        $monthly_orders = $this->conn->query("SELECT COUNT(*) as count FROM `order_list` WHERE pickup_branch_id = '$branch_id' AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())");
        $stats['monthly_orders'] = $monthly_orders->fetch_assoc()['count'];
        
        $result = [
            'branch' => $branch,
            'stats' => $stats
        ];
        
        return json_encode(['status' => 'success', 'data' => $result]);
        
    } catch (Exception $e) {
        error_log("Branch stats exception: " . $e->getMessage());
        return json_encode(['status' => 'failed', 'msg' => 'An error occurred while fetching branch statistics']);
    }
}

// Method to get orders for a specific branch
function get_branch_orders() {
    extract($_POST);
    
    if(empty($branch_id)) {
        return json_encode(['status' => 'failed', 'msg' => 'Branch ID is required']);
    }
    
    $branch_id = (int)$branch_id;
    $page = isset($page) ? (int)$page : 1;
    $limit = isset($limit) ? (int)$limit : 10;
    $offset = ($page - 1) * $limit;
    
    try {
        // Get total count
        $count_query = $this->conn->query("SELECT COUNT(*) as total FROM `order_list` WHERE pickup_branch_id = '$branch_id'");
        $total_orders = $count_query->fetch_assoc()['total'];
        
        // Get orders with pagination
        $orders_query = $this->conn->query("
            SELECT o.*, u.firstname, u.lastname, u.email,
                   COUNT(oi.id) as item_count
            FROM `order_list` o 
            INNER JOIN `users` u ON o.customer_id = u.id 
            LEFT JOIN `order_items` oi ON o.id = oi.order_id
            WHERE o.pickup_branch_id = '$branch_id'
            GROUP BY o.id
            ORDER BY o.created_at DESC
            LIMIT $limit OFFSET $offset
        ");
        
        $orders = [];
        while($order = $orders_query->fetch_assoc()) {
            $orders[] = $order;
        }
        
        $result = [
            'orders' => $orders,
            'total' => $total_orders,
            'page' => $page,
            'limit' => $limit,
            'total_pages' => ceil($total_orders / $limit)
        ];
        
        return json_encode(['status' => 'success', 'data' => $result]);
        
    } catch (Exception $e) {
        error_log("Branch orders exception: " . $e->getMessage());
        return json_encode(['status' => 'failed', 'msg' => 'An error occurred while fetching branch orders']);
    }
}
}


$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
	case 'delete_img':
		echo $Master->delete_img();
	break;
	case 'save_category':
		echo $Master->save_category();
	break;
	case 'delete_category':
		echo $Master->delete_category();
	break;
	case 'save_product':
		echo $Master->save_product();
	break;
	case 'delete_product':
		echo $Master->delete_product();
	break;
	case 'save_stock':
		echo $Master->save_stock();
	break;
	case 'delete_stock':
		echo $Master->delete_stock();
	break;
	case 'add_to_card':
		echo $Master->add_to_card();
	break;
	case 'update_cart':
		echo $Master->update_cart();
	break;
	case 'delete_cart':
		echo $Master->delete_cart();
	break;
	case 'place_order':
		echo $Master->place_order();
	break;
	case 'delete_order':
		echo $Master->delete_order();
	break;
	case 'update_order_status':
		echo $Master->update_order_status();
	break;
	case 'save_inquiry':
		echo $Master->save_inquiry();
	break;
	case 'delete_inquiry':
		echo $Master->delete_inquiry();
	break;
	default:
		// echo $sysset->index();
		break;
}
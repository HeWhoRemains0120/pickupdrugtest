<?php
require_once('../config.php');

class Invoices extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}

	// Generate unique invoice code
	function generate_invoice_code() {
		$year = date('Y');
		$month = date('m');
		
		// Get the last invoice for this month
		$qry = $this->conn->query("SELECT invoice_code FROM invoice_list 
			WHERE invoice_code LIKE 'INV-$year-$month-%' 
			ORDER BY id DESC LIMIT 1");
		
		if($qry->num_rows > 0) {
			$row = $qry->fetch_assoc();
			$last_code = $row['invoice_code'];
			$number = intval(substr($last_code, -4)) + 1;
		} else {
			$number = 1;
		}
		
		$invoice_code = 'INV-' . $year . '-' . $month . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
		
		return $this->conn->query("SELECT 1") ? json_encode(['status' => 'success', 'code' => $invoice_code]) : json_encode(['status' => 'failed']);
	}

	// Get order items - Fixed and enhanced version
	// Get order items - Diagnostic version to understand database schema
	function get_order_items() {
		// Handle both GET and POST requests
		$order_id = isset($_POST['order_id']) ? $_POST['order_id'] : (isset($_GET['order_id']) ? $_GET['order_id'] : null);
		
		if(!$order_id) {
			return json_encode(array('status' => 'failed', 'msg' => 'Order ID is required'));
		}
		
		$items = array();
		$debug_info = array();
		
		// First, let's check what tables exist and get order details
		$order_qry = $this->conn->query("SELECT * FROM order_list WHERE id = '$order_id'");
		if($order_qry->num_rows == 0) {
			return json_encode(array('status' => 'failed', 'msg' => 'Order not found'));
		}
		
		$order = $order_qry->fetch_assoc();
		$debug_info['order_data'] = $order;
		
		// Check what tables exist
		$tables_qry = $this->conn->query("SHOW TABLES");
		$tables = array();
		while($row = $tables_qry->fetch_array()) {
			$tables[] = $row[0];
		}
		$debug_info['available_tables'] = $tables;
		
		// Get column information for key tables
		if(in_array('product_list', $tables)) {
			$product_columns = array();
			$columns_qry = $this->conn->query("SHOW COLUMNS FROM product_list");
			while($col = $columns_qry->fetch_assoc()) {
				$product_columns[$col['Field']] = $col['Type'];
			}
			$debug_info['product_list_columns'] = $product_columns;
		}
		
		if(in_array('order_items', $tables)) {
			$order_items_columns = array();
			$columns_qry = $this->conn->query("SHOW COLUMNS FROM order_items");
			while($col = $columns_qry->fetch_assoc()) {
				$order_items_columns[$col['Field']] = $col['Type'];
			}
			$debug_info['order_items_columns'] = $order_items_columns;
		}
		
		if(in_array('cart_list', $tables)) {
			$cart_list_columns = array();
			$columns_qry = $this->conn->query("SHOW COLUMNS FROM cart_list");
			while($col = $columns_qry->fetch_assoc()) {
				$cart_list_columns[$col['Field']] = $col['Type'];
			}
			$debug_info['cart_list_columns'] = $cart_list_columns;
		}
		
		// Try to get order items without complex joins first
		if(in_array('order_items', $tables)) {
			$simple_qry = $this->conn->query("SELECT * FROM order_items WHERE order_id = '$order_id'");
			if($simple_qry && $simple_qry->num_rows > 0) {
				$debug_info['order_items_raw'] = array();
				while($row = $simple_qry->fetch_assoc()) {
					$debug_info['order_items_raw'][] = $row;
					
					// Try to build item data from available columns
					$item = array(
						'product_name' => $row['product_name'] ?? $row['item_name'] ?? $row['name'] ?? 'Product',
						'description' => $row['description'] ?? '',
						'quantity' => floatval($row['quantity'] ?? $row['qty'] ?? 1),
						'price' => floatval($row['price'] ?? $row['unit_price'] ?? $row['amount'] ?? 0),
						'total' => floatval($row['total'] ?? $row['total_amount'] ?? 0)
					);
					
					// Calculate total if not provided
					if($item['total'] == 0) {
						$item['total'] = $item['quantity'] * $item['price'];
					}
					
					$items[] = $item;
				}
			}
		}
		
		// If no order_items, try cart_list
		if(empty($items) && in_array('cart_list', $tables)) {
			$simple_qry = $this->conn->query("SELECT * FROM cart_list WHERE order_id = '$order_id'");
			if($simple_qry && $simple_qry->num_rows > 0) {
				$debug_info['cart_list_raw'] = array();
				while($row = $simple_qry->fetch_assoc()) {
					$debug_info['cart_list_raw'][] = $row;
					
					$item = array(
						'product_name' => $row['product_name'] ?? $row['item_name'] ?? $row['name'] ?? 'Product',
						'description' => $row['description'] ?? '',
						'quantity' => floatval($row['quantity'] ?? $row['qty'] ?? 1),
						'price' => floatval($row['price'] ?? $row['unit_price'] ?? $row['amount'] ?? 0),
						'total' => floatval($row['total'] ?? $row['total_amount'] ?? 0)
					);
					
					if($item['total'] == 0) {
						$item['total'] = $item['quantity'] * $item['price'];
					}
					
					$items[] = $item;
				}
			}
		}
		
		// If we have product_id references, try to get product names
		if(!empty($items) && isset($debug_info['product_list_columns'])) {
			// Determine product name column
			$product_name_col = null;
			if(isset($debug_info['product_list_columns']['name'])) {
				$product_name_col = 'name';
			} elseif(isset($debug_info['product_list_columns']['product_name'])) {
				$product_name_col = 'product_name';
			} elseif(isset($debug_info['product_list_columns']['title'])) {
				$product_name_col = 'title';
			}
			
			$debug_info['selected_product_name_col'] = $product_name_col;
			
			// Update items with product information if we have product_id
			foreach($items as $key => $item) {
				$raw_data = $debug_info['order_items_raw'][$key] ?? $debug_info['cart_list_raw'][$key] ?? null;
				if($raw_data && isset($raw_data['product_id']) && $product_name_col) {
					$product_qry = $this->conn->query("SELECT * FROM product_list WHERE id = '{$raw_data['product_id']}'");
					if($product_qry && $product_qry->num_rows > 0) {
						$product = $product_qry->fetch_assoc();
						$items[$key]['product_name'] = $product[$product_name_col] ?? $items[$key]['product_name'];
						$items[$key]['description'] = $product['description'] ?? $items[$key]['description'];
					}
				}
			}
		}
		
		// Fallback: Check if order has JSON items
		if(empty($items) && isset($order['items']) && !empty($order['items'])) {
			$order_items = json_decode($order['items'], true);
			if(is_array($order_items)) {
				$debug_info['json_items'] = $order_items;
				foreach($order_items as $item) {
					$items[] = array(
						'product_name' => $item['name'] ?? $item['product_name'] ?? 'Product',
						'description' => $item['description'] ?? '',
						'quantity' => floatval($item['quantity'] ?? $item['qty'] ?? 1),
						'price' => floatval($item['price'] ?? $item['unit_price'] ?? 0),
						'total' => floatval($item['total'] ?? ($item['quantity'] * $item['price']) ?? 0)
					);
				}
			}
		}
		
		// Final fallback: Create generic item from order
		if(empty($items)) {
			$items[] = array(
				'product_name' => 'Order Item',
				'description' => 'Items from Order #' . ($order['code'] ?? $order['id']),
				'quantity' => 1,
				'price' => floatval($order['total_amount'] ?? $order['amount'] ?? 0),
				'total' => floatval($order['total_amount'] ?? $order['amount'] ?? 0)
			);
			$debug_info['fallback_used'] = true;
		}
		
		if(!empty($items)) {
			return json_encode(array(
				'status' => 'success', 
				'items' => $items,
				'debug_info' => $debug_info
			));
		} else {
			return json_encode(array(
				'status' => 'failed', 
				'msg' => 'No order items found',
				'debug_info' => $debug_info
			));
		}
	}

	// Create invoice from order
	function create_from_order() {
		extract($_POST);
		$data = "";
		
		// Generate invoice code if not provided
		if(empty($invoice_code)) {
			$year = date('Y');
			$month = date('m');
			$qry = $this->conn->query("SELECT invoice_code FROM invoice_list 
				WHERE invoice_code LIKE 'INV-$year-$month-%' 
				ORDER BY id DESC LIMIT 1");
			
			if($qry->num_rows > 0) {
				$row = $qry->fetch_assoc();
				$last_code = $row['invoice_code'];
				$number = intval(substr($last_code, -4)) + 1;
			} else {
				$number = 1;
			}
			$invoice_code = 'INV-' . $year . '-' . $month . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
		}
		
		foreach($_POST as $k => $v){
			if(!in_array($k,array('id'))){
				if(!empty($data)) $data .= ",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		
		$data .= ", `created_by`='{$_settings->userdata('id')}' ";
		
		// Check if invoice code already exists
		$check = $this->conn->query("SELECT * FROM `invoice_list` WHERE `invoice_code` = '$invoice_code' ")->num_rows;
		if($check > 0){
			return json_encode(['status'=>'failed','msg'=>'Invoice Code already exists.']);
			exit;
		}
		
		$sql = "INSERT INTO `invoice_list` SET {$data}";
		$save = $this->conn->query($sql);
		
		if($save){
			$invoice_id = $this->conn->insert_id;
			$resp['status'] = 'success';
			$resp['msg'] = 'Invoice created successfully!';
			$resp['id'] = $invoice_id;
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = 'An error occurred while saving the invoice.';
			$resp['sql'] = $sql;
		}
		return json_encode($resp);
	}

	// Save invoice (create or update)
	// Save invoice (create or update) - Robust version with comprehensive error handling
	function save_invoice() {
		try {
			// Check if required globals exist
			if (!isset($_settings)) {
				global $_settings;
			}
			
			// Initialize variables
			$id = isset($_POST['id']) ? $_POST['id'] : '';
			$is_update = !empty($id);
			
			// Validate required fields
			if (!isset($_POST['customer_id']) || empty($_POST['customer_id'])) {
				return json_encode(['status' => 'failed', 'msg' => 'Customer is required.']);
			}
			
			if (!isset($_POST['invoice_date']) || empty($_POST['invoice_date'])) {
				return json_encode(['status' => 'failed', 'msg' => 'Invoice date is required.']);
			}
			
			if (!isset($_POST['due_date']) || empty($_POST['due_date'])) {
				return json_encode(['status' => 'failed', 'msg' => 'Due date is required.']);
			}
			
			// Generate invoice code if creating new and not provided
			$invoice_code = isset($_POST['invoice_code']) ? trim($_POST['invoice_code']) : '';
			if (!$is_update && empty($invoice_code)) {
				$year = date('Y');
				$month = date('m');
				
				$qry = $this->conn->query("SELECT invoice_code FROM invoice_list 
					WHERE invoice_code LIKE 'INV-$year-$month-%' 
					ORDER BY id DESC LIMIT 1");
				
				if ($qry && $qry->num_rows > 0) {
					$row = $qry->fetch_assoc();
					$last_code = $row['invoice_code'];
					$number = intval(substr($last_code, -4)) + 1;
				} else {
					$number = 1;
				}
				$invoice_code = 'INV-' . $year . '-' . $month . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
			}
			
			// Check for duplicate invoice code (only for new invoices or if code changed)
			if (!empty($invoice_code)) {
				$check_sql = "SELECT id FROM invoice_list WHERE invoice_code = '" . $this->conn->real_escape_string($invoice_code) . "'";
				if ($is_update) {
					$check_sql .= " AND id != '" . $this->conn->real_escape_string($id) . "'";
				}
				$check = $this->conn->query($check_sql);
				if ($check && $check->num_rows > 0) {
					return json_encode(['status' => 'failed', 'msg' => 'Invoice code already exists.']);
				}
			}
			
			// Prepare main invoice data
			$invoice_data = array();
			
			// Required fields
			$invoice_data['customer_id'] = $this->conn->real_escape_string($_POST['customer_id']);
			$invoice_data['invoice_date'] = $this->conn->real_escape_string($_POST['invoice_date']);
			$invoice_data['due_date'] = $this->conn->real_escape_string($_POST['due_date']);
			
			// Optional fields with defaults
			$invoice_data['order_id'] = isset($_POST['order_id']) && !empty($_POST['order_id']) ? $this->conn->real_escape_string($_POST['order_id']) : 'NULL';
			$invoice_data['subtotal'] = isset($_POST['subtotal']) ? floatval($_POST['subtotal']) : 0;
			$invoice_data['tax_rate'] = isset($_POST['tax_rate']) ? floatval($_POST['tax_rate']) : 0;
			$invoice_data['tax_amount'] = isset($_POST['tax_amount']) ? floatval($_POST['tax_amount']) : 0;
			$invoice_data['discount_amount'] = isset($_POST['discount_amount']) ? floatval($_POST['discount_amount']) : 0;
			$invoice_data['total_amount'] = isset($_POST['total_amount']) ? floatval($_POST['total_amount']) : 0;
			$invoice_data['paid_amount'] = isset($_POST['paid_amount']) ? floatval($_POST['paid_amount']) : 0;
			$invoice_data['balance_amount'] = isset($_POST['balance_amount']) ? floatval($_POST['balance_amount']) : 0;
			$invoice_data['status'] = isset($_POST['status']) ? intval($_POST['status']) : 0;
			$invoice_data['payment_method'] = isset($_POST['payment_method']) ? $this->conn->real_escape_string($_POST['payment_method']) : '';
			$invoice_data['notes'] = isset($_POST['notes']) ? $this->conn->real_escape_string($_POST['notes']) : '';
			
			// Add invoice code
			if (!empty($invoice_code)) {
				$invoice_data['invoice_code'] = $this->conn->real_escape_string($invoice_code);
			}
			
			// Build SQL query
			if ($is_update) {
				// Update existing invoice
				$sql_parts = array();
				foreach ($invoice_data as $key => $value) {
					if ($key === 'order_id' && $value === 'NULL') {
						$sql_parts[] = "`{$key}` = NULL";
					} else {
						$sql_parts[] = "`{$key}` = '{$value}'";
					}
				}
				$sql_parts[] = "`date_updated` = NOW()";
				
				$sql = "UPDATE `invoice_list` SET " . implode(', ', $sql_parts) . " WHERE `id` = '" . $this->conn->real_escape_string($id) . "'";
			} else {
				// Create new invoice
				$invoice_data['created_by'] = $_settings->userdata('id');
				$invoice_data['date_created'] = date('Y-m-d H:i:s');
				
				$columns = array();
				$values = array();
				foreach ($invoice_data as $key => $value) {
					$columns[] = "`{$key}`";
					if ($key === 'order_id' && $value === 'NULL') {
						$values[] = "NULL";
					} else {
						$values[] = "'{$value}'";
					}
				}
				
				$sql = "INSERT INTO `invoice_list` (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $values) . ")";
			}
			
			// Execute main query
			$save = $this->conn->query($sql);
			
			if (!$save) {
				return json_encode([
					'status' => 'failed', 
					'msg' => 'Database error: ' . $this->conn->error,
					'sql' => $sql
				]);
			}
			
			// Get invoice ID
			$invoice_id = $is_update ? $id : $this->conn->insert_id;
			
			// Handle invoice items
			$items_saved = 0;
			$items_error = '';
			
			// Delete existing items if updating
			if ($is_update) {
				$this->conn->query("DELETE FROM `invoice_items` WHERE `invoice_id` = '" . $this->conn->real_escape_string($invoice_id) . "'");
			}
			
			// Save new items
			if (isset($_POST['item_name']) && is_array($_POST['item_name'])) {
				foreach ($_POST['item_name'] as $k => $item_name) {
					$item_name = trim($item_name);
					if (empty($item_name)) continue;
					
					$item_description = isset($_POST['item_description'][$k]) ? trim($_POST['item_description'][$k]) : '';
					$item_qty = isset($_POST['item_qty'][$k]) ? floatval($_POST['item_qty'][$k]) : 1;
					$item_price = isset($_POST['item_price'][$k]) ? floatval($_POST['item_price'][$k]) : 0;
					$item_total = isset($_POST['item_total'][$k]) ? floatval($_POST['item_total'][$k]) : ($item_qty * $item_price);
					
					$item_sql = "INSERT INTO `invoice_items` SET 
						`invoice_id` = '" . $this->conn->real_escape_string($invoice_id) . "',
						`item_name` = '" . $this->conn->real_escape_string($item_name) . "',
						`description` = '" . $this->conn->real_escape_string($item_description) . "',
						`quantity` = '$item_qty',
						`unit_price` = '$item_price',
						`total_price` = '$item_total'";
					
					if ($this->conn->query($item_sql)) {
						$items_saved++;
					} else {
						$items_error = $this->conn->error;
					}
				}
			}
			
			// Prepare response
			$response = [
				'status' => 'success',
				'msg' => $is_update ? 'Invoice updated successfully!' : 'Invoice created successfully!',
				'id' => $invoice_id,
				'invoice_code' => $invoice_code,
				'items_saved' => $items_saved
			];
			
			if (!empty($items_error)) {
				$response['items_warning'] = 'Some items may not have been saved: ' . $items_error;
			}
			
			return json_encode($response);
			
		} catch (Exception $e) {
			return json_encode([
				'status' => 'failed',
				'msg' => 'System error: ' . $e->getMessage()
			]);
		}
	}

	// Delete invoice
	function delete_invoice(){
		extract($_POST);
		
		// Delete invoice items first
		$del_items = $this->conn->query("DELETE FROM `invoice_items` WHERE `invoice_id` = '{$id}'");
		
		// Delete invoice payments
		$del_payments = $this->conn->query("DELETE FROM `invoice_payments` WHERE `invoice_id` = '{$id}'");
		
		// Delete main invoice
		$del = $this->conn->query("DELETE FROM `invoice_list` WHERE id = '{$id}'");
		
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success','Invoice successfully deleted.');
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}

	// Record payment
	function record_payment() {
		extract($_POST);
		
		// Insert payment record
		$payment_data = "";
		$payment_data .= "`invoice_id`='$invoice_id'";
		$payment_data .= ", `payment_date`='$payment_date'";
		$payment_data .= ", `amount`='$amount'";
		$payment_data .= ", `payment_method`='".$this->conn->real_escape_string($payment_method)."'";
		$payment_data .= ", `reference_number`='".$this->conn->real_escape_string($reference_number)."'";
		$payment_data .= ", `notes`='".$this->conn->real_escape_string($notes)."'";
		$payment_data .= ", `created_by`='{$_settings->userdata('id')}'";
		
		$save_payment = $this->conn->query("INSERT INTO `invoice_payments` SET {$payment_data}");
		
		if($save_payment){
			// Update invoice paid amount and balance
			$invoice = $this->conn->query("SELECT * FROM `invoice_list` WHERE `id` = '$invoice_id'")->fetch_assoc();
			$new_paid_amount = $invoice['paid_amount'] + $amount;
			$new_balance = $invoice['total_amount'] - $new_paid_amount;
			
			// Determine new status
			$new_status = 0; // Pending
			if($new_balance <= 0) {
				$new_status = 1; // Paid
			} elseif($new_paid_amount > 0) {
				$new_status = 2; // Partially Paid
			}
			
			$update = $this->conn->query("UPDATE `invoice_list` SET 
				`paid_amount` = '$new_paid_amount', 
				`balance_amount` = '$new_balance', 
				`status` = '$new_status' 
				WHERE `id` = '$invoice_id'");
			
			if($update){
				$resp['status'] = 'success';
				$resp['msg'] = 'Payment recorded successfully!';
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = 'Payment recorded but failed to update invoice.';
			}
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = 'An error occurred while recording payment.';
		}
		return json_encode($resp);
	}

	// Get invoice details
	function get_invoice() {
		extract($_POST);
		$qry = $this->conn->query("SELECT i.*, 
			CONCAT(c.firstname,' ', COALESCE(CONCAT(c.middlename,' '), ''), c.lastname) as customer_name,
			c.email as customer_email, c.contact as customer_contact,
			o.code as order_code
			FROM `invoice_list` i 
			LEFT JOIN `customer_list` c ON i.customer_id = c.id 
			LEFT JOIN `order_list` o ON i.order_id = o.id
			WHERE i.id = '$id'");
		
		if($qry->num_rows > 0){
			$invoice = $qry->fetch_assoc();
			
			// Get invoice items
			$items_qry = $this->conn->query("SELECT * FROM `invoice_items` WHERE `invoice_id` = '$id'");
			$items = [];
			while($item = $items_qry->fetch_assoc()){
				$items[] = $item;
			}
			$invoice['items'] = $items;
			
			// Get payment history
			$payments_qry = $this->conn->query("SELECT p.*, 
				CONCAT(u.firstname,' ', u.lastname) as recorded_by_name
				FROM `invoice_payments` p 
				LEFT JOIN `users` u ON p.created_by = u.id
				WHERE p.invoice_id = '$id' 
				ORDER BY p.payment_date DESC");
			$payments = [];
			while($payment = $payments_qry->fetch_assoc()){
				$payments[] = $payment;
			}
			$invoice['payments'] = $payments;
			
			$resp['status'] = 'success';
			$resp['data'] = $invoice;
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = 'Invoice not found.';
		}
		return json_encode($resp);
	}

	// Update invoice status
	function update_status() {
		extract($_POST);
		
		$update = $this->conn->query("UPDATE `invoice_list` SET `status` = '$status' WHERE `id` = '$id'");
		
		if($update){
			$resp['status'] = 'success';
			$resp['msg'] = 'Status updated successfully!';
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = 'An error occurred while updating status.';
		}
		return json_encode($resp);
	}

	// Get overdue invoices (for automated status update)
	function update_overdue_invoices() {
		$today = date('Y-m-d');
		$update = $this->conn->query("UPDATE `invoice_list` SET `status` = 3 
			WHERE `due_date` < '$today' AND `status` IN (0, 2) AND `balance_amount` > 0");
		
		if($update){
			$affected = $this->conn->affected_rows;
			$resp['status'] = 'success';
			$resp['msg'] = "$affected invoices marked as overdue.";
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = 'An error occurred while updating overdue invoices.';
		}
		return json_encode($resp);
	}

	// Generate invoice report
	function generate_report() {
		extract($_POST);
		
		$where = "WHERE 1=1";
		if(!empty($date_from)) {
			$where .= " AND i.invoice_date >= '$date_from'";
		}
		if(!empty($date_to)) {
			$where .= " AND i.invoice_date <= '$date_to'";
		}
		if(!empty($status) && $status !== 'all') {
			$where .= " AND i.status = '$status'";
		}
		if(!empty($customer_id)) {
			$where .= " AND i.customer_id = '$customer_id'";
		}
		
		$qry = $this->conn->query("SELECT i.*, 
			CONCAT(c.firstname,' ', COALESCE(CONCAT(c.middlename,' '), ''), c.lastname) as customer_name,
			c.email as customer_email,
			o.code as order_code
			FROM `invoice_list` i 
			LEFT JOIN `customer_list` c ON i.customer_id = c.id 
			LEFT JOIN `order_list` o ON i.order_id = o.id
			$where
			ORDER BY i.invoice_date DESC");
		
		$invoices = [];
		$total_amount = 0;
		$total_paid = 0;
		$total_balance = 0;
		
		while($row = $qry->fetch_assoc()){
			$invoices[] = $row;
			$total_amount += $row['total_amount'];
			$total_paid += $row['paid_amount'];
			$total_balance += $row['balance_amount'];
		}
		
		$resp['status'] = 'success';
		$resp['data'] = $invoices;
		$resp['summary'] = [
			'total_invoices' => count($invoices),
			'total_amount' => $total_amount,
			'total_paid' => $total_paid,
			'total_balance' => $total_balance
		];
		
		return json_encode($resp);
	}

	// Bulk operations
	function bulk_action() {
		extract($_POST);
		$ids = implode(',', $invoice_ids);
		
		switch($action) {
			case 'delete':
				// Delete invoice items first
				$this->conn->query("DELETE FROM `invoice_items` WHERE `invoice_id` IN ($ids)");
				// Delete invoice payments
				$this->conn->query("DELETE FROM `invoice_payments` WHERE `invoice_id` IN ($ids)");
				// Delete invoices
				$delete = $this->conn->query("DELETE FROM `invoice_list` WHERE `id` IN ($ids)");
				
				if($delete) {
					$resp['status'] = 'success';
					$resp['msg'] = 'Selected invoices deleted successfully!';
				} else {
					$resp['status'] = 'failed';
					$resp['msg'] = 'An error occurred while deleting invoices.';
				}
				break;
				
			case 'mark_paid':
				$update = $this->conn->query("UPDATE `invoice_list` SET 
					`status` = 1, 
					`paid_amount` = `total_amount`, 
					`balance_amount` = 0 
					WHERE `id` IN ($ids)");
				
				if($update) {
					$resp['status'] = 'success';
					$resp['msg'] = 'Selected invoices marked as paid!';
				} else {
					$resp['status'] = 'failed';
					$resp['msg'] = 'An error occurred while updating invoices.';
				}
				break;
				
			case 'mark_cancelled':
				$update = $this->conn->query("UPDATE `invoice_list` SET `status` = 4 WHERE `id` IN ($ids)");
				
				if($update) {
					$resp['status'] = 'success';
					$resp['msg'] = 'Selected invoices marked as cancelled!';
				} else {
					$resp['status'] = 'failed';
					$resp['msg'] = 'An error occurred while updating invoices.';
				}
				break;
				
			default:
				$resp['status'] = 'failed';
				$resp['msg'] = 'Invalid action specified.';
		}
		
		return json_encode($resp);
	}
}

$Invoices = new Invoices();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();

switch ($action) {
	case 'generate_invoice_code':
		echo $Invoices->generate_invoice_code();
		break;
	case 'create_from_order':
		echo $Invoices->create_from_order();
		break;
	case 'save_invoice':
		echo $Invoices->save_invoice();
		break;
	case 'get_order_items':
		echo $Invoices->get_order_items();
		break;
	case 'delete_invoice':
		echo $Invoices->delete_invoice();
		break;
	case 'record_payment':
		echo $Invoices->record_payment();
		break;
	case 'get_invoice':
		echo $Invoices->get_invoice();
		break;
	case 'update_status':
		echo $Invoices->update_status();
		break;
	case 'update_overdue_invoices':
		echo $Invoices->update_overdue_invoices();
		break;
	case 'generate_report':
		echo $Invoices->generate_report();
		break;
	case 'bulk_action':
		echo $Invoices->bulk_action();
		break;
	default:
		break;
}
?>
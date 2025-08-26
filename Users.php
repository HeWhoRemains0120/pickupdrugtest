<?php
require_once('../config.php');
Class Users extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	public function save_users(){
		extract($_POST);
		
		// Check for duplicate username
		$username = $this->conn->real_escape_string($username);
		$username_check = $this->conn->query("SELECT * FROM `users` WHERE `username` = '{$username}'" . (isset($id) && !empty($id) ? " AND id != '{$id}'" : ""));
		if($username_check->num_rows > 0){
			return 2; // Username already exists
		}
		
		// Handle password
		if(empty($_POST['password'])){
			unset($_POST['password']);
		} else {
			$_POST['password'] = md5($_POST['password']);
		}
		
		// Prepare data for database
		$data = '';
		foreach($_POST as $k => $v){
			if(!in_array($k,array('id'))){
				$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=" , ";
				$data .= " `{$k}` = '{$v}' ";
			}
		}
		
		// Insert or Update user first
		if(empty($id)){
			$qry = $this->conn->query("INSERT INTO users SET {$data}");
			if($qry){
				$id = $this->conn->insert_id;
				$this->settings->set_flashdata('success','User Details successfully saved.');
			} else {
				return "Database Error: " . $this->conn->error;
			}
		} else {
			$qry = $this->conn->query("UPDATE users SET $data WHERE id = {$id}");
			if($qry){
				$this->settings->set_flashdata('success','User Details successfully updated.');
			} else {
				return "Database Error: " . $this->conn->error;
			}
		}
		
		// Update session data if current user
		foreach($_POST as $k => $v){
			if($k != 'id' && $this->settings->userdata('id') == $id){
				$this->settings->set_userdata($k,$v);
			}
		}
		
		// Handle image upload after successful database operation
		if(!empty($_FILES['img']['tmp_name'])){
			$upload_result = $this->processAvatarUpload($id);
			if($upload_result !== true){
				// Image upload failed but user was saved, so we still return success but with a warning
				$this->settings->set_flashdata('warning', 'User saved but avatar upload failed: ' . $upload_result);
			}
		}
		
		return 1;
	}
	
	private function processAvatarUpload($id){
		// Check if uploads directory exists
		if(!is_dir(base_app."uploads/avatars")){
			if(!mkdir(base_app."uploads/avatars", 0755, true)){
				return "Failed to create uploads directory";
			}
		}
		
		// Validate file upload
		if($_FILES['img']['error'] !== UPLOAD_ERR_OK){
			return "File upload error: " . $_FILES['img']['error'];
		}
		
		// Check file size (limit to 5MB)
		if($_FILES['img']['size'] > 5 * 1024 * 1024){
			return "File too large. Maximum size is 5MB";
		}
		
		// Get file info
		$file_extension = strtolower(pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION));
		$allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
		
		if(!in_array($file_extension, $allowed_extensions)){
			return "Invalid file type. Only JPG, PNG, and GIF images are allowed.";
		}
		
		// Check if GD extension is available
		if(!extension_loaded('gd')){
			// Simple file copy without image processing
			$fname = "uploads/avatars/$id." . $file_extension;
			
			// Remove old avatar files
			$old_files = glob(base_app."uploads/avatars/$id.*");
			foreach($old_files as $old_file){
				if(is_file($old_file)){
					unlink($old_file);
				}
			}
			
			// Move uploaded file
			if(!move_uploaded_file($_FILES['img']['tmp_name'], base_app.$fname)){
				return "Failed to save image file";
			}
			
		} else {
			// Use GD for image processing
			
			// Get file info using finfo if available, otherwise use file extension
			if(function_exists('finfo_open')){
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				$mime_type = finfo_file($finfo, $_FILES['img']['tmp_name']);
				finfo_close($finfo);
			} else {
				// Fallback to extension-based detection
				switch($file_extension){
					case 'jpg':
					case 'jpeg':
						$mime_type = 'image/jpeg';
						break;
					case 'png':
						$mime_type = 'image/png';
						break;
					case 'gif':
						$mime_type = 'image/gif';
						break;
					default:
						$mime_type = 'unknown';
				}
			}
			
			// Accept common image types
			$accepted_types = array(
				'image/jpeg',
				'image/jpg', 
				'image/png',
				'image/gif'
			);
			
			if(!in_array($mime_type, $accepted_types)){
				return "Invalid file type. Only JPG, PNG, and GIF images are allowed. Detected: " . $mime_type;
			}
			
			// Create image resource based on MIME type
			switch($mime_type){
				case 'image/jpeg':
					$uploadfile = imagecreatefromjpeg($_FILES['img']['tmp_name']);
					break;
				case 'image/png':
					$uploadfile = imagecreatefrompng($_FILES['img']['tmp_name']);
					break;
				case 'image/gif':
					$uploadfile = imagecreatefromgif($_FILES['img']['tmp_name']);
					break;
				default:
					return "Unsupported image format";
			}
			
			if(!$uploadfile){
				return "Failed to process image. File may be corrupted";
			}
			
			// Resize image
			$temp = imagescale($uploadfile, 200, 200);
			if(!$temp){
				imagedestroy($uploadfile);
				return "Failed to resize image";
			}
			
			// Set file path (always save as PNG when processing)
			$fname = "uploads/avatars/$id.png";
			
			// Remove old avatar if exists
			$old_files = glob(base_app."uploads/avatars/$id.*");
			foreach($old_files as $old_file){
				if(is_file($old_file)){
					unlink($old_file);
				}
			}
			
			// Save new avatar as PNG
			$upload = imagepng($temp, base_app.$fname, 9);
			
			// Clean up memory
			imagedestroy($uploadfile);
			imagedestroy($temp);
			
			if(!$upload){
				return "Failed to save image file";
			}
		}
		
		// Update database with avatar path
		$avatar_path = $fname . "?v=" . time();
		$update_query = "UPDATE `users` SET `avatar` = '" . $this->conn->real_escape_string($avatar_path) . "' WHERE id = '{$id}'";
		
		if(!$this->conn->query($update_query)){
			return "Failed to update avatar in database: " . $this->conn->error;
		}
		
		// Update session if current user
		if($this->settings->userdata('id') == $id){
			$this->settings->set_userdata('avatar', $avatar_path);
		}
		
		return true;
	}

	public function delete_users(){
		extract($_POST);
		$qry = $this->conn->query("DELETE FROM users WHERE id = $id");
		if($qry){
			$this->settings->set_flashdata('success','User Details successfully deleted.');
			if(is_file(base_app."uploads/avatars/$id.png"))
				unlink(base_app."uploads/avatars/$id.png");
			return 1;
		} else {
			return false;
		}
	}
	
	function registration(){
    $resp = array();
    
    if(!empty($_POST['password']))
        $_POST['password'] = md5($_POST['password']);
    else
        unset($_POST['password']);
    extract($_POST);
    $main_field = ['firstname', 'middlename', 'lastname', 'gender', 'contact', 'email', 'status', 'password'];
    $data = "";
    $check = $this->conn->query("SELECT * FROM `customer_list` where email = '{$email}' ".($id > 0 ? " and id!='{$id}'" : "")." ")->num_rows;
    if($check > 0){
        $resp['status'] = 'failed';
        $resp['msg'] = 'Email already exists.';
        return json_encode($resp);
    }
    foreach($_POST as $k => $v){
        $v = $this->conn->real_escape_string($v);
        if(in_array($k, $main_field)){
            if(!empty($data)) $data .= ", ";
            $data .= " `{$k}` = '{$v}' ";
        }
    }
    if(empty($id)){
        $sql = "INSERT INTO `customer_list` set {$data} ";
    }else{
        $sql = "UPDATE `customer_list` set {$data} where id = '{$id}' ";
    }
    $save = $this->conn->query($sql);
    if($save){
        $uid = !empty($id) ? $id : $this->conn->insert_id;
        $resp['status'] = 'success';
        $resp['uid'] = $uid;
        if(!empty($id))
            $resp['msg'] = 'User Details has been updated successfully';
        else
            $resp['msg'] = 'Your Account has been created successfully';

        // Simple image upload - no processing, just move file
        if(isset($_FILES['img']) && $_FILES['img']['error'] == 0){
            
            // Create directory
            $upload_dir = "uploads/customers";
            if(!is_dir(base_app . $upload_dir)){
                mkdir(base_app . $upload_dir, 0777, true);
            }
            
            // Check file extension
            $file_ext = strtolower(pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION));
            if(in_array($file_ext, ['jpg', 'jpeg', 'png'])){
                
                // Simple filename: userID.originalExtension
                $filename = $uid . '.' . $file_ext;
                $file_path = $upload_dir . '/' . $filename;
                $full_path = base_app . $file_path;
                
                // Delete old file if exists
                if(file_exists($full_path)){
                    unlink($full_path);
                }
                
                // Just move the uploaded file - no processing
                if(move_uploaded_file($_FILES['img']['tmp_name'], $full_path)){
                    // Update database
                    $avatar_path = $file_path . '?v=' . time();
                    $this->conn->query("UPDATE `customer_list` set `avatar` = '{$avatar_path}' where id = '{$uid}'");
                } else {
                    $resp['msg'] .= " - Failed to upload image";
                }
                
            } else {
                $resp['msg'] .= " - Only JPG, JPEG, PNG files allowed";
            }
        }
        
        // Set user session
        if(!empty($uid) && $this->settings->userdata('login_type') != 1){
            $user = $this->conn->query("SELECT * FROM `customer_list` where id = '{$uid}' ");
            if($user->num_rows > 0){
                $res = $user->fetch_array();
                foreach($res as $k => $v){
                    if(!is_numeric($k) && $k != 'password'){
                        $this->settings->set_userdata($k, $v);
                    }
                }
                $this->settings->set_userdata('login_type', '2');
            }
        }
    }else{
        $resp['status'] = 'failed';
        $resp['msg'] = $this->conn->error;
        $resp['sql'] = $sql;
    }
    
    if($resp['status'] == 'success' && isset($resp['msg']))
        $this->settings->set_flashdata('success', $resp['msg']);
    return json_encode($resp);
}
	public function delete_customer(){
		extract($_POST);
		$avatar = $this->conn->query("SELECT avatar FROM customer_list where id = $id");
		$qry = $this->conn->query("DELETE FROM customer_list where id = $id");
		if($qry){
			$this->settings->set_flashdata('success','Customer Details has been deleted successfully.');
			$resp['status'] = 'success';
			if($avatar->num_rows > 0){
				$avatar = explode("?", $avatar->fetch_array()[0])[0];
				if(is_file(base_app.$avatar)){
					unlink(base_app.$avatar);
				}
			}
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = $this->conn->error;
		}

		return json_encode($resp);
	}
	
}

$users = new users();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
switch ($action) {
	case 'save':
		echo $users->save_users();
	break;
	case 'delete':
		echo $users->delete_users();
	break;
	case 'registration':
		echo $users->registration();
	break;
	case 'delete_customer':
		echo $users->delete_customer();
	break;
	default:
		// echo $sysset->index();
		break;
}
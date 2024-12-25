<?php
/*
Plugin Name: Custom WooCommerce Panel
Description: A customized WooCommerce panel for managing products, orders, and categories with user management.
Version: 1.0
Author: Your Name
*/

// Register Custom Role
function custom_woocommerce_role()
{
  add_role(
    'custom_woocommerce_manager',
    __('Custom WooCommerce Manager'),
    array(
      'read' => true,
      'edit_posts' => true,
      'delete_posts' => true,
      'delete_others_posts' => true,
      'edit_published_posts' => true,
      'publish_posts' => true,
      'manage_categories' => true,
      'edit_others_posts' => true,
      'read_private_posts' => true,
      'edit_pages' => true,
      'edit_others_pages' => true,
      'publish_pages' => true,
      'delete_pages' => true,
      'delete_others_pages' => true,
      'delete_published_pages' => true,
      'delete_private_pages' => true,
      'edit_private_pages' => true,
      'read_private_pages' => true,
      'manage_links' => true,
      'moderate_comments' => true,
      'upload_files' => true,
      'export' => true,
      'import' => true,
      'create_users' => true,
      'delete_users' => true,
      'promote_users' => true,
      'edit_users' => true,
      'list_users' => true,
      'remove_users' => true,
      'read' => true,
      'manage_options' => true,
      'edit_products' => true,
      'publish_products' => true,
      'edit_published_products' => true,
      'delete_published_products' => true,
      'manage_woocommerce' => true,
      'edit_shop_order' => true,
      'read_shop_order' => true,
      'delete_shop_order' => true,
      'edit_others_shop_orders' => true,
      'publish_shop_orders' => true,
      'read_private_shop_orders' => true,
      'delete_others_shop_orders' => true,
      'manage_product_terms' => true, // Manage product categories
      'edit_product_terms' => true,   // Edit product categories
      'delete_product_terms' => true, // Delete product categories
      'assign_product_terms' => true, // Assign product categories
      'edit_others_products' => true,
      'delete_products' => true,
      'delete_others_products' => true,
      'manage_categories' => true, // Manage all product categories
    )
  );
}
add_action('init', 'custom_woocommerce_role');

// Add capabilities to Administrator, Shop Manager, and Custom WooCommerce Manager roles
function add_capabilities_to_roles()
{
  $roles = ['administrator', 'shop_manager', 'custom_woocommerce_manager'];

  $capabilities = [
    // General capabilities
    'read',
    'manage_options',
    'upload_files',
    'edit_posts',
    'edit_others_posts',
    'delete_posts',
    'delete_others_posts',
    'publish_posts',
    'edit_published_posts',
    'delete_published_posts',
    'read_private_posts',
    'edit_pages',
    'edit_others_pages',
    'publish_pages',
    'delete_pages',
    'delete_others_pages',
    'delete_published_pages',
    'edit_private_pages',
    'read_private_pages',

    // WooCommerce capabilities
    'manage_woocommerce',
    'view_woocommerce_reports',
    'edit_products',
    'read_products',
    'delete_products',
    'edit_product',
    'delete_product',
    'read_product',
    'edit_shop_orders',
    'manage_shop_orders',
    'publish_shop_orders',
    'edit_others_shop_orders',
    'delete_shop_orders',
    'read_shop_orders',
    'delete_others_shop_orders',
    'read_private_shop_orders',
    'read_private_products',
    'delete_private_shop_orders',

    // Product term capabilities
    'manage_product_terms',
    'edit_product_terms',
    'delete_product_terms',
    'assign_product_terms',

    // User capabilities
    'create_users',
    'delete_users',
    'edit_users',
    'list_users',
    'promote_users',
    'remove_users',

    // Additional capabilities
    'manage_categories',
    'moderate_comments',
    'export',
    'import',
  ];

  foreach ($roles as $role) {
    $roleObject = get_role($role);
    if ($roleObject) {
      foreach ($capabilities as $cap) {
        $roleObject->add_cap($cap);
      }
    }
  }
}
add_action('admin_init', 'add_capabilities_to_roles');


// Limit Admin Menu and Add Top Navbar
function custom_admin_menu()
{
  if (current_user_can('custom_woocommerce_manager')) {
    remove_menu_page('index.php'); // Dashboard
    remove_menu_page('upload.php'); // Media
    remove_menu_page('edit.php'); // Posts
    remove_menu_page('edit-comments.php'); // Comments
    remove_menu_page('themes.php'); // Appearance
    remove_menu_page('plugins.php'); // Plugins
    remove_menu_page('users.php'); // Users
    remove_menu_page('tools.php'); // Tools
    remove_menu_page('options-general.php'); // Settings
  }
}
add_action('admin_menu', 'custom_admin_menu', 999);

// Hide Help Dropdown
function hide_help_dropdown()
{
  if (current_user_can('custom_woocommerce_manager')) {
    echo '<style>
            #contextual-help-link-wrap {
                display: none !important;
            }
        </style>';
  }
}
add_action('admin_head', 'hide_help_dropdown');

// Enqueue Custom CSS for Admin Panel
function enqueue_custom_admin_styles()
{
  if (current_user_can('custom_woocommerce_manager')) {
    echo '<style>
            #wpadminbar {
      display: none;
    }

    /* Adjust body and html to account for the hidden admin bar */
    body.admin-bar #wpcontent,
    body.admin-bar #wpfooter {
      padding-top: 0;
    }

    /* Apply a lightish grey background with darkish grey fonts and prevent horizontal scrolling */
    body,
    html {
      margin: 0 !important;
      padding: 0 !important;
      background-color: #f0f0f0;
      /* Lightish grey background */
      color: #333333;
      /* Darkish grey font */
      font-family: "Arial", sans-serif;
      overflow-x: hidden;
      /* Prevents horizontal scrolling */
    }

    #wpcontent,
    #wpfooter,
    #wpbody {
      margin-right: 0;
      padding: 180px 20px;
      /* Adjusted padding to account for navbar height */
      box-sizing: border-box;
      width: 100%;
      max-width: 100%;
      /* Ensure it takes the full width */
      /* overflow-x: hidden; */
      /* Prevents horizontal scrolling */
    }

    #logout-button {
      background-color: #d9534f;
      border: none;
      color: #ffffff;
      padding: 10px 20px;
      cursor: pointer;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 16px;
      border-radius: 4px;
      position: relative;
      /* Changed from fixed to relative */
      margin-left: 20px;
      /* Adjust the margin as needed */
      margin-right: 20px;

    }

    #logout-button:hover {
      background-color: #c9302c;
    }

    .top-navbar {
      direction: rtl;
      background-color: #252526;
      padding: 10px 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: fixed;
      width: 100vw;
      top: 0;
      z-index: 9999;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
      height: 60px;
    }

    .top-navbar .navbar-item {
      width: 100%;
      color: #ffffff;
      text-decoration: none;
      font-size: 16px;
      padding: 10px;
      text-align: center;
      border-radius: 4px;
      transition: background-color 0.3s;
      position: relative;
      margin-right: 20px;
      cursor: pointer;
      border: 2px solid #444444;

    }

    .top-navbar .navbar-item:hover {
      background-color: #3a3a3a;
    }

    .top-navbar .navbar-item .dropdown {
      display: none;
      position: absolute;
      background-color: #252526;
      top: 35px;
      left: 0;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
      z-index: 1000;
      min-width: 200px;
    }

    .top-navbar .navbar-item:hover .dropdown {
      display: block;
    }

    .top-navbar .dropdown a {
      color: #cfcfcf;
      text-decoration: none;
      padding: 10px;
      display: block;
      transition: background-color 0.3s;
    }

    .top-navbar .dropdown a:hover {
      background-color: #3a3a3a;
    }


    /* Hide the default sidebar */
    #adminmenuwrap,
    #adminmenuback {
      display: none;
    }

    /* Custom top navbar */
    #wpcontent {
      margin-left: 0;
      padding: 80px 20px;
      box-sizing: border-box;
      width: 100%;
    }

    .woocommerce-layout__activity-panel-wrapper {
      display: none !important;
    }

    /* Style for WooCommerce Products Table */
    table.wp-list-table.products {
      background-color: #f9f9f9;
      /* Light grey background */
      border-collapse: separate;
      border-spacing: 0 10px;
      /* Add space between rows */
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 100%;
    }

    table.wp-list-table.products thead th {
      background-color: #333333;
      /* Dark grey background for header */
      color: #ffffff;
      /* White text */
      padding: 10px;
      text-align: left;
    }

    table.wp-list-table.products tbody tr {
      background-color: #ffffff;
      /* White background for rows */
      border-radius: 4px;
      /* Rounded corners */
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    table.wp-list-table.products tbody td {
      padding: 10px;
      color: #333333;
      /* Dark grey text */
    }

    /* Style for WooCommerce Orders Table */
    table.wp-list-table.orders {
      background-color: #f1f1f1;
      /* Light grey background */
      border-collapse: separate;
      border-spacing: 0 10px;
      /* Add space between rows */
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 100%;
    }

    table.wp-list-table.orders thead th {
      background-color: #444444;
      /* Slightly darker grey background for header */
      color: #ffffff;
      /* White text */
      padding: 10px;
      text-align: left;
    }

    table.wp-list-table.orders tbody tr {
      background-color: #ffffff;
      /* White background for rows */
      border-radius: 4px;
      /* Rounded corners */
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    table.wp-list-table.orders tbody td {
      padding: 10px;
      color: #444444;
      /* Slightly darker grey text */
    }

    /* General styles for both tables */
    .wp-list-table th,
    .wp-list-table td {
      border-top: none;
      /* Remove default top border */
      border-bottom: 1px solid #ddd;
      /* Add bottom border to cells */
    }

    .wp-list-table tbody tr:nth-child(even) {
      background-color: #f1f1f1;
      /* Light grey background for even rows */
    }

    .wp-list-table tbody tr:hover {
      background-color: #e0e0e0;
      /* Slightly darker grey on hover */
    }

    .wp-list-table tbody tr td a {
      color: #0073aa;
      /* Link color */
      text-decoration: none;
    }

    .wp-list-table tbody tr td a:hover {
      text-decoration: underline;
      /* Underline on hover */
    }

    /* Style for the top switch bar */
.top-switch-bar {
    background-color: #1a1a1a; /* Dark background to match the panel style */
    color: #ffffff; /* White text */
    padding: 10px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: fixed;
    width: 100%;
    top: 0;
    z-index: 10000; /* Ensure it is above everything else */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    height: 40px;
}

.top-switch-bar a {
    color: #ffffff; /* White text */
    text-decoration: none;
    font-size: 16px;
    padding: 10px 10px;
    border-radius: 4px;
    transition: background-color 0.3s;
}

.top-switch-bar a:hover {
    background-color: #333333; /* Slightly lighter background on hover */
}

/* Adjusting the custom navbar to be below the switch bar */
.top-navbar {
    top: 40px; /* Adjusted to be below the top switch bar */
}

.woocommerce-layout__header{
    display: none;
}


</style>';
  }
}
add_action('admin_head', 'enqueue_custom_admin_styles');

function custom_top_switch_bar()
{
  // Determine the link based on whether the user is on the admin panel or the main site
  $current_url = (is_admin()) ? site_url() : admin_url();
  $link_text = (is_admin()) ? 'بازدید سایت' : 'ورود به پنل';

  echo '<div class="top-switch-bar navbar-item">
            <a href="' . $current_url . '">' . $link_text . '</a>
          </div>';
}
add_action('admin_head', 'custom_top_switch_bar');
add_action('wp_head', 'custom_top_switch_bar');


function hide_admin_bar_for_visitors()
{
  if (!is_admin()) { // Check if the user is on the frontend
    add_filter('show_admin_bar', '__return_false');
  }
}
add_action('after_setup_theme', 'hide_admin_bar_for_visitors');

function redirect_custom_woocommerce_manager_dashboard()
{
  if (current_user_can('custom_woocommerce_manager')) {
    wp_redirect(admin_url('edit.php?post_type=shop_order'));
    exit;
  }
}
add_action('load-index.php', 'redirect_custom_woocommerce_manager_dashboard');


function remove_dashboard_widgets()
{
  if (current_user_can('custom_woocommerce_manager')) {
    remove_meta_box('dashboard_activity', 'dashboard', 'normal');
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
    remove_meta_box('dashboard_primary', 'dashboard', 'side');
    remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
    remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
  }
}
add_action('wp_dashboard_setup', 'remove_dashboard_widgets');



function custom_top_navbar()
{
  if (current_user_can('custom_woocommerce_manager')) {
    echo '<div class="top-navbar">
    <div class="navbar-item">محصولات
      <div class="dropdown">
        <a href="' . admin_url('edit.php?post_type=product') . '">همه محصولات</a>
        <a href="' . admin_url('post-new.php?post_type=product') . '">افزودن محصول</a>
        <a href="' . admin_url('edit-tags.php?taxonomy=product_cat&post_type=product') . '">دسته‌بندی محصولات</a>
        <a href="' . admin_url('edit-tags.php?taxonomy=product_tag&post_type=product') . '">برچسب‌ها</a>
      </div>
    </div>
    <div class="navbar-item">سفارش‌ها
      <div class="dropdown">
        <a href="' . admin_url('edit.php?post_type=shop_order') . '">همه سفارش‌ها</a>
      </div>
    </div>
    <div class="navbar-item">دسته‌بندی‌ها
      <div class="dropdown">
        <a href="' . admin_url('edit-tags.php?taxonomy=category') . '">همه دسته‌بندی‌ها</a>
        <a href="' . admin_url('post-new.php') . '">افزودن دسته‌بندی</a>
      </div>
    </div>

    <a id="logout-button" class="navbar-item" href="' . wp_logout_url() . '">خروج</a>
  </div>';
  }
}
add_action('admin_head', 'custom_top_navbar');





// Add User Management Page
function custom_user_management_page()
{
  add_menu_page(
    'User Management',
    'User Management',
    'manage_options',
    'user-management',
    'user_management_page_callback',
    'dashicons-admin-users',
    100
  );
}
add_action('admin_menu', 'custom_user_management_page');

// User Management Page Callback
function user_management_page_callback()
{
  ?>
  <div class="wrap">
    <h1>User Management</h1>
    <h2>Create User</h2>
    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
      <input type="hidden" name="action" value="create_custom_user">
      <?php wp_nonce_field('create_user_nonce', 'create_user_nonce_field'); ?>
      <table class="form-table">
        <tr valign="top">
          <th scope="row">Username</th>
          <td><input type="text" name="username" required /></td>
        </tr>
        <tr valign="top">
          <th scope="row">Password</th>
          <td><input type="password" name="password" required /></td>
        </tr>
      </table>
      <?php submit_button('Create User'); ?>
    </form>
    <h2>Existing Users</h2>
    <?php list_existing_users(); ?>
  </div>
  <?php
}

// Create User
function create_custom_user()
{
  if (isset($_POST['username']) && isset($_POST['password']) && wp_verify_nonce($_POST['create_user_nonce_field'], 'create_user_nonce')) {
    $username = sanitize_text_field($_POST['username']);
    $password = sanitize_text_field($_POST['password']);

    if (!username_exists($username)) {
      $user_id = wp_create_user($username, $password, $username . '@example.com');
      if (!is_wp_error($user_id)) {
        wp_update_user(array('ID' => $user_id, 'role' => 'custom_woocommerce_manager'));
        wp_safe_redirect(add_query_arg('message', 'user_created', wp_get_referer()));
        exit;
      } else {
        wp_safe_redirect(add_query_arg('message', 'user_create_error', wp_get_referer()));
        exit;
      }
    } else {
      wp_safe_redirect(add_query_arg('message', 'user_exists', wp_get_referer()));
      exit;
    }
  }
}
add_action('admin_post_create_custom_user', 'create_custom_user');

// Update User Password
function update_custom_user_password()
{
  if (isset($_POST['user_id']) && isset($_POST['new_password']) && wp_verify_nonce($_POST['update_user_password_nonce_field'], 'update_user_password_nonce')) {
    $user_id = sanitize_text_field($_POST['user_id']);
    $new_password = sanitize_text_field($_POST['new_password']);

    $user = get_userdata($user_id);
    if ($user) {
      wp_set_password($new_password, $user_id);
      wp_safe_redirect(add_query_arg('message', 'password_updated', wp_get_referer()));
      exit;
    } else {
      wp_safe_redirect(add_query_arg('message', 'user_not_found', wp_get_referer()));
      exit;
    }
  }
}
add_action('admin_post_update_custom_user_password', 'update_custom_user_password');

// Display Admin Notices
function custom_user_notices()
{
  if (isset($_GET['message'])) {
    switch ($_GET['message']) {
      case 'user_created':
        echo '<div class="notice notice-success is-dismissible"><p>User created successfully!</p></div>';
        break;
      case 'user_create_error':
        echo '<div class="notice notice-error is-dismissible"><p>Error creating user.</p></div>';
        break;
      case 'user_exists':
        echo '<div class="notice notice-warning is-dismissible"><p>Username already exists.</p></div>';
        break;
      case 'password_updated':
        echo '<div class="notice notice-success is-dismissible"><p>Password updated successfully!</p></div>';
        break;
      case 'user_not_found':
        echo '<div class="notice notice-error is-dismissible"><p>User not found.</p></div>';
        break;
    }
  }
}
add_action('admin_notices', 'custom_user_notices');

// List Existing Users
function list_existing_users()
{
  $args = array(
    'role' => 'custom_woocommerce_manager',
  );
  $users = get_users($args);

  if (!empty($users)) {
    echo '<table class="wp-list-table widefat fixed striped users">';
    echo '<thead><tr><th>Username</th><th>Email</th><th>Actions</th></thead><tbody>';
    foreach ($users as $user) {
      echo '<tr>';
      echo '<td>' . esc_html($user->user_login) . '</td>';
      echo '<td>' . esc_html($user->user_email) . '</td>';
      echo '<td>';
      echo '<a href="' . esc_url(admin_url('user-edit.php?user_id=' . $user->ID)) . '">Edit</a> | ';
      echo '<a href="' . esc_url(admin_url('users.php?action=delete&user=' . $user->ID . '&_wpnonce=' . wp_create_nonce('bulk-users'))) . '" onclick="return confirm(\'Are you sure you want to delete this user?\')">Delete</a> | ';
      echo '<a href="#" onclick="showUpdatePasswordForm(' . esc_js($user->ID) . ');return false;">Change Password</a>';
      echo '</td>';
      echo '</tr>';
    }
    echo '</tbody></table>';
  } else {
    echo '<p>No users found.</p>';
  }
  ?>
  <div id="update-password-form" style="display:none;">
    <h2>Update Password</h2>
    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
      <input type="hidden" name="action" value="update_custom_user_password">
      <input type="hidden" id="user_id" name="user_id" value="">
      <?php wp_nonce_field('update_user_password_nonce', 'update_user_password_nonce_field'); ?>
      <table class="form-table">
        <tr valign="top">
          <th scope="row">New Password</th>
          <td><input type="password" name="new_password" required /></td>
        </tr>
      </table>
      <?php submit_button('Update Password'); ?>
    </form>
  </div>
  <script type="text/javascript">
    function showUpdatePasswordForm(userId) {
      document.getElementById('update-password-form').style.display = 'block';
      document.getElementById('user_id').value = userId;
    }
  </script>
  <?php
}
?>
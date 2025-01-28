# Development Process Explained  

This document explains the development process.  
For more information on the setup process, see the `README`.  

## Dependencies
* **WooCommerce:** Ensure WooCommerce is installed and activated.
* **WordPress:** Tested with WordPress 6.3+ and WooCommerce 8.1+.

## Folder Structure  

```
custom-payment-gateway/
├── includes/
│ ├── custom-order-status.php
│ ├── custom-product.php
│ └── shoehaven-courses.php
└── dime-payment-gateway.php
```
## *1.Custom payment gateway*
The file `dime-payment-gateway.php` is the main file for the plugins and it begins with the `plugin header` which tells wordpress that this is a plugin and also shares more information about the plugin.
It also contain `hooks ` which add functionality for the plugin.
-The plugin header looks like this:
```php
/*
*Plugin Name: "Dime payment gateway"
*Description:       This is a simple payment gateway called Pay gateway.
*
**/
```
This header gives information like, **Whats the name of the plugin,the description of the plugin,the author of the plugin,the version of PHP and many more**.For our case the name of the plugin is `Dime payment gateway`
From the folder structure we have php files which have is needed for other functionality we will see shortly and we use require once to embed them
```
require_once plugin_dir_path( __FILE__ ) . '/includes/custom-product.php';
require_once plugin_dir_path( __FILE__ ) . '/includes/custom-order-status.php';
require_once plugin_dir_path( __FILE__ ) . '/includes/shoehaven-courses.php';
```
For security best practice we two code the first block code checks if the file is accesssed directly and not in wordpress environment. second block of code checks if woocommmerce is activated.
```
//First code
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//second code
if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) return;
```
`Hooks` are the piece of code which interact with wordpress code.and we have two types `actions` and `filters`.Actions allows you to add data while filters allows you to change date.
For instane we have 
```php
add_action('plugins_loaded', 'init_dime_payment_gateway');
```
The function `init_dime_payment_gateway` is hooked to action `plugins_loaded` in wordpress.It ensures that the custom payment gateway class is initialized only after all active plugins, including WooCommerce, have fully loaded
##### **Dime Payment Gateway Initialization**

This code below snippet defines and registers a custom payment gateway for WooCommerce.Here is the class initialization

```
class WC_Dime_Payment_Gateway extends WC_Payment_Gateway
    {
        public function __construct()
        {
          \\see file for more lines  
        }

        // Define admin form fields
        public function init_form_fields()
        {
         \\see file lines
        }
	}
```

###### **Key Functions:**

1. **Class Initialization:**  
   - `init_dime_payment_gateway`: Hooks into `plugins_loaded` to initialize the custom payment gateway class (`WC_Dime_Payment_Gateway`) only if WooCommerce is active.

2. **Payment Gateway Class:**  
   - Extends `WC_Payment_Gateway` to customize payment logic for WooCommerce.

3. **Constructor (`__construct`)**
   - Registers payment settings, admin form fields, and hooks for order status management.
   - Adds an icon for the payment method during checkout.

4. **Admin Settings (`init_form_fields`)**
   - Allows configuration of gateway settings, including enabling/disabling, titles, and descriptions.


##### **Payment Processing Function**

This code snippet handles the payment process logic for the custom Dime Payment Gateway.This function manages the complete flow of payment processing, including order status updates and customer notifications for success or failure scenarios.And this is where you will handle your **API functionality**
```
 //processing payment function
        public function process_payment($order_id)
        {
       
        }
```
###### **Key Functions:**

1. **Order Retrieval (`wc_get_order`)**  
   - Fetches the WooCommerce order using its ID.

2. **On Success:**  
   - Marks the payment as complete with `$order->payment_complete()`  
   - Updates the order status to `successful`  
   - Adds a success order note and clears the cart  
   - Redirects the user to the success page.

3. **On Failure:**  
   - Updates the order status to `failed`  
   - Displays an error message and halts the payment process.

##### Creating custom order status - "Successful".
This file `'/includes/custom-order-status.php'` registers and manages a custom WooCommerce order status called **"Successful."**

#### **Key Functions:**  

1. **Registering Custom Status (`register_successful_order_status`)**  
   - Adds a new order status (`wc-successful`) with a label visible in the admin order list.

2. **Adding Status to WooCommerce (`add_successful_to_order_statuses`)**  
   - Displays the "Successful" status in the WooCommerce status dropdown.

3. **Bulk Action Support (`rudr_register_bulk_action`)**  
   - Enables a bulk action option to change multiple orders to the "Successful" status.

4. **Handling Bulk Actions (`rudr_bulk_process_custom_status`)**  
   - Updates the status of selected orders to "Successful" and displays a confirmation.



## *2.Creating a custom field for product.*
The file `'/includes/custom-product.php'` has the functionality required to add a custom field text for a woocommerce product.
###### **Key Functions:**  

1. **Adding the Custom Field (`add_custom_product_field`)**  
   - Displays a custom text input field in the product edit screen.

2. **Saving the Field Value (`save_custom_product_field`)**  
   - Stores the custom field value in the product metadata when the product is updated.

3. **Displaying the Field Value (`display_custom_product_field`)**  
   - Outputs the custom field value on the product page under the product description.

## *3.Creating custom "Courses" Post Type for WordPress*  

The file `'/includes/shoehaven-courses.php'` powers up your WordPress site by registering a "Courses" post type, perfect for organizing educational content like tutorials or training sessions.  

###### **Key Functions:**  
- **Custom Post Type (`register_course_post_type`)**  
  - Adds a "Shoe Courses" section to the WordPress admin menu.  
  - Supports titles, content, featured images, and comments.  
  - Customizes URLs with `/course` slugs. 

- **Custom Taxonomy (`register_course_taxonomy`)**  
  - Adds "Course Categories" to group related courses.  
  - Fully manageable in the admin with hierarchical structures.  


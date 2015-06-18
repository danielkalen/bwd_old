<?php 
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

function custom_override_checkout_fields( $fields ) {
	// $fields_billing = $fields['billing'];
	// $fields_shipping = $fields['shipping'];
	// $fields_account = $fields['account'];
	// $fields_order = $fields['order'];

	// ==== Billing =================================================================================
		$fields['billing']['billing_first_name'] = array(
			'type' => 'text',
			'label' => 'First Name',
			'placeholder' => 'First Name',
			'required' => TRUE,
			'class' => array(
				'checkout-section-fieldset',
				'fieldset',
				'required',
				'quarter',
				'firstname'
			),
			'input_class' => array(
				'checkout-section-fieldset-input',
				'input'
			),
			'label_class' => array(
				'checkout-section-fieldset-label'
			)
		);


		$fields['billing']['billing_last_name'] = array(
			'type' => 'text',
			'label' => 'Last Name',
			'placeholder' => 'Last Name',
			'required' => TRUE,
			'class' => array(
				'checkout-section-fieldset',
				'fieldset',
				'required',
				'quarter',
				'lastname'
			),
			'input_class' => array(
				'checkout-section-fieldset-input',
				'input'
			),
			'label_class' => array(
				'checkout-section-fieldset-label'
			)
		);


		$fields['billing']['billing_company'] = array(
			'type' => 'text',
			'label' => 'Company',
			'placeholder' => 'Company',
			'required' => FALSE,
			'class' => array(
				'checkout-section-fieldset',
				'fieldset',
				'half',
				'company'
			),
			'input_class' => array(
				'checkout-section-fieldset-input',
				'input'
			),
			'label_class' => array(
				'checkout-section-fieldset-label'
			)
		);


		$fields['billing']['billing_address_1'] = array(
			'type' => 'text',
			'label' => 'Street Address',
			'placeholder' => 'Street Address',
			'required' => TRUE,
			'class' => array(
				'checkout-section-fieldset',
				'fieldset',
				'required',
				'half',
				'address_1'
			),
			'input_class' => array(
				'checkout-section-fieldset-input',
				'input'
			),
			'label_class' => array(
				'checkout-section-fieldset-label'
			)
		);


		$fields['billing']['billing_address_2'] = array(
			'type' => 'text',
			'label' => 'Apt/Suite',
			'placeholder' => 'Apt/Suite (optional)',
			'required' => FALSE,
			'class' => array(
				'checkout-section-fieldset',
				'fieldset',
				'quarter',
				'address_2'
			),
			'input_class' => array(
				'checkout-section-fieldset-input',
				'input'
			),
			'label_class' => array(
				'checkout-section-fieldset-label'
			)
		);


		$fields['billing']['billing_city'] = array(
			'type' => 'text',
			'label' => 'Town/City',
			'placeholder' => 'Town/City',
			'required' => TRUE,
			'class' => array(
				'checkout-section-fieldset',
				'fieldset',
				'required',
				'quarter',
				'city'
			),
			'input_class' => array(
				'checkout-section-fieldset-input',
				'input'
			),
			'label_class' => array(
				'checkout-section-fieldset-label'
			)
		);


		$fields['billing']['billing_postcode'] = array(
			'type' => 'text',
			'label' => 'Zip Code',
			'placeholder' => 'Zip Code',
			'required' => TRUE,
			'class' => array(
				'checkout-section-fieldset',
				'fieldset',
				'required',
				'quarter',
				'zip'
			),
			'input_class' => array(
				'checkout-section-fieldset-input',
				'input'
			),
			'label_class' => array(
				'checkout-section-fieldset-label'
			)
		);

		$fields['billing']['billing_state']['class'][] = 'checkout-section-fieldset fieldset state required select quarter';
		$fields['billing']['billing_state']['input_class'][] = 'checkout-section-fieldset-select input';
		$fields['billing']['billing_state']['label_class'][] = 'checkout-section-fieldset-label';


		$fields['billing']['billing_email'] = array(
			'type' => 'text',
			'label' => 'Email Address',
			'placeholder' => 'Email Address',
			'required' => TRUE,
			'class' => array(
				'checkout-section-fieldset',
				'fieldset',
				'required',
				'quarter',
				'email'
			),
			'input_class' => array(
				'checkout-section-fieldset-input',
				'input',
				'email'
			),
			'label_class' => array(
				'checkout-section-fieldset-label'
			)
		);


		$fields['billing']['billing_phone'] = array(
			'type' => 'text',
			'label' => 'Phone Number',
			'placeholder' => 'Phone Number',
			'required' => TRUE,
			'class' => array(
				'checkout-section-fieldset',
				'fieldset',
				'required',
				'quarter',
				'phone'
			),
			'input_class' => array(
				'checkout-section-fieldset-input',
				'input',
				'phone'
			),
			'label_class' => array(
				'checkout-section-fieldset-label'
			)
		);



	// ==== Shipping =================================================================================
		$fields['shipping']['shipping_first_name'] = array(
			'type' => 'text',
			'label' => 'First Name',
			'placeholder' => 'First Name',
			'required' => TRUE,
			'class' => array(
				'checkout-section-fieldset',
				'fieldset',
				'required',
				'quarter',
				'firstname'
			),
			'input_class' => array(
				'checkout-section-fieldset-input',
				'input'
			),
			'label_class' => array(
				'checkout-section-fieldset-label'
			)
		);


		$fields['shipping']['shipping_last_name'] = array(
			'type' => 'text',
			'label' => 'Last Name',
			'placeholder' => 'Last Name',
			'required' => TRUE,
			'class' => array(
				'checkout-section-fieldset',
				'fieldset',
				'required',
				'quarter',
				'lastname'
			),
			'input_class' => array(
				'checkout-section-fieldset-input',
				'input'
			),
			'label_class' => array(
				'checkout-section-fieldset-label'
			)
		);


		$fields['shipping']['shipping_company'] = array(
			'type' => 'text',
			'label' => 'Company',
			'placeholder' => 'Company',
			'required' => FALSE,
			'class' => array(
				'checkout-section-fieldset',
				'fieldset',
				'half',
				'company'
			),
			'input_class' => array(
				'checkout-section-fieldset-input',
				'input'
			),
			'label_class' => array(
				'checkout-section-fieldset-label'
			)
		);


		$fields['shipping']['shipping_address_1'] = array(
			'type' => 'text',
			'label' => 'Street Address',
			'placeholder' => 'Street Address',
			'required' => TRUE,
			'class' => array(
				'checkout-section-fieldset',
				'fieldset',
				'required',
				'half',
				'address_1'
			),
			'input_class' => array(
				'checkout-section-fieldset-input',
				'input'
			),
			'label_class' => array(
				'checkout-section-fieldset-label'
			)
		);


		$fields['shipping']['shipping_address_2'] = array(
			'type' => 'text',
			'label' => 'Apt/Suite',
			'placeholder' => 'Apt/Suite (optional)',
			'required' => FALSE,
			'class' => array(
				'checkout-section-fieldset',
				'fieldset',
				'quarter',
				'address_2'
			),
			'input_class' => array(
				'checkout-section-fieldset-input',
				'input'
			),
			'label_class' => array(
				'checkout-section-fieldset-label'
			)
		);


		$fields['shipping']['shipping_city'] = array(
			'type' => 'text',
			'label' => 'Town/City',
			'placeholder' => 'Town/City',
			'required' => TRUE,
			'class' => array(
				'checkout-section-fieldset',
				'fieldset',
				'required',
				'quarter',
				'city'
			),
			'input_class' => array(
				'checkout-section-fieldset-input',
				'input'
			),
			'label_class' => array(
				'checkout-section-fieldset-label'
			)
		);


		$fields['shipping']['shipping_postcode'] = array(
			'type' => 'text',
			'label' => 'Zip Code',
			'placeholder' => 'Zip Code',
			'required' => TRUE,
			'class' => array(
				'checkout-section-fieldset',
				'fieldset',
				'required',
				'quarter',
				'zip'
			),
			'input_class' => array(
				'checkout-section-fieldset-input',
				'input'
			),
			'label_class' => array(
				'checkout-section-fieldset-label'
			)
		);

		$fields['shipping']['shipping_state']['class'][] = 'checkout-section-fieldset fieldset state required select quarter';
		$fields['shipping']['shipping_state']['input_class'][] = 'checkout-section-fieldset-select input';
		$fields['shipping']['shipping_state']['label_class'][] = 'checkout-section-fieldset-label';

		$fields['order']['order_comments']['class'][] = 'checkout-section-fieldset fieldset order_commments textarea half';
		$fields['order']['order_comments']['input_class'][] = 'checkout-section-fieldset-textarea input';
		$fields['order']['order_comments']['label_class'][] = 'checkout-section-fieldset-label';



     return $fields;
}
















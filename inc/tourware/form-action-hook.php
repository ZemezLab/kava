<?php
// hmjim elementor multistep form hook filter, add data in cloud

add_filter( 'wp_ajax_pafe_ajax_form_builder', 'tyto_ajax_form_builder' );
add_filter( 'wp_ajax_nopriv_pafe_ajax_form_builder', 'tyto_ajax_form_builder' );

function tyto_ajax_form_builder() {
	// construct url for sending Post in cloud
	$myApi     = site_url( '?rest_route=/tyto/v1/inquiry/' );
	$jsonData  = stripslashes( html_entity_decode( $_POST["fields"] ) );
	$form_data = json_decode( $jsonData, true );
	$reffer    = $_POST['referrer'];
	$re        = '/.recordId=(.*)&(.*)&dates=(.*)-(.*)/m';
	preg_match_all( $re, $reffer, $matches, PREG_SET_ORDER, 0 );
	if ( sizeof( $matches ) == 0 ) {
		$re = '/.recordId=(.*)&(.*)/m';
		preg_match_all( $re, $reffer, $matches, PREG_SET_ORDER, 0 );
	}

	//setup arguments data if exist
	$setup_arguments = [];
	if ( isset( $matches[0][1] ) ) {
		$setup_arguments['recordId'] = $matches[0][1];
	}
	if ( isset( $matches[0][2] ) ) {
		$recordType                    = explode( '=', $matches[0][2] );
		$setup_arguments['recordType'] = $recordType[0] . 's';
	}

	if ( isset( $matches[0][3] ) ) {
		$curr_startdate                 = DateTime::createFromFormat( 'd.m.Y', $matches[0][3] );
		$setup_arguments['travelBegin'] = $curr_startdate->format( 'Y-m-d' );
	}
	if ( isset( $matches[0][4] ) ) {
		$curr_enddate                 = DateTime::createFromFormat( 'd.m.Y', $matches[0][4] );
		$setup_arguments['travelEnd'] = $curr_enddate->format( 'Y-m-d' );
	}

	foreach ( $form_data as $k => $v ) {
		if ( $v['name'] == 'tyto_travelBegin' ) {
			$custom_begin_date = $v['value'];
		}
		if ( $v['name'] == 'tyto_travelEnd' ) {
			$custom_end_date = $v['value'];
		}
		if ( $v['name'] == 'tyto_salutation' ) {
			if ( $v['value'] != '' ) {
				$setup_arguments['salutation'] = $v['value'];
			} else {
				$setup_arguments['salutation'] = null;
			}
		}
		if ( $v['name'] == 'tyto_firstname' ) {
			if ( $v['value'] != '' ) {
				$setup_arguments['firstname'] = $v['value'];
			} else {
				$setup_arguments['firstname'] = null;
			}
		}
		if ( $v['name'] == 'tyto_email' ) {
			if ( $v['value'] != '' ) {
				$setup_arguments['email'] = $v['value'];
			} else {
				$setup_arguments['email'] = null;
			}
		}
		if ( $v['name'] == 'tyto_adults' ) {
			if ( $v['value'] == '' ) {
				$setup_arguments['adults'] = 0;
			}
		}
		if ( $v['name'] == 'tyto_childs' ) {
			if ( $v['value'] == '' ) {
				$setup_arguments['childs'] = 0;
			}
		}
		if ( $v['name'] == 'tyto_prefix' ) {
			if ( $v['value'] != '' ) {
				$setup_arguments['prefix'] = $v['value'];
			} else {
				$setup_arguments['prefix'] = null;
			}
		}
		if ( $v['name'] == 'tyto_lastname' ) {
			if ( $v['value'] != '' ) {
				$setup_arguments['lastname'] = $v['value'];
			} else {
				$setup_arguments['lastname'] = null;
			}
		}
		if ( $v['name'] == 'tyto_phone' ) {
			if ( $v['value'] != '' ) {
				$setup_arguments['phone'] = $v['value'];
			} else {
				$setup_arguments['phone'] = null;
			}
		}
		if ( $v['name'] == 'tyto_offers' ) {
			if ( $v['value'] == 1 ) {
				$setup_arguments['offers'] = 1;
			}
		}
		if ( $v['name'] == 'tyto_privacy' ) {
			if ( $v['value'] == 1 ) {
				$setup_arguments['privacy'] = 1;
			}
		}
	}
	foreach ( $form_data as $key => $val ) {
		if ( $val['name'] == 'tyto_privacy' ) {
			if ( $val['value'] == 1 ) {
				$setup_arguments['message'] .= $val['label'] . ' : true' . PHP_EOL;
			} else {
				$setup_arguments['message'] .= $val['label'] . ' : false' . PHP_EOL;
			}
		} else if ( $val['name'] == 'tyto_offers' ) {
			if ( $val['value'] == 1 ) {
				$setup_arguments['message'] .= $val['label'] . ' : true' . PHP_EOL;
			} else {
				$setup_arguments['message'] .= $val['label'] . ' : false' . PHP_EOL;
			}
		} else {
			$setup_arguments['message'] .= $val['label'] . ' : ' . $val['value'] . PHP_EOL;
		}
	}
	if ( $setup_arguments['travelBegin'] == null && $custom_begin_date != '' ) {
		$setup_arguments['travelBegin'] = $custom_begin_date;
	} else {
		$setup_arguments['travelBegin'] = '3000-01-01';
	}
	if ( $setup_arguments['travelEnd'] == null && $custom_end_date != '' ) {
		$setup_arguments['travelEnd'] = $custom_end_date;
	} else {
		$setup_arguments['travelEnd'] = '3000-01-02';
	}

	// demo data
	$result = array(
		'recordId'               => $setup_arguments['recordId'],
		'recordType'             => $setup_arguments['recordType'],
		'salutation'             => $setup_arguments['salutation'],
		'firstname'              => $setup_arguments['firstname'],
		'email'                  => $setup_arguments['email'],
		'adults'                 => $setup_arguments['adults'],
		'childs'                 => $setup_arguments['childs'],
		'prefix'                 => $setup_arguments['prefix'],
		'lastname'               => $setup_arguments['lastname'],
		'phone'                  => $setup_arguments['phone'],
		'travelBegin'            => $setup_arguments['travelBegin'],
		'travelEnd'              => $setup_arguments['travelEnd'],
		'message'                => $setup_arguments['message'],
		'allowAlternativeOffers' => $setup_arguments['offers'],
		'privacy'                => $setup_arguments['privacy'],

	);
	// Send Post in cloud
	$response = wp_remote_post( $myApi, array(
			'method' => 'POST',
			'body'   => $result,
		)
	);
	// if Something wropng :(
	if ( is_wp_error( $response ) ) {
		$error_message = $response->get_error_message();
		echo "Something went wrong: $error_message";
	}
}

// hmjim end
?>
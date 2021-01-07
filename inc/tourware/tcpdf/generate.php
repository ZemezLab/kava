<?php
add_filter( 'generate_rewrite_rules', function ( $wp_rewrite ) {
	$wp_rewrite->rules = array_merge(
		[ 'download-pdf/(\d+)/?$' => 'index.php?dl_id=$matches[1]' ],
		$wp_rewrite->rules
	);
//    flush_rewrite_rules(); TODO
} );
add_filter( 'query_vars', function ( $query_vars ) {
	$query_vars[] = 'dl_id';

	return $query_vars;
} );
add_action( 'template_redirect', function () {
	$dl_id = intval( get_query_var( 'dl_id' ) );

	if ( $dl_id ) {
		$pdf_file = tyto_pdf( $dl_id );
		header( "Content-Description: File Transfer" );
		header( "Content-Type: application/force-download; charset=UTF-8", true, 200 );
		header( "Content-Disposition: attachment; filename=" . get_the_title( $dl_id ) . '.pdf' );
		header( "Content-Transfer-Encoding:binary" );
		header( "Content-Length: " . filesize( $pdf_file ) );

		ob_clean();
		flush();

		readfile( $pdf_file );
		die;
	}
} );

add_action( "wp_ajax_nopriv_tyto_termine", "tyto_termine" );
add_action( "wp_ajax_tyto_termine", "tyto_termine" );

function tyto_termine() {
	if ( $_POST["termine_start_date"] !== "" && $_POST["termine_end_date"] !== "" && $_POST["termine_price_date"] !== "" ) {
		$tyto_count_day_out = ( strtotime( $_POST["termine_end_date"] ) - strtotime( $_POST["termine_start_date"] ) ) / ( 60 * 60 * 24 );
		$tyto_termine       = $_POST["termine_start_date"] . ' - ' . $_POST["termine_end_date"] . ', ' . $tyto_count_day_out . ' Tag(e) ab ' . $_POST["termine_price_date"] . ' €';
		update_post_meta( $_POST["termine_post_id"], 'tyto_termine', $tyto_termine );
	} else {
		update_post_meta( $_POST["termine_post_id"], 'tyto_termine', '' );
	}
	if ( $_POST["termine_additional_option"] !== "" ) {
		update_post_meta( $_POST["termine_post_id"], 'termine_additional_option', $_POST["termine_additional_option"] );
	} else {
		update_post_meta( $_POST["termine_post_id"], 'termine_additional_option', '' );
	}
}


add_action( "wp_ajax_nopriv_ttyto_pdf", "tyto_pdf" );
add_action( "wp_ajax_ttyto_pdf", "tyto_pdf" );

function tyto_pdf( $dl_id = null ) {
	if ( $_POST['content'] ) {
		$dl_id = $_POST['content'];
	}
	$tyto_termine_out           = get_post_meta( $dl_id, 'tyto_termine' );
	$tyto_additional_option_out = get_post_meta( $dl_id, 'termine_additional_option' );
	$pdflogo                    = \TyTo\Config::getValue( 'pdflogo' );
	$pdftitlecolor              = \TyTo\Config::getValue( 'pdftitlecolor' );
	$pdfsubtitlecolor           = \TyTo\Config::getValue( 'pdfsubtitlecolor' );
	$pdftextcolor               = \TyTo\Config::getValue( 'pdftextcolor' );

	require_once('tcpdf.php');
	$record = json_decode( get_post_meta( $dl_id, 'tytorawdata', true ) );
	tyto_get_route_with_airports( $record );

	class MYPDF extends TCPDF {


		//Page header
		public function Header() {
			$record_h      = json_decode( get_post_meta( get_query_var( 'dl_id' ), 'tytorawdata', true ) );
			$pdftitlecolor = \TyTo\Config::getValue( 'pdftitlecolor' );
			// Set font
			$this->SetFont( 'helvetica', 'M', 12 );
			$head_text = '<p></p>';
			$head_text .= '<p style="color:' . $pdftitlecolor . ';">' . $record_h->client->name . '</p>';
			$this->writeHTMLCell( 0, 0, '', '', $head_text, 0, 1, 0, true, 'C', true );
		}

		// Page footer
		public function Footer() {
			// $record        = json_decode( get_post_meta( get_query_var( 'dl_id' ), 'tytorawdata', true ) );
			$pdftitlecolor = \TyTo\Config::getValue( 'pdftitlecolor' );
			$pdffooter     = \TyTo\Config::getValue( 'pdffooter' );
			// Position at 15 mm from bottom
			$this->SetY( - 15 );
			// Set font
			$this->SetFont( 'helvetica', 'M', 8 );
			// Page number
			$this->Cell( - 30, - 30, $this->getAliasNumPage() . ' / ' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M' );
			$this->StartTransform();
			$this->Rotate( 90 );

			// $this->Cell(90,-30,'Anderungen vorbehalten        Stand  '. date("M, Y"), 0, false, 'C', 0, '', 0, false, 'T', 'M');
			$right_text = '<span style="float:left;color:' . $pdftitlecolor . ';">Änderungen vorbehalten&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Stand&nbsp;&nbsp;' . date( "M, Y" ) . '</span>';
			// $this->writeHTMLCell(90, -30, '', '',$right_texts , 0, 1, 0, true, 'L', true);
			$this->SetFont( 'helvetica', 'B', 8 );
			$right_text .= '<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';
			$right_text .= '<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';
			$right_text .= '<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';
			$right_text .= '<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';
			$right_text .= '<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';
			$right_text .= '<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';
			$right_text .= '<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';
			$right_text .= '<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';
			$right_text .= '<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';
			// $right_text .= '<span style="float:right;color:' . $pdftitlecolor . ';">' . $record->client->name . '</span>';
			$this->writeHTMLCell( 500, - 30, '', '', $right_text, 0, 1, 0, true, 'L', true );
			$this->StopTransform();
			$this->SetFont( 'helvetica', 'M', 8 );
			$footer_bottom = $pdffooter;
			$this->writeHTMLCell( 150, - 30, '', - 20, $pdffooter, 0, 1, 0, true, 'L', true );
		}

		public function Output( $name = 'doc.pdf', $dest = 'I' ) {
			$this->tcpdflink = false;

			return parent::Output( $name, $dest );
		}
	}

	$gallery_img_array = [];
    $tyto_additional_fields = get_option('tyto_additional_fields', []);
	$additionalFields_ = [];
	if ( ! empty( $tyto_additional_fields ) ) {
		foreach ( $tyto_additional_fields as $r ) {
			if ( $r['name'] !== 'servicesIncluded' && $r['name'] !== 'servicesExcluded' ) {
				$additionalFields_[ $r['name'] ] = $r['fieldLabel'];
			}
		}
	}
	set_query_var( 'additional_fields', $additionalFields_ );
	$curr_additional_field = [];
	foreach ( $record as $k => $v ) {
		if ( $k == 'additionalFields' ) {
			if ( $v !== null ) {
				foreach ( $v as $_k => $_v ) {
					if ( $_v !== null && $_v !== '' ) {
						$curr_additional_field[ $_k ] = $additionalFields_[ $_k ];
					}
				}
			}
		}
	}

	if ( count( $record->images ) > 0 ) {
		foreach ( $record->images as $key => $value ) {
			if ( strpos( $value->image, 'unsplash' ) ) {
				$unsplash_img_array  = explode( '?', $value->image );
				$gallery_img         = $unsplash_img_array[0] . '?fm=jpg&crop=focalpoint&fit=crop&w=300' . '{{{}}}' . $unsplash_img_array[0] . '?fm=jpg&crop=focalpoint&fit=crop&w=300';
				$gallery_img_array[] = $gallery_img;
			} else {
				$gallery_thumbnails_option = array(
					"secure"  => true,
					"width"   => 300,
					"crop"    => "fill",
					"gravity" => "center"
				);

				$gallery_original_option = array(
					"secure" => true,
					"width"  => 300
				);

				if ( 'http' === substr( $value->image, 0, 4 ) ) {
					$gallery_thumbnails_option['type'] = 'fetch';
					$gallery_original_option['type']   = 'fetch';
				}
				$gallery_img_thumbnail = \Cloudinary::cloudinary_url( $value->image, $gallery_thumbnails_option );
				$gallery_img_original  = \Cloudinary::cloudinary_url( $value->image, $gallery_original_option );
				$gallery_img           = $gallery_img_thumbnail . '{{{}}}' . $gallery_img_original;
				$gallery_img_array[]   = $gallery_img;
			}
		}
	}
	$show_dates = false;
	if ( count( $record->dates ) ) {
		foreach ( $record->dates as $item ) {
			if ( $item->price ) {
				$show_dates = true;
			}
		}
	}
	foreach ( $record->itinerary as $value ) {
		foreach ( $value->brick->images as $data ) {
			if ( strpos( $data->image, 'unsplash' ) ) {
				$unsplash_img_array  = explode( '?', $data->image );
				$gallery_img         = $unsplash_img_array[0] . '?fm=jpg&crop=focalpoint&fit=crop&w=1024' . '{{{}}}' . $unsplash_img_array[0] . '?fm=jpg&crop=focalpoint&fit=crop&w=1920';
				$gallery_img_array[] = $gallery_img;
			} else {
				$gallery_thumbnails_option = array(
					"secure"  => true,
					"width"   => 1024,
					"crop"    => "fill",
					"gravity" => "center"
				);

				$gallery_original_option = array(
					"secure" => true,
					"width"  => 1400
				);

				if ( 'http' === substr( $data->image, 0, 4 ) ) {
					$gallery_thumbnails_option['type'] = 'fetch';
					$gallery_original_option['type']   = 'fetch';
				}
				$gallery_img_thumbnail = \Cloudinary::cloudinary_url( $data->image, $gallery_thumbnails_option );
				$gallery_img_original  = \Cloudinary::cloudinary_url( $data->image, $gallery_original_option );
				$gallery_img           = $gallery_img_thumbnail . '{{{}}}' . $gallery_img_original;
				$gallery_img_array[]   = $gallery_img;
			}
		}
	}

// create new PDF document
	$pdf = new MYPDF( PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false );

// set document information
	$pdf->SetCreator( PDF_CREATOR );
	$pdf->SetAuthor( $record->client->name );
	$pdf->SetTitle( $record->title );
	$pdf->SetSubject( '' );
	$pdf->SetKeywords( '' );

// set default monospaced font
	$pdf->SetDefaultMonospacedFont( PDF_FONT_MONOSPACED );

// set margins
	$pdf->SetMargins( PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT );
	$pdf->SetHeaderMargin( 5 );
	$pdf->SetFooterMargin( PDF_MARGIN_FOOTER );

// set auto page breaks
	$pdf->SetAutoPageBreak( true, PDF_MARGIN_BOTTOM );

// set image scale factor
// $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	ob_end_clean();
// set some language-dependent strings (optional)
	if ( @file_exists( dirname( __FILE__ ) . '/lang/eng.php' ) ) {
		require_once( dirname( __FILE__ ) . '/lang/eng.php' );
		$pdf->setLanguageArray( $l );
	}

// ---------------------------------------------------------

// set default font subsetting mode
	$pdf->setFontSubsetting( true );

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
	$pdf->SetFont( 'helvetica', '', 10, '', true );
	ob_end_clean();
// Add a page
// This method has several options, check the source code documentation for more information.
	$pdf->AddPage();
// set text shadow effect
// $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

// Set some content to print
	$html = '<style> p,li,div {color:' . $pdftextcolor . ';} span {color:' . $pdftitlecolor . ';} h1 {text-align:center; color:' . $pdftitlecolor . ';} h2 {text-align:center; color:' . $pdfsubtitlecolor . ';} h3 {text-align:center;} strong {color:' . $pdftitlecolor . ';} table{padding:0px 0 20px 0;} a {color:' . $pdfsubtitlecolor . ';} </style>';

	$html .= '<table style="vertical-align:top;text-align:center;">';
	$html .= '<tr>';
	$html .= '<td>';
	$html .= '<a href="' . get_home_url() . '" dir="ltr">';
	$html .= '<img width="200" src="' . $pdflogo . '">';
	$html .= '</a>';
	$html .= '</td>';
	$html .= '</tr>';
	$html .= '</table>';

	$html .= '<table style="text-align:center;">';
	$html .= '<tr>';
	foreach ( $gallery_img_array as $key => $value ) {
		if ( $key < 3 ) {
			$gallery_img_divided = explode( '{{{}}}', $value );
			$html                .= '<td>';
			$html                .= '<a href="' . get_post_permalink( $dl_id ) . '" dir="ltr">';
			$html                .= '<img width="165" height="110" src="' . $gallery_img_divided[1] . '">';
			$html                .= '</a>';
			$html                .= '</td>';
		}
	}
	if ( $record->type == 'INDEPENDENT' ) {
		$diference = strtotime( $record->travelEnd ) - strtotime( $record->travelBegin );
		$print_day = $diference / 86400;
		$print_day = $print_day + 1;

	} else {
		if ( count( $record->itinerary ) != 0 ) {
			$print_day = count( $record->itinerary );
		}
	}
	$html .= '</tr>';
	$html .= '</table>';

	if ( $tyto_termine_out[0] == '' ) {

		if ( $record->type == 'INDEPENDENT' ) {
			if ( $record->itinerary[0]->travel->price == null ) {
				$price_current_state = number_format( $record->price, 0, ',', '.' );
			} else {
				$price_current_state = number_format( $record->itinerary[0]->travel->price, 0, ',', '.' );
			}
		} else {
			if ( $record->itinerary[0]->travel->price == null ) {
				$price_current_state = number_format( $record->price, 0, ',', '.' );
			} else {
				$price_current_state = number_format( $record->itinerary[0]->travel->price, 0, ',', '.' );
			}
		}

		if ( $price_current_state !== '0' ) {
			if ( $print_day > '0' ) {
				$html .= '<h3>';
				$html .= $print_day;
				$html .= ' Tag(e) ab ';
				$html .= $price_current_state;
				$html .= ' € ';
				$html .= '</h3>';
				$html .= $item->note;
			}
			if($print_day == '0' && $record->travelBegin != null){
				$ddate = new DateTime($record->travelBegin);
				$html .= '<h3>';
				$html .= 'Datum: ';
				$html .= $ddate->format('d.m.Y');
				$html .= ' ab ';
				$html .= $price_current_state;
				$html .= ' € ';
				$html .= '</h3>';				
			}
		}

	}
	if ( $tyto_termine_out[0] != '' && $tyto_termine_out[0] != '0' ) {
		$html .= '<h3>';
		$html .= $tyto_termine_out[0];
		$html .= '</h3>';
	}


	$html .= '<div>';
	$html .= '</div>';

	$html .= '<div>';
	$html .= '</div>';
	if ( $record->countries != null && ! empty( $record->countries ) ) {
		$html .= '<h2 style="font-size:24px;text-align:center;">';
		foreach ( $record->countries as $kkey => $vval ) {
			$cnt_country[] = $vval->short_de;
		}
		$html .= implode( ", ", $cnt_country );
		$html .= '</h2>';
	}
	if ( $record->title !== "" ) {
		$html .= '<a style="text-decoration:none;" href="' . get_post_permalink( $dl_id ) . '" dir="ltr">';
		$html .= '<h1 class="title">' . $record->title . '</h1>';
		$html .= '</a>';
	} else {
		$html .= '<a href="' . get_post_permalink( $dl_id ) . '" dir="ltr">';
		$html .= '<h1 class="title">' . $record->itinerary[0]->travel->title . '</h1>';
		$html .= '</a>';
	}
	if ( $record->subtitle != null ) {
		$html .= '<h2 style="font-weight: 400;text-align:center;">' . $record->subtitle . '</h2>';
	}
	$html .= '<div>';
	$html .= '</div>';

	$html .= '<div>';
	$html .= '</div>';


	if ( $record->highlights != null && ! empty( $record->highlights ) ) {
		$html .= '<h2>Highlights</h2>';
		$html .= '<ul>';
		foreach ( explode( "\n", $record->highlights ) as $item ) {
			if ( isset( $item ) && $item !== '' && strlen( $item ) <= 200 && strlen( $item ) > 3 ) {
				$html .= '<li>';
				$html .= $item;
				$html .= '</li>';
			}
		}
		$html .= '</ul>';
	}
	$html .= '<br pagebreak="true"/>';
	if ( $record->description != null ) {
		$html .= '<h2>Informationen</h2>';
		$html .= '<p>' . $record->description . '</p>';
	}
	if ( $record->servicesIncluded != null ) {
		$html .= '<h2>Inklusivleistungen</h2>';
		$html .= '<ul>';
		foreach ( explode( "\n", $record->servicesIncluded ) as $item ) {
			if ( isset( $item ) && $item !== '' && strlen( $item ) <= 200 && strlen( $item ) > 3 ) {
				$html .= '<li><span class="editor-icon editor-icon-tick goto-icon-tick"></span>';
				$html .= $item;
				$html .= '</li>';
			}
		}
		$html .= '</ul>';
	}
	if ( $record->servicesExcluded != null ) {
		$html .= '<h2>Exklusivleistungen</h2>';
		$html .= '<ul>';
		foreach ( explode( "\n", $record->servicesExcluded ) as $item ) {
			if ( isset( $item ) && $item !== '' && strlen( $item ) <= 200 && strlen( $item ) > 3 ) {
				$html .= '<li><span class="editor-icon editor-icon-untick ion-close-round"></span>';
				$html .= $item;
				$html .= '</li>';
			}
		}
		$html .= '</ul>';
	}
	if ( $record->servicesNote != null ) {
		$html .= '<h2>Hinweise</h2>';
		$html .= '<p>';
		$html .= $record->servicesNote;
		$html .= '</p>';
	}


	if ( sizeof( $record->itinerary ) > 0 ) {
		$html      .= '<h2>';
		$html      .= 'Reiseverlauf';
		$html      .= '</h2>';
		$item_date = null;
		$day       = 1;
		if ( isset( $record->dates ) && ! empty( $record->dates ) ) {
			$item_date = date_create( $record->dates[0]->start );
		}
		$step = 0;
		$pos  = 0;
		$last = count( $record->itinerary ) - 1;


		foreach ( $record->itinerary as $kk => $item ) {

			//$html .= '<h1>' . $step - $pos  . '</h1>';
			if ( isset( $item->flights ) && sizeof( $item->flights ) > 0 && $kk != 0 && $kk != $last ) {
				$html .= ' _flight' . $step;
				$step ++;
				$pos = 0;
				// $html .= ' _pos'.$step.'-'.$pos;
			}
			if ( $kk == $last ) {
				// $html .= ' _pos'.$step.'-'.($pos+1);
			}
			if ( $item->days > 1 ) {
				$html .= '<strong>Tag ' . $day . ' - ' . ( $day + $item->days - 1 ) . ': ' . $item->brick->title . '</strong>';
			} else {
				$html .= '<strong>Tag ' . $day . ': ' . $item->brick->title . '</strong>';
			}
			// $html .= '<h2>' . $item->brick->title . '</h2>';
			$day        += $item->days;
			$imgs_lngth = sizeof( $item->brick->images );
			if ( $imgs_lngth > 0 ) {
				$cloudinary_options = array(
					"secure" => true,
					"width"  => 300,
					"height" => 300,
					"crop"   => "thumb"
				);
				$unsplash_options   = '?fm=jpg&crop=focalpoint&fit=crop&h=300&w=300';
				if ( strpos( $item->brick->images[0]->image, 'unsplash' ) ) {
					$img_array = explode( "?", $item->brick->images[0]->image );
					$image_url = $img_array[0] . $unsplash_options;
				} else {
					$image_url = \Cloudinary::cloudinary_url( $item->brick->images[0]->image, $cloudinary_options );
				}
				//$html .= '<img class="tour-acc-img" src="' . $image_url . '" alt="' . $item->brick->title . '">';
			}
			if ( $item->brick->description !== "" ) {
				$html .= strip_tags( $item->brick->description, '<p><h1><strong><b><a><ul><ol><li><br>' );
			} else {
				$html .= '<div></div>';
			}

			$accommodation         = [];
			$accommodation_wp_post = [];
			$acc                   = $item->accommodations[0];

			if ( empty( $acc ) ) {
				$acc = $item->brick->defaultAccommodation;
			}

			if ( $acc ) {

				$accommodations = get_posts( array(
					'meta_key'       => 'tytoid',
					'meta_value'     => isset( $acc->accommodation->id ) ? $acc->accommodation->id : $acc->accommodation,
					'post_type'      => 'tytoaccommodations',
					'post_status'    => 'any',
					'posts_per_page' => 1
				) );

				$accommodation_wp_post = array_shift( $accommodations );
				$accommodation         = json_decode( get_post_meta( $accommodation_wp_post->ID, 'tytorawdata', true ) );

			}

			if ( $accommodation != null ) {

				$meals = array();
				if ( $item->brick->breakfast ) {
					array_push( $meals, '<span style="color:' . $pdftextcolor . ';">Frühstück</span>' );
				}
				if ( $item->brick->lunch ) {
					array_push( $meals, '<span style="color:' . $pdftextcolor . ';">Mittagessen</span>' );
				}
				if ( $item->brick->lunchbox ) {
					array_push( $meals, '<span style="color:' . $pdftextcolor . ';">Lunchbox</span>' );
				}
				if ( $item->brick->dinner ) {
					array_push( $meals, '<span style="color:' . $pdftextcolor . ';">Abendessen</span>' );
				}
				if ( $item->brick->customMealType ) {
					array_push( $meals, '<span style="color:' . $pdftextcolor . ';">' . $item->customMealType . '</span>' );
				}
				if ( empty( $meals ) ) {
					array_push( $meals, '<span style="color:' . $pdftextcolor . ';">Selbstversorger</span>' );
				}

				$hotel_img_options = array(
					"secure" => true,
					"width"  => 300,
					"height" => 300,
					"crop"   => "thumb"
				);
				if ( 'http' === substr( $accommodation->images[0]->image, 0, 4 ) ) {
					$hotel_img_options['type'] = 'fetch';
				}
				$html .= ( (int) $item->brick->days > 1 ) ? 'Übernachtungen in' : '<span style="color:' . $pdftextcolor . '; font-weight:bold;">Übernachtung: ' . $accommodation->title . '</span><div></div>';
				// $html .= '<span style="color:' . $pdftextcolor . ';">' . $item->brick->defaultAccommodation->title . '</span><div></div>';
				$html .= '<span style="color:' . $pdftextcolor . '; font-weight:bold;">Verpflegung: </span>';
				$html .= join( ' / ', $meals );
				$html .= '<div class="sec">';
				if ( $accommodation->images[0]->image ) {
					//$html .= '<img style="display:block;" src="' . zCloudinary::cloudinary_url($accommodation->images[0]->image, $hotel_img_options) . '"><p></p>';
				}
				$html .= '<div></div>';
				$html .= '<span style="color:' . $pdftextcolor . '; font-weight:bold;">Hotelbeschreibung: </span>';
				$html .= $accommodation->description;
				$html .= '</div>';

			}
			$pos ++;
		}
	}
	if ( $tyto_termine_out[0] != null ) {
		$html .= '<div>';
		$html .= '<h2>Termine und Preise</h2>';
		$html .= '<p>' . $tyto_termine_out[0] . ' </p>';
		$html .= '</div>';
	}
	if ( $record->additionalOptions != null ) {
		$html .= '<h2>Optionen und Pakete</h2>';
		foreach ( $record->additionalOptions as $option ) {

			if ( $option->label !== $tyto_additional_option_out[0] ) {
				$html .= '&nbsp;&nbsp;&nbsp;&nbsp;<b>' . $option->label . ': </b> ';
				$html .= '<span>' . number_format( $option->price, 0, ',', '.' ) . ' €</span>';
				$html .= '<br>';
			} else {
				$html .= 'X&nbsp;&nbsp;<b>' . $option->label . ': </b> ';
				$html .= '<span>' . number_format( $option->price, 0, ',', '.' ) . ' €</span>';
				$html .= '<br>';
			}

		}
	}

// Print text using writeHTMLCell()
	$pdf->writeHTMLCell( 0, 0, '', '', $html, 0, 1, 0, true, '', true );

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
	return $pdf->Output( $record->title . '.pdf', 'I' );
//============================================================+
// END OF FILE
//============================================================+

}

?>
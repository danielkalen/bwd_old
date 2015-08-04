<?php

class K_PDFRenderer extends TCPDF {

	public $k_headerColor = array( 255, 255, 255 );
	public $k_headerBackground = array( 255, 255, 255 );
	public $k_headerTitle = '';
	public $k_headerSubTitle = '';
	public $k_headerTitleColor = '';
	public $k_headerSubTitleColor = '';
	public $k_logoFilePath = '';
	public $k_showLines = true;
	public $k_customHeader = false;
	public $k_headerHTML = '';
	public $k_customFooter = false;
	public $k_footerHTML = '';
	public $k_linesColor = '';
	public $logo = '';
	static $charWidthMap = array();
	static $strArrMap = array();


	public function Header() {


		$this->Rect( 0, 0, 210, 297, 'F', '', $this->k_headerColor );
		$this->Rect( 0, 0, 210, 29, 'F', '', $this->k_headerBackground );

		if ( $this->header_xobjid === false ) {


			if ( $this->k_customHeader ) {
				$headerdata = $this->getHeaderData();
				$headerdata['logo'] = $this->k_logoFilePath;

				$this->header_xobjid = $this->startTemplate( $this->w, $this->tMargin );
/*
				$this->writeHTMLCell(0,0,$this->original_lMargin,$this->getHeaderMargin(),'<table style="border-bottom:1x solid black">
    <tr>
        <td width="25%"><b>Nostoi di Marita Marinucci</b><br>Via Ipponio, 2<br>00183 - Roma<br>p.i. 12783791002</td>
        <td width="20%">
            <img src="'.$headerdata['logo'].'" height="80">
        </td>
        <td width="50%" style="text-align:right"><br><br><br>'.$this->k_headerSubTitle.'</td>
    </tr>
</table>');
*/
				$this->writeHTMLCell(0,0,$this->original_lMargin,$this->getHeaderMargin(),$this->k_headerHTML);
				$this->endTemplate();
			} else {
				$this->header_xobjid = $this->startTemplate( $this->w, $this->tMargin );
				$headerfont = $this->getHeaderFont();
				$headerdata = $this->getHeaderData();
				$this->y = $this->header_margin;
				if ( $this->rtl ) {
					$this->x = $this->w - $this->original_rMargin;
				} else {
					$this->x = $this->original_lMargin;
				}
				$headerdata['logo'] = $this->k_logoFilePath;
				if ( ( $headerdata['logo'] ) AND ( $headerdata['logo'] != K_BLANK_IMAGE ) ) {
					$imgtype = TCPDF_IMAGES::getImageFileType( K_PATH_IMAGES . $headerdata['logo'] );
					if ( ( $imgtype == 'eps' ) OR ( $imgtype == 'ai' ) ) {
						$this->ImageEps( $headerdata['logo'], '', '', $headerdata['logo_width'] );
					} elseif ( $imgtype == 'svg' ) {
						$this->ImageSVG( $headerdata['logo'], '', '', $headerdata['logo_width'] );
					} else {
						$imageSize = getimagesize( $headerdata['logo'] );
						if ( $imageSize !== false ) {
							$scale = 20 / $imageSize[1];
							$headerdata['logo_width'] = $scale * $imageSize[0];
						}
						$this->Image( $headerdata['logo'], '', '', $headerdata['logo_width'], 20 );
					}
					$imgy = $this->getImageRBY();
				} else {
					$imgy = $this->y;
				}
				$cell_height = $this->getCellHeight( $headerfont[2] / $this->k );

				if ( $this->getRTL() ) {
					$header_x = $this->original_rMargin + ( $headerdata['logo_width'] * 1.1 );
				} else {
					$header_x = $this->original_lMargin + ( $headerdata['logo_width'] * 1.1 );
				}
				$cw = $this->w - $this->original_lMargin - $this->original_rMargin - ( $headerdata['logo_width'] * 1.1 );


				// header title
				$this->SetTextColorArray( $this->k_headerTitleColor );
				$this->SetFont( $headerfont[0], 'B', $headerfont[2] + 1 );

				$this->SetY( 10 );
				$this->SetX( $header_x );
				$this->Cell( $cw, $cell_height, $this->k_headerTitle, 0, 1, '', 0, '', 0 );

				// header subtitle
				$this->SetTextColorArray( $this->k_headerSubTitleColor );
				$this->SetFont( $headerfont[0], $headerfont[1], $headerfont[2] );
				$this->SetX( $header_x );
				$this->MultiCell( $cw, $cell_height, $this->k_headerSubTitle, 0, '', 0, 1, '', '', true, 0, false, true, 0, 'T', false );
				// print an ending header line
				$this->SetLineStyle( array(
					'width' => 0.85 / $this->k,
					'cap'   => 'butt',
					'join'  => 'miter',
					'dash'  => 0,
					'color' => $this->k_linesColor
				) );
				$this->SetY( ( 2.835 / $this->k ) + max( $imgy, $this->y ) );
				if ( $this->rtl ) {
					$this->SetX( $this->original_rMargin );
				} else {
					$this->SetX( $this->original_lMargin );
				}

				if ( $this->k_showLines ) {
					$this->Cell( ( $this->w - $this->original_lMargin - $this->original_rMargin ), 0, '', 'T', 0, 'C' );
				}
				$this->endTemplate();
			}


		}

		// print header template
		$x = 0;
		$dx = 0;
		if ( ! $this->header_xobj_autoreset AND $this->booklet AND ( ( $this->page % 2 ) == 0 ) ) {
			// adjust margins for booklet mode
			$dx = ( $this->original_lMargin - $this->original_rMargin );
		}
		if ( $this->rtl ) {
			$x = $this->w + $dx;
		} else {
			$x = 0 + $dx;
		}
		$this->printTemplate( $this->header_xobjid, $x, 0, 0, 0, '', '', false );
		if ( $this->header_xobj_autoreset ) {
			// reset header xobject template at each page
			$this->header_xobjid = - 1;
		}



		// print header template
		$x = 0;
		$dx = 0;
		$this->printTemplate( $this->header_xobjid, $x, 0, 0, 0, '', '', false );
	}

	public function Footer() {

		if ($this->k_customFooter) {
			$this->writeHTMLCell( 210, 0, '', '', $this->k_footerHTML, 0, 1, false, true, '', true );
		} else {

			$cur_y = $this->y;
			$this->SetTextColorArray( $this->k_headerSubTitleColor );
			//set style for cell border


			$line_width = ( 0.85 / $this->k );

			$this->SetLineStyle( array(
					'width' => $line_width,
					'cap'   => 'butt',
					'join'  => 'miter',
					'dash'  => 0,
					'color' => $this->k_linesColor
				) );

			$w_page = isset( $this->l['w_page'] ) ? $this->l['w_page'] . ' ' : '';
			if ( empty( $this->pagegroups ) ) {
				$pagenumtxt = $w_page . $this->getAliasNumPage() . ' / ' . $this->getAliasNbPages();
			} else {
				$pagenumtxt = $w_page . $this->getPageNumGroupAlias() . ' / ' . $this->getPageGroupAlias();
			}
			$this->SetY( $cur_y );
			//Print page number
			if ( $this->getRTL() ) {
				$this->SetX( $this->original_rMargin );
				$this->Cell( 0, 0, $pagenumtxt, 'T', 0, 'L' );
			} else {
				$this->SetX( $this->original_lMargin );
				$this->Cell( 0, 0, $this->getAliasRightShift() . $pagenumtxt, ( $this->k_showLines ) ? 'T' : 0, 0, 'R' );
			}

		}

		//$this->writeHTMLCell( 210, 0, '', '', '<p style="text-align: center">Via Ipponio, 2 - 00183 – Roma - p.i. 12783791002 – www.nostoi.it – sales@nostoi.it</p>', 0, 1, false, true, '', true );




	}

	public function GetCharWidth( $char, $notlast = true ) {
		if ( isset( K_PDFRenderer::$charWidthMap[ $char ] ) ) {
			$chw = K_PDFRenderer::$charWidthMap[ $char ];
		} else {
			$chw = parent::GetCharWidth( $char, $notlast );
			K_PDFRenderer::$charWidthMap[ $char ] = $chw;
		}

		return $chw;
	}


	public function GetArrStringWidth( $sa, $fontname = '', $fontstyle = '', $fontsize = 0, $getarray = false ) {

		$key = md5( implode( '.', $sa ) . '-' . $fontname . '-' . $fontsize . '-' . $fontstyle . '-' . $getarray );
		if ( isset( K_PDFRenderer::$strArrMap[ $key ] ) ) {
			return K_PDFRenderer::$strArrMap[ $key ];
		} else {
			$w = parent::GetArrStringWidth( $sa, $fontname, $fontstyle, $fontsize, $getarray );
			K_PDFRenderer::$strArrMap[ $key ] = $w;

			return $w;
		}

	}

}
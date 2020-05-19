<?php
use Dompdf\Dompdf;
use Dompdf\Options;

if ( !class_exists( 'NBT_PDF_Maker' ) ) :

	class NBT_PDF_Maker
	{
		public $html;
		public $settings;

		public function __construct( $html, $settings = array() ) {
			$this->html = $html;

			$default_settings = array(
				'paper_size'		=> 'A4',
				'paper_orientation'	=> 'portrait',
				'font_subsetting'	=> false,
			);
			$this->settings = $settings + $default_settings;
		}

		public function output() {
			if ( empty( $this->html ) ) {
				return;
			}

			require NBT_PDF_PATH . 'inc/vendor/autoload.php';

			// set options
			$options = new Options();
			$options->setdefaultFont( 'dejavu sans');
			$options->setTempDir( NBT_Solutions_Pdf_Creator::get_temp('dompdf') );
			$options->setLogOutputFile( NBT_Solutions_Pdf_Creator::get_temp('dompdf') . "/log.htm");
			$options->setFontDir( NBT_Solutions_Pdf_Creator::get_temp('fonts') );
			$options->setFontCache( NBT_Solutions_Pdf_Creator::get_temp('fonts') );
			$options->setIsRemoteEnabled( true );
			$options->setIsFontSubsettingEnabled( $this->settings['font_subsetting'] );

			// instantiate and use the dompdf class
			$dompdf = new Dompdf( $options );
			$dompdf->loadHtml( $this->html );
			$dompdf->setPaper( $this->settings['paper_size'], $this->settings['paper_orientation'] );
			$dompdf = apply_filters( 'wpo_wcpdf_before_dompdf_render', $dompdf, $this->html );
			$dompdf->render();
			$dompdf = apply_filters( 'wpo_wcpdf_after_dompdf_render', $dompdf, $this->html );

			return $dompdf->output();
		}
	}
endif;
?>
<?php
/**
 * @package MB_PiwikTracking\Views\Frontend
 */

if ( !defined( 'MB_PIWIKTRACKING_VERSION' ) ) {
	exit;
}

/**
 * Frontend script class.
 *
 * Outputs the frontend data.
 *
 * @since 1.0.0
 */
abstract class MB_PiwikTracking_ViewFrontendScript {
	/**
	 * Output code.
	 *
	 * Outputs HTML/JavaScrit code according to the options.
	 *
	 * @since 1.0.0
	 *
	 * @param array $options The values defined in the backend.
	 * @return void
	 */
	public static function output( $options, $data = NULL ) {
		?>

<!-- Piwik -->
<script type="text/javascript">var _paq = _paq || []; <?php
		$customVars = array();
		if ( $options['log_usernames'] && isset( $data['username'] ) ) {
			$customVars[1] = array( 'Visitor', $data['username'] );
			echo '_paq.push(["setCustomVariable",1,"Visitor","' . $data['username'] . '","visit"]); ';
		}
?>_paq.push(["trackPageView"]); _paq.push(["enableLinkTracking"]); (function() { var u =<?php
		if ($options['ssl_compat']) {
			echo '(("https:" == document.location.protocol) ? "https" : "http") + "://' . $options['address'] . '/"; ';
		} else {
			echo '"http://' . $options['address'] . '/"; ';
		}
?>_paq.push(["setTrackerUrl", u+"piwik.php"]); _paq.push(["setSiteId", "<?php echo $options['site_id']; ?>"]); var d=document, g=d.createElement("script"), s=d.getElementsByTagName("script")[0]; g.type="text/javascript"; g.defer=true; g.async=true; g.src=u+"piwik.js"; s.parentNode.insertBefore(g,s); })();</script>
<noscript><img src="<?php echo ( $options['ssl_compat'] ? 'https' : 'http' ) . '://' . $options['address']; ?>/piwik.php?idsite=<?php echo $options['site_id']; ?>&amp;rec=1<?php echo (!empty($customVars) ? '&amp;_cvar=' . urlencode(json_encode($customVars)) : '' ); ?>" style="border:0" alt="" /></noscript>
<?php
	}
}

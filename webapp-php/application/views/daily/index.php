<?php slot::start('head') ?>
    <title>Crashes per Active Daily User for <?php out::H($product) ?></title>
    <link title="CSV formatted Crashes per Active Daily User for <?php out::H($product) ?>" type="text/csv" rel="alternate" href="<?php out::H($url_csv); ?>" />

<?php
echo html::stylesheet(array(
		'css/daily.css',
	), array('screen', 'screen'));
?>
<?php slot::end() ?>

<?php
echo '<script>var data = ' . json_encode($graph_data) . ";\n";
if ($form_selection == 'by_report_type') {?>
    window.socGraphByReportType = true;
<?php } else { ?>
    window.socGraphByReportType = false;
<?php } ?>
</script>
<?php
echo html::script(array(
		'js/flot-0.7/jquery.flot.pack.js',
		'js/socorro/daily.js',
	));
?>


<div class="page-heading">
	<h2>Crashes per Active Daily User</h2>
</div>

<?php
    View::factory('daily/daily_search', array(
        'date_start' => $date_start,
        'date_end' => $date_end,
        'duration' => $duration,
        'form_selection' => $form_selection,
		'graph_data' => $graph_data,
        'operating_system' => $operating_system,
        'operating_systems' => $operating_systems,
        'product' => $product,
        'products' => $products,
        'hang_type' => $hang_type,
        'throttle_default' => $throttle_default,
        'url_form' => $url_form,
        'versions' => $versions
	))->render(TRUE);
?>

<div class="panel daily_graph">
	<div class="title">
		<h2>Crashes per 100 ADUs</h2>
    </div>

	<div class="body">
        <?php if (!empty($graph_data)) { ?>
		    <div id="adu-chart"></div>
		    <p class="adu-chart-help">This graph uses an approximate <a href="https://wiki.mozilla.org/Socorro/SocorroUI/Branches_Admin#Throttle">throttle value</a> for each version, which may not be completely accurate for the entire time period.</p>
        <?php } else { ?>
            <p>No Active Daily User crash data is available for this report.</p>
        <?php } ?>
	</div>
</div>


<?php if (!empty($graph_data) && $form_selection == 'by_report_type') { ?>
<form id="adu-chart-controls"><div class='graph-ratio-checkbox'>
    <table><tr><th></th><?php
         foreach ($chosen_report_types as $rt) { ?><th><?= $rt ?></th> <?php } ?></tr><?php
        for ($i = 0; $i < count($graph_data); $i++){
	$item = $graph_data[$i];
	if ($i % count($statistic_keys) == 0) {
	$version_index = $i / count($statistic_keys);
	if ($i != 0) {
	    echo "</tr>\n";
	} ?><tr><th><?= $versions[$version_index]?></th><?php
	}
?><td><input type='checkbox' name='graph_data_<?= $i ?>' id='graph_data_<?= $i ?>' checked="checked" title="<?= $item['label'] ?>" /></td>
	<?php } ?>
    </tr></table>
</div>
<div><button>Update Graph</button></div>
</form>
<?php } ?>


<br class="clear" />

<?php
    if (!empty($graph_data)) {
        View::factory($file_crash_data, array(
            'dates' => $dates,
            'operating_systems' => $operating_systems,
            'results' => $results,
            'statistics' => $statistics,
	    	'url_csv' => $url_csv,
            'versions' => $versions
	    ))->render(TRUE);
    }
?>

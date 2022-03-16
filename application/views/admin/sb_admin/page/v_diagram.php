
<link rel="stylesheet" type="text/css" href="baru/jquery.dataTables.css">
<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

<div class="row">
	<div class="col-md-6">
		<h2>Loyal:</h2>
		<table class="table table-bordered" id="tableLoyal">
			<thead>
				<tr>
					<th>No</th>
					<th>Nama</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$no=1;
					foreach ($dataTeamLoyal as $key) {
				?>
				<tr>
					<td><?php echo $no; ?></td>
					<td><?php echo $key->nama; ?></td>
				</tr>
				<?php $no++; }?>
			</tbody>
		</table>
	</div>
	<div class="col-md-6">
		<h2>Kurang Loyal:</h2>
		<table class="table table-bordered" id="tableTidakLoyal">
			<thead>
				<tr>
					<th>No</th>
					<th>Nama</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$no=1;
					foreach ($dataTeamTidakLoyal as $key) {
				?>
				<tr>
					<td><?php echo $no; ?></td>
					<td><?php echo $key->nama; ?></td>
				</tr>
				<?php $no++; }?>
			</tbody>
		</table>
	</div>
</div>

<script src="js/jquery-1.10.1.min.js"></script>
<script type="text/javascript" charset="utf8" src="baru/jquery.dataTables.js"></script>
<script src="baru/highcharts.js"></script>
<script src="baru/modules/exporting.js"></script>
<script src="baru/export-data.js"></script>
<script type="text/javascript">
Highcharts.chart('container', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Diagram Clustering'
    },
    xAxis: {
        categories: ['Loyal', 'Kurang Loyal']
    },
    yAxis: {
        title: {
            text: 'Total Pelanggan'
        }

    },

    credits: {
        enabled: false
    },
    series: [{
        name: 'Loyalitas',
        data: [<?php echo $totalLoyal; ?>,<?php echo $totalTidakLoyal; ?>]
    }]
});

$(document).ready( function () {
    $('#tableLoyal').DataTable();
    $('#tableTidakLoyal').DataTable();
} );

</script>
				<div class="row">
<?php 

foreach ($view_details as $view_detail) {

 ?>
					<div class="col-lg-3 col-md-6">
						<div class="panel <?php echo $view_detail[BG_COLOR] ?>">
							<div class="panel-heading">
								<div class="row">
									<div class="col-xs-3">
										<i class="fa <?php echo $view_detail[ICON] ?> fa-5x"></i>
									</div>
									<div class="col-xs-9 text-right">
										<div class="huge"><?php echo ($view_total[$view_detail[NAME]] === NULL) ? "N/A" : $view_total[$view_detail[NAME]] ?></div>
										<div><?php echo $view_detail[NAME] ?></div>
									</div>
								</div>
							</div>
							<a href="<?php echo $view_detail[HREF] ?>">
								<div class="panel-footer">
									<span class="pull-left">View Details</span>
									<span class="pull-right"><i class="fa fa-circle-right"></i></span>
									<div class="clearfix"></div>
								</div>
							</a>
						</div>
					</div>
<?php 

}

 ?>
				</div>
				<!-- /.row -->

				<div class="row">
					<script type="text/javascript">
				      google.charts.load('current', {'packages':['bar']});
				      google.charts.setOnLoadCallback(drawChart);

				      function drawChart() {
				        // var data = google.visualization.arrayToDataTable([
				        //   ['Year', 'Sales', 'Expenses', 'Profit'],
				        //   ['2014', 1000, 400, 200],
				        //   ['2015', 1170, 460, 250],
				        //   ['2016', 660, 1120, 300],
				        //   ['2017', 1030, 540, 350]
				        // ]);
				        var data = google.visualization.arrayToDataTable([
<?php echo $google_visualization_arrayToDataTable($columns, $column_total) ?>
				        ]);

				        var options = {
				          chart: {
				            title: '',
				            subtitle: '',
				          }
				        };

				        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

				        chart.draw(data, google.charts.Bar.convertOptions(options));
				      }
				    </script>
				    <div id="columnchart_material" style="width: 'auto'"></div>
				</div>

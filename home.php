<?php include 'db_connect.php' ?>
<!-- <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Category', 'Sold Per Day'],
          ['Chinese',     11],
          ['Mexican',      2],
          ['Pizza',  2],
          ['Japanese', 2],
          ['Thai',    7]
        ]);

        var options = {
          
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
    </script>
<style>
   span.float-right.summary_icon {
    font-size: 3rem;
    position: absolute;
    right: 1rem;
    top: 0;
}
.imgs{
		margin: .5em;
		max-width: calc(100%);
		max-height: calc(100%);
	}
	.imgs img{
		max-width: calc(100%);
		max-height: calc(100%);
		cursor: pointer;
	}
	#imagesCarousel,#imagesCarousel .carousel-inner,#imagesCarousel .carousel-item{
		height: 60vh !important;background: black;
	}
	#imagesCarousel .carousel-item.active{
		display: flex !important;
	}
	#imagesCarousel .carousel-item-next{
		display: flex !important;
	}
	#imagesCarousel .carousel-item img{
		margin: auto;
	}
	#imagesCarousel img{
		width: auto!important;
		height: auto!important;
		max-height: calc(100%)!important;
		max-width: calc(100%)!important;
	}
</style> -->

<div class="container-fluid1">
	<div class="row mt-3 ml-3 mr-3 dashcard">
        <!-- <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <?php echo "Welcome back". $_SESSION['login_name']."!"  ?>
                    <hr>
                </div>
            </div>      			
        </div> -->
        <div class="col-lg-12">
          <div class="Welcome mt-0 mb-3"><h5><?php echo "Welcome back, ". $_SESSION['login_name']."!";?><h5></div>
        </div>
        <!-- Left side -->
        <div class="container">
    <div class="row">
        <!-- Left side -->
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12 mb-5">
                    <div class="card border-0">
                        <div class="card-body">
                           <h6 class="text-center mt-1 mb-2"> <b>Favorite Orders for the Month</b></h6>
                           
                            <!-- Styles -->
                              <style>
                              #chartdiv1 {
                                width: 100%;
                                height: 350px;
                                background-color: white;
                              }
                              </style>

                              <!-- Resources -->
                              <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
                              <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
                              <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

                              <!-- Chart code -->
                              <script>
                              am5.ready(function() {


                              // Create root element
                              // https://www.amcharts.com/docs/v5/getting-started/#Root_element
                              var root = am5.Root.new("chartdiv1");


                              // Set themes
                              // https://www.amcharts.com/docs/v5/concepts/themes/
                              root.setThemes([
                                am5themes_Animated.new(root)
                              ]);


                              // Create chart
                              // https://www.amcharts.com/docs/v5/charts/xy-chart/
                              var chart = root.container.children.push(am5xy.XYChart.new(root, {
                                panX: false,
                                panY: false,
                                paddingLeft: 0,
                                wheelX: "panX",
                                wheelY: "zoomX",
                                layout: root.verticalLayout
                              }));


                              // Add legend
                              // https://www.amcharts.com/docs/v5/charts/xy-chart/legend-xy-series/
                              var legend = chart.children.push(
                                am5.Legend.new(root, {
                                  centerX: am5.p50,
                                  x: am5.p50
                                })
                              );

                              var data = [{
                                "year": "2022",
                                "chinese": 2.5,
                                "mexican": 2.5,
                                "pizza": 2.1,
                                "japanese": 1,
                                "korean": 0.8,
                                "thai": 0.4
                              }, {
                                "year": "2023",
                                "chinese": 2.6,
                                "mexican": 2.7,
                                "pizza": 2.2,
                                "japanese": 5,
                                "korean": 2.4,
                                "thai": 2.3
                              }, {
                                "year": "2024",
                                "chinese": 2.8,
                                "mexican": 2.4,
                                "pizza": 2.4,
                                "japanese": 0.3,
                                "korean": 0.9,
                                "thai": 0.5
                              }]


                              // Create axes
                              // https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
                              var xRenderer = am5xy.AxisRendererX.new(root, {
                                cellStartLocation: 0.1,
                                cellEndLocation: 0.9,
                                minorGridEnabled: true
                              })

                              var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
                                categoryField: "year",
                                renderer: xRenderer,
                                tooltip: am5.Tooltip.new(root, {})
                              }));

                              xRenderer.grid.template.setAll({
                                location: 1
                              })

                              xAxis.data.setAll(data);

                              var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
                                renderer: am5xy.AxisRendererY.new(root, {
                                  strokeOpacity: 0.1
                                })
                              }));


                              // Add series
                              // https://www.amcharts.com/docs/v5/charts/xy-chart/series/
                              function makeSeries(name, fieldName) {
                                var series = chart.series.push(am5xy.ColumnSeries.new(root, {
                                  name: name,
                                  xAxis: xAxis,
                                  yAxis: yAxis,
                                  valueYField: fieldName,
                                  categoryXField: "year"
                                }));

                                series.columns.template.setAll({
                                  tooltipText: "{name}, {categoryX}:{valueY}",
                                  width: am5.percent(90),
                                  tooltipY: 0,
                                  strokeOpacity: 0
                                });

                                series.data.setAll(data);

                                // Make stuff animate on load
                                // https://www.amcharts.com/docs/v5/concepts/animations/
                                series.appear();

                                series.bullets.push(function () {
                                  return am5.Bullet.new(root, {
                                    locationY: 0,
                                    sprite: am5.Label.new(root, {
                                      text: "{valueY}",
                                      fill: root.interfaceColors.get("alternativeText"),
                                      centerY: 0,
                                      centerX: am5.p50,
                                      populateText: true
                                    })
                                  });
                                });

                                legend.data.push(series);
                              }

                              makeSeries("Chinese", "chinese");
                              makeSeries("Mexican", "mexican");
                              makeSeries("Pizza", "pizza");
                              makeSeries("Japanese", "japanese");
                              makeSeries("Korean", "korean");
                              makeSeries("Thai", "thai");


                              // Make stuff animate on load
                              // https://www.amcharts.com/docs/v5/concepts/animations/
                              chart.appear(1000, 100);

                              }); // end am5.ready()
                              </script>

                              <!-- HTML -->
                              <div id="chartdiv1"></div>
                                                          
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>

        <!-- Right side -->
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12 mb-5">
                    <div class="card border-0">
                        <div class="card-body">
                            <h6 class="text-center mt-1 mb-2"><b>List of Products for the Month</b></h6>
                            <div id="doughnut" "></div> <!-- Ensure height has 'px' -->
                            <!-- Styles -->
                              <style>
                              #chartdiv {
                                width: 100%;
                                height: 350px;
                                background-color: white;
                              }
                              </style>

                              <!-- Resources -->
                              <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
                              <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
                              <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

                              <!-- Chart code -->
                              <script>
                              am5.ready(function() {

                              // Create root element
                              // https://www.amcharts.com/docs/v5/getting-started/#Root_element
                              var root = am5.Root.new("chartdiv");


                              // Set themes
                              // https://www.amcharts.com/docs/v5/concepts/themes/
                              root.setThemes([
                                am5themes_Animated.new(root)
                              ]);


                              // Create chart
                              // https://www.amcharts.com/docs/v5/charts/xy-chart/
                              var chart = root.container.children.push(am5xy.XYChart.new(root, {
                                panX: true,
                                panY: true,
                                wheelX: "panX",
                                wheelY: "zoomX",
                                pinchZoomX: true,
                                paddingLeft:0,
                                layout: root.verticalLayout
                              }));

                              chart.set("colors", am5.ColorSet.new(root, {
                                colors: [
                                  am5.color(0x73556E),
                                  am5.color(0x9FA1A6),
                                  am5.color(0xF2AA6B),
                                  am5.color(0xF28F6B),
                                  am5.color(0xA95A52),
                                  am5.color(0xE35B5D),
                                  am5.color(0xFFA446)
                                ]
                              }))

                              // Create axes
                              // https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
                              var xRenderer = am5xy.AxisRendererX.new(root, {
                                minGridDistance: 50,
                                minorGridEnabled: true
                              });

                              xRenderer.grid.template.setAll({
                                location: 1
                              })

                              var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
                                maxDeviation: 0.3,
                                categoryField: "country",
                                renderer: xRenderer,
                                tooltip: am5.Tooltip.new(root, {})
                              }));

                              var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
                                maxDeviation: 0.3,
                                min: 0,
                                renderer: am5xy.AxisRendererY.new(root, {
                                  strokeOpacity: 0.1
                                })
                              }));


                              // Create series
                              // https://www.amcharts.com/docs/v5/charts/xy-chart/series/
                              var series = chart.series.push(am5xy.ColumnSeries.new(root, {
                                name: "Series 1",
                                xAxis: xAxis,
                                yAxis: yAxis,
                                valueYField: "value",
                                categoryXField: "country",
                                tooltip: am5.Tooltip.new(root, {
                                  labelText: "{valueY}"
                                }),
                              }));

                              series.columns.template.setAll({
                                tooltipY: 0,
                                tooltipText: "{categoryX}: {valueY}",
                                shadowOpacity: 0.1,
                                shadowOffsetX: 2,
                                shadowOffsetY: 2,
                                shadowBlur: 1,
                                strokeWidth: 2,
                                stroke: am5.color(0xffffff),
                                shadowColor: am5.color(0x000000),
                                cornerRadiusTL: 50,
                                cornerRadiusTR: 50,
                                fillGradient: am5.LinearGradient.new(root, {
                                  stops: [
                                    {}, // will use original column color
                                    { color: am5.color(0x000000) }
                                  ]
                                }),
                                fillPattern: am5.GrainPattern.new(root, {
                                  maxOpacity: 0.15,
                                  density: 0.5,
                                  colors: [am5.color(0x000000), am5.color(0x000000), am5.color(0xffffff)]
                                })
                              });


                              series.columns.template.states.create("hover", {
                                shadowOpacity: 1,
                                shadowBlur: 10,
                                cornerRadiusTL: 10,
                                cornerRadiusTR: 10
                              })

                              series.columns.template.adapters.add("fill", function (fill, target) {
                                return chart.get("colors").getIndex(series.columns.indexOf(target));
                              });

                              // Set data
                              var data = [{
                                country: "Chinese",
                                value: 24
                              }, {
                                country: "Mexican",
                                value: 22
                              }, {
                                country: "Pizza",
                                value: 13
                              }, {
                                country: "Japanese",
                                value: 12
                              }, {
                                country: "Thai",
                                value: 10
                              }];

                              xAxis.data.setAll(data);
                              series.data.setAll(data);

                              // Make stuff animate on load
                              // https://www.amcharts.com/docs/v5/concepts/animations/
                              series.appear(1000);
                              chart.appear(1000, 100);

                              }); // end am5.ready()
                              </script>

                              <!-- HTML -->
                              <div id="chartdiv"></div>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>




            <!-- Table Panel -->
            <div class="col-md-12 mb-5">
                <div class="card">
                    <div class="card-header">
                        <b>List of Orders </b>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Invoice</th>
                                    <th>Order Number</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $i = 1;
                                $order = $conn->query("SELECT * FROM orders order by unix_timestamp(date_created) desc ");
                                while($row=$order->fetch_assoc()):
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $i++ ?></td>
                                    <td>
                                        <p> <?php echo date("M d,Y",strtotime($row['date_created'])) ?></p>
                                    </td>
                                    <td>
                                        <p> <?php echo $row['amount_tendered'] > 0 ? $row['ref_no'] : 'N/A' ?></p>
                                    </td>
                                    <td>
                                        <p><?php echo $row['order_number'] ?></p>
                                    </td>
                                    <td>
                                        <p class="text-center"> <?php echo number_format($row['total_amount'],2) ?></p>
                                    </td>
                                    <td class="text-center">
                                        <?php if($row['amount_tendered'] > 0): ?>
                                            <span class="badge badge-success">Paid</span>
                                        <?php else: ?>
                                            <span class="badge badge-primary">Unpaid</span>
                                        <?php endif; ?>
                                    </td>
                                   
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Table Panel -->
    </div>
</div>

 
<script>
	$('#manage-records').submit(function(e){
        e.preventDefault()
        start_load()
        $.ajax({
            url:'ajax.php?action=save_track',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success:function(resp){
                resp=JSON.parse(resp)
                if(resp.status==1){
                    alert_toast("Data successfully saved",'success')
                    setTimeout(function(){
                        location.reload()
                    },800)

                }
                
            }
        })
    })
    $('#tracking_id').on('keypress',function(e){
        if(e.which == 13){
            get_person()
        }
    })
    $('#check').on('click',function(e){
            get_person()
    })
    function get_person(){
            start_load()
        $.ajax({
                url:'ajax.php?action=get_pdetails',
                method:"POST",
                data:{tracking_id : $('#tracking_id').val()},
                success:function(resp){
                    if(resp){
                        resp = JSON.parse(resp)
                        if(resp.status == 1){
                            $('#name').html(resp.name)
                            $('#address').html(resp.address)
                            $('[name="person_id"]').val(resp.id)
                            $('#details').show()
                            end_load()

                        }else if(resp.status == 2){
                            alert_toast("Unknow tracking id.",'danger');
                            end_load();
                        }
                    }
                }
            })
    }
</script>

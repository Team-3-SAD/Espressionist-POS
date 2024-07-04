<?php include 'db_connect.php'; ?>

<?php
$year = "2024"; // Example year for filtering
$data = []; // Initialize an array to hold data for the chart

// Query to fetch monthly total sales with month names
$query = "SELECT YEAR(date_created) AS year, MONTH(date_created) AS month, SUM(total_amount) AS total_sales 
          FROM orders 
          WHERE amount_tendered > 0 AND YEAR(date_created) = '$year' 
          GROUP BY YEAR(date_created), MONTH(date_created) 
          ORDER BY YEAR(date_created), MONTH(date_created)";

$sales = $conn->query($query);

if ($sales->num_rows > 0) {
    while ($row = $sales->fetch_array()) {
        // Get month name from month number
        $month_name = date("F", mktime(0, 0, 0, $row['month'], 1));
        
        // Prepare data for the chart
        $data[] = [
            "year" => $row['year'],
            "month" => $month_name,
            "total_sales" => floatval($row['total_sales'])
        ];
    }
}
?>

<div class="container-fluid1 mr-5 ml-5">
    <div class="row mt-3 ml-5 mr-5 dashcard">
        <div class="col-lg-12">
            <div class="Welcome mt-0 mb-3"><h5>Welcome back, <?php echo htmlspecialchars($_SESSION['login_name']); ?>!</h5></div>
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
                                    <h6 class="text-center mt-1 mb-2"><b>Total Sales for each Month</b></h6>
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

                                    <script>
                                        am5.ready(function() {
                                            var root = am5.Root.new("chartdiv1");
                                            root.setThemes([
                                                am5themes_Animated.new(root)
                                            ]);

                                            var chart = root.container.children.push(am5xy.XYChart.new(root, {
                                                panX: false,
                                                panY: false,
                                                paddingLeft: 0,
                                                wheelX: "panX",
                                                wheelY: "zoomX",
                                                layout: root.verticalLayout
                                            }));

                                            var legend = chart.children.push(
                                                am5.Legend.new(root, {
                                                    centerX: am5.p50,
                                                    x: am5.p50
                                                })
                                            );

                                            // JavaScript Chart Configuration
                                            var data = <?php echo json_encode($data); ?>;

                                            var xRenderer = am5xy.AxisRendererX.new(root, {
                                                cellStartLocation: 0.1,
                                                cellEndLocation: 0.9,
                                                minorGridEnabled: true
                                            });

                                            var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
                                                categoryField: "month",
                                                renderer: xRenderer,
                                                tooltip: am5.Tooltip.new(root, {})
                                            }));

                                            xRenderer.grid.template.setAll({
                                                location: 1
                                            });

                                            xAxis.data.setAll(data);

                                            var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
                                                renderer: am5xy.AxisRendererY.new(root, {
                                                    strokeOpacity: 0.1
                                                })
                                            }));

                                            // Adjust series creation to use 'total_sales' field
                                            function makeSeries(name, fieldName, color) {
                                                var series = chart.series.push(am5xy.ColumnSeries.new(root, {
                                                    name: name,
                                                    xAxis: xAxis,
                                                    yAxis: yAxis,
                                                    valueYField: fieldName,
                                                    categoryXField: "month",
                                                    fill: color,
                                                    stroke: color
                                                }));

                                                series.columns.template.setAll({
                                                    tooltipText: "{categoryX}:{valueY}",
                                                    width: am5.percent(90),
                                                    tooltipY: 0,
                                                    strokeOpacity: 0
                                                });

                                                series.data.setAll(data);

                                                series.appear();

                                                series.bullets.push(function() {
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

                                            // Call makeSeries for each category
                                            makeSeries("Total Sales for the Month", "total_sales", am5.color(0x191922)); // Adjust color as needed

                                            chart.appear(1000, 100);
                                        });
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
                                    <h6 class="text-center mt-1 mb-2"><b>Number of Product Sales in Each Category</b></h6>
                                    <div id="doughnut"></div> <!-- Ensure height has 'px' -->
                                    <!-- Styles -->
                                    <style>
                                    #chartdiv2 {
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
                                        var root = am5.Root.new("chartdiv2");

                                        root.setThemes([
                                            am5themes_Animated.new(root)
                                        ]);

                                        var chart = root.container.children.push(am5xy.XYChart.new(root, {
                                            panX: true,
                                            panY: true,
                                            wheelX: "panX",
                                            wheelY: "zoomX",
                                            pinchZoomX: true,
                                            paddingLeft: 0,
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
                                        }));

                                        var xRenderer = am5xy.AxisRendererX.new(root, {
                                            minGridDistance: 50,
                                            minorGridEnabled: true
                                        });

                                        xRenderer.grid.template.setAll({
                                            location: 1
                                        });

                                        var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
                                            maxDeviation: 0.3,
                                            categoryField: "category",
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

                                        var series = chart.series.push(am5xy.ColumnSeries.new(root, {
                                            name: "Series 1",
                                            xAxis: xAxis,
                                            yAxis: yAxis,
                                            valueYField: "order_count",
                                            categoryXField: "category",
                                            tooltip: am5.Tooltip.new(root, {
                                                labelText: "{valueY}"
                                            })
                                        }));

                                        series.columns.template.setAll({
                                            tooltipY: 0,
                                            tooltipText: "{category}: {valueY}",
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
                                        });

                                        series.columns.template.adapters.add("fill", function (fill, target) {
                                            return chart.get("colors").getIndex(series.columns.indexOf(target));
                                        });

                                        // Replace with actual data fetched from the database
                                        var data = [
                                            { category: "Espresso-Based", order_count: 24 },
                                            { category: "Ice-Blended", order_count: 22 },
                                            { category: "Non-Caffeinated", order_count: 13 },
                                            { category: "Iced Teas", order_count: 12 },
                                            { category: "Foods", order_count: 10 }
                                        ];

                                        xAxis.data.setAll(data);
                                        series.data.setAll(data);

                                        series.appear(1000);
                                        chart.appear(1000, 100);
                                    });
                                    </script>

                                    <!-- HTML -->
                                    <div id="chartdiv2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 mb-5">
            <div class="row justify-content-center">
                <div class="col-md-12 mb-5">
                    <div class="card border-0">
                        <div class="card-body text-center">
                            <h6 class="text-center mt-1 mb-2"><b>Mix 'N Match</b></h6>

                            <?php
                            // Run the apriori_algo.py script
                            $output = shell_exec('python assets/apriori/apriori.py');

                            // Fetch the specific item with ID 1 from the database
                            $sql = "SELECT antecedent, consequent FROM frequent_items WHERE id = 1";
                            $result = $conn->query($sql);

                            if ($result && $result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                echo '<div class="row">
                                        <div class="col-md-6 mt-3 ">
                                            <div class="Mix1 mb-3">
                                                <img src="assets/uploads/espression.jpg" class="rounded" width="60%">
                                                <h6 class="card-title mt-2">' . htmlspecialchars($row["antecedent"]) . '</h6>
                                                <p class="card-text"> Espressionist Top Pick</p>
                                            </div>
                                        </div>

                                        <div class="row justify-content-center">
                                            <div class="divider2"></div>
                                        </div>

                                        <div class="col-md-6 mt-3 ">
                                            <div class="Mix2 mb-3">
                                                <img src="assets/uploads/espression.jpg" class="rounded" width="60%">
                                                <h6 class="card-title mt-2">' . htmlspecialchars($row["consequent"]) . '</h6>
                                                <p class="card-text"> Espressionist Popular Combo </p>
                                            </div>
                                        </div>
                                    </div>
                                    <center><a href="billing/index.php" class="btn col-md-3 btn-secondary mt-3 mb-4">Order</a></center>';
                            } else {
                                echo '<div class="text-center mb-5">
                                        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                            <img src="assets/uploads/empty state.jpg" class="rounded" width="30%">
                                            <span style="font-size: 18px;"><b>No Data Available</b></span>
                                            <span>No content has been added yet. Get started by adding orders.</span>
                                        </div>
                                    </div>';
                            }

                            $conn->close();
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#manage-records').submit(function(e){
        e.preventDefault();
        start_load();
        $.ajax({
            url:'ajax.php?action=save_track',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success:function(resp){
                resp = JSON.parse(resp);
                if(resp.status == 1){
                    alert_toast("Data successfully saved",'success');
                    setTimeout(function(){
                        location.reload();
                    }, 800);
                }
            }
        });
    });

    $('#tracking_id').on('keypress', function(e){
        if(e.which == 13){
            get_person();
        }
    });

    $('#check').on('click', function(e){
        get_person();
    });

    function get_person(){
        start_load();
        $.ajax({
            url:'ajax.php?action=get_pdetails',
            method:"POST",
            data:{tracking_id : $('#tracking_id').val()},
            success:function(resp){
                if(resp){
                    resp = JSON.parse(resp);
                    if(resp.status == 1){
                        $('#name').html(resp.name);
                        $('#address').html(resp.address);
                        $('[name="person_id"]').val(resp.id);
                        $('#details').show();
                        end_load();
                    }else if(resp.status == 2){
                        alert_toast("Unknown tracking id.",'danger');
                        end_load();
                    }
                }
            }
        });
    }
</script>
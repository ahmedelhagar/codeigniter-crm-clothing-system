<style>
    canvas{
        -moz-user-select: none;
        -webkit-user-select: none;
        -ms-user-select: none;
    }
  </style>
  <script>
    var MONTHS = [<?php
                $i=12;while($i >= 1){
                    $now = new DateTime($i.' days ago');
                    $now->setTimezone(new DateTimezone('Africa/Cairo'));
                    echo "'".$now->format('Y-m-d')."'";
                    echo ($i == 1) ? '' : ',' ;
                $i--;}
                ?>];
    var config = {
        type: 'line',
        data: {
            labels: [<?php
                $i=7;while($i >= 1){
                    $now = new DateTime($i.' days ago');
                    $now->setTimezone(new DateTimezone('Africa/Cairo'));
                    echo "'".$now->format('Y-m-d')."'";
                    echo ($i == 1) ? '' : ',' ;
                $i--;}
                ?>],
            datasets: [
                <?php
                    $stores = $this->users_model->getByData('stores','');
                    $colors = array('red','green','blue','orange','yellow');
                    if($stores){
                        $i = 0;
                        foreach($stores as $store){
                            ?>
                                {
                                    label: 'فرع <?php echo $store['name']; ?>',
                                    backgroundColor: window.chartColors.<?php echo $colors[$i]; ?>,
                                    borderColor: window.chartColors.<?php echo $colors[$i]; ?>,
                                    data: [
                                        <?php 
                                            $monthi=7;while($monthi >= 1){
                                                $moneyVal = 0;
                                                $now = new DateTime($monthi.' days ago');
                                                $now->setTimezone(new DateTimezone('Africa/Cairo'));
                                                $currentDate = $now->format('Y-m-d');
                                                $moneyData = $this->users_model->getByData('transactions',' WHERE (created_at LIKE \'%'.$currentDate.'%\') AND (place_id = '.$store['id'].') AND (state = 0) AND (u_id = '.$this->uri->segment(3).')');
                                                foreach($moneyData as $moneyDat){
                                                    if($moneyData){
                                                        $moneyVal += $moneyDat['price']-$moneyDat['discount'];
                                                    }else{
                                                        $moneyVal = 0;
                                                    }
                                                }
                                                echo $moneyVal;
                                                echo ($monthi == 1) ? '' : ',' ;
                                            $monthi--;}
                                        ?>
                                    ],
                                    fill: false,
                                },
                            <?php
                        $i++;}
                    }
                ?>
        ]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Chart.js Line Chart'
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                }
            },
            hover: {
                mode: 'nearest',
                intersect: true
            },
            scales: {
                x: {
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Month'
                    }
                },
                y: {
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Value'
                    }
                }
            }
        }
    };

    window.onload = function() {
        var ctx = document.getElementById('canvas').getContext('2d');
        window.myLine = new Chart(ctx, config);
    };

    document.getElementById('randomizeData').addEventListener('click', function() {
        config.data.datasets.forEach(function(dataset) {
            dataset.data = dataset.data.map(function() {
                return randomScalingFactor();
            });

        });

        window.myLine.update();
    });

    var colorNames = Object.keys(window.chartColors);
    document.getElementById('addDataset').addEventListener('click', function() {
        var colorName = colorNames[config.data.datasets.length % colorNames.length];
        var newColor = window.chartColors[colorName];
        var newDataset = {
            label: 'Dataset ' + config.data.datasets.length,
            backgroundColor: newColor,
            borderColor: newColor,
            data: [],
            fill: false
        };

        for (var index = 0; index < config.data.labels.length; ++index) {
            newDataset.data.push(randomScalingFactor());
        }

        config.data.datasets.push(newDataset);
        window.myLine.update();
    });

    document.getElementById('addData').addEventListener('click', function() {
        if (config.data.datasets.length > 0) {
            var month = MONTHS[config.data.labels.length % MONTHS.length];
            config.data.labels.push(month);

            config.data.datasets.forEach(function(dataset) {
                dataset.data.push(randomScalingFactor());
            });

            window.myLine.update();
        }
    });

    document.getElementById('removeDataset').addEventListener('click', function() {
        config.data.datasets.splice(0, 1);
        window.myLine.update();
    });

    document.getElementById('removeData').addEventListener('click', function() {
        config.data.labels.splice(-1, 1); // remove the label first

        config.data.datasets.forEach(function(dataset) {
            dataset.data.pop();
        });

        window.myLine.update();
    });
</script>
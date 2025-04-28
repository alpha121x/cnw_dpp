function linkclick(url) {


//    $('#id').html(url);
    on_update_call(url);
    $('#popup-chart-modal').modal('show');
    //console.log(url);
}

function on_update_call(val) {
    var segments = new Array();
    var category = new Array();
    var data = new Array();
    $.ajax({
        url: "services/fillchartsub.php?rdid=" + val,
        type: "GET",
        dataType: "json",
        success: function callback(response) {
            if (response[0]) {
                if (response[0].length > 10) {
//                    $('#chart_popup').width('100%');
                    $('#chart_popup').width(50 * response[0].length);
                } else {
                    $('#chart_popup').width('65%');
                }

                for (var i = 0; i < response[0].length; i++) {
                    segments.push(response[0][i].segment_id);
                }
            }
            if (response[1]) {
                for (var i = 0; i < response[1].length; i++) {
                    category.push(response[1][i].stage);
                }
            }
            if (response[2]) {
                var iMax = response[2].length;
                var jMax = 1;

                for (i = 0; i < iMax; i++) {
                    data[i] = new Array();
                    data[i].push(parseInt(response[2][i].x));
                    data[i].push(parseInt(response[2][i].y));
                    data[i].push(parseInt(response[2][i].status));
                }
                generate_chart(segments, category, data);
            }
        }
    });
}


function generate_chart(segments, category, data) {

    $('#chart_popup').highcharts({

        chart: {
            type: 'heatmap',
            width: 1000,
//		marginTop: 40,
//		marginBottom: 80
        },


        title: {
            text: 'Physical Progress'
        },
        credits: {
            enabled: false
        },

        xAxis: {
            categories: segments,
            title: {
                // text: 'Work Plan Kilometers Wise',
                text: 'Kilometers',
                style: {
                    color: '#4C76AB'
                }
            },
            showFirstLabel: true,
            labels: {
                overflow: 'justify',
                padding: 10,
                rotation: 0,
                x: 20
            }
        },

        yAxis: {
            // categories: ["10%", "20%", "30%", "40%", "50%", "60%", "70%", "80%", "90%", "100%"],
            categories: category,
            // tickPositions: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
            title: {
                text: 'Work Completion',
                style: {
                    color: '#4C76AB'
                }
            },
        },

        colorAxis: {
            dataClasses: [
                {
                    from: 0,
                    to: 0,
                    color: '#ffffff'  // Lightest green
                },
                {
                    from: 1,
                    to: 1,
                    color: '#8ee78a'
                },
                {
                    from: 2,
                    to: 2,
                    color: '#65d463'
                },
                {
                    from: 3,
                    to: 3,
                    color: '#1fac1b'
                },
                {
                    from: 4,
                    to: 4,
                  }
            ]
        },

        legend: {
            align: 'right',
            layout: 'vertical',
            margin: 0,
            enabled: false,
            verticalAlign: 'top',
            y: 25,
            symbolHeight: 280
        },

        tooltip: {
            formatter: function () {
                return 'KM & Work Plan: <b>' + this.series.xAxis.categories[this.point.x] + '</b><br>Completion: <b>' + this.series.yAxis.categories[this.point.y] + '</b>';
            }
        },

        series: [{
            name: 'Status',
            borderWidth: 1,
            data: data,
            dataLabels: {
                enabled: false,
                color: '#000000'
            }
        }]

    });

}
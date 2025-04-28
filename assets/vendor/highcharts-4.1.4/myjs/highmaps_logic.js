var currentLevel = 0;
var divisions = null;
var divisions1 = {};
var files = ["getdivisions.php", "getdistricts.php", "getroads.php"];
//var acronyms = ["MTN", "RWP", "FSD", "SWL", "SRG", "GRW", "LHR", "BWP", "DGK"];
var identifiers = ["", "division", "district"];
var identifiers1 = ["", "Division", "District", "", ""];
var values = new Array(4);
var categories = [];
var chart1Loaded = false;
var chart2Loaded = false;
var played = false;
var timer;
//var colors1 = ['rgba(19,64,117,0.05)', 'rgba(19,64,117,0.2)', 'rgba(19,64,117,0.4)',
//    'rgba(19,64,117,0.5)'];
var colors1 = ['rgba(19,64,117,0.05)', 'rgba(19,64,117,0.2)', 'rgba(19,64,117,0.4)',
    'rgba(19,64,117,0.5)'];
var colors2 = ['rgba(19,64,117,0.05)', 'rgba(19,64,117,0.2)', 'rgba(19,64,117,0.4)',
    'rgba(19,64,117,0.5)'];
var colorsStages = ['#317118', '#FFFB41', '#006FFF', '#000000'];
var fontSize;

var divisionNumber = 0;
var k = 0;
var l = 0;
var c = 0;

var workDoneCounts = {"zero": 0, "first": 0, "second": 0, "third": 0, "fourth": 0};
var utilezedCounts = {"zero": 0, "first": 0, "second": 0, "third": 0, "fourth": 0};


function play() {
    $('#map-button').removeAttr('disabled');
    timer = setInterval(function () {
        chart1Loaded = false;
        chart2Loaded = false;
        if (currentLevel < 3 && divisionNumber < 9) {
            currentLevel = 1;
            values[1] = divisions[divisionNumber];
            values[2] = null;
            values[3] = null;
            values[4] = null;
            $('#map-button').removeAttr('disabled');
            $('#disDivVal').text(identifiers[currentLevel].toUpperCase() + "\n" + divisions[divisionNumber]);
            chart(files[currentLevel], currentLevel, divisions[divisionNumber]);
//            fetchInfo();
            divisionNumber++;
        } else {
            currentLevel = 2;

            if (k < Object.keys(divisions1).length) {
                if (l < divisions1[Object.keys(divisions1)[k]].length - 1) {
                    values[1] = divisions[k];
                    values[2] = divisions1[Object.keys(divisions1)[k]][l];
                    values[3] = null;
//                    values[4] = null;
                    $('#disDivVal').text(identifiers[currentLevel].toUpperCase() + "\n" + divisions1[Object.keys(divisions1)[k]][l]);
                    chart(files[currentLevel], currentLevel, divisions1[Object.keys(divisions1)[k]][l]);
//                    fetchInfo();
//                    console.log(c + " " + divisions1[Object.keys(divisions1)[k]][l]);
                    l++;
                    c++;
                } else {
                    values[1] = divisions[k];
                    values[2] = divisions1[Object.keys(divisions1)[k]][l];
                    values[3] = null;
//                    values[4] = null;
                    $('#disDivVal').text(identifiers[currentLevel].toUpperCase() + "\n" + divisions1[Object.keys(divisions1)[k]][l]);
                    chart(files[currentLevel], currentLevel, divisions1[Object.keys(divisions1)[k]][l]);
//                    console.log(c + " " + divisions1[Object.keys(divisions1)[k]][l]);
//                    fetchInfo();
                    c++;
                    l = 0;
                    k++;
                }
            } else {
                divisions = null;
                divisions1 = {};
                values = new Array(4);
                currentLevel = 0;
                divisionNumber = 0;
                k = 0;
                l = 0;
                c = 0;
                chart(files[currentLevel], currentLevel, "");
//                fetchInfo();
            }
        }

    }, 4000);
//    }, 0);
}

function fetchInfo() {
    var url = "services/getinfo.php?";
    for (var i = 1; i <= values.length; i++) {
        if (values[i] != null) {
            url += identifiers[i] + "=" + values[i] + "&";
        }
    }
//    console.log(url);
//    console.log(values);
    $.ajax({
        url: url,
        type: "GET",
        dataType: 'json',
        async: true,
        success: function (res) {
//            console.log(res);
//            $('#workDoneVal').text(parseFloat(res.workdone).toFixed(2) + " %");
//            if (parseFloat(res.utilization) == 0) {
//                $('#utilizeVal').text(0 + " %")
//            } else {
//                $('#utilizeVal').text((parseFloat(res.utilization) / parseFloat(res.allocate) * 100).toFixed(2) + " %");
//            }
//            $('#roadVal').text(res.road_count);
//            $('#KMVal').text(Math.floor(res.road_length));
//            $('#allocationVal').text(Math.floor(parseFloat(res.allocate) / 1000000) + " M");
//            $('#releaseVal').text(Math.floor(parseFloat(res.utilization / 1000000)) + " M");
//            if (currentLevel == 0) {
//                $('#KMVal').text(1532);
//                $('#allocationVal').text(16500 + " M");
//                $('#roadVal').text(151);
//            }

            scope.$apply(function () {
                scope.workDoneVal = res[1].workdone;
                // scope.utilizeVal = (res[0].utilization / res[0].allocate) * 100;
				scope.utilizeVal = (res[0].utilization) / (res[0].allocate/ 1000000) * 100;
                scope.roadVal = res[0].road_count;
                scope.KMVal = res[0].road_length;
                scope.allocationVal = res[0].allocate / 1000000;
                // scope.releaseVal = res.utilization / 1000000;
				scope.releaseByPndVal = res[0].release_by_pnd;
                scope.contrReleaseVal = res[0].controlling_release;
				scope.revised_allocation = res[1].revised_allocation;
				
				  scope.utilization = res[0].utilization;
                scope.actual_expenditure_24 = res[0].actual_expenditure_24;
				scope.roadsCount = res[0].road_count
                // if (currentLevel == 0) {
                // scope.roadVal = 154;
                // scope.KMVal = 1455;
                // scope.allocationVal = 16000;
                // }
            });
        },
        error: function () {
            alert("Something is wrong!");
        }
    });
}

function chart(filename, level, identifier) {

    if (scope != null) {
        scope.progress = [];
    }
    var url = "services/" + filename;
    if (identifier != "") {
        url = "services/" + filename + "?" + identifiers[level] + "=" + identifier;
    }
//    console.log(url);
    var mapData = [];
    var data1 = [];
    var data2 = [];
    var stages = [], stagesProgress = [];
    categories = [];
    var chartData1 = [];
//    var chartData1 = {name: 'Districts', data : []};
//    var chartData2 = {name : "Punjab", data: []};
    var chartData2 = [];
    var mapChart1;
    var mapChart2;
    var highChart1, highChart2, highChart3;
    var minWork, maxWork, minUtilMoney, maxUtilMoney;

    if (currentLevel == 0) {
        fontSize = '7px';
    } else {
        fontSize = '11px';
    }

    $.ajax({
        url: url,
        type: "GET",
        dataType: 'json',
        async: true,

        success: function callback(response) {
            //console.log(response);
//            var temp1 = [];
//            var temp2 = [];
//            if(currentLevel<3){
            for (var key in workDoneCounts) {
                workDoneCounts[key] = 0;
            }
            for (var key in utilezedCounts) {
                utilezedCounts[key] = 0;
            }
            //mapData = $.parseJSON(response.split("*")[0]);
            mapData = $.parseJSON(response[0]);
            //stages = $.parseJSON(response.split("*")[1]);
            stages = $.parseJSON(response[1]);
			stagesProgress = $.parseJSON(response[2]);
//            console.log(stages);
//            var scope = angular.element($("#table_div")).scope();
            scope.$apply(function () {
                scope.re_res = stages.resovled_re;
                scope.re_unres = stages.unresovled_re;
                scope.tpv_res = stages.resovled_tpv;
                scope.tpv_unres = stages.unresovled_tpv;
                scope.approvedVal = stages.approved;
                scope.unapprovedVal = stages.unapproved;
                //scope.total_re = stages.compaction_re;
                scope.total_re = stages.total_re;
                scope.pass_re = stages.total_re_pass;
                scope.fail_re = stages.total_re_fail;

                scope.total_dept = stages.total_dept;
                scope.pass_dept = stages.total_dept_pass;
                scope.fail_dept = stages.total_dept_fail;

                scope.total_tpv = stages.total_tpv;
                scope.pass_tpv = stages.total_tpv_pass;
                scope.fail_tpv = stages.total_tpv_fail;

                scope.total_pass = stages.total_re_pass + stages.total_dept_pass + stages.total_tpv;
                scope.total_fail = stages.total_re_fail + stages.total_dept_fail + stages.total_tpv_fail;
				
                //	if (currentLevel == 0) {
                //        scope.constructionVal = 21;
                //		scope.rehabilitationVal = 133;
                //   }
            });
//            }
//            else{
//                mapData = $.parseJSON(response.split(",*")[0]);
//                stages = $.parseJSON(response.split("*")[1]);
//            }
            $('#earthWorkVal').text(stagesProgress['ew'] + " % | " + stagesProgress['ew_km'] + " KM");
            $('#subBaseVal').text(stagesProgress['sb'] + " % | " + stagesProgress['sb_km'] + " KM");
            $('#baseVal').text(stagesProgress['base'] + " % | " + stagesProgress['b_km'] + " KM");
            $('#surfaceVal').text(stagesProgress['surface'] + " % | " + stagesProgress['sf_km'] + " KM");
            // $('#furnitureVal').text(stages['furniture'] + " KMs");
//            $('#issueResolve').text(stages['resovled'] + " / "+ stages['unresovled']);
            for (var j = 0; j < mapData.features.length; j++) {

//                console.log(mapData.features[j].properties.physical_p);
//                chartData1.push(Math.floor(mapData.features[j].properties.physical_p));
                chartData1.push(mapData.features[j].properties.physical_p);

//                chartData2.push(Math.floor(mapData.features[j].properties.utilization_p));
                chartData2.push(mapData.features[j].properties.utilization_p);
//                chartData1["data"].push[Math.floor(mapData.features[j].properties.physical_p)];
//                chartData2["data"].push[Math.floor(mapData.features[j].properties.utilization)];

                if (mapData.features[j].properties.physical_p == 0) {
                    workDoneCounts["zero"] += 1;
                } else if (mapData.features[j].properties.physical_p > 0 && mapData.features[j].properties.physical_p <= 25) {
                    workDoneCounts["first"] += 1;
                } else if (mapData.features[j].properties.physical_p > 25 && mapData.features[j].properties.physical_p <= 50) {
                    workDoneCounts["second"] += 1;
                } else if (mapData.features[j].properties.physical_p > 50 && mapData.features[j].properties.physical_p <= 75) {
                    workDoneCounts["third"] += 1;
                } else if (mapData.features[j].properties.physical_p > 75 && mapData.features[j].properties.physical_p <= 100) {
                    workDoneCounts["fourth"] += 1;
                }

                if (mapData.features[j].properties.utilization_p == 0) {
                    utilezedCounts["zero"] += 1;
                } else if (mapData.features[j].properties.utilization_p > 0 && mapData.features[j].properties.utilization_p <= 25) {
                    utilezedCounts["first"] += 1;
                } else if (mapData.features[j].properties.utilization_p > 25 && mapData.features[j].properties.utilization_p <= 50) {
                    utilezedCounts["second"] += 1;
                } else if (mapData.features[j].properties.utilization_p > 50 && mapData.features[j].properties.utilization_p <= 75) {
                    utilezedCounts["third"] += 1;
                } else if (mapData.features[j].properties.utilization_p > 75 && mapData.features[j].properties.utilization_p <= 100) {
                    utilezedCounts["fourth"] += 1;
                }

                categories.push(mapData.features[j].properties.id);
                if (mapData.features[j].properties.district && mapData.features[j].properties.division) {
                    divisions1[mapData.features[j].properties.division].push(mapData.features[j].properties.district);
                }
                data1.push({
                    "code": mapData.features[j].properties.id,
                    "value": mapData.features[j].properties.physical_p
//                    "value": Math.floor(mapData.features[j].properties.physical_p)
//                    "name" : mapData.features[j].properties.id
                });
                data2.push({
                    "code": mapData.features[j].properties.id,
                    "value": mapData.features[j].properties.utilization_p
//                    "value": Math.floor(mapData.features[j].properties.utilization_p)
//                    "name" : mapData.features[j].properties.id
                });
            }
            if (divisions == null) {
                divisions = categories.slice();
                divisions.sort();
                for (var i in divisions) {
                    divisions1[divisions[i]] = [];
                }
            }
//
            var stagesData = [];
            var stagesCategories = [];

            for (var k = 0; k < 4; k++) {
//                stagesData.push(stages[obj]);
                stagesData.push({y: stages[Object.keys(stages)[k]], color: colorsStages[k]});
//                stagesCategories.push(obj);
            }

            highChart1 = new Highcharts.Chart({
                chart: {
                    type: 'column',
                    renderTo: 'chart1',
                    events: {
                        load: function (event) {
                            fetchInfo();
                        }
                    }
                },
                credits: false,
                title: {
                    text: 'Work Done (Civil Division)',
                    style: {
                        color: 'darkred',
                        fontSize: "16px"
                    }
                },
                subtitle: {
                    text: ''
                },

                xAxis: {
                    categories: categories,
                    crosshair: true
                },
                yAxis: {
                    title: {
                        text: 'Work Done %'
                    }
                },
                colors: ['#FF4D4D'],
                tooltip: {
                    headerFormat: '<span style="font-size:10px">Work Done</span><table>' +
                        '<tr><td style="color:{series.color};padding:0">{} </td>' +
                        '<td style="padding:0"><b>{point.key}: {point.y:.2f} %</b></td></tr>',
                    pointFormat: '',
                    footerFormat: '</table>',
                    shared: false,
                    useHTML: true
                },

                legend: {
                    enabled: false
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        pointWidth: 10,
                        dataLabels: {
                            enabled: false,
                            format: '{point.y:.1f}%'
                        }
                    }
                },

                series: [
                    {
                        name: "",
                        data: chartData1,
                        point: {
                            events: {
                                mouseOver: function () {
//                                    console.log(this);
                                    if (chart1Loaded) {
                                        mapChart1.tooltip.refresh(mapChart1.series[0].data[this.index]);
                                        mapChart1.series[0].data[this.index].setState('hover');
                                    }
                                },
                                mouseOut: function () {
                                    if (chart1Loaded) {
                                        mapChart1.tooltip.hide();
                                        mapChart1.series[0].data[this.index].setState();
                                    }
                                }
                            }
                        }
                    }
                ]
            });


            highChart2 = new Highcharts.Chart({
                credits: false,
                chart: {
                    type: 'column',
                    renderTo: 'chart2'
                },
                title: {
                    text: 'Financial Progress (Civil Division)',
                    style: {
                        color: 'blue',
                        fontSize: "16px"
                    }
                },
                subtitle: {
                    text: ''
                },

                xAxis: {
                    categories: categories,
                    crosshair: true
                },
                yAxis: {
                    title: {
                        text: 'Rs Utilized %'
                    }
                },

                tooltip: {
                    headerFormat: '<span style="font-size:10px">Rs Utilized</span><table>' +
                        '<tr><td style="color:{series.color};padding:0">{} </td>' +
                        '<td style="padding:0"><b>{point.key}: {point.y:.2f} %</b></td></tr>',
                    pointFormat: '',
                    footerFormat: '</table>',
                    shared: false,
                    useHTML: true
                },

                legend: {
                    enabled: false
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        pointWidth: 10,
                        dataLabels: {
                            enabled: false,
                            format: '{point.y:.1f}%'
                        }
                    }
                },

                series: [
                    {
                        name: "", data: chartData2,
                        point: {
                            events: {
                                mouseOver: function () {
//                                console.log(this);
                                    if (chart1Loaded) {
                                        mapChart2.tooltip.refresh(mapChart2.series[0].data[this.index]);
                                        mapChart2.series[0].data[this.index].setState('hover');
                                    }
                                },
                                mouseOut: function () {
                                    if (chart1Loaded) {
                                        mapChart2.tooltip.hide();
                                        mapChart2.series[0].data[this.index].setState();
                                    }
                                }
                            }
                        }
                    }
                ]
            });


            highChart3 = new Highcharts.Chart({
                chart: {
                    type: 'column',
                    renderTo: 'chart3',
                    events: {
                        load: function (event) {

                        }
                    }
                },
                credits: false,
                title: {
                    text: 'Physical Progress (Component Wise)',
                    style: {
                        fontSize: "16px"
                    }
                },
                subtitle: {
                    text: ''
                },

                xAxis: {
                    categories: ["Earth Work", "Sub Base", "Base", "Surface"],
                    crosshair: true
                },
                yAxis: {
                    title: {
                        text: 'Progress %'
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{} </td>' +
                        '<td style="padding:0"><b>Percentage: {point.y:.1f}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },

                legend: {
                    enabled: false
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        pointWidth: 10,
                        dataLabels: {
                            enabled: false,
                            format: '{point.y:.1f}%'
                        },
                        column: {
                            colorByPoint: true
                        }
                    }
                },

                series: [
                    {
                        name: "Stages", data: stagesData
                    }
                ]
            });


            mapChart1 = new Highcharts.Map({
                credits: false,
                chart: {
                    renderTo: 'map1',
                    borderWidth: 0,
                    events: {
                        load: function (event) {
                            //var scope = angular.element($("#table_div")).scope();
                            chart1Loaded = true;
                            scope.getPhysicalProgress();
                            scope.progress = [];
                        }
                    }

                },

//                colors: colors1,

                title: {
                    text: ''
                },

                mapNavigation: {
                    enabled: true
//                            enableDoubleClickZoomTo: true
                },

                legend: {
                    title: {
                        text: '',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
                        }
                    },
                    align: 'right',
                    itemStyle: {
                        color: 'white',
                        fontSize: "11px"
                    },
                    verticalAlign: 'middle',
                    floating: false,
                    layout: 'vertical',
                    backgroundColor: '#303030',
                    valueDecimals: 0,
                    width: 90,
                    maxHeight: 150,
                    symbolPadding: 5,
                    itemDistance: 100,
                    itemMarginBottom: -5,
                    itemMarginTop: 0
//                    backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || 'rgba(255, 255, 255, 0.85)'
//                            symbolRadius: 0,
//                            symbolHeight: 14
                },


                colorAxis: {
                    dataClasses: [
                        {
                            to: 0,
                            color: '#ffffff',
                            name: 0 + " (" + workDoneCounts["zero"] + ")"
                        },
                        {
                            from: 0.000000000001,
                            to: 25,
                            name: "0.1 - 25" + " (" + workDoneCounts["first"] + ")",
                            color: '#FFCCCC'
                        },
                        {
                            from: 25.000000000001,
                            to: 50,
                            name: "25 - 50" + " (" + workDoneCounts["second"] + ")",
                            color: '#FF8080'
                        },
                        {
                            from: 50.000000000001,
                            to: 75,
                            name: "50 - 75" + " (" + workDoneCounts["third"] + ")",
                            color: '#FF3333'
                        },
                        {
                            from: 75.000000000001,
                            to: 200,
                            name: "75 - 100" + " (" + workDoneCounts["fourth"] + ")",
                            color: 'rgba(204, 0, 0, 1)'
                        }
                    ],
//                    minColor : 'rgba(255, 204, 204, 1)',
//                    maxColor : 'rgba(178, 0, 0, 0.8)',
//                    tickInterval: 10,
                    tickLength: 10,
                    tickColor: 'rgba(178, 0, 0, 0.5)',
                    tickWidth: 1
//                    min: 1,
//                    max: 100
                },

                xAxis: {
                    minRange: 0.1
                },

                yAxis: {
                    minRange: 0.1
                },

                series: [
                    {
                        data: data1,
                        mapData: mapData,
                        joinBy: ["id", "code"],
                        animation: true,
                        name: 'Work Done (Civil Division)',
                        states: {
                            hover: {
                                color: '#BADA55'
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            format: '{point.properties.id}',
                            style: {
                                fontSize: fontSize,
                                textShadow: "0 0 0px contrast,0 0 3px contrast"
                            },
                            allowOverlap: true
//                            rotation : 15
                        },
//                        tooltip: {
//
//                            valueSuffix: '%',
//                            pointFormat: '{point.code}: {point.value}'
//                        },

                        tooltip: {
                            headerFormat: '',
                            pointFormat: '<table><span style="font-size:10px">Work Done</span><br/><tr><td style="color:{series.color};padding:0">{} </td>' +
                                '<td style="padding:0"><b>{point.code}: {point.value:.2f} %</b></td></tr></table>',
                            footerFormat: '',
                            shared: true,
                            useHTML: true
                        },


                        point: {
                            events: {
                                click: function () {


//                                        mapData = [];
//                                        console.log(this.properties.distt_name);
//                                        console.log($('#container').highcharts().series.entries());
//                                        console.log(mapChart.get(0).data);

//                                        $('#container').highcharts().series[0].remove();
//                                        $.ajax({
//                                            url: "services/getdistrict.php",
//                                            type: "GET",
//                                            dataType: 'text',
//                                            async: false,
//                                            success: function(data){
////                                                mapChart.series[0].setData(new Array($.parseJSON(data)));
//
//
//                                            }
//                                        });
                                    if (currentLevel < 3 && !played) {
                                        scope.$apply(function () {
                                            scope.progressHidden = false;
                                        });
                                        chart1Loaded = false;
                                        chart2Loaded = false;
                                        $('#map-button').removeAttr('disabled');
                                        currentLevel++;
                                        for (j = 0; j <= currentLevel; j++) {
                                            if (j == currentLevel) {
                                                values[j] = this.properties.id
                                            } else if (j > currentLevel) {
                                                values[j] = null;
                                            }
//                                            values[j] = this.properties.id ? j <= currentLevel : null;
                                        }
//                                        $('#disDivVal').text(identifiers1[currentLevel].toUpperCase());
                                        $('#disDivVal').text(identifiers1[currentLevel].toUpperCase() + "\n" + values[currentLevel]);
//                                        $('#disDivIdentifier').text(values[currentLevel]);
//                                        alert(this.properties.id+ ", "+this.properties.temporal_p+ ", "+identifiers[currentLevel]);
                                        chart(files[currentLevel], currentLevel, this.properties.id);
                                        $('#tree').append('<a id=' + currentLevel + ' onclick=goToLevel(this.id); style="cursor: pointer">'
                                            + identifiers1[currentLevel].toUpperCase() + " " + values[currentLevel] + '</a>');
//                                        fetchInfo();
//                                        $('#container').highcharts().setTitle('afdsafaaf');
//                                        $('#container').highcharts().redraw();
                                    } else {
//                                        window.open ("http://202.166.167.115/kprp_cm/main.php?pa="+this.properties.id+"&from=from_cm", '_blank');
                                    }
                                },
                                mouseOver: function () {
//                                    console.log(this);
                                    if (chart1Loaded) {

                                        highChart1.tooltip.refresh(highChart1.series[0].data[this.index]);
                                        highChart1.series[0].data[this.index].setState('hover');
                                    }
//                                    highChart2.tooltip.refresh(highChart2.series[0].data[3]);
                                },
                                mouseOut: function () {
                                    if (chart1Loaded) {
                                        highChart1.tooltip.hide();
                                        highChart1.series[0].data[this.index].setState();
                                    }
                                }
                            }
                        }
                    }
                ]
            });

//            console.log(fontSize);
            mapChart2 = new Highcharts.Map({
                credits: false,
                chart: {
                    renderTo: 'map2',
                    borderWidth: 0,
                    events: {
                        load: function (event) {
                            chart2Loaded = true;
                            setTimeout(function () {
                                getRoadsInfo('box1');
                            }, 550);
                        }
                    }
                },

//                colors: colors2,

                title: {
                    text: ''
                },

                mapNavigation: {
                    enabled: true
//                            enableDoubleClickZoomTo: true
                },

                legend: {
                    title: {
                        text: '',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
                        }
                    },
                    align: 'right',
                    itemStyle: {
                        color: 'white',
                        fontSize: "11px"
                    },
                    verticalAlign: 'middle',
                    floating: false,
                    layout: 'vertical',
                    backgroundColor: '#303030',
                    valueDecimals: 0,
                    width: 90,
                    maxHeight: 150,
                    symbolPadding: 5,
                    itemDistance: 100,
                    itemMarginBottom: -5,
                    itemMarginTop: 0
//                    backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || 'rgba(255, 255, 255, 0.85)'
//                            symbolRadius: 0,
//                            symbolHeight: 14
                },

                xAxis: {
                    minRange: 0.1
                },

                yAxis: {
                    minRange: 0.1
                },

                colorAxis: {
                    dataClasses: [
                        {
                            to: 0,
                            color: '#ffffff',
                            name: 0 + " (" + utilezedCounts["zero"] + ")"
                        },
                        {
                            from: 0.000000000001,
                            to: 25,
                            name: "0.1 - 25" + " (" + utilezedCounts["first"] + ")",
                            color: '#CCCCFF'
                        },
                        {
                            from: 25.000000000001,
                            to: 50,
                            name: "25 - 50" + " (" + utilezedCounts["second"] + ")",
                            color: '#9999FF'
                        },
                        {
                            from: 50.000000000001,
                            to: 75,
                            name: "50 - 75" + " (" + utilezedCounts["third"] + ")",
                            color: '#4D4DFF'
                        },
                        {
                            from: 75.000000000001,
                            to: 100,
                            name: "75 - 100" + " (" + utilezedCounts["fourth"] + ")",
                            color: '#0000FF'
                        }
                    ],
//                    minColor : 'rgba(255, 255, 204, 1)',
//                    maxColor : 'rgba(255, 255, 0, 0.8)',
//                    tickInterval: 10,
                    tickLength: 10,
                    tickColor: 'rgba(255, 255, 0, 0.8)',
                    tickWidth: 1
//                    min: 1,
//                    max: 100
                },

                series: [
                    {
                        data: data2,
                        mapData: mapData,
                        joinBy: ["id", "code"],
                        animation: true,
                        name: 'Rs Utilized',
                        states: {
                            hover: {
                                color: '#BADA55'
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            format: '{point.properties.id}',
                            style: {
                                fontSize: fontSize,
                                textShadow: "0 0 0px contrast,0 0 3px contrast"
                            },
                            allowOverlap: true
                        },
//                        tooltip: {
//
//                            valueSuffix: '%',
//                            pointFormat: '{point.code}: {point.value}'
//                        },
                        tooltip: {
                            useHTML: true,
                            headerFormat: '',
                            pointFormat: '<table><tr><span style="font-size:10px">Rs Utilized</span>' +
                                '<br/><td style="color:{series.color};padding:0">{} </td>' +
                                '<td style="padding:0"><b>{point.code}: {point.value:.2f} %</b></td></tr>',
                            footerFormat: '</table>',
                            formatter: function () {
                                return '<img src="../images/uuslogo.png">';
                            },
                            shared: true
                        },

                        point: {
                            events: {
                                click: function () {

//                                        mapData = [];
//                                        console.log(this.properties.distt_name);
//                                        console.log($('#container').highcharts().series.entries());
//                                        console.log(mapChart.get(0).data);

//                                        $('#container').highcharts().series[0].remove();
//                                        $.ajax({
//                                            url: "services/getdistrict.php",
//                                            type: "GET",
//                                            dataType: 'text',
//                                            async: false,
//                                            success: function(data){
////                                                mapChart.series[0].setData(new Array($.parseJSON(data)));
//
//
//                                            }
//                                        });
                                    if (currentLevel < 3 && !played) {
                                        scope.$apply(function () {
                                            scope.progressHidden = false;
                                        });
                                        chart2Loaded = false;
                                        chart1Loaded = false;
                                        $('#map-button').removeAttr('disabled');
                                        currentLevel++;
                                        for (j = 0; j <= currentLevel; j++) {
                                            if (j == currentLevel) {
                                                values[j] = this.properties.id
                                            } else if (j > currentLevel) {
                                                values[j] = null;
                                            }
//                                            values[j] = this.properties.id ? j <= currentLevel : null;
                                        }
                                        $('#disDivVal').text(identifiers1[currentLevel].toUpperCase() + "\n" + values[currentLevel]);
//                                        alert(this.properties.id+ ", "+this.properties.temporal_p+ ", "+identifiers[currentLevel]);
                                        chart(files[currentLevel], currentLevel, this.properties.id);
                                        $('#tree').append('<a id=' + currentLevel + ' onclick=goToLevel(this.id); style="cursor: pointer">'
                                            + identifiers1[currentLevel].toUpperCase() + " " + values[currentLevel] + '</a>');
//                                        fetchInfo();
//                                        $('#container').highcharts().setTitle('afdsafaaf');
//                                        $('#container').highcharts().redraw();
                                    } else {
//                                        window.open("http://202.166.167.115/kprp_cm/main.php?pa="+this.properties.id+"&from=from_cm", '_blank');
//                                        window.location = "http://202.166.167.115/kprp_cm/main.php?pa="+this.properties.id+"&from=from_cm";
                                    }
                                },
                                mouseOver: function () {
//                                    console.log(this);
                                    if (chart2Loaded) {
                                        highChart2.tooltip.refresh(highChart2.series[0].data[this.index]);
                                        highChart2.series[0].data[this.index].setState('hover');
                                    }
                                },
                                mouseOut: function () {
                                    if (chart2Loaded) {
                                        highChart2.tooltip.hide();
                                        highChart2.series[0].data[this.index].setState();
                                    }
                                }
                            }
                        }
                    }
                ]
            });

//                },
//                error: function () {
//                    $('#container').html('<div class="loading">' +
//                            '<i class="icon-frown icon-large"></i> ' +
//                            'Error loading data' +
//                            '</div>');
//                }
//            });
        },
        error: function () {
            $scope.progressHidden = true;
        }

    });
};

function goTo(url) {
    window.open(url, '_blank');
}

function goToPrevious() {
    if (currentLevel <= 1 && !played) {
        chart1Loaded = false;
        chart2Loaded = false;
        $('#map-button').attr('disabled', true);
        values[currentLevel] = null;
        $('#disDivVal').text("Punjab");
        currentLevel -= 1;

//        fetchInfo();
        chart(files[currentLevel], currentLevel, values[currentLevel]);
        $('#tree').children().slice(currentLevel + 1).remove();
    } else if (currentLevel > 0 && !played) {
        chart1Loaded = false;
        chart2Loaded = false;
        values[currentLevel] = null;
        currentLevel -= 1;
        $('#disDivVal').text(identifiers1[currentLevel].toUpperCase() + "\n" + values[currentLevel]);
//        fetchInfo();
        chart(files[currentLevel], currentLevel, values[currentLevel]);
        $('#tree').children().slice(currentLevel + 1).remove();
    }
}

function playResume() {
    if (!played) {
        played = true;
        $("#play-button").removeClass("glyphicon-play");
        $("#play-button").addClass("glyphicon-pause");
        play();
    } else {
        played = false;
        clearInterval(timer);
        $("#play-button").removeClass("glyphicon-pause");
        $("#play-button").addClass("glyphicon-play");
    }
}

function goToStart() {
    if (currentLevel != 0 && !played) {
        chart1Loaded = false;
        chart2Loaded = false;
        fontSize = '7px';
        $('#map-button').attr('disabled', true);
        $('#disDivVal').text("Punjab");
        currentLevel = 0;
        values = new Array(4);
//        fetchInfo();
        chart(files[currentLevel], currentLevel, "");
        $('#tree').children().slice(currentLevel + 1).remove();

    }
}

function stop() {
    if (played) {
        chart1Loaded = false;
        chart2Loaded = false;
        currentLevel = 0;
        fontSize = '7px';
        clearInterval(timer);
        played = false;
        divisionNumber = 0;
        k = 0;
        l = 0;
        c = 0;
        $('#map-button').attr('disabled', true);
        $("#play-button").removeClass("glyphicon-pause");
        $("#play-button").addClass("glyphicon-play");
        values = new Array(4);
        $('#disDivVal').text("Punjab");
        chart(files[currentLevel], currentLevel, "");
        $('#tree').children().slice(currentLevel + 1).remove();
    }
}

function goToLevel(level) {
    level = parseInt(level);
    if (level == 0) {
        goToStart();
    } else if (currentLevel > 0 && !played) {
        chart1Loaded = false;
        chart2Loaded = false;
        currentLevel = level;
//        values[level] = null;
        chart(files[level], level, values[level]);
        $('#tree').children().slice(level + 1).remove();
        $('#disDivVal').text(identifiers1[level].toUpperCase() + "\n" + values[level]);
//        fetchInfo();
    }

//    console.log($('#tree').append());
}

function getRoadsInfo(id) {
//    var table = '<table class="table table-responsive table-bordered table-striped" style="border: 1px solid #ff0000">';
//    var th = '<tr><th>ID</th><th>Name</th><th>District</th><th>Sub</th><th>Details</th></tr>';
//    $('#table_div').html("");
//       $('#roadFilter').val("");
    if (id == 'box1' || id == 'box2' || id == 'box3') {

//        var tb = "";
        var url = "services/getroadsinfo.php?";
        scope.$apply(function () {
            scope.progressHidden = false;
        });

        for (var i = 1; i <= values.length; i++) {
            if (values[i] != null) {
                url += identifiers[i] + "=" + values[i] + "&";
            }
        }
        var count = 0;
//        var roadsCount = "";
        $.ajax({
            url: url,
            type: "GET",
            dataType: 'json',
            async: true,

            success: function callback(response) {
//                console.log(response.length);
                if (response) {
                    for (var i in response) {
                        if (response[i].sub_road_refrence_id == '0') {
                            count++;
                        }
                    }
                    //var scope = angular.element($("#table_div")).scope();
                    scope.$apply(function () {
                        scope.roadsFilter = '';
                        scope.status = false;
                        scope.roadsData = response;
                        // scope.roadsCount = count;
                        scope.progressHidden = true;
                    });
//                if(response){
//                for(var i in response){
//                    if(response[i].sub_road_refrence_id == '0'){count++;}
//
////                    tb = tb+ '<tr><td>'+response[i].road_id_main+'</td><td>'+response[i].road_name+'</td><td>'+response[i].district+'</td><td>'
////                        +response[i].sub_road_refrence_id+'</td><td><a href='+"http://202.166.167.115/kprp_cm/main.php?road_id_main="
////                        +response[i].road_id_main+"&extent="+response[i].extent+' target='+"blank"+'>Detail</a>,<a href="" id='+response[i].road_id
////                        +' onclick="showPopup(this.id);"> Summary</a></td></tr>';
//                    tb = tb+ '<tr><td>'+response[i].road_id_main+'</td><td>'+response[i].road_name+'</td><td>'+response[i].district+'</td><td>'
//                        +response[i].sub_road_refrence_id+'</td><td><a href='+"http://202.166.167.115/kprp_cm/main.php?road_id_main="
//                        +response[i].road_id_main+"&extent="+response[i].extent+' target='+"blank"+'>Detail</a>,<a target="_blank" href="charts.php?id='+response[i].road_id+'" id="'+response[i].road_id+'" '+' > Summary</a></td></tr>';
//                }
//                    roadsCount = '<tr><h4 style="font-style: oblique;font-size: 14px;"><b>Total Roads : '+count+'</b></h4></tr>';
//                $('#table_div').html(table +  roadsCount+ th + tb + "</table>");
//
//                    for(var i = 0;i<= response.length; i ++){
//                        $(".iframe").colorbox({iframe:true, width:"60%", height:"75%"});
//                        $("#"+response[i].road_id).addClass("iframe");
//                    }
//                }else{
//                    roadsCount = '<tr><h4 style="font-style: oblique;font-size: 14px;"><b>Total Roads : '+count+'</b></h4></tr>';
//                    $('#table_div').html(table +roadsCount+th + tb + "</table>");
//                }
//                console.log(count);
                } else {
                    //var scope = angular.element($("#table_div")).scope();
                    scope.$apply(function () {
                        scope.roadsFilter = '';
                        scope.roadsData = [];
                        scope.status = false;
                        // scope.roadsCount = count;
                        scope.progressHidden = true;
                    });
                }
            },
            error: function () {
//            roadsCount = '<tr><h4 style="font-style: oblique;font-size: 14px;"><b>Total Roads : '+count+'</b></h4></tr>';
//            $('#table_div').html(table +roadsCount + th + tb + "</table>");
//                var scope = angular.element($("#table_div")).scope();
                scope.$apply(function () {
                    scope.roadsFilter = '';
                    scope.status = false;
                    scope.roadsData = [];
                    // scope.roadsCount = count;
                    scope.progressHidden = true;
                });
            }
        });

    }
}


function getRoadsStatusInfo(status) {
//    var table = '<table class="table table-responsive table-bordered table-striped" style="border: 1px solid #ff0000">';
//    var th = '<tr><th>ID</th><th>Name</th><th>District</th><th>Sub</th><th>Detail</th></tr>';
//    $('#table_div').html("");
//    $('#roadFilter').val("");
//    var tb = "";
    var url = "services/getroadslevelinfo.php?";
    for (var i = 1; i <= values.length; i++) {
        if (values[i] != null) {
            url += identifiers[i] + "=" + values[i] + "&";
        }
    }
    url += "status=" + status;
    scope.$apply(function () {
        scope.progressHidden = false;
    });
    var count = 0;
//    var roadsCount = '';
    $.ajax({
        url: url,
        type: "GET",
        dataType: 'json',
        async: true,

        success: function callback(response) {

            if (response) {

                for (var i in response) {
                    if (response[i].sub_road_refrence_id == '0') {
                        count++;
                    }
                }
                //var scope = angular.element($("#table_div")).scope();
                scope.$apply(function () {
                    scope.roadsFilter = '';
                    scope.status = false;
                    scope.roadsData = response;
                    // scope.roadsCount = count;
                    scope.progressHidden = true;
                });

//            for(var i in response){
//                if(response[i].sub_road_refrence_id == '0'){count++;}
////                tb = tb+ '<tr><td>'+response[i].road_id_main+'</td><td>'+response[i].road_name+'</td><td>'+response[i].district+'</td><td>'
////                    +response[i].sub_road_refrence_id+'</td><td><a href='+"http://202.166.167.115/kprp_cm/main.php?road_id_main="
////                    +response[i].road_id_main+"&extent="+response[i].extent+' target='+"blank"+'>Detail</a>,<a href="" id='+response[i].road_id
////                    +' onclick="showPopup(this.id);"> Summary</a></td></tr>';
//
//                tb = tb+ '<tr><td>'+response[i].road_id_main+'</td><td>'+response[i].road_name+'</td><td>'+response[i].district+'</td><td>'
//                    +response[i].sub_road_refrence_id+'</td><td><a href='+"http://202.166.167.115/kprp_cm/main.php?road_id_main="
//                    +response[i].road_id_main+"&extent="+response[i].extent+' target='+"blank"+'>Detail</a>,<a target="_blank" href="charts.php?id='+response[i].road_id+'" id="'+response[i].road_id+'" '+' > Summary</a></td></tr>';
//            }
//                roadsCount = '<tr><h4 style="font-style: oblique;font-size: 14px;"><b>Total Roads : '+count+'</b></h4></tr>';
//            $('#table_div').html(table +roadsCount+ th + tb + "</table>");
//                for(var i = 0;i<= response.length; i ++){
//                    $(".iframe").colorbox({iframe:true, width:"60%", height:"75%"});
//                    $("#"+response[i].road_id).addClass("iframe");
//                }
//            }else{
//                roadsCount = '<tr><h4 style="font-style: oblique;font-size: 14px;"><b>Total Roads : '+count+'</b></h4></tr>';
//                $('#table_div').html(table +roadsCount+ th + tb + "</table>");
//            }
            } else {
                //var scope = angular.element($("#table_div")).scope();
                scope.$apply(function () {
                    scope.roadsFilter = '';
                    scope.roadsData = [];
                    scope.status = false;
                    // scope.roadsCount = count;
                    scope.progressHidden = true;
                });
            }
        },
        error: function () {
//            roadsCount = '<tr><h4 style="font-style: oblique;font-size: 14px;"><b>Total Roads : '+count+'</b></h4></tr>';
//            $('#table_div').html(table +roadsCount + th + tb + "</table>");
//            var scope = angular.element($("#table_div")).scope();
            scope.$apply(function () {
                scope.roadsFilter = '';
                scope.roadsData = [];
                scope.status = false;
                // scope.roadsCount = count;
                scope.progressHidden = true;
            });
        }
    });
}

function getRoadsUtilizationInfo() {
//    var table = '<table class="table table-responsive table-bordered table-striped" style="border: 1px solid #ff0000">';
//    var th = '<tr><th>ID</th><th>Name</th><th>District</th><th>Sub</th><th>Detail</th></tr>';
//    $('#table_div').html("");
//    var tb = "";
//    $('#roadFilter').val("");
    var url = "services/getutilization.php?";
    scope.$apply(function () {
        scope.progressHidden = false;
    });
    for (var i = 1; i <= values.length; i++) {
        if (values[i] != null) {
            url += identifiers[i] + "=" + values[i] + "&";
        }
    }
    var count = 0;
//    var roadsCount= '';
    $.ajax({
        url: url,
        type: "GET",
        dataType: 'json',
        async: true,

        success: function callback(response) {

            if (response) {

                for (var i in response) {
                    if (response[i].sub_road_refrence_id == '0') {
                        count++;
                    }
                }
                //var scope = angular.element($("#table_div")).scope();
                scope.$apply(function () {
                    scope.roadsFilter = '';
                    scope.status = false;
                    scope.roadsData = response;
                    // scope.roadsCount = count;
                    scope.progressHidden = true;
                });

//                for(var i in response){
//                    if(response[i].sub_road_refrence_id == '0'){count++;}
////                    tb = tb+ '<tr><td>'+response[i].road_id_main+'</td><td>'+response[i].road_name+'</td><td>'+response[i].district+'</td><td>'
////                        +response[i].sub_road_refrence_id+'</td><td><a href='+"http://202.166.167.115/kprp_cm/main.php?road_id_main="
////                        +response[i].road_id_main+"&extent="+response[i].extent+' target='+"blank"+'>Detail</a>,<a href="" id='+response[i].road_id
////                        +' onclick="showPopup(this.id);"> Summary</a></td></tr>';
//
//                    tb = tb+ '<tr><td>'+response[i].road_id_main+'</td><td>'+response[i].road_name+'</td><td>'+response[i].district+'</td><td>'
//                        +response[i].sub_road_refrence_id+'</td><td><a href='+"http://202.166.167.115/kprp_cm/main.php?road_id_main="
//                        +response[i].road_id_main+"&extent="+response[i].extent+' target='+"blank"+'>Detail</a>,<a target="_blank" href="charts.php?id='+response[i].road_id+'" id="'+response[i].road_id+'" '+' > Summary</a></td></tr>';
//                }
//                roadsCount = '<tr><h4 style="font-style: oblique;font-size: 14px;"><b>Total Roads : '+count+'</b></h4></tr>';
//                $('#table_div').html(table +roadsCount + th + tb + "</table>");
//                for(var i = 0;i<= response.length; i ++){
//                    $(".iframe").colorbox({iframe:true, width:"60%", height:"75%"});
//                    $("#"+response[i].road_id).addClass("iframe");
//                }
//            }else{
//                roadsCount = '<tr><h4 style="font-style: oblique;font-size: 14px;"><b>Total Roads : '+count+'</b></h4></tr>';
//                $('#table_div').html(table +roadsCount + th + tb + "</table>");
//            }
            } else {
                //var scope = angular.element($("#table_div")).scope();
                scope.$apply(function () {
                    scope.roadsFilter = '';
                    scope.status = false;
                    scope.roadsData = [];
                    // scope.roadsCount = count;
                    scope.progressHidden = true;
                });
            }
        },
        error: function () {
//            roadsCount = '<tr><h4 style="font-style: oblique;font-size: 14px;"><b>Total Roads : '+count+'</b></h4></tr>';
//            $('#table_div').html(table +roadsCount + th + tb + "</table>");
//            var scope = angular.element($("#table_div")).scope();
            scope.$apply(function () {
                scope.roadsFilter = '';
                scope.status = false;
                scope.roadsData = [];
                // scope.roadsCount = count;
                scope.progressHidden = true;
            });
        }
    });

}

//function getResUnres(resOrUnres, filename ){
//    var url = "services/"+filename+"?";
//    for (var i = 1; i <= values.length; i++) {
//        if (values[i] != null) {
//            url += identifiers[i] + "=" + values[i] + "&";
//        }
//    }
//
//    url+="resunres="+resOrUnres;
//    var count = 0;
//    //var scope = angular.element($("#table_div")).scope();
//    $.ajax({
//        url: url,
//        type: "GET",
//        dataType: 'json',
//        async: true,
//
//        success: function callback(response) {
//
//            if(response){
//                for(var i in response){
//                    if(response[i].sub_road_refrence_id == '0'){count++;}
//                }
//                scope.$apply(function(){
//                    scope.roadsFilter = '';
//                    scope.status = true;
//                    scope.roadsData = response;
//                    scope.roadsCount = count;
//                });
//            }else{
//                scope.$apply(function(){
//                    scope.roadsFilter = '';
//                    scope.status = true;
//                    scope.roadsData =  [];
//                    scope.roadsCount = count;
//                });
//            }
//        },error: function(){
//            //var scope = angular.element($("#table_div")).scope();
//            scope.$apply(function(){
//                scope.roadsFilter = '';
//                scope.status = true;
//                scope.roadsData =  [];
//                scope.roadsCount = count;
//            });
//        }
//    });
//}

chart(files[currentLevel], currentLevel, "");

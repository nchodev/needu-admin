$(function(e){
  'use strict'

   /*echart2*/
  var chartdata = [
    {
      name: 'Male',
      type: 'bar',
      data: [10, 15, 9, 18, 10, 15, 13, 14, 18, 17, 16, 14],
	  symbolSize:10,
	    itemStyle: {
			normal: { barBorderRadius: [100 ,100, 0 ,0],
			}
		},
    },
    {
      name: 'Female',
      type: 'bar',
      data: [10, 14, 10, 15, 9, 25, 18, 17, 14, 12, 16, 13],
	  symbolSize:10,
	    itemStyle: {
			normal: { barBorderRadius: [100 ,100, 0 ,0],
			barBorderWidth:['2']
			}
		},
    }
  ];

  var chart = document.getElementById('echart1');
  var barChart = echarts.init(chart);

  var option = {
    grid: {
      top: '6',
      right: '0',
      bottom: '17',
      left: '25',
	  borderColor: 'rgba(119, 119, 142, 0.08)',
    },
    xAxis: {
      data: [ 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov','Dec' ],

      axisLine: {
        lineStyle: {
          color:'rgba(161, 161, 161,0.3)'
        }
      },
      axisLabel: {
        fontSize: 10,
        color: '#a1a1a1'
      }
    },
	tooltip: {
		show: true,
		showContent: true,
		alwaysShowContent: true,
		triggerOn: 'mousemove',
		trigger: 'axis',
		axisPointer:
		{
			label: {
				show: false,
			}
		}

	},
    yAxis: {
      splitLine: {
        lineStyle: {
          color: 'rgba(119, 119, 142, 0.08)'
        }
      },
      axisLine: {
        lineStyle: {
          color: 'rgba(119, 119, 142, 0.08)'
        }
      },
      axisLabel: {
        fontSize: 10,
        color: '#a1a1a1'
      }
    },
    series: chartdata,
    color:[ '#467fcf', '#5eba00',]
  };

  barChart.setOption(option);
  window.addEventListener('resize',function(){
    barChart.resize();
  })

  /*--echart-1---*/

  /*chart-donut*/
var options = {
    chart: {
        height: 280,
        type: "radialBar",
    },

    colors: ["#467fcf", "#5eba00", "#6900ba"],

    plotOptions: {

        radialBar: {
            hollow: {
                size: "55%"
              },
            track: {
                background: "rgba(0, 0, 0, 0.05)",
                opacity: 0.5,
            },
            dataLabels: {
                name: {
                    fontSize: "14px",
                },
                value: {
                    fontSize: "16px",
                    color: "#0f4069",
                },
                total: {
                    show: !0,
                    label: "Total",
                    color: "#0f4069",
                    formatter: function(e) {
                        return 249;
                    },
                },
            },
        },
    },

    stroke: {
        lineCap: "round",
    },
    series: [55, 78,34],
    labels: ["Lesbiennes", "Gays", "Bisexuel(le)s"],
    legend: {
        show: false,
        floating: true,
        fontSize: "13px",
        position: "left",
        offsetX: 0,
        offsetY: 1,
        labels: {
            useSeriesColors: true,
        },
        markers: {
            size: 1,
        },
    },
  };

var chart = new ApexCharts(document.querySelector("#visit-by-departments"), options);

chart.render();


});

/**---- VectorMap ----**/

! function($) {
	"use strict";

	var VectorMap = function() {
	};

	VectorMap.prototype.init = function() {
		//various examples
		$('#world-map-markers').vectorMap({
			map : 'world_mill_en',
			scaleColors : ['#467fcf', '#5eba00'],
			normalizeFunction : 'polynomial',
			hoverOpacity : 0.7,
			hoverColor : false,
			regionStyle : {
				initial : {
					fill : '#f4f5f7',
					'stroke': '#fff',
                    'stroke-width' : 1,
                    'stroke-opacity': 1
				},
				hover: {
					'fill': 'rgb(70, 127, 207, 0.3)',
                    'stroke': '#467fcf',
					'stroke-opacity': 0.5
                }
			},
			 markerStyle: {
                initial: {
                    r: 6,
                    'fill': '#467fcf',
                    'fill-opacity': 0.7,
                    'stroke': '#edf0f5',
                    'stroke-width' : 9,
                    'stroke-opacity': 0.2
                },

                hover: {
                    'stroke': '#fff',
                    'fill-opacity': 0.8,
                    'stroke-width': 1.5
                }
            },
			backgroundColor : 'transparent',
			markers : [{
				latLng : [41.90, 12.45],
				name : 'Vatican City'
			}, {
				latLng : [43.73, 7.41],
				name : 'Monaco'
			},{
				latLng : [43.93, 12.46],
				name : 'San Marino'
			}, {
				latLng : [47.14, 9.52],
				name : 'Liechtenstein'
			},{
				latLng : [17.3, -62.73],
				name : 'Saint Kitts and Nevis'
			}, {
				latLng : [3.2, 73.22],
				name : 'Maldives'
			}, {
				latLng : [35.88, 14.5],
				name : 'Malta'
			}, {
				latLng : [12.05, -61.75],
				name : 'Grenada'
			}, {
				latLng : [13.16, -61.23],
				name : 'Saint Vincent and the Grenadines'
			}, {
				latLng : [13.16, -59.55],
				name : 'Barbados'
			}, {
				latLng : [17.11, -61.85],
				name : 'Antigua and Barbuda'
			}, {
				latLng : [-4.61, 55.45],
				name : 'Seychelles'
			}, {
				latLng : [7.35, 134.46],
				name : 'Palau'
			}, {
				latLng : [42.5, 1.51],
				name : 'Andorra'
			}, {
				latLng : [14.01, -60.98],
				name : 'Saint Lucia'
			},{
				latLng : [1.3, 103.8],
				name : 'Singapore'
			},{
				latLng : [-20.2, 57.5],
				name : 'Mauritius'
			}, {
				latLng : [26.02, 50.55],
				name : 'Bahrain'
			}, {
				latLng : [0.33, 6.73],
				name : 'São Tomé and Príncipe'
			}]
		});

	},
	//init
	$.VectorMap = new VectorMap, $.VectorMap.Constructor =
	VectorMap
}(window.jQuery),

//initializing
function($) {
	"use strict";
	$.VectorMap.init()
}(window.jQuery);


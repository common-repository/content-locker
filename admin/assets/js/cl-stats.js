/*!
* Content Locker
*
* https://mythemeshop.com/plugins/content-locker/
*
* (c) Copyright mythemeshop.com
*
* @author  MyThemeShop
*/

/*global google*/
;(function($) {

	"use strict";

	window.CL_Statistics = {

		// default chart options that will be changed when the chart is drawing
        defaults: {
            isStacked: true,
            fontSize: 11,
            legend: {
                position: 'in',
                format: 'dd MMM'
            },
            pointSize: 7,
            lineWidth: 3,
            tooltip: {
                showColorCode: true,
                textStyle: {fontSize: '11', color: '#333'}
            },
            colors: [],
            hAxis: {
                baselineColor: '#fff',
                gridlines: {color: '#fff'},
                textStyle: {fontSize: '11', color: '#333'},
                format: 'dd MMM'
            },
            vAxis: {
                baselineColor: '#111',
                gridlines: {color: '#f6f6f6'},
                textPosition: 'in',
                textStyle: {fontSize: '11', color: '#333'}
            },
            chartArea: { height: '250', width: '100%', top: 0 }
        },

		init: function() {

			var self = this;

			this.init_button_selector();
            this.init_date_range_selector();

            $(window).resize(function() {
                self.redraw_chart();
            });
		},

		/**
         * Inits buttons selector
         */
        init_button_selector: function() {

			var self = this,
				selectors = window.cl_chart_selectors;

            $('.mts-cl-selector-item')
                .addClass('mts-cl-inactive')
                .removeClass('mts-cl-active');

            for( var index in selectors) {
                $( '.mts-cl-selector-' + selectors[index] )
                    .addClass('mts-cl-active')
                    .removeClass('mts-cl-inactive');
            }

            $( '.mts-cl-selector-item' ).click(function() {

				var selector = $(this).data( 'selector' ),
					$item = $( '.mts-cl-selector-' + selector);

				if ( $item.hasClass('mts-cl-active') ) {
					$item.removeClass('mts-cl-active').addClass('mts-cl-inactive');
				}
				else {
					$item.addClass('mts-cl-active').removeClass('mts-cl-inactive');
				}

				self.redraw_chart();

				return false;
            });
        },

		/**
         * Inits date range selector.
         */
		init_date_range_selector: function() {

            $('#mts-cl-date-start').datepicker({
				changeMonth: true,
				changeYear: true
			});
            $('#mts-cl-date-end').datepicker({
				changeMonth: true,
				changeYear: true
			});

			$(window).load(function(){
				$('#ui-datepicker-div').addClass('cmb2-element');
			});
		},

		/**
         * Returns currently active selectors.
         */
        get_active_selectors: function() {

            var result = [];
            $( '.mts-cl-selector-item.mts-cl-active' ).each(function() {
                result.push( $(this).data('selector') );
            });

            if ( !result.length ) {
				return false;
			}

            return result;
        },

		/**
         * Draws the chart.
         */
        draw_chart: function ( params ) {

            var options = $.extend( true, {}, this.defaults ),
				chart_type = params.type || 'line',
				chart_function = 'LineChart';

            this._params = params;

            if ( 'area' === chart_type ) {
                chart_function = 'AreaChart';
                options.legend.position = 'in';
                options.areaOpacity = 0.1;
            }
            else if ( 'column' === chart_type ) {
                options.legend.position = 'none';
                chart_function = 'ColumnChart';
            }
            else {
                options.legend.position = 'none';
                chart_function = 'LineChart';
            }

            // Create the data table.
            var active_selectors = this.get_active_selectors(),
				dataTable = new google.visualization.DataTable();

            var data = [];
            var columns = [];
            var colors = [];

            columns.push({
				type: 'date',
				title: 'Date'
			});

            // building the columns and colors
            var row = window.cl_chart_data[0];
            for ( var column in row ) {

                if ( 'date' === column ) {
					continue;
				}

                // if the page contains selectors
                if ( active_selectors && $.inArray( column, active_selectors) === -1 ) {
					continue;
				}

                // column & title
                columns.push({
					type: 'number',
					title: row[column]['title']
				});

                colors.push(row[column]['color']);
            }

            // building the data array
            for( var index in window.cl_chart_data ) {
                var row = window.cl_chart_data[index];

                var chartRow = [ row['date']['value'] ];
                for ( var column in row ) {

                    if ( 'date' === column ) {
						continue;
					}

                    // if the page contains selectors
                    if ( active_selectors && $.inArray( column, active_selectors) === -1 ) {
						continue;
					}

                    chartRow.push( row[column]['value'] );
                }
                data.push(chartRow);
            }

            for( var i in columns ) {
				dataTable.addColumn( columns[i].type, columns[i].title );
			}
            options.colors = colors;
            dataTable.addRows(data);

            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization[chart_function](document.getElementById('chart'));
            chart.draw(dataTable, options);
        },

        redraw_chart: function() {
            this.draw_chart( this._params );
        }

	};

	// Init
	$(document).ready(function(){
		window.CL_Statistics.init();
	});

})(jQuery);

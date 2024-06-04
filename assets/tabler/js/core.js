let hexToRgba = function(hex, opacity) {
	let result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
	let rgb = result ? {
		r: parseInt(result[1], 16),
		g: parseInt(result[2], 16),
		b: parseInt(result[3], 16)
	} : null;

	return 'rgba(' + rgb.r + ', ' + rgb.g + ', ' + rgb.b + ', ' + opacity + ')';
};

function comparing(id, obj) {
	for (i=0; i<obj.length; i++) {
		if (id == obj[i].marketplace) {
			return i;
		}
	}
}

function redirect(uri) {
	if(confirm('Lanjutkan tindakan ini?')) {
		window.location = uri;
	} else {
		return false;
	}
}

function view_detail(uri) {
	$.ajax({
		url    : uri,
		type   : "GET",
		success: function(res) {
      var data = res[0];

      $("[name='data_id']").val(data.id);
      $("[name='up_nomorsurat']").val(data.nomor_surat);
      $("[name='up_namapengirim']").val(data.nama_pengirim);
      $("[name='up_waktu']").val(data.waktu);
      $("[name='up_lampiran']").val(data.lampiran);
      $("[name='up_perihal']").val(data.perihal);
      $("[name='up_namapenerima']").val(data.nama_penerima);
      $("[name='up_isisurat']").val(data.isi_surat);
      $("[name='up_unitpenerbit']").val(data.unit_penerbit);
      $("[name='up_tempat']").val(data.tempat);
      $("[name='up_pengesah']").val(data.pengesah);
      $("[name='up_tembusan']").val(data.tembusan);

			$("#view_detail").modal();
		},
		error: function(xhr, status, errorMessage) {
			alert('Terjadi kesalahan, mohon coba kembali');
		}
	});
}

require(['daterangepicker'], function() {
	$('.date-range').daterangepicker({
		locale: {
			format: 'YYYY-MM-DD',
			separator: ','
		}
	});
	$('.month-range').daterangepicker({
		locale: {
			format: 'YYYY-MM',
			singleDatePicker: true,
			separator: ','
		}
	});
});

require(['jquery', 'datepicker', 'datepicker_eng'], function() {
	$(document).ready(function() {
		$(".datex").datepicker({
			language: 'en',
			timepicker: true,
			timeFormat: "hh:ii:00",
			dateFormat: 'yyyy-mm-dd'
		});
	});
});

$(document).ready(function() {

	require(['summernote'], function() {
		$('#summernote').summernote({
			toolbar: [
				['style', ['bold', 'italic', 'underline', 'clear']],
				['fontsize', ['fontsize']],
				['color', ['color']],
				['para', ['ul', 'ol', 'paragraph']],
				['height', ['height']]
			]
		});
	});
			
	require(['select2'], function() {
		$('.select2').select2();
	});

	/** Constant div card */
	const DIV_CARD = 'div.card';

	/** Initialize tooltips */
	$('[data-toggle="tooltip"]').tooltip();

	/** Initialize popovers */
	$('[data-toggle="popover"]').popover({
		html: true
	});

	/** Function for remove card */
	$('[data-toggle="card-remove"]').on('click', function(e) {
		let $card = $(this).closest(DIV_CARD);

		$card.remove();

		e.preventDefault();
		return false;
	});

	/** Function for collapse card */
	$('[data-toggle="card-collapse"]').on('click', function(e) {
		let $card = $(this).closest(DIV_CARD);

		$card.toggleClass('card-collapsed');

		e.preventDefault();
		return false;
	});

	/** Function for fullscreen card */
	$('[data-toggle="card-fullscreen"]').on('click', function(e) {
		let $card = $(this).closest(DIV_CARD);

		$card.toggleClass('card-fullscreen').removeClass('card-collapsed');

		e.preventDefault();
		return false;
	});

	/**  */
	if ($('[data-sparkline]').length) {
		let generateSparkline = function($elem, data, params) {
			$elem.sparkline(data, {
				type: $elem.attr('data-sparkline-type'),
				height: '100%',
				barColor: params.color,
				lineColor: params.color,
				fillColor: 'transparent',
				spotColor: params.color,
				spotRadius: 0,
				lineWidth: 2,
				highlightColor: hexToRgba(params.color, .6),
				highlightLineColor: '#666',
				defaultPixelsPerValue: 5
			});
		};

		require(['sparkline'], function() {
			$('[data-sparkline]').each(function() {
				let $chart = $(this);

				generateSparkline($chart, JSON.parse($chart.attr('data-sparkline')), {
					color: $chart.attr('data-sparkline-color')
				});
			});
		});
	}

	/**  */
	if ($('.chart-circle').length) {
		require(['circle-progress'], function() {
			$('.chart-circle').each(function() {
				let $this = $(this);

				$this.circleProgress({
					fill: {
						color: tabler.colors[$this.attr('data-color')] || tabler.colors.blue
					},
					size: $this.height(),
					startAngle: -Math.PI / 4 * 2,
					emptyFill: '#F4F4F4',
					lineCap: 'round'
				});
			});
		});
	}
});
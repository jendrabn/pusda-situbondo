@extends('layouts.admin')

@section('content')
  @include('partials.menuTreeIsiUraian')

  <div class="card card-outline card-tabs">
    <div class="card-header">
      <ul class="nav nav-tabs" id="tab">
        <li class="nav-item">
          <a class="nav-link active" id="tabel-tab" data-toggle="pill" href="#tabel" role="tab" aria-controls="tabel"
            aria-selected="true">Tabel {{ $title }}</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="fitur-tab" data-toggle="pill" href="#fitur" role="tab" aria-controls="fitur"
            aria-selected="false">Fitur {{ $title }}</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="file-tab" data-toggle="pill" href="#file" role="tab" aria-controls="file"
            aria-selected="false">File Pendukung
            {{ $title }}</a>
        </li>
      </ul>
    </div>

    <div class="card-body">
      <div class="tab-content" id="tabContent">
        <div class="tab-pane fade active show" id="tabel">
          @include('admin.isi-uraian.tabel')
        </div>
        <div class="tab-pane fade" id="fitur">
          @include('admin.isi-uraian.fitur-form')
        </div>
        <div class="tab-pane fade" id="file">
          @include('admin.isi-uraian.file-pendukung')
        </div>
      </div>
    </div>
  </div>
  @include('admin.isi-uraian.tahun-modal')
  @include('admin.isi-uraian.chart-modal')
@endsection

@section('scripts')
  <script>
    let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);

    $.extend(true, $.fn.dataTable.defaults, {
      orderCellsTop: true,
      order: [
        [1, 'desc']
      ],
      pageLength: 100,
    });

    let table = $('.datatable-isiuraian:not(.ajaxTable)').DataTable({
      buttons: dtButtons,
      ordering: false
    })

    $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
      $($.fn.dataTable.tables(true)).DataTable()
        .columns.adjust();
    });

    $(".datatable-isiuraian").on("change", "select.sumber-data", function(e) {
      $.ajax({
        headers: {
          'x-csrf-token': _token
        },
        method: 'PUT',
        url: $(this).data('url'),
        data: {
          skpd_id: e.target.value
        },
        success: function(res) {
          location.reload();
        },
        error: function(err) {
          console.log(err);
          return;
        }
      });
    })

    $('.datatable-isiuraian').on('click', 'tbody .btn-show-chart', function(e) {
      const containerElm = $('#chart-container');
      const chartElm = $('#chart-isi-uraian');

      $.ajax({
        method: 'GET',
        url: $(this).data('url'),
        success: function(data) {

          chartElm.remove();
          containerElm.append('<canvas id="chart-isi-uraian" width="100%" height="100%"></canvas>');
          const ctx = $('#chart-isi-uraian');

          const chart = new Chart(ctx, {
            type: 'bar',
            data: {
              labels: data.isi.map(val => val.tahun),
              datasets: [{
                label: data.uraian,
                data: data.isi.map(val => val.isi),
                backgroundColor: "#36a2eb",
                borderWidth: 1
              }]
            },
            options: {
              indexAxis: 'y',
              responsive: true,
            }
          });

          $('#modal-chart').modal('show');
        },
        error: function(err) {
          console.log(err);
        }
      });

    });
  </script>
@endsection

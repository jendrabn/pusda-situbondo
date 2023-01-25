@extends('layouts.admin')

@section('content')

  <div class="row">
    <div class="col-lg-6">
      @if ($tabel)
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              Tambah {{ $tabel->nama_menu }}
            </h3>
          </div>
          <div class="card-body">
            <form action="{{ route('admin.uraian.' . $crudRoutePart . '.store') }}" method="POST">
              @csrf
              <input name="table_id" type="hidden" value="{{ $tabel->id }}">

              <div class="form-group">
                <label class="required" for="parent_id">Kategori</label>
                <select class="form-control select2" id="parent_id" name="parent_id" style="width: 100%">
                  <option value="">Parent</option>
                  @foreach ($uraian as $item)
                    <option value="{{ $item->id }}">{{ $item->uraian }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label class="required" for="uraian">Uraian</label>
                <input class="form-control" name="uraian" type="text" value="{{ old('uraian') }}">
              </div>
              <div class="form-group">
                <button class="btn btn-danger" type="submit">Save</button>
              </div>
            </form>
          </div>
        </div>
      @endif

    </div>
    <div class="col-lg-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            Pilih Menu Treeview {{ $title }}
          </h3>
        </div>
        <div class="card-body jstree overflow-auto">
          <ul>
            <li data-jstree='{"opened":true}'>
              @if ($crudRoutePart === 'delapankeldata')
                8 Kelompok Data
              @elseif ($crudRoutePart === 'rpjmd')
                RPJMD
              @elseif ($crudRoutePart === 'indikator')
                Indikator
              @elseif ($crudRoutePart === 'bps')
                BPS
              @endif

              @foreach ($categories as $category)
                @if ($category->childs->count())
                  <ul>
                    @foreach ($category->childs as $child)
                      <li>
                        {{ $child->nama_menu }}
                        @if ($child->childs->count())
                          <ul>
                            @foreach ($child->childs as $child)
                              <li> {{ $child->nama_menu }}
                                <ul>
                                  @if ($child->childs->count())
                                    @foreach ($child->childs as $child)
                                      <li @if (isset($tabel) && $tabel->id === $child->id) data-jstree='{ "selected" : true }' @endif>
                                        <a
                                           href="{{ route('admin.uraian.' . $crudRoutePart . '.index', $child->id) }}">{{ $child->nama_menu }}</a>
                                      </li>
                                    @endforeach
                                  @endif
                                </ul>
                              </li>
                            @endforeach
                          </ul>
                        @endif
                      </li>
                    @endforeach
                  </ul>
                @endif
            </li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
  </div>

  @if ($tabel)
    <div class="card">
      <div class="card-header">
        <h3 class="jstree">
          Uraian Form {{ $tabel->nama_menu }} List
        </h3>
      </div>
      <div class="card-body">
        <table class="table-bordered table-striped table-hover ajaxTable datatable datatable-Uraian table">
          <thead>
            <tr>
              <th width="10"></th>
              <th>ID</th>
              <th>Uraian</th>
              <th>&nbsp;</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($uraian as $item)
              <tr>
                <td></td>
                <td>{{ $item->id }}</td>
                <td>{{ $item->uraian }}</td>
                <td>
                  <a class="btn btn-xs btn-info"
                     href="{{ route('admin.uraian.' . $crudRoutePart . '.edit', [$tabel->id, $item->id]) }}">
                    Edit
                  </a>
                  <form style="display: inline-block;"
                        action="{{ route('admin.uraian.' . $crudRoutePart . '.destroy', $item->id) }}" method="POST"
                        onsubmit="return confirm('Are You Sure?');">
                    @method('DELETE')
                    @csrf
                    <input class="btn btn-xs btn-danger" type="submit" value="Delete">
                  </form>
                </td>
              </tr>
              @foreach ($item->childs as $item)
                <tr>
                  <td></td>
                  <td>{{ $item->id }}</td>
                  <td style="text-indent: 1rem;">{{ $item->uraian }}</td>
                  <td>
                    <a class="btn btn-xs btn-info"
                       href="{{ route('admin.uraian.' . $crudRoutePart . '.edit', [$tabel->id, $item->id]) }}">
                      Edit
                    </a>
                    <form style="display: inline-block;"
                          action="{{ route('admin.uraian.' . $crudRoutePart . '.destroy', $item->id) }}" method="POST"
                          onsubmit="return confirm('Are You Sure?');">
                      @method('DELETE')
                      @csrf
                      <input class="btn btn-xs btn-danger" type="submit" value="Delete">
                    </form>
                  </td>
                </tr>
              @endforeach
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @endif
@endsection

@section('scripts')
  @parent
  <script>
    $(function() {
      $('.datatable-Uraian').DataTable({
        pageLength: 50,
      });
    });
  </script>
@endsection

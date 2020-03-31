@php
  // -----------------------
  // Backpack ChartJS Widget
  // -----------------------
  // Uses:
  // - Backpack\CRUD\app\Http\Controllers\ChartController
  // - https://github.com/ConsoleTVs/Charts
  // - https://github.com/chartjs/Chart.js

  $controller = new $widget['controller'];
  $chart = $controller->chart;
  $path = $controller->getLibraryFilePath();
@endphp

<div class="{{ $widget['wrapperClass'] ?? 'col-sm-6 col-md-4' }}">
  <div class="{{ $widget['class'] ?? 'card mb-2' }}">
    @if (isset($widget['content']['header']))
    <div class="card-header">{!! $widget['content']['header'] !!}</div>
    @endif
    <div class="card-body">

      {!! $widget['content']['body'] ?? '' !!}

      <div class="card-wrapper">
        {!! $chart->container() !!}
      </div>

    </div>
  </div>
</div>

@push('after_scripts')
  @if (is_array($path))
    @foreach ($path as $string)
      <script src="{{ $string }}" charset="utf-8"></script>
    @endforeach
  @elseif (is_string($path))
    <script src="{{ $path }}" charset="utf-8"></script>
  @endif

  {!! $chart->script() !!}

@endpush
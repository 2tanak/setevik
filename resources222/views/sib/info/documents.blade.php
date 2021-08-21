@extends('layouts.app')

@section('title', 'Документы (файлы)')

@section('content')
    <div class="row">
        <div class="well">
            <h2>Информация / Документы (файлы)</h2>

            <div class="table-responsive">
                <table class="table table-striped table-bordered table-forum" style="margin-bottom: auto">
                    <thead>
                    <tr>
                        <th class="text-center" style="width: 5%">№<br>п/п</th>
                        <th class="hidden-xs hidden-sm text-center v-align-m">Наименование</th>
                        <th class="hidden-xs hidden-sm text-center v-align-m" style="width: 200px;">Дата размещения</th>
                        <th class="hidden-xs hidden-sm text-center v-align-m" style="width: 200px;">Количество файлов</th>
                    </tr>
                    </thead>
                    <tbody>

                        @foreach($data as $k => $item)
						
								     @if(Auth::user()->cabinet_id == 2)
			 @if(count($item->badges->where('user_id',Auth::user()->id)) <= 0 && count($item->watches->where('user_id',Auth::user()->id)) == 0  )
			 @php continue ;@endphp
			 @endif
			 @endif

                            <tr>
                                <td class="text-center">{{ $k + 1 }}</td>
                                <td>
                                    <h4>
                                        <i class="fa fa-book fa-2x text-muted" style="font-size: 1.2em;"></i> &nbsp;
                                        <a href="/info/documents/{{ $item->id }}">
                                            {{ $item->title }}
                                        </a>
                                        @if (!$item->watches->contains('user_id', auth()->id()))
                                            <span class="text-red">(new)</span>
                                        @endif
                                    </h4>
                                    <div class="hidden-sm hidden-md hidden-lg">
                                        <br>
                                        Дата размещения: {{ $item->created_at->format('d.m.Y') }}<br>
                                        Количество файлов: {{ $item->files->count() }}
                                    </div>
                                </td>
                                <td class="hidden-xs hidden-sm text-center">
                                    <small>
                                        <i>{{ $item->created_at->format('d.m.Y') }}</i>
                                    </small>
                                </td>
                                <td class="hidden-xs hidden-sm text-center">
                                    <small>
                                        <i>{{ $item->files->count() }}</i>
                                    </small>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            <div class="text-center pagination-custom">
                {{ $data->links() }}
            </div>
        </div>
    </div>
@endsection
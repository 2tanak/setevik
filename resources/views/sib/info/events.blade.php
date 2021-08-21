@extends('layouts.app')

@section('title', 'Линейка событии')

@section('content')
    <div class="row">
        <div class="well">
            <h2>Информация / Линейка событии</h2>

            {{--<events :items="{{ json_encode($data) }}"></events>--}}

            @foreach($data as $item)
					     @if(Auth::user()->cabinet_id == 2)
			 @if(count($item->badges->where('user_id',Auth::user()->id)) <= 0 && count($item->watches->where('user_id',Auth::user()->id)) == 0  )
			 @php continue ;@endphp
			 @endif
			 @endif

                <div class="row">
                    <div class="content">
                        <div class="col-md-4">
                            @if($item->announcePic)
                                <img class="img-responsive" src="/storage/{{ ltrim($item->announcePic->path, 'public/') }}" width="649" height="378" alt="announce" title="{{ $item->title }}">
                            @endif
                        </div>
                        <div class="col-md-8 padding-left-0">
                            <h3 class="margin-top-0">
                                <a href="/info/events/{{ $item->id }}">
                                    {{ $item->title }}
                                </a>

                                @if (!$item->watches->contains('user_id', auth()->id()))
                                    <span class="text-red">(new)</span>
                                @endif
                            </h3>
                            <p style="padding-bottom: 10px">
                                <b>СТОИМОСТЬ:</b> {{ $item->price }} <br>
                                <b>ДАТА:</b> {{ $item->date }} <br>
                                <b>ФОРМАТ ПРОВЕДЕНИЯ:</b> {{ $item->format }} <br>
                                <b>МЕСТО ПРОВЕДЕНИЯ:</b> {{ $item->location }}
                            </p>
                            <p class="preview-text">{!! $item->announce !!} <br></p>
                            <div class="hidden-xs">
                                <a class="btn btn-primary" href="/info/events/{{ $item->id }}"> Подробнее </a>
                            </div>
                            <div class="hidden-sm hidden-md hidden-lg">
                                <a class="btn btn-primary btn-read-more" href="/info/events/{{ $item->id }}"> Подробнее </a>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
            @endforeach

            <div class="text-center">
                {{ $data->links() }}
            </div>
        </div>
    </div>
@endsection
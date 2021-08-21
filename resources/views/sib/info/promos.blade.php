@extends('layouts.app')

@section('title', 'Промо и акции')

@section('content')
    <div class="row">
        <div class="well">
            <h2>Информация / Промо и акции</h2>

            @foreach($data as $item)
                <div class="row">
                    <div class="content">
                        <div class="col-md-4">
                            @if($item->announcePic)
                                <img class="img-responsive" src="{{ preg_replace('/public\//', '/storage/', $item->announcePic->path) }}" width="649" height="378" alt="announce" title="{{ $item->title }}">
                            @endif
                            <ul class="list-inline padding-10 text-center">
                                <li>
                                    <i class="fa fa-calendar"></i>
                                    {{ $item->created_at->format('d.m.Y') }}
                                </li>
                                <li>
                                    <i class="fa fa-eye"></i>
                                    {{ $item->watches->count() }} Просмотров
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-8 padding-left-0">
                            <h3 class="margin-top-0">
                                <a href="/info/promos/{{ $item->id }}">
                                    {{ $item->title }}
                                </a>

                                @if (!$item->watches->contains('user_id', auth()->id()))
								
                                    <span class="text-red">(new)</span>
                                @endif
                            </h3>
                            <p class="preview-text">{!! $item->announce !!} <br></p>
                            <div class="hidden-xs">
                                <a class="btn btn-primary" href="/info/promos/{{ $item->id }}"> Подробнее </a>
                            </div>
                            <div class="hidden-sm hidden-md hidden-lg">
                                <a class="btn btn-primary btn-read-more" href="/info/promos/{{ $item->id }}"> Подробнее </a>
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
@extends('layouts.app')

@section('title', 'Новости')

@section('content')
    <div class="row">
        <div class="well">
            @if (auth()->user()->cabinet->id == \App\Models\Cabinet::SIB)
                <h2>Online Smart System / Новости</h2>
            @else
                <h2>Новости</h2>
            @endif
            <br>

            @foreach($data as $item)
			
			@if(Auth::user()->cabinet_id == 2)
			 @if(count($item->badges->where('user_id',Auth::user()->id)) <= 0 && count($item->watches->where('user_id',Auth::user()->id)) == 0  )
			 @php continue ;@endphp
			 @endif
			 @endif

			@if(Auth::user()->cabinet_id == 3)
			 @if(count($item->badges->where('user_id',Auth::user()->id)) !=2 && count($item->watches->where('user_id',Auth::user()->id)) == 0  )
			 @php continue ;@endphp
			 @endif
			@endif


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
                                <a href="/oss/info/news/{{ $item->id }}">{{ $item->title }}</a>

                                @if (!$item->watches->contains('user_id', auth()->id()))
                                    <span class="text-red">(new)</span>
                                @endif
                            </h3>
                            <p class="preview-text">{!! $item->announce !!}</p>
                            <br>
                            <div class="hidden-xs">
                                <a class="btn btn-primary" href="/oss/info/news/{{ $item->id }}"> Читать </a>
                            </div>
                            <div class="hidden-sm hidden-md hidden-lg">
                                <a class="btn btn-primary btn-read-more" href="/info/news/{{ $item->id }}"> Читать </a>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
				
            @endforeach

            <div class="text-center">{{ $data->links() }}</div>
        </div>
    </div>
@endsection

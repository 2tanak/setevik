@extends('layouts.app')

@section('title', 'Линейка событии')

@section('content')
    <div class="row">
        <div class="well news-detail">
            <div class="content">
                @if ($item->detailPic)
                    <img class="img-responsive padding-top-25" src="{{ preg_replace('/public\//', '/storage/', $item->detailPic->path) }}" alt="detail" title="{{ $item->title }}">
                    <br>
                @endif

                <h3>
                    {{ $item->title }}
                </h3>

                <p class="detail-text">
                    {!! $item->detail !!}
                </p>

                @if ($item->files()->count())
                    <hr/>
                    <div class="inbox-download">
                        <ul class="inbox-download-list">

              @foreach($item->files as $file)
							
							
		     @if(Auth::user()->cabinet_id == 2)
			 @if(count($item->badges->where('user_id',Auth::user()->id)) <= 0 && count($item->watches->where('user_id',Auth::user()->id)) == 0  )
			 @php continue ;@endphp
			 @endif
			 @endif

			

							
							
                                <?php $ext = pathinfo(preg_replace('/public\//', '/storage/', $file->path), PATHINFO_EXTENSION); ?>
                                <li>
                                    @if(in_array($ext, ['jpeg', 'jpg', 'png']))
                                        <img class="img img-responsive" src="{{ preg_replace('/public\//', '/storage/', $file->path) }}" alt="" width="300"> <br>
                                        <a href="/storage/{{ ltrim($file->path, 'public/') }}" download="{{ $file->original_name }}">Скачать</a>
                                    @else
                                        <div class="well well-sm text-center">
                                                <span>
                                                    @if(in_array($ext, ['xls']))
                                                        <i class="fa fa-file-excel-o"></i>
                                                    @elseif(in_array($ext, ['word']))
                                                        <i class="fa fa-file-word-o"></i>
                                                    @elseif(in_array($ext, ['pdf']))
                                                        <i class="fa fa-file-pdf-o"></i>
                                                    @else
                                                        <i class="fa fa-file"></i>
                                                    @endif
                                                </span>
                                            <br>
                                            <strong>{{ $file->original_name }}</strong>
                                            <br>
                                            <br>
                                            <a href="{{ preg_replace('/public\//', '/storage/', $file->path) }}" download="{{ $file->original_name }}"> Скачать</a>
                                        </div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <br>
                <a class="btn btn-primary" href="/info/promos">Возврат к списку</a>
            </div>
        </div>
    </div>
@endsection

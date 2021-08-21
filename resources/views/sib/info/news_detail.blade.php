@extends('layouts.app')

@section('title', 'Новости')

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="well padding-20 news-detail">
                <div class="content">
                    @if ($item->detailPic)
                        <img class="img-responsive padding-top-25" src="{{ preg_replace('/public\//', '/storage/', $item->detailPic->path) }}" alt="detail" title="{{ $item->title }}">
                        <br>
                    @endif

                    <span class="news-date-time">
                        {{ $item->created_at->format('d.m.Y') }}
                    </span>

                    <h3>{{ $item->title }}</h3>

                    <div>{!! $item->detail !!}</div>

                    @if ($item->files()->count())
                        <hr/>
                        <div class="inbox-download">
                            <ul class="inbox-download-list">

                                @foreach($item->files as $file)
                                    <?php $ext = pathinfo(preg_replace('/public\//', '/storage/', $file->path), PATHINFO_EXTENSION); ?>
                                    <li>
                                        @if(in_array($ext, ['jpeg', 'jpg', 'png']))
                                            <img class="img img-responsive" src="/storage/{{ ltrim($file->path, 'public/') }}" alt="" width="300"> <br>
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
                    <a class="btn btn-primary" href="/info/news">Возврат к списку</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('title', 'Документы (файлы)')

@section('content')
    <div class="row">
        <div class="well">
            <h2>Информация / Документы (файлы) / {{ $item->title }}</h2>

            <p class="detail-text">
                {!! $item->detail !!}
            </p>

            @if ($item->files()->count())
                <hr/>
                <div class="inbox-download">
                    <ul class="inbox-download-list">

                        @foreach($item->files as $file)
                            <?php $ext = pathinfo(preg_replace('/public\//', '/storage/', $file->path), PATHINFO_EXTENSION); ?>
                            <li>
                                @if(in_array($ext, ['jpeg', 'jpg', 'png']))
                                    <img class="img img-responsive" src="{{ preg_replace('/public\//', '/storage/', $file->path) }}" alt="" width="300"> <br>
                                    <a href="/storage/public/{{ ltrim($file->path, 'public/') }}" download="{{ $file->original_name }}">Скачать</a>
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
            <br>
            <a href="/info/documents" class="btn btn-primary">Возврат к списку</a>
        </div>
    </div>
@endsection
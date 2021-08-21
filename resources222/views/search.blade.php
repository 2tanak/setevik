@extends('layouts.app')

@section('title', 'Поиск')

@section('content')
    <div class="row">
        <div class="well">
            <h2>Поиск</h2>

            <div class="widget-body">
                <form class="form-horizontal" action="" name="search_form">
                    <fieldset>
                        <div class="form-group">
                            <label class="col-md-2 control-label"></label>
                            <div class="col-md-10">
                                <input class="form-control" name="q" type="text" value="{{ $q }}">
                            </div>
                        </div>
                        <fieldset>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="submit" name="SEARCH" value="Поиск" class="btn btn-primary">
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </fieldset>
                </form>
            </div>
        </div>

        <div id="modalInfo" class="modal fade">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">Информация о пользователе</h4>
                    </div>
                    <div class="modal-body"></div>
                </div>
            </div>
        </div>

        <div class="well">
            <div class="search-user-wrapper">
                <div class="row">
                    <br>


             @if (count($data) > 0)
               @foreach($data as $item)
				  <div class="col-md-12">
                    <div class="well">
                      <div class="media">
                        <div class="media-left">
                          <div class="item">
                             <a href="#myModalBox-{{ $item->user_info->id }}">
                                <img class="media-object {{ $item->user_info->is_active ? 'active' : '' }}" height="50px" src="{{ File::exists('./'.$item->user_info->photo) ? $item->user_info->photo : '/img/avatars/no-photo.jpg' }}" alt="{{ $item->user_info->name }}">
                              </a>
							</div>
                           </div>
                         <div class="media-body">
							
							
							
                                           {{$item->user_info->name}} {{ $item->user_info->last_name }}
											
											<br>

                                            ID:
                                            <a href="#myModalBox-{{ $item->user_info->id }}" data-toggle="modal" data-html="true">
                                                {{ $item->user_info->getPublicId() }}
                                            </a>

                                            @if ($item->user_info->package->name)
                                                <br>
                                                Пакет: {{ $item->user_info->package->name }}
                                            @endif
                                        </div>
                                    </div>
                                    <div id="myModalBox-{{ $item->user_info->id }}" class="modal fade">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    <h4 class="modal-title">Информация о пользователе</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div>
                                                        <div class="media-body">
                                                            <b>Ф.И.О.:</b> {{ $item->user_info->getFullName() }}<br>
                                                            <b>ID:</b> {{ $item->user_info->getPublicId() }}<br>
                                                            @if($item->user_info->isPartner())
                                                                <b>Куратор:</b> {{ ($item->user_info->getCuratorNodePartner()) ? $item->user_info->getCuratorPartner()->getFullName() : '' }}<br>
                                                                <b>Пакет:</b> {{ $item->user_info->package->name }}<br>
                                                                @if($item->user_info->activated_at)
                                                                    <b>Дата регистрации:</b> {{ $item->user_info->activated_at->format('d.m.Y') }}<br>
                                                                @endif
                                                            @else
                                                                <b>Куратор:</b> {{ $item->user_info->getCuratorResident() ? $item->user_info->getCuratorResident()->getFullName() : '' }}<br>
                                                                @if($item->user_info->oss_activated_at)
                                                                    <b>Дата регистрации:</b> {{ $item->user_info->oss_activated_at->format('d.m.Y') }}<br>
                                                                @endif
                                                            @endif
                                                            {{--<b>Статус:</b> {{ $item->user_info->status->name }}<br>--}}
                                                            <b>Квалификация:</b>  {{ $item->user_info->is_qualified ? 'Есть' : 'Нет' }}<br>
                                                            @if($item->user_info->package_id)
                                                            <hr>
                                                            <a href="/me-and-my-team/{{ $item->user_info->tree_node_id }}">Посмотреть в бинаре</a> <br>
                                                            @endif
                                                        </div>
														
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    @else
                        <p class="txt-color-red">Ничего не найдено</p>
                    @endif

                    <div class="text-center">
                        @if(count($data) > 0)
						
                         
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
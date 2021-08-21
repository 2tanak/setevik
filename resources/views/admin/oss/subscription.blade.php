@extends('layouts.app')

@section('title', 'Абонементы')

@section('content')
    <div class="row">
        <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-0" data-widget-editbutton="false" data-widget-fullscreenbutton="true">
            <header>
                <span class="widget-icon">
                    <i class="fa fa-fw fa-ticket"></i>
                </span>
                <h2>Абонементы</h2>
            </header>
            <div>
                <div class="jarviswidget-editbox"></div>
                <div class="widget-body">
                    <div class="report-wrapper">
                        <table class="display compact table-bordered">
                            <thead>
                                <tr>
                                    <th>&#8470;</th>
                                    <th>Резидент</th>
                                    <th>Логин</th>
                                    <th>Продукт</th>
                                    <th>Активен</th>
                                    <th>Окончание последнего абонемента</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $counter = 1; ?>
                                @foreach($users as $user)
                                    @if($user->subscriptions->count())
                                        <tr>
                                            <td class="text-center">
                                                {{ $counter }}
                                            </td>
                                            <td class="text-center">
                                                {{ $user->getFullName() }}
                                            </td>
                                            <td class="text-center">
                                                {{ $user->login }}
                                            </td>
                                            <td class="text-center">
                                                {{ $user->subscriptions->last()->product->name }}
                                            </td>
                                            <td class="text-center">
                                                @if($user->subscriptions->last()->expired_at > \Carbon\Carbon::now())
                                                    Да
                                                @else
                                                    Нет
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                {{ $user->subscriptions->last()->expired_at->format('Y-m-d H:i:s') }}
                                            </td>
                                        </tr>
                                        <?php $counter++; ?>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
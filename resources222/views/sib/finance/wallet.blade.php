@extends('layouts.app')

@section('title', 'Кошелёк')

@section('content')
    <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 lock-wrapper">
        <div class="lockscreen animated flipInY">
            <div class="padding-20 text-center">
                <img src="/img/maintenance.png" class="img-responsive" style="margin: 0 auto;" alt="log">
            </div>
            <br>
            <div class="row">

                <div class="padding-20 text-center">
                    <p>Упс) Мы ещё здесь работаем) <br>
                        Страница находится в разработке и уже скоро будет доступна.</p>
                </div>
            </div>
        </div>
    </div>
@endsection

<?/*
@section('content')
    <div class="row">
        <div class="well" id="wallet">
            <h2>Финансы / Кошелёк</h2>

            <!-- #BONUSES -->
            <div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <tbody>
                        <tr class="no-skin">
                            <th></th>
                            <th>Заработано в системе, $</th>
                            <th>Ожидается к выплате, $</th>
                            <th>Доступно к выводу, $</th>
                        </tr>
                        <?php
                        $total = 0;
                        ?>
                        @foreach ($wallets as $wallet)
                            <tr>
                                <th>
                                    {{ $wallet->bonus->name }}
                                </th>
                                <td>
                                    @money($wallet->earned)
                                </td>
                                <td>
                                    @money($wallet->expected)
                                </td>
                                <td>
                                    @money($wallet->available)
                                </td>
                            </tr>
                            <?php $total += $wallet->earned; ?>
                        @endforeach

                        <tr>
                            <th>ИТОГО</th>
                            <td>
                                @money($total) (выведено: @money(0))
                            </td>
                            <td>
                                @money(0)
                            </td>
                            <td>
                                @money(0) (запрошено: <span id="requestedWithdraw">@money(0)</span>)
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <a href="#" data-target="#modalUserWithdraw" data-toggle="modal" class="btn btn-success pull-right">Вывести средства</a>
            <div class="clb"></div>
        </div>

        <!-- #STATEMENTS -->
        <div class="well">
            <h2>История операций</h2>
            <div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered statement">
                        <tbody>
                        <tr>
                            <th class="text-center reports-first-column">ID</th>
                            <th class="text-center">Дата</th>
                            <th class="text-center">Сальдо на начало, $</th>
                            <th class="text-center">Сумма операции, $</th>
                            <th class="text-center">Сальдо на конец, $</th>
                            <th class="text-center">Вид операции</th>
                            <th class="text-center">Инициатор</th>
                            <th class="text-center">Пакет</th>
                            <th class="text-center">Уровень</th>
                        </tr>

                        @foreach ($statements as $statement)
                            <tr>
                                <td>
                                    {{ $statement->id }}
                                </td>
                                <td>
                                    {{ $statement->created_at->format('d.m.Y H:i:s') }}
                                </td>
                                <td class="text-right">
                                    @money($statement->balance_begin)
                                </td>
                                <td class="text-right">
                                    @money($statement->amount)
                                </td>
                                <td class="text-right">
                                    @money($statement->balance_end)
                                </td>
                                <td class="text-center">
                                    {{ $statement->bonus->name }}
                                </td>
                                <td>
                                    <a href="#">
                                        {{ $statement->initiator->id }}
                                    </a>
                                </td>
                                <td>
                                    @if ($statement->package)
                                        {{ $statement->package->name }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if ($statement->level)
                                        {{ $statement->level }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
                <div class="text-center">
                    {{ $statements->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
*/?>

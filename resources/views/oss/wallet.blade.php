@extends('layouts.app')

@section('title', 'Кошелёк')

@section('content')
    <div class="row" id="wallet">
        <div class="well">
            @if (auth()->user()->cabinet->id == \App\Models\Cabinet::SIB)
                <h2>Online Smart System / Кошелёк</h2>
            @else
                <h2>Кошелёк</h2>
            @endif

            <div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <tbody>
                            <tr class="no-skin">
                                <th><h4><b>Заработано:</b></h4></th>
                                {{--<th><h4><b>Заработано на Платформе OSS за:</b></h4></th>--}}
                                <th class="text-center"><h4><b>2020 г.</b></h4></th>
                                <th class="text-center"><h4><b>2021 г.</b></h4></th>
                            </tr>
                            @foreach ($wallets as $mounth => $wallet)
                                <tr>
                                    @if(isset($wallet['ru_month']))
                                        <td>{{$wallet['ru_month']}}</td>
                                    @endif
                                    
                                    @foreach ($wallet as $year => $item)
                                        @if(isset($item['sum']))
                                            <td class="text-center">${{ $item['sum'] }}</td>
                                        @endif
                                    @endforeach
                                </tr>
                            @endforeach
                            <tr>
                                <th><h4><b>Итого:</b></h4></th>
                                @foreach($total as $key=> $value)
                                    <td class="text-center">
                                        <h4><b>${{$value}}</b></h4>
                                    </td>
                                @endforeach
                              
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="well">
            <h2>История операций</h2>
            <div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered">
                        <tbody>
                        <tr>
                            <th class="text-center">Дата</th>
                            <th class="text-center" style="width: 50%">Инициатор</th>
                            <th class="text-center">Сумма, $</th>
                            <th class="text-center">Продукт</th>
                            <th class="text-center">Операция</th>
                        </tr>

                        @foreach ($data as $item)
                            <tr>
                                <td class="text-center">
                                    {{ $item->created_at }}
                                </td>
                                <td class="text-center">
                                    {{ $item->customer->name }} {{ $item->customer->last_name }}
                                </td>
                                <td class="text-center">
                                    @money($item->price)
                                </td>
                                <td class="text-center">
                                    {{ $item->product->name }}
                                </td>
                                <td class="text-center">
                                    {{ $item->description }}
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
                <div class="text-center">
                    {{ $data->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
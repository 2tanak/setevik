@extends('layouts.app')

@section('title', 'Запросы на вывод средств')

@section('content')
    <div class="row">
        <div class="well" id="wallet">
            <h2>Финансы / Просмотр запроса на вывод средств</h2>
            <!-- #BONUSES -->
            <div>
                <div class="table-responsive">
                    <?php if(!empty($data)){
                        foreach($data as $item){
                            echo $item['id'].'<hr>';
                        }
                    }else{?>
                           <h1>Данные по такому запросу отсутствуют</h1>
                    <?php }?>
                </div>
            </div>
            <div class="clb"></div>
        </div>
    </div>
@endsection
<style>
    .status_label{
        color: #ffffff;
        font-size: 12px;
        padding-top: 5px;
        padding-bottom: 5px;
        padding-left: 10px;
        padding-right: 10px;
        border-radius: 3px;
    }
    .status_label_new{
        background-color: #9a0325;
    }
    .status_label_in_process{
        background-color: #b9662b;
    }
    .status_label_cancel{
        background-color: #0d0f12;
    }
    .status_label_success{
        background-color: #0aa66e;
    }
    .orders_btn{
        color: #ffffff;
        padding: 5px;
        border-radius: 3px;
    }
    .orders_btn:hover{
        background-color: #000000;
        color: #ffffff;
        padding: 5px;
        border-radius: 3px;
    }
    .orders_view{
        background-color: #0c7cd5;
        color: #ffffff;
        padding: 5px;
        border-radius: 3px;
    }
    .orders_update{
        background-color: #b9662b;
        color: #ffffff;
        padding: 5px;
        border-radius: 3px;
    }
    .orders_success{
        background-color: #0aa66e;
        color: #ffffff;
        padding: 5px;
        border-radius: 3px;
    }
    .orders_delete{
        background-color: #9a0325;
        color: #ffffff;
        padding: 5px;
        border-radius: 3px;
    }
</style>

<?php

namespace App\Http\Controllers\Oss;

use App\Models\Sale;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class WalletController extends OssController
{
	public function __construct()
    {
        parent::__construct();
       
    }

    public function index()
    {
        $ru_month   = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];
        $en_month   = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $wallets     = [];
        $total      = ['2020' => 0 , '2021' => 0];
        $years = ['2020-01-01', '2021-01-01'];

        foreach($years as $year) {
            $start = Carbon::createFromFormat('Y-m-d', $year);
            // $date[] = $start;
            while ($start->year) {
                // dump($start->year);
                if ($start->year <= Carbon::now()->addYear()->year) {
                    $wallets[$start->format('F')]['ru_month'] = str_replace($en_month, $ru_month, $start->format('F'));
                    $wallets[$start->format('F')][substr($year, 0, 4)] = [
                        'sum'   => 0,
                        'date'  => str_replace($en_month, $ru_month, $start->format('F'))
                    ];
                } else {
                    break;
                }
    
                $start->addMonth();
            }
        }
        
        foreach ($wallets as $mounth => $wallet) {

            foreach ($wallet as $year => $walletItem) {
                $list = Sale::where('seller_id', Auth::id())->get();


                foreach ($list as $keyitem => $item) {

                      if($item->created_at->format('Y') == $year && $item->created_at->format('F') == $mounth) {
                        //   $wallets[$item->created_at->format('F')][$year]['sum'] += $item->price;
                        //   $total += $item->price;
                        $wallets[$mounth][$year]['sum'] += $item->price;
                        $total[$year] += $item->price;
                      }
                     
                  }
                
            }
    }

        $data = Sale::with(['product', 'customer'])
            ->where('seller_id', Auth::id())
            ->orderByDesc('id')
            ->paginate(20);

        return view('oss.wallet', compact('data', 'wallets', 'total'));
    }
}

<?php

namespace App\Observers;

use App\Models\Requisition;
use App\Events\Requisitions\Registered;
use App\Events\Requisitions\ConfirmedByOwner;
use App\Events\Requisitions\ConfirmedByAdmin;
use App\Events\Requisitions\ConfirmedByCurator;
use App\Events\Requisitions\CanceledByAdmin;
use App\Events\Requisitions\CanceledByCurator;

use Carbon\Carbon;

class RequisitionObserver
{
    public function creating(Requisition $requisition)
    {
        event(new Registered($requisition));
    }

    public function updating(Requisition $requisition)
    {
        if ($requisition->isDirty('curator_quittance_id')) {
            $requisition->curator_confirmed_at = Carbon::now();
            event(new ConfirmedByCurator($requisition));
        }

        if ($requisition->isDirty('user_quittance_id')) {
            event(new ConfirmedByOwner($requisition));
        }

        if ($requisition->isDirty('is_confirmed')) {
            $requisition->confirmed_at = Carbon::now();
            //Если есть квитанция от менеджера
            if($requisition->curator_quittance_id !== NULL)
            {
                //Статус заявки проставляемый админом
                if($requisition->is_cancelled == 0){
                    event(new ConfirmedByAdmin($requisition));
                }
                else{
                    event(new CanceledByAdmin($requisition));
                }
            }
            //Если нету квитанции от менеджера
            else{
                //Статус заявки проставляемый менеджером
                if($requisition->is_cancelled == 1){
                    event(new CanceledByCurator($requisition));
                }
            }
        }
    }
}
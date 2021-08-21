<?php

namespace App\Http\Controllers;

use App\Models\Quittance;
use App\Models\Requisition;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class QuittanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:resident,resident-na,partner,partner-na');
    }

    /**
     * Check permissions
     *
     * @param $id
     * @return bool
     */
    public function hasAccess($id)
    {
        $user = Auth::user();
        $quittance = Quittance::with('file')->findOrFail($id);

        if ($user->isAdmin()) {
            return true;
        }

        $requisition = Requisition::where('curator_id', $user->id)
            ->where('user_quittance_id', $quittance->id)
            ->first();

        if ($requisition) {
            return true;
        }


        return false;
    }

    /**
     * Download the quittance
     *
     * @param $id - Quittance ID
     * @return mixed
     */
    public function download($id)
    {
        if ($this->hasAccess($id)) {

            $quittance = Quittance::with('file')->findOrFail($id);
            $storagePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
            $filePath = sprintf('%s%s', $storagePath, ltrim($quittance->file->path, '/'));

            return response()->download($filePath, $quittance->file->original_name);
        }

        return abort(404);
    }

    /**
     * Getting encoded image
     *
     * @param $id
     * @return string
     */
    public function image($id)
    {
        if ($this->hasAccess($id)) {
            $quittance = Quittance::with('file')->findOrFail($id);
            $storagePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
            $path = sprintf('%s%s', $storagePath, ltrim($quittance->file->path, '/'));

            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);

            return 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        return abort(404);
    }
}

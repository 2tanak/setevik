<?php

namespace App\Http\Controllers\Admin\Sib;

use App\Models\Documents;
use App\Http\Controllers\Admin\AdminController;

use Illuminate\Http\Request;

class DocumentsController extends AdminController
{
    /**
     * List of documents
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $data = Documents::orderByDesc('created_at')->get();
        return view('admin.sib.documents')->with('data', $data);
    }

    /**
     *
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $item = Documents::with('files')->find($id);

        if (is_null($item)) {
            $item = new Documents();
            $item->id = 0;
        }

        return view('admin.sib.documents_detail')->with('item', $item);
    }

    /**
     *
     *
     * @param Request $request
     * @return Documents|\Illuminate\Database\Eloquent\Model
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
        ]);

        // checkbox
        $request->merge([
            'is_active' => $request->input('is_active') == 'on'
        ]);

        $document = Documents::create($request->all());

        return $document;
    }

    /**
     *
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
        ]);

        // checkbox
        $request->merge([
            'is_active' => $request->input('is_active') == 'on'
        ]);

        $item = Documents::findOrFail($id);
        $item->update($request->all());

        return $item;
    }

    /**
     * Delete
     *
     * @param $id
     * @throws \Exception
     */
    public function delete($id)
    {
        $document = Documents::findOrFail($id);
        $document->detachAllFiles();
        $document->delete();
    }

    /**
     * Delete from document
     *
     * @param $documentId
     * @param $fileId
     * @return mixed
     */
    public function deleteFile($documentId, $fileId)
    {
        $document = Documents::findOrFail($documentId);
        $document->detachFile($fileId);
        return $document->files;
    }

    /**
     * Store and attach file
     *
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function uploadFile(Request $request, $id)
    {
        $this->validate($request, [
            'files' => 'required',
        ]);

        $document = Documents::findOrFail($id);
        $document->attachFiles($request->file('files'));

        return $document->files;
    }
}

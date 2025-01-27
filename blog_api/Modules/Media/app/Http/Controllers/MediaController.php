<?php

namespace Modules\Media\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Media\Services\MediaStorage;
use Modules\Media\Services\ObjectStorage;

class MediaController extends Controller
{
    private ObjectStorage $storage;

    public function __construct(ObjectStorage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('media::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('media::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "files" => 'required|array',
            "files.*" => 'file',
        ]);

        $urls = [];

        $files = $request->file('files');

        foreach ($files as $key => $file) {
            $url = $this->storage->store('files', $file, $file->getClientOriginalName());
            if($url) {
                $urls[] =  $this->storage->getUrl($url);
            }
        }

        return response()->json([
            'status'  => true,
            'data'     => [
                'urls' => $urls
            ],
            'message' => 'Successfully uploaded',
        ], 200);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('media::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('media::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}

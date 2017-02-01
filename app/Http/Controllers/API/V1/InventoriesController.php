<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Inventory;
use App\Http\Requests\NewInventoryRequest;

class InventoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewInventoryRequest $request)
    {
        $inventory = Inventory::create($request->all());
        return response()->json([
            'message' => 'New inventory created',
            'data' => $inventory,
            'link' => route('inventories.show', ['inventory' => $inventory->id]),
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }
}

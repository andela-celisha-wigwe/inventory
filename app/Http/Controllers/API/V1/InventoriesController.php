<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Repositories\API\V1\InventoryRepository;

use App\Http\Requests\NewInventoryRequest;

class InventoriesController extends Controller
{
    /**
     * $inventoryRepo InventoryRepository instance
     * to handle transacetion with inventory table
     * 
     * @var InventoryRepository
     */
    protected $inventoryRepo;

    /**
     * Instantiate the Inventory repository
     */
    public function __construct()
    {
        $this->inventoryRepo = new InventoryRepository();
    }

    /**
     * Display a listing of inventories.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $inventories = $this->inventoryRepo->getInventories($request->name, $request->description, $request->by, $request->order, $request->limit);

        list($message, $status) = $inventories->count() ? ['All inventories.', 200] : ['No inventories.', 404];

        return response()->json([
            'message' => $message,
            'data' => $inventories,
            'count' => $inventories->count(),
            'link' => route('inventories.index')
        ], $status);
    }

    /**
     * Store a newly created inventory in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewInventoryRequest $request)
    {
        $inventory = $this->inventoryRepo->storeInventory($request->all());
        return response()->json([
            'message' => 'New inventory created',
            'data' => $inventory,
            'link' => route('inventories.show', ['inventory' => $inventory->id]),
        ], 201);
    }

    /**
     * Display the specified inventory.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $inventory = $this->inventoryRepo->getInventory((int) $id);
        list($message, $status) = $inventory ? ['Inventory found.', 200] : ['Inventory not found.', 404];

        return response()->json([
            'message' => $message,
            'data' => $inventory,
            'link' => route('inventories.index'),
        ], $status);
    }
}

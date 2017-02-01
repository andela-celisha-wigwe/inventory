<?php

namespace App\Http\Repositories\API\V1;

use App\Inventory;

class InventoryRepository
{
	/**
	 * The condition to use for searching the database
	 * 
	 * @var string
	 */
	private $condition;

	/**
	 * $allowedOrders The allowed orders to be used for sorting a collection
	 * @var array
	 */
	private $allowedOrders = ["asc", "desc"];

	public function __construct()
	{
		$this->condition = env('DB_CONNECTION') == 'pgsql' ? 'ILIKE' : 'LIKE';
	}

	/**
	 * getInventories Rerieve a collection of Inventory records given the optional query parameters
	 * @param  type $name        	The query name to be searched
	 * @param  string $description 	The description name to be searched
	 * @return array             	A collection of Inventories
	 */
	public function getInventories($name, $description, $by = "name", $order = "asc", $limit = null)
	{
		$by = is_null($by) ? "name" : $by;
		$order = is_null($order) || !in_array($order, $this->allowedOrders) ? "asc" : $order;
		$query = Inventory::where('name', $this->condition, "%$name%")
							->where('description', $this->condition, "%$description%")
							->orderBy($by, $order);
		if (!is_null($limit)) {
			$query = $query->take($limit);
		}
		return [$query->get(), [
				'name' => $name,
	            'description' => $description,
	            'order' => $order,
	            'by' => $by,
	            'limit' => $limit,
			]
		];
	}

	/**
	 * storeInventory Create a new Inventory record given the inventory data
	 * 
	 * @param  array $data 	The data to be stored
	 * @return Inventory 	The newly created repository instance
	 */
	public function storeInventory($data)
	{
		return Inventory::create($data);
	}

	/**
	 * getInventory Get the Inventory record given the id
	 * 
	 * @param  int $id 		The id of the inventory
	 * @return Inventory 	The found repository instance
	 */
	public function getInventory($id)
	{
		return Inventory::find($id);
	}
}
<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class InventoryTest extends TestCase
{
    public function testAPICanCreateANewInventory()
    {
    	$data = [
    		'name' => 'Inventory 1',
    		'description' => 'All about the first inventory'
    	];
    	$response = $this->call('POST', '/api/v1/inventories', $data);
		
		$this->assertEquals(201, $response->status());

		$content = json_decode($response->content());
		$this->assertEquals("New inventory created", $content->message);
		$this->assertEquals("http://localhost/api/v1/inventories/1", $content->link);

		$data = $content->data;
		$this->assertEquals('Inventory 1', $data->name);
		$this->assertEquals('All about the first inventory', $data->description);
    }

    public function testAPICannotCreateANewInventoryWithInvalidData()
    {
    	$data = [
    		'name' => '',
    		'description' => 45
    	];
    	$response = $this->call('POST', '/api/v1/inventories', $data);
		
		$this->assertEquals(400, $response->status());

		$content = json_decode($response->content());
		$this->assertEquals("Cannot create inventory due to bad data", $content->message);

		$errors = $content->errors;
		$this->assertEquals(['The name field is required.'], $errors->name);
		$this->assertEquals(['The description must be a string.'], $errors->description);
    }

    public function testAPIReturnsExpectedResponseWhenThereAreNoInventories()
    {
    	$response = $this->call('GET', '/api/v1/inventories');
		
		$this->assertEquals(404, $response->status());

		$content = json_decode($response->content());
		$this->assertEquals("No inventories.", $content->message);
		$this->assertEquals("http://localhost/api/v1/inventories", $content->link);
		$this->assertEquals(0, $content->count);
		$this->assertCount(0, $content->data);

        $params = $content->params;
        $this->assertNull($params->name);
        $this->assertNull($params->description);
        $this->assertEquals("asc", $params->order);
        $this->assertEquals("name", $params->by);
        $this->assertNull($params->limit);
    }

    public function testAPIReturnsExpectedResponseWhenThereAreInventoriesAndInDescendingOrder()
    {
    	$description = 'zzzzzzzzzzzzzzzzzzzzzzzzz';

    	$this->manufactureInventories(99);
    	\App\Inventory::create([
    		'name' => 'Searchable Inventory',
    		'description' => $description
    	]);

    	$response = $this->call('GET', '/api/v1/inventories?by=description&order=desc');
		
		$this->assertEquals(200, $response->status());

		$content = json_decode($response->content());

		$this->assertEquals("All inventories.", $content->message);
		$this->assertEquals("http://localhost/api/v1/inventories", $content->link);
		$this->assertEquals(100, $content->count);
		$this->assertCount(100, $content->data);

		$inventory = $content->data[0];
		$this->assertEquals($description, $inventory->description);

        $params = $content->params;
        $this->assertNull($params->name);
        $this->assertNull($params->description);
        $this->assertEquals("desc", $params->order);
        $this->assertEquals("description", $params->by);
        $this->assertNull($params->limit);
    }

    public function testAPIReturnsExpectedResponseWhenThereAreInventoriesAndInAscendingOrder()
    {
    	$name = 'AAAAAAAAAAAAAAAAAAAAAAAAAAAA';

    	$this->manufactureInventories(99);
    	\App\Inventory::create([
    		'name' => $name,
    		'description' => 'Desription of Searchable Inventory'
    	]);

    	$response = $this->call('GET', '/api/v1/inventories?by=name');
		
		$this->assertEquals(200, $response->status());

		$content = json_decode($response->content());
		$this->assertEquals("All inventories.", $content->message);
		$this->assertEquals("http://localhost/api/v1/inventories", $content->link);
		$this->assertEquals(100, $content->count);
		$this->assertCount(100, $content->data);

		$inventory = $content->data[0];
		$this->assertEquals($name, $inventory->name);

        $params = $content->params;
        $this->assertNull($params->name);
        $this->assertNull($params->description);
        $this->assertEquals("asc", $params->order);
        $this->assertEquals("name", $params->by);
        $this->assertNull($params->limit);
    }

    public function testAPIReturnsExpectedResponseWhenThereAreInventoriesWithSearchParameters()
    {
    	$this->manufactureInventories(99);
    	\App\Inventory::create([
    		'name' => 'Searchable Inventory',
    		'description' => 'All about this searchable inventory'
    	]);

    	$response = $this->call('GET', '/api/v1/inventories?name=searchable&description=about');
		
		$this->assertEquals(200, $response->status());

		$content = json_decode($response->content());
		$this->assertEquals("All inventories.", $content->message);
		$this->assertEquals("http://localhost/api/v1/inventories", $content->link);
		$this->assertEquals(1, $content->count);
		$this->assertCount(1, $content->data);
    }

    public function testAPIReturnsExpectedResponseWhenThereAreInventoriesWithLimitParameter()
    {
    	$this->manufactureInventories(99);

    	$response = $this->call('GET', '/api/v1/inventories?limit=10');
		
		$this->assertEquals(200, $response->status());

		$content = json_decode($response->content());
		$this->assertEquals("All inventories.", $content->message);
		$this->assertEquals("http://localhost/api/v1/inventories", $content->link);
		$this->assertEquals(10, $content->count);
		$this->assertCount(10, $content->data);

        $params = $content->params;
        $this->assertNull($params->name);
        $this->assertNull($params->description);
        $this->assertEquals("asc", $params->order);
        $this->assertEquals("name", $params->by);
        $this->assertEquals(10, $params->limit);
    }

    public function testAPIReturnsExpectedResponseWhenThereAreNoInventoriesWithSearchParameters()
    {
    	$this->manufactureInventories(99);
    	\App\Inventory::create([
    		'name' => 'Searchable Inventory',
    		'description' => 'All about this searchable inventory'
    	]);

    	$response = $this->call('GET', '/api/v1/inventories?name=notsearchable&description=notabout');
		
		$this->assertEquals(404, $response->status());

		$content = json_decode($response->content());
		$this->assertEquals("No inventories.", $content->message);
		$this->assertEquals("http://localhost/api/v1/inventories", $content->link);
		$this->assertEquals(0, $content->count);
		$this->assertCount(0, $content->data);

        $params = $content->params;
        $this->assertEquals("notsearchable", $params->name);
        $this->assertEquals("notabout", $params->description);
        $this->assertEquals($params->order, "asc");
        $this->assertEquals($params->by, "name");
        $this->assertNull($params->limit);
    }

    public function testAPIReturnsIndividualInventory()
    {
    	$this->manufactureInventories(99);
    	\App\Inventory::create([
    		'name' => 'Inventory 2',
    		'description' => 'All about another inventory'
    	]);

    	$response = $this->call('GET', '/api/v1/inventories/100');
		
		$this->assertEquals(200, $response->status());

		$content = json_decode($response->content());
		$this->assertEquals("Inventory found.", $content->message);
		$this->assertEquals("http://localhost/api/v1/inventories", $content->link);

		$data = $content->data;
		$this->assertEquals("Inventory 2", $data->name);
		$this->assertEquals("All about another inventory", $data->description);
    }

    public function testAPIReturnsExpectedResponseWhenRequestedInventoryIsNotFound()
    {
    	$this->manufactureInventories(99);

    	$response = $this->call('GET', '/api/v1/inventories/100');
		
		$this->assertEquals(404, $response->status());

		$content = json_decode($response->content());
		$this->assertEquals("Inventory not found.", $content->message);
		$this->assertEquals("http://localhost/api/v1/inventories", $content->link);
		$this->assertNull($content->data);
    }
}

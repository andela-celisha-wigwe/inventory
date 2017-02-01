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
    }

    public function testAPIReturnsExpectedResponseWhenThereAreInventories()
    {
    	$this->manufactureInventories(99);

    	$response = $this->call('GET', '/api/v1/inventories');
		
		$this->assertEquals(200, $response->status());

		$content = json_decode($response->content());
		$this->assertEquals("All inventories.", $content->message);
		$this->assertEquals("http://localhost/api/v1/inventories", $content->link);
		$this->assertEquals(99, $content->count);
		$this->assertCount(99, $content->data);
    }
}

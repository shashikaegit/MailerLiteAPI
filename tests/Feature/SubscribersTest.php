<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubscribersTest extends TestCase
{
    use WithFaker;

    private $name;
    private $email;
    private $country;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = \Faker\Factory::create();

        $this->name = $this->faker->name;
        $this->email = $this->faker->unique()->safeEmail;
        $this->country = $this->faker->country;
    }

    /**
     * Create Subscriber
     *
     * @return array
     */
    public function testCreateSuccessSubscriber()
    {
        //Create request
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'country' => $this->country,
        ];

        $response = $this->post('/subscriber', $data);

        $response->assertStatus(200);
        //return created email
        $subscriberEmail = $response->json('data.data.email');
        $subscriberID = $response->json('data.data.id');
        return ['email' => $subscriberEmail, 'id' => $subscriberID];
    }

    /**
     * Create Already Exists Subscriber
     * @depends testCreateSuccessSubscriber
     * @return void
     */
    public function testCreateAlreadyExistsSubscriber($subscriberData)
    {
        $data = [
            'name' => $this->name,
            'email' => $subscriberData['email'],
            'country' => $this->country,
        ];

        $response = $this->post('/subscriber', $data);

        $response->assertStatus(200)->assertJson([
            'message' => "Oops! It seems like you're already subscribed to our newsletter. ".
                "If you're having trouble receiving our emails, please contact our support team for assistance.",
            'status' => false
        ]);
    }

    /**
     * Get All Subscribers
     *
     * @return void
     */
    public function testGetAllSubscribers()
    {
        $response = $this->get('/subscriber/list');

        $response->assertStatus(200)->assertJson([
            'message' => "Success: The requested data has been found and retrieved successfully.",
            'status' => true
        ]);
    }

    /**
     * subscriber search by email
     * @depends testCreateSuccessSubscriber
     * @return void
     */
    public function testSubscriberSearchByEmail($subscriberData)
    {
        $response = $this->get('/subscriber/list?searchField='.$subscriberData['email']);

        $response->assertStatus(200)->assertJson([
            'message' => "Success: The requested data has been found and retrieved successfully.",
            'status' => true
        ]);
    }

    /**
     * subscriber search by wrong email
     * @depends testCreateSuccessSubscriber
     * @return void
     */
    public function testSubscriberSearchByWrongEmail($subscriberData)
    {
        $response = $this->get('/subscriber/list?searchField=test'.$subscriberData['email']);

        $response->assertStatus(200)->assertJson([
            'status' => false
        ]);
    }

    /**
     * Update Subscriber
     * @depends testCreateSuccessSubscriber
     * @return void
     */
    public function testUpdateSubscriber($subscriberData)
    {
        $data = [
            'name' => $this->name,
            'email' => $subscriberData['email'],
            'country' => $this->country,
        ];

        $response = $this->put('/subscriber/'.$subscriberData['id'], $data);

        $response->assertStatus(200)->assertJson([
            'message' => "Success! Your subscription preferences have been updated Thank you for staying ".
                "connected with us.",
            'status' => true
        ]);
    }

    /**
     * Delete Subscriber
     * @depends testCreateSuccessSubscriber
     * @return void
     */
    public function testDeleteSubscriber($subscriberData)
    {
        $response = $this->delete('/subscriber/'.$subscriberData['id']);

        $response->assertStatus(200)->assertJson([
            'message' => "Success! You have been unsubscribed from our newsletter.We're sorry to see you go, ".
                "but thank you for being a part of our community.",
            'status' => true
        ]);
    }
}

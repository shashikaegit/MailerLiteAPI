<?php

namespace App\Repositories\Subscriber;

use App\Models\Setting;
use App\Responses\ResponseInterface;
use Exception;
use MailerLite\MailerLite;
use MailerLiteApi\Exceptions\MailerLiteSdkException;
use MailerLiteApi\MailerLite as MailerLiteApiClient;

class ExternalApiSubscriberRepository implements SubscriberRepositoryInterface
{
    /**
     * @var Setting
     */
    private $setting;
    /**
     * @var Setting
     */
    private $apiKey;
    /**
     * @var ResponseInterface
     */
    private $response;

    public function __construct(Setting $setting, ResponseInterface $response)
    {
        $this->setting = $setting;
        $this->response = $response;
        $this->apiKey = '';
        $this->setApiKey();
    }

    /**
     * Set API Key from settings table
     * @return void
     */
    public function setApiKey()
    {
        //Get API key from database
        $setting = $this->setting->where('key', 'mailerlite_apikey')->select('value')->first();
        if ($setting) {
            $this->apiKey = $setting->value;
        }
    }

    /**
     * Get API Client
     * @return MailerLite
     */
    public function getApiClient() : MailerLite
    {
        return new MailerLite(['api_key' => $this->apiKey]);
    }

    /**
     * Get Mailer Lite API Client
     * @return MailerLiteApiClient
     * @throws MailerLiteSdkException
     */
    public function getMailerLiteApiClient() : MailerLiteApiClient
    {
        return new MailerLiteApiClient(['api_key' => $this->apiKey]);
    }

    /**
     * @inheritDoc
     */
    public function all(array $data = []): ResponseInterface
    {
        try {
            //Get Total subscribers
            $totalSubscribers = $this->getMailerLiteApiClient()->subscribers()->count();

            if (isset($data['search']) && $data['search'] != '') {
                //Call API find method
                $apiResponse = $this->getApiClient()->subscribers->find($data['search']);
            } else {
                //Call API get method
                $apiResponse = $this->getApiClient()->subscribers->get($data);
            }

            //Check and set result array
            if ($apiResponse['status_code'] == 200) {
                $this->response->setStatus(true);

                //Set Total subscribers
                $apiResponse['body']['meta']['total'] = (int)$totalSubscribers->count;

                // If the search value is not empty, the response object  will be converted to an array
                // Because the request is coming from the MailerLite API 'find' method.
                if (isset($data['search']) && $data['search'] != '') {
                    //Set Total subscribers
                    $apiResponse['body']['meta']['total'] = count($apiResponse['body']['data']);
                    $apiResponse['body']['data'] = [$apiResponse['body']['data']];
                }

                $this->response->setData($apiResponse['body']);
                $this->response->setMessage('Success: The requested data has been found and retrieved successfully.');
            }
        } catch (Exception $exception) {
            $this->response->setMessage($exception->getMessage());
        }
        return $this->response;
    }

    /**
     * @inheritDoc
     */
    public function create(array $data): ResponseInterface
    {
        try {
            //Check if subscriber is already exists or not
            $subscriber = $this->getApiClient()->subscribers->find($data['email']);

            if ($subscriber['status_code'] == 200) {
                $this->response->setMessage("Oops! It seems like you're already subscribed to our newsletter. "
                    . "If you're having trouble receiving our emails, please contact our support team for assistance.");
            }
        } catch (Exception $exception) { // Save data if subscribers is not found
            try {
                //Call API create method
                $apiResponse = $this->getApiClient()->subscribers->create($data);

                //Check and set result array
                if ($apiResponse['status_code'] == 200 || $apiResponse['status_code'] == 201) {
                    $this->response->setStatus(true);
                    $this->response->setData($apiResponse['body']);
                    $this->response->setMessage('Success! You have been subscribed to our newsletter.'
                    .' Thank you for joining us.');
                }
            } catch (Exception $exception) {
                $this->response->setMessage($exception->getMessage());
            }
        }
        return $this->response;
    }

    /**
     * @inheritDoc
     */
    public function update(int $id, array $data): ResponseInterface
    {
        try {
            //Call API update method
            $apiResponse = $this->getApiClient()->subscribers->update($id, $data);

            //Check and set result array
            if ($apiResponse['status_code'] == 200) {
                $this->response->setStatus(true);
                $this->response->setData($apiResponse['body']);
                $this->response->setMessage('Success! Your subscription preferences have been updated'
                    .' Thank you for staying connected with us.');
            }
        } catch (Exception $exception) {
            $this->response->setMessage($exception->getMessage());
        }
        return $this->response;
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id): ResponseInterface
    {
        try {
            //Call API delete method
            $apiResponse = $this->getApiClient()->subscribers->delete($id);

            //Check and set result array
            if ($apiResponse['status_code'] == 200 || $apiResponse['status_code'] == 204) {
                $this->response->setStatus(true);
                $this->response->setData($apiResponse['body']);
                $this->response->setMessage("Success! You have been unsubscribed from our newsletter."
                    . "We're sorry to see you go, but thank you for being a part of our community.");
            }
        } catch (Exception $exception) {
            $this->response->setMessage($exception->getMessage());
        }
        return $this->response;
    }

    public function show(string $id): ResponseInterface
    {
        try {
            //Call API update method
            $apiResponse = $this->getApiClient()->subscribers->find($id);

            //Check and set result array
            if ($apiResponse['status_code'] == 200) {
                $this->response->setStatus(true);
                $this->response->setData($apiResponse['body']);
                $this->response->setMessage('Success');
            }
        } catch (Exception $exception) {
            $this->response->setMessage($exception->getMessage());
        }
        return $this->response;
    }
}

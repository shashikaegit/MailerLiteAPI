<?php

namespace App\Repositories\Subscriber;

use App\Responses\ResponseInterface;

interface SubscriberRepositoryInterface
{
    /**
     * Get All Subscribers
     * @param array $data
     * @return ResponseInterface
     */
    public function all(array $data = []): ResponseInterface;

    /**
     * Subscriber create method
     * @param array $data
     * @return ResponseInterface
     */
    public function create(array $data): ResponseInterface;

    /**
     * Subscriber update method
     * @param int $id
     * @param array $data
     * @return ResponseInterface
     */
    public function update(int $id, array $data): ResponseInterface;

    /**
     * Subscriber delete method
     * @param int $id
     * @return ResponseInterface
     */
    public function delete(int $id): ResponseInterface;

    /**
     * Subscriber show method
     * @param string $id
     * @return ResponseInterface
     */
    public function show(string $id): ResponseInterface;
}

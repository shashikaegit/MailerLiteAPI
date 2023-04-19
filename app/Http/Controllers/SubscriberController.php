<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriberRequest;
use App\Repositories\Subscriber\SubscriberRepositoryInterface;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SubscriberController extends Controller
{
    /**
     * @var SubscriberRepositoryInterface
     */
    private $subscriberRepository;

    /**
     * SubscriberController __construct method
     */
    public function __construct(SubscriberRepositoryInterface $subscriberRepository)
    {
        $this->subscriberRepository = $subscriberRepository;
    }

    /**
     * Subscribers list page
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('Subscribers.list');
    }

    /**
     * Subscribers create page
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('Subscribers.create');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $subscriberApi = $this->subscriberRepository->show($id);

        if ($subscriberApi->getStatus()) {
            $subscriber = $subscriberApi->getData();
            return view('Subscribers.edit', compact('id', 'subscriber'));
        } else {
            abort(404);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request): \Illuminate\Http\JsonResponse
    {
        //Set per page limit
        $data = [
            'limit' => $request->get('length')
        ];

        //Set cursor
        $cursor = $request->get('previous');
        if ($request->get('click') == 'next') {
            $cursor = $request->get('next');
        }

        if ($cursor != null) {
            $data['cursor'] = $cursor;
        }

        //Search by email
        if ($request->get('searchField') != '') {
            $data['search'] = $request->get('searchField');
        }

        $list = $this->subscriberRepository->all($data);

        $result = [
            'draw' => $request->get('draw'),
            'recordsTotal' => (isset($list->getData()['meta']))? $list->getData()['meta']['total'] : 0,
            'recordsFiltered' => (isset($list->getData()['meta']))? $list->getData()['meta']['total'] : 0,
            'data' => (isset($list->getData()['data']))? $list->getData()['data'] : [],
            'status' => $list->getStatus(),
            'message' => $list->getMessage(),
            'meta' => (isset($list->getData()['meta']))? $list->getData()['meta'] : [],
            'search_failed' => false
        ];

        // If the search field is enabled and the status is false, set the search failed flag to true.
        // This is used to display a frontend error message.
        if ($request->get('searchField') != '' && !$list->getStatus()) {
            $result['search_failed'] = true;
        }

        return response()->json($result);
    }

    /**
     * @param SubscriberRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(SubscriberRequest $request): \Illuminate\Http\JsonResponse
    {
        //Create request
        $data = [
            'email' => $request->get('email'),
            'fields' => [
                'name' => $request->get('name'),
                'country' => $request->get('country'),
            ],
            'status' => 'active',
            'subscribed_at' => Carbon::now()->toDateTimeString(),
            'ip_address' => $request->ip()
        ];
        $list = $this->subscriberRepository->create($data);

        $result = [
            'data' => $list->getData(),
            'status' => $list->getStatus(),
            'message' => $list->getMessage()
        ];

        return response()->json($result);
    }

    /**
     * @param SubscriberRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(SubscriberRequest $request, $id): \Illuminate\Http\JsonResponse
    {
        //Update request
        $data = [
            'fields' => [
                'name' => $request->get('name'),
                'country' => $request->get('country'),
            ],
            'status' => 'active',
            'subscribed_at' => Carbon::now()->toDateTimeString(),
            'ip_address' => $request->ip()
        ];
        $list = $this->subscriberRepository->update($id, $data);

        $result = [
            'data' => $list->getData(),
            'status' => $list->getStatus(),
            'message' => $list->getMessage()
        ];
        return response()->json($result);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $list = $this->subscriberRepository->delete($id);

        $result = [
            'data' => $list->getData(),
            'status' => $list->getStatus(),
            'message' => $list->getMessage()
        ];
        return response()->json($result);
    }
}

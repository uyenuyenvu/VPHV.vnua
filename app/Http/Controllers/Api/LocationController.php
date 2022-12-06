<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Location\DeleteLocationRequest;
use App\Http\Requests\Location\StoreLocationRequest;
use App\Http\Requests\Location\UpdateLocationRequest;
use App\Repositories\Location\LocationRepositoryInterface;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LocationController extends Controller
{
    use ResponseTrait;

    public function __construct(private LocationRepositoryInterface $locationRepository)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $data = $request->all();
        $relationships = [];
        $columns = ['*'];
        $paginate = $data['limit'] ?? config('constants.limit_of_paginate', 10);
        $condition = [];

        if (isset($data['q'])) {
            $condition[] = ['name', 'like', '%' . $data['q'] . '%'];
            $orCondition = [
                ['description', 'like', '%' . $data['q'] . '%'],
            ];
            $condition[] = ['name', 'or', $orCondition];
        }
        $location = $this->locationRepository->getListPaginateBy($condition, $relationships, $columns, $paginate);

        return $this->responseSuccess(['location' => $location]);
    }

    public function getALl(Request $request): JsonResponse
    {
        $data = $request->all();
        $condition = [];
        if (isset($data['q'])) {
            $condition[] = ['name', 'like', '%' . $data['q'] . '%'];
        }
        $locations= $this->locationRepository->allBy($condition);
        return $this->responseSuccess(['locations' => $locations]);
    }

    public function store(StoreLocationRequest $request): JsonResponse
    {
        try {
            $data = $request->all();
            $location = $this->locationRepository->create($data);
            return $this->responseSuccess(['location' => $location]);

        } catch (\Exception $exception) {
            Log::error('Error store location', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }

    public function update(UpdateLocationRequest $request, $id): JsonResponse
    {
        try {
            $data = $request->all();
            $location = $this->locationRepository->findById($id);
            $this->locationRepository->createOrUpdate($location);
            $location?->fill($data);
            return $this->responseSuccess();
        } catch (\Exception $exception) {
            Log::error('Error update location', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $this->locationRepository->deleteById($id);

            return $this->responseSuccess();
        } catch (\Exception $exception) {
            Log::error('Error delete location', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }

    public function getAllId(): JsonResponse
    {
        $locations = $this->locationRepository->all()?->pluck('id')?->toArray();
        return $this->responseSuccess([
            'locations' => $locations
        ]);
    }

    public function deleteSelected(DeleteLocationRequest $request): JsonResponse
    {
        try {
            $locationIds = $request->input('id', []);
            $condition[] = ['id', 'in', $locationIds];
            $this->locationRepository->deleteBy($condition);
            return $this->responseSuccess();
        } catch (\Exception $exception) {
            Log::error('Error delete select location', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }
}

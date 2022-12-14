<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Department\DeleteDepartmentRequest;
use App\Http\Requests\Department\StoreDepartmentRequest;
use App\Http\Requests\Department\UpdateDepartmentRequest;
use App\Models\Location;
use App\Models\Schedule;
use App\Models\User;
use App\Repositories\Department\DepartmentRepositoryInterface;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
{
    use ResponseTrait;

    public function index(Request $request): JsonResponse
    {
//        $data = $request->all();
//        $relationships = ['createBy', 'updateBy','leader'];
//        $columns = ['*'];
//        $paginate = $data['limit'] ?? config('constants.limit_of_paginate', 10);
//        $condition = [];
//
//        if (isset($data['q'])) {
//            $condition[] = ['name', 'like', '%' . $data['q'] . '%'];
//            $orCondition = [
//                ['department_code', 'like', '%' . $data['q'] . '%'],
//            ];
//            $condition[] = ['name', 'or', $orCondition];
//        }
//
//        if (isset($data['department_code'])) {
//            $condition[] = ['department_code' => $data['department_code']];
//        }
//
//        $department = $this->departmentRepository->getListPaginateBy($condition, $relationships, $columns, $paginate);
        $schedules = Schedule::all();
        return $this->responseSuccess(['schedules' => $schedules]);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $data = $request->all();
            $leader = User::where('user_name', $data['leader_name'])->first();
            if ($leader){
                $data['leader_id']=$leader->id;
            }else{
                $data['leader_orther_name']=$data['leader_name'];
            }
            $location = Location::where('name', $data['location_name'])->first();
            if ($location){
                $data['location_id']=$location->id;
            }else{
                $data['location_other_name']=$data['location_name'];
            }
            $schedule = Schedule::create(array_merge($data, [
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]));
            return $this->responseSuccess(['schedule' => $schedule]);

        } catch (\Exception $exception) {
            Log::error('Error store schedules', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }

    public function update(UpdateDepartmentRequest $request, $id): JsonResponse
    {
        try {
            $data = $request->all();
            $department = $this->departmentRepository->findById($id);
            $department?->fill(array_merge($data, [
                'updated_by' => auth()->id(),
            ]));
            $this->departmentRepository->createOrUpdate($department);

            return $this->responseSuccess();
        } catch (\Exception $exception) {
            Log::error('Error update department', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $this->departmentRepository->deleteById($id);

            return $this->responseSuccess();
        } catch (\Exception $exception) {
            Log::error('Error delete department', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }
    public function show($id): JsonResponse
    {
        try {
            $schedule = Schedule::find($id);
            if ($schedule){
                return $this->responseSuccess(['schedule' => $schedule]);
            }

        } catch (\Exception $exception) {
            Log::error('Error delete department', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }
}

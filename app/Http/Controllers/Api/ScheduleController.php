<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Department\DeleteDepartmentRequest;
use App\Http\Requests\Department\StoreDepartmentRequest;
use App\Http\Requests\Department\UpdateDepartmentRequest;
use App\Models\Element;
use App\Models\Location;
use App\Models\Schedule;
use App\Models\User;
use App\Repositories\Department\DepartmentRepositoryInterface;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
    public function scheduleByWeek(Request $request): JsonResponse
    {
        $data = $request->all();
        $query = Schedule::whereBetween('start_time',[$data['start_time'], $data['end_time']])
            ->with(['elements']);
        if (isset($data['department_id'])){
            $query->where('department_id', $request->input('department_id'));
        }
        $schedules = $query
            ->orderBy('start_time')
            ->get()
            ->groupBy(function($data) {
                return Carbon::parse($data->start_time)->format('Y-m-d');
            });
        return $this->responseSuccess(['schedules' => $schedules]);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $data = $request->all();
            $leader = User::where('user_name', $data['leader_orther_name'])->first();
            if ($leader){
                $data['leader_id']=$leader->id;
                $data['leader_orther_name']=$leader->user_name;
            }else{
                $data['leader_orther_name']=$data['leader_orther_name'];
            }
            $location = Location::where('name', $data['location_other_name'])->first();
            if ($location){
                $data['location_id']=$location->id;
                $data['location_other_name']=$location->name;
            }else{
                $data['location_other_name']=$data['location_other_name'];
            }
            $schedule = Schedule::create(array_merge($data, [
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]));
            if ($schedule){
                $elements = $request->input('elements');
                foreach ($elements as $element){
                    $user = User::where('user_name', $element)->first();
                    if ($user){
                        $newElement = Element::create([
                            'user_id'=>$user->id,
                            'schedule_id'=> $schedule->id,
                            'name'=>$element
                        ]);
                    }else{
                        $newElement = Element::create([
                            'schedule_id'=> $schedule->id,
                            'name'=>$element
                        ]);
                    }

            }
            }
            return $this->responseSuccess(['schedule' => $schedule]);

        } catch (\Exception $exception) {
            Log::error('Error store schedules', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }

    public function acceptSchedule($id){
        try {
            $schedule = Schedule::find($id);
            if ($schedule){
                $schedule->status = 1;
                $schedule->save();
                return $this->responseSuccess();
            }else{
                return $this->responseError();

            }

        } catch (\Exception $exception) {
            Log::error('Error accept', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }

    public function checkUserInSchedule(Request $request){
        $user = User::where('user_name', $request->input('name'))->first();
        if ($user){
            $schedule = DB::table('element_schedules')
                            ->join('schedules', 'element_schedules.schedule_id','=','schedules.id')
                            ->where('element_schedules.user_id', $user->id)
                            ->orWhereBetween('schedules.start_time', [$request->input('start_time'), $request->input('end_time')])
                            ->orWhereBetween('schedules.start_time', [$request->input('start_time'), $request->input('end_time')])
                            ->get();
            if (count($schedule) > 0){
                return $this->responseSuccess(['schedule' => false]);
            }
            return $this->responseSuccess(['schedule' => true]);
        }else{
            return $this->responseSuccess(['schedule' => true]);
        }
    }

    public function cancelSchedule($id){
        try {
            $schedule = Schedule::find($id);

            if ($schedule){
                $schedule->close_by = auth()->id();
                $schedule->save();
                return $this->responseSuccess();
            }else{
                return $this->responseError();

            }

        } catch (\Exception $exception) {
            Log::error('Error accept', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $data = $request->all();
            $schedule = Schedule::find($id);
            $schedule?->fill(array_merge($data, [
                'updated_by' => auth()->id(),
            ]));
            $schedule->update($data);
            if ($schedule){
                $leader = User::where('user_name', $data['leader_orther_name'])->first();
                if ($leader){
                    $data['leader_id']=$leader->id;
                    $data['leader_orther_name']=$leader->user_name;
                }else{
                    $data['leader_orther_name']=$data['leader_orther_name'];
                }
                $location = Location::where('name', $data['location_other_name'])->first();
                if ($location){
                    $data['location_id']=$location->id;
                    $data['location_other_name']=$location->name;
                }else{
                    $data['location_other_name']=$data['location_other_name'];
                }
                $elements = Element::where('schedule_id', $schedule->id)->get();
                foreach ($elements as $element){
                    $i = Element::find($element->id);
                    $i->delete();
                }

                $elements = $request->input('elements');
                foreach ($elements as $element){
                    $user = User::where('user_name', $element)->first();
                    if ($user){
                        $newElement = Element::create([
                            'user_id'=>$user->id,
                            'schedule_id'=> $schedule->id,
                            'name'=>$element
                        ]);
                    }else{
                        $newElement = Element::create([
                            'schedule_id'=> $schedule->id,
                            'name'=>$element
                        ]);
                    }

                }
            }
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
                $elements = Element::where('schedule_id', $id)->get()->pluck('name')->toArray();
                return $this->responseSuccess(['schedule' => $schedule, 'elements'=>$elements]);
            }
            return $this->responseError();

        } catch (\Exception $exception) {
            Log::error('Error delete department', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }
}

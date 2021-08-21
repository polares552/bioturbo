<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SolarBoardRequest;
use App\Http\Resources\SolarBoardResource;
use App\Services\SolarBoardService;
use Illuminate\Support\Str;

class SolarBoardController extends Controller
{
    private $solarboard_service;

    public function __construct(SolarBoardService $solarboard_service)
    {
        $this->solarboard_service = $solarboard_service;
    }

    public function list()
    {
        return SolarBoardResource::collection($this->solarboard_service->list());
    }

    public function find(string $code)
    {
        if (!Str::isUuid($code)) {
            return response()->json(['status' => false, 'message' => 'Invalid code!'], 404);
        }

        if (!$solarboard = $this->solarboard_service->find($code)) {
            return response()->json(['status' => false, 'message' => 'Solar board not found!'], 404);
        }
        return new SolarBoardResource($solarboard);
    }

    public function store(SolarBoardRequest $request)
    {
        if ($this->solarboard_service->store($request->all())) {
            return response()->json(['status' => true, 'message' => 'Registered successfully!'], 200);
        }
        return response()->json(['status' => false, 'message' => 'Ops!, Failed to register.'], 500);
    }

    public function update(SolarBoardRequest $request, string $code)
    {
        if (!Str::isUuid($code)) {
            return response()->json(['status' => false, 'message' => 'Invalid code!'], 404);
        }

        if ($this->solarboard_service->update($request->all(), $code)) {
            return response()->json(['status' => true, 'message' => 'Registration updated successfully!'], 200);
        }
        return response()->json(['status' => false, 'message' => 'Ops!, Failed to updater.'], 500);
    }

    public function delete(string $code)
    {
        if (!Str::isUuid($code)) {
            return response()->json(['status' => false, 'message' => 'Invalid code!'], 404);
        }
        if ($this->solarboard_service->delete($code)) {
            return response()->json(['status' => true, 'message' => 'Record successfully deleted!'], 200);
        }
        return response()->json(['status' => false, 'message' => 'Ops!, Failed to delete!'], 500);
    }
}

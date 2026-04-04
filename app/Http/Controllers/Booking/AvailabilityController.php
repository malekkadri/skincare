<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\AvailableSlotsRequest;
use App\Models\Service;
use App\Services\AvailabilityService;
use Illuminate\Http\JsonResponse;

class AvailabilityController extends Controller
{
    public function __invoke(AvailableSlotsRequest $request, AvailabilityService $availabilityService): JsonResponse
    {
        $service = Service::query()->active()->findOrFail($request->integer('service_id'));

        return response()->json([
            'service_id' => $service->id,
            'date' => $request->string('date')->toString(),
            'slots' => $availabilityService->getAvailableSlots($service, $request->string('date')->toString()),
        ]);
    }
}

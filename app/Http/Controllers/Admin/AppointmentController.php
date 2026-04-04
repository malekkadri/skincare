<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAppointmentRequest;
use App\Http\Requests\Admin\UpdateAppointmentRequest;
use App\Http\Requests\Admin\UpdateAppointmentStatusRequest;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Service;
use App\Services\AvailabilityService;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function __construct(protected AvailabilityService $availabilityService, protected WhatsAppService $whatsAppService)
    {
    }

    public function index(Request $request): View
    {
        $appointments = Appointment::query()
            ->with(['customer', 'service'])
            ->when($request->filled('date'), fn ($q) => $q->whereDate('appointment_date', $request->string('date')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('service_id'), fn ($q) => $q->where('service_id', $request->integer('service_id')))
            ->orderByDesc('appointment_date')
            ->orderBy('start_time')
            ->paginate(20)
            ->withQueryString();

        return view('admin.appointments.index', [
            'appointments' => $appointments,
            'services' => Service::query()->ordered()->get(),
            'statuses' => Appointment::statuses(),
        ]);
    }

    public function show(Appointment $appointment): View
    {
        $appointment->load(['customer', 'service', 'whatsappLogs']);

        return view('admin.appointments.show', [
            'appointment' => $appointment,
            'statuses' => Appointment::statuses(),
        ]);
    }

    public function create(): View
    {
        return view('admin.appointments.create', $this->formData(new Appointment()));
    }

    public function store(StoreAppointmentRequest $request): RedirectResponse
    {
        $this->persist($request->validated(), null);

        return redirect()->route('admin.appointments.index')->with('success', 'Appointment created successfully.');
    }

    public function edit(Appointment $appointment): View
    {
        return view('admin.appointments.edit', $this->formData($appointment));
    }

    public function update(UpdateAppointmentRequest $request, Appointment $appointment): RedirectResponse
    {
        $this->persist($request->validated(), $appointment);

        return redirect()->route('admin.appointments.show', $appointment)->with('success', 'Appointment updated successfully.');
    }

    public function updateStatus(UpdateAppointmentStatusRequest $request, Appointment $appointment): RedirectResponse
    {
        $oldStatus = $appointment->status;
        $status = $request->validated('status');

        $appointment->status = $status;
        $appointment->cancelled_at = $status === Appointment::STATUS_CANCELLED ? now() : null;
        $appointment->save();

        if ($status === Appointment::STATUS_CANCELLED && $oldStatus !== Appointment::STATUS_CANCELLED) {
            $this->whatsAppService->sendBookingCancellation($appointment->load('customer'));
        }

        if (in_array($status, [Appointment::STATUS_PENDING, Appointment::STATUS_CONFIRMED], true)
            && $oldStatus !== $status
            && $oldStatus !== Appointment::STATUS_PENDING) {
            $this->whatsAppService->sendBookingRescheduled($appointment->load('customer'));
        }

        return back()->with('success', 'Appointment status updated.');
    }

    public function resendConfirmation(Appointment $appointment): RedirectResponse
    {
        $this->whatsAppService->sendBookingConfirmation($appointment->load('customer'));

        return back()->with('success', 'Confirmation message send attempt logged.');
    }

    protected function formData(Appointment $appointment): array
    {
        return [
            'appointment' => $appointment,
            'services' => Service::query()->active()->ordered()->get(),
            'customers' => Customer::query()->orderBy('first_name')->orderBy('last_name')->limit(200)->get(),
            'statuses' => Appointment::statuses(),
        ];
    }

    protected function persist(array $validated, ?Appointment $appointment = null): void
    {
        DB::transaction(function () use ($validated, $appointment): void {
            $service = Service::query()->active()->findOrFail($validated['service_id']);
            $customer = ! empty($validated['customer_id'])
                ? Customer::query()->findOrFail($validated['customer_id'])
                : Customer::query()->create([
                    'first_name' => $validated['customer']['first_name'],
                    'last_name' => $validated['customer']['last_name'] ?? null,
                    'phone' => $validated['customer']['phone'],
                    'email' => $validated['customer']['email'] ?? null,
                    'preferred_language' => $validated['customer']['preferred_language'] ?? 'fr',
                    'preferred_currency' => $validated['customer']['preferred_currency'] ?? 'TND',
                    'notes' => $validated['customer']['notes'] ?? null,
                ]);

            $start = Carbon::parse($validated['appointment_date'].' '.$validated['start_time'], 'Africa/Tunis');
            $end = $start->copy()->addMinutes((int) $service->duration_minutes + (int) $service->buffer_minutes);

            if ($this->availabilityService->hasOverlap(
                $validated['appointment_date'],
                $start->format('H:i:s'),
                $end->format('H:i:s'),
                $appointment?->id,
            )) {
                abort(422, 'Selected slot is not available.');
            }

            $currency = $validated['booked_currency'];
            $price = $currency === 'EUR' ? $service->price_eur : $service->price_tnd;

            $payload = [
                'customer_id' => $customer->id,
                'service_id' => $service->id,
                'appointment_date' => $validated['appointment_date'],
                'start_time' => $start->format('H:i:s'),
                'end_time' => $end->format('H:i:s'),
                'status' => $validated['status'],
                'booked_currency' => $currency,
                'preferred_language' => $customer->preferred_language ?? 'fr',
                'booked_price' => $price,
                'service_name_snapshot_fr' => $service->name_fr,
                'service_name_snapshot_en' => $service->name_en,
                'service_price_tnd_snapshot' => $service->price_tnd,
                'service_price_eur_snapshot' => $service->price_eur,
                'notes' => $validated['notes'] ?? null,
                'admin_notes' => $validated['admin_notes'] ?? null,
                'cancelled_at' => $validated['status'] === Appointment::STATUS_CANCELLED ? now() : null,
            ];

            $existing = $appointment?->replicate();
            if ($appointment) {
                $appointment->update($payload);
                $record = $appointment->fresh();
            } else {
                $record = Appointment::query()->create($payload);
            }

            if ($existing && ($existing->appointment_date->toDateString() !== $record->appointment_date->toDateString() || $existing->start_time !== $record->start_time)) {
                $this->whatsAppService->sendBookingRescheduled($record->load('customer'));
            }
        });
    }
}

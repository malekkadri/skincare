<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\CustomerStepRequest;
use App\Http\Requests\Booking\FinalizeBookingRequest;
use App\Http\Requests\Booking\SelectDateStepRequest;
use App\Http\Requests\Booking\SelectServiceStepRequest;
use App\Http\Requests\Booking\SelectSlotStepRequest;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Setting;
use App\Services\AvailabilityService;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BookingWizardController extends Controller
{
    public function __construct(protected AvailabilityService $availabilityService, protected WhatsAppService $whatsAppService)
    {
    }

    public function service(Request $request): View
    {
        if ($request->filled('service')) {
            $service = Service::query()->active()->where('slug', $request->string('service')->toString())->first();

            if ($service) {
                session(['booking_wizard.service_id' => $service->id]);
            }
        }

        return view('booking.service', [
            'services' => Service::query()->active()->ordered()->get(),
            'wizard' => $this->wizardData(),
        ]);
    }

    public function saveService(SelectServiceStepRequest $request): RedirectResponse
    {
        $service = Service::query()->active()->findOrFail($request->integer('service_id'));
        session(['booking_wizard.service_id' => $service->id]);

        return redirect()->route('booking.date');
    }

    public function date(): View|RedirectResponse
    {
        if (! session('booking_wizard.service_id')) {
            return redirect()->route('booking.service');
        }

        $wizard = $this->wizardData();
        if (! $wizard['service']) {
            session()->forget('booking_wizard.service_id');

            return redirect()->route('booking.service')->with('error', __('booking.service_unavailable'));
        }

        return view('booking.date', compact('wizard'));
    }

    public function saveDate(SelectDateStepRequest $request): RedirectResponse
    {
        session(['booking_wizard.appointment_date' => $request->validated('appointment_date')]);

        return redirect()->route('booking.slot');
    }

    public function slot(): View|RedirectResponse
    {
        if (! session('booking_wizard.service_id')) {
            return redirect()->route('booking.service');
        }
        if (! session('booking_wizard.appointment_date')) {
            return redirect()->route('booking.date');
        }

        $wizard = $this->wizardData();
        if (! $wizard['service']) {
            session()->forget('booking_wizard.service_id');

            return redirect()->route('booking.service')->with('error', __('booking.service_unavailable'));
        }

        $slots = $this->availabilityService->getAvailableSlots($wizard['service'], $wizard['appointment_date']);

        return view('booking.slot', compact('wizard', 'slots'));
    }

    public function saveSlot(SelectSlotStepRequest $request): RedirectResponse
    {
        if (! session('booking_wizard.service_id')) {
            return redirect()->route('booking.service');
        }
        if (! session('booking_wizard.appointment_date')) {
            return redirect()->route('booking.date');
        }

        $wizard = $this->wizardData();
        if (! $wizard['service']) {
            session()->forget('booking_wizard.service_id');

            return redirect()->route('booking.service')->with('error', __('booking.service_unavailable'));
        }

        $slots = $this->availabilityService->getAvailableSlots($wizard['service'], $wizard['appointment_date']);

        abort_unless(in_array($request->validated('start_time'), $slots, true), 422, __('booking.slot_unavailable'));

        session(['booking_wizard.start_time' => $request->validated('start_time')]);

        return redirect()->route('booking.customer');
    }

    public function customer(): View|RedirectResponse
    {
        if (! session('booking_wizard.start_time')) {
            return redirect()->route('booking.slot');
        }

        $wizard = $this->wizardData();
        if (! $wizard['service']) {
            session()->forget('booking_wizard.service_id');

            return redirect()->route('booking.service')->with('error', __('booking.service_unavailable'));
        }

        return view('booking.customer', compact('wizard'));
    }

    public function saveCustomer(CustomerStepRequest $request): RedirectResponse
    {
        session(['booking_wizard.customer' => $request->validated()]);

        return redirect()->route('booking.review');
    }

    public function review(): View|RedirectResponse
    {
        if (! session('booking_wizard.customer')) {
            return redirect()->route('booking.customer');
        }

        $wizard = $this->wizardData();
        if (! $wizard['service']) {
            session()->forget('booking_wizard.service_id');

            return redirect()->route('booking.service')->with('error', __('booking.service_unavailable'));
        }

        return view('booking.review', compact('wizard'));
    }

    public function confirm(FinalizeBookingRequest $request): RedirectResponse
    {
        if (! session('booking_wizard.customer')) {
            return redirect()->route('booking.customer');
        }

        $wizard = $this->wizardData();
        if (! $wizard['service']) {
            session()->forget('booking_wizard.service_id');

            return redirect()->route('booking.service')->with('error', __('booking.service_unavailable'));
        }

        $customerData = $wizard['customer'];

        $appointment = DB::transaction(function () use ($wizard, $customerData): Appointment {
            $service = Service::query()->active()->findOrFail($wizard['service']->id);
            $start = Carbon::parse($wizard['appointment_date'].' '.$wizard['start_time'], 'Africa/Tunis');
            $end = $start->copy()->addMinutes((int) $service->duration_minutes + (int) $service->buffer_minutes);

            if ($this->availabilityService->hasOverlap($wizard['appointment_date'], $start->format('H:i:s'), $end->format('H:i:s'))) {
                abort(422, __('booking.slot_unavailable'));
            }

            $customer = Customer::query()->firstOrCreate(
                ['phone' => $customerData['phone']],
                [
                    'first_name' => $customerData['first_name'],
                    'last_name' => $customerData['last_name'] ?? null,
                    'email' => $customerData['email'] ?? null,
                    'preferred_language' => $customerData['preferred_language'],
                    'preferred_currency' => session('currency', 'TND'),
                    'notes' => $customerData['notes'] ?? null,
                ]
            );

            $customer->fill([
                'first_name' => $customerData['first_name'],
                'last_name' => $customerData['last_name'] ?? null,
                'email' => $customerData['email'] ?? null,
                'preferred_language' => $customerData['preferred_language'],
                'preferred_currency' => session('currency', 'TND'),
            ])->save();

            $currency = session('currency', 'TND');
            $price = $currency === 'EUR' ? $service->price_eur : $service->price_tnd;

            return Appointment::query()->create([
                'customer_id' => $customer->id,
                'service_id' => $service->id,
                'appointment_date' => $wizard['appointment_date'],
                'start_time' => $start->format('H:i:s'),
                'end_time' => $end->format('H:i:s'),
                'status' => Appointment::STATUS_PENDING,
                'booked_currency' => $currency,
                'preferred_language' => $customerData['preferred_language'],
                'booked_price' => $price,
                'service_name_snapshot_fr' => $service->name_fr,
                'service_name_snapshot_en' => $service->name_en,
                'service_price_tnd_snapshot' => $service->price_tnd,
                'service_price_eur_snapshot' => $service->price_eur,
                'notes' => $customerData['notes'] ?? null,
            ]);
        });

        $this->whatsAppService->sendBookingConfirmation($appointment->load('customer'));

        $token = Str::uuid()->toString();
        session(['booking_success' => ['token' => $token, 'appointment_id' => $appointment->id]]);
        session()->forget('booking_wizard');

        return redirect()->route('booking.success', ['token' => $token]);
    }

    public function success(string $token): View|RedirectResponse
    {
        $success = session('booking_success');
        if (! $success || $success['token'] !== $token) {
            return redirect()->route('booking.service');
        }

        $appointment = Appointment::query()->with(['customer', 'service'])->findOrFail($success['appointment_id']);

        return view('booking.success', ['appointment' => $appointment]);
    }

    protected function wizardData(): array
    {
        $serviceId = session('booking_wizard.service_id');
        $date = session('booking_wizard.appointment_date');
        $slot = session('booking_wizard.start_time');
        $customer = session('booking_wizard.customer');


        $service = $serviceId ? Service::query()->active()->find($serviceId) : null;

        return [
            'service' => $service,
            'appointment_date' => $date,
            'start_time' => $slot,
            'customer' => $customer,
            'currency' => session('currency', 'TND'),
            'locale' => app()->getLocale(),
            'settings' => Setting::current(),
        ];
    }
}

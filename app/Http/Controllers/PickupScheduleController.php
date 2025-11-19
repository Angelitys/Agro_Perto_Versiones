<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PickupSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PickupScheduleController extends Controller
{
    /**
     * Display pickup schedule for producers
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->type !== 'producer') {
            abort(403, 'Acesso negado. Apenas produtores podem acessar esta página.');
        }

        // Buscar pedidos que precisam ser retirados
        $pickupSchedules = PickupSchedule::whereHas('order.items.product', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with(['order.user', 'order.items.product'])
        ->orderBy('pickup_date', 'asc')
        ->orderBy('pickup_time', 'asc')
        ->paginate(15);

        return view('pickup-schedule.index', compact('pickupSchedules'));
    }

    /**
     * Show pickup schedule for a specific date
     */
    public function byDate(Request $request)
    {
        $user = Auth::user();
        $date = $request->get('date', now()->format('Y-m-d'));
        
        if ($user->type !== 'producer') {
            abort(403, 'Acesso negado. Apenas produtores podem acessar esta página.');
        }

        $pickupSchedules = PickupSchedule::whereHas('order.items.product', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->whereDate('pickup_date', $date)
        ->with(['order.user', 'order.items.product'])
        ->orderBy('pickup_time', 'asc')
        ->get();

        return view('pickup-schedule.by-date', compact('pickupSchedules', 'date'));
    }

    /**
     * Create pickup schedule for an order
     */
    public function create(Order $order)
    {
        $user = Auth::user();
        
        // Verificar se o usuário pode criar agenda para este pedido
        if ($user->type === 'producer') {
            // Produtor pode criar agenda para pedidos que contenham seus produtos
            $hasUserProducts = $order->items()->whereHas('product', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->exists();
            
            if (!$hasUserProducts) {
                abort(403, 'Você não pode criar agenda para este pedido.');
            }
        } elseif ($user->type === 'consumer') {
            // Consumidor pode criar agenda apenas para seus próprios pedidos
            if ($order->user_id !== $user->id) {
                abort(403, 'Você não pode criar agenda para este pedido.');
            }
        } else {
            abort(403, 'Acesso negado.');
        }

        return view('pickup-schedule.create', compact('order'));
    }

    /**
     * Store pickup schedule
     */
    public function store(Request $request, Order $order)
    {
        $request->validate([
            'pickup_date' => 'required|date|after_or_equal:today',
            'pickup_time' => 'required|date_format:H:i',
            'pickup_location' => 'required|string|max:255',
            'pickup_instructions' => 'nullable|string|max:1000',
            'contact_phone' => 'required|string|max:20',
        ]);

        $user = Auth::user();
        
        // Verificar permissões novamente
        if ($user->type === 'producer') {
            $hasUserProducts = $order->items()->whereHas('product', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->exists();
            
            if (!$hasUserProducts) {
                abort(403, 'Você não pode criar agenda para este pedido.');
            }
        } elseif ($user->type === 'consumer') {
            if ($order->user_id !== $user->id) {
                abort(403, 'Você não pode criar agenda para este pedido.');
            }
        }

        // Verificar se já existe agenda para este pedido
        if ($order->pickupSchedule) {
            return back()->with('error', 'Este pedido já possui uma agenda de retirada.');
        }

        $pickupSchedule = PickupSchedule::create([
            'order_id' => $order->id,
            'pickup_date' => $request->pickup_date,
            'pickup_time' => $request->pickup_time,
            'pickup_location' => $request->pickup_location,
            'pickup_instructions' => $request->pickup_instructions,
            'contact_phone' => $request->contact_phone,
            'status' => 'scheduled',
            'created_by' => $user->id,
        ]);

        // Atualizar status do pedido
        $order->update(['status' => 'ready_for_pickup']);

        return redirect()->route('pickup-schedule.show', $pickupSchedule)
            ->with('success', 'Agenda de retirada criada com sucesso!');
    }

    /**
     * Show pickup schedule details
     */
    public function show(PickupSchedule $pickupSchedule)
    {
        $user = Auth::user();
        
        // Verificar permissões
        if ($user->type === 'producer') {
            $hasUserProducts = $pickupSchedule->order->items()->whereHas('product', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->exists();
            
            if (!$hasUserProducts) {
                abort(403, 'Você não pode ver esta agenda.');
            }
        } elseif ($user->type === 'consumer') {
            if ($pickupSchedule->order->user_id !== $user->id) {
                abort(403, 'Você não pode ver esta agenda.');
            }
        }

        $pickupSchedule->load(['order.user', 'order.items.product']);

        return view('pickup-schedule.show', compact('pickupSchedule'));
    }

    /**
     * Update pickup schedule status
     */
    public function updateStatus(Request $request, PickupSchedule $pickupSchedule)
    {
        $request->validate([
            'status' => 'required|in:scheduled,confirmed,completed,cancelled',
            'notes' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        
        // Verificar permissões
        if ($user->type === 'producer') {
            $hasUserProducts = $pickupSchedule->order->items()->whereHas('product', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->exists();
            
            if (!$hasUserProducts) {
                abort(403, 'Você não pode atualizar esta agenda.');
            }
        } elseif ($user->type === 'consumer') {
            if ($pickupSchedule->order->user_id !== $user->id) {
                abort(403, 'Você não pode atualizar esta agenda.');
            }
        }

        $pickupSchedule->update([
            'status' => $request->status,
            'notes' => $request->notes,
            'updated_by' => $user->id,
        ]);

        // Atualizar status do pedido baseado no status da agenda
        $orderStatus = match($request->status) {
            'confirmed' => 'ready_for_pickup',
            'completed' => 'delivered',
            'cancelled' => 'cancelled',
            default => $pickupSchedule->order->status,
        };

        $pickupSchedule->order->update(['status' => $orderStatus]);

        $statusMessages = [
            'scheduled' => 'Agenda marcada',
            'confirmed' => 'Agenda confirmada',
            'completed' => 'Retirada concluída',
            'cancelled' => 'Agenda cancelada',
        ];

        return back()->with('success', $statusMessages[$request->status] . ' com sucesso!');
    }

    /**
     * Get available pickup times for a date
     */
    public function getAvailableTimes(Request $request)
    {
        $date = $request->get('date');
        $producerId = $request->get('producer_id');

        if (!$date || !$producerId) {
            return response()->json(['error' => 'Data e produtor são obrigatórios'], 400);
        }

        // Buscar horários já ocupados
        $occupiedTimes = PickupSchedule::whereHas('order.items.product', function($query) use ($producerId) {
            $query->where('user_id', $producerId);
        })
        ->whereDate('pickup_date', $date)
        ->whereIn('status', ['scheduled', 'confirmed'])
        ->pluck('pickup_time')
        ->toArray();

        // Horários disponíveis (8h às 18h, de hora em hora)
        $availableTimes = [];
        for ($hour = 8; $hour <= 18; $hour++) {
            $time = sprintf('%02d:00', $hour);
            if (!in_array($time, $occupiedTimes)) {
                $availableTimes[] = [
                    'value' => $time,
                    'label' => $time,
                ];
            }
        }

        return response()->json($availableTimes);
    }

    /**
     * Get pickup schedule calendar data
     */
    public function getCalendarData(Request $request)
    {
        $user = Auth::user();
        $start = $request->get('start');
        $end = $request->get('end');

        if ($user->type !== 'producer') {
            return response()->json(['error' => 'Acesso negado'], 403);
        }

        $pickupSchedules = PickupSchedule::whereHas('order.items.product', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->whereBetween('pickup_date', [$start, $end])
        ->with(['order.user'])
        ->get();

        $events = $pickupSchedules->map(function($schedule) {
            $statusColors = [
                'scheduled' => '#fbbf24', // yellow
                'confirmed' => '#10b981', // green
                'completed' => '#6b7280', // gray
                'cancelled' => '#ef4444', // red
            ];

            return [
                'id' => $schedule->id,
                'title' => $schedule->order->user->name,
                'start' => $schedule->pickup_date . 'T' . $schedule->pickup_time,
                'backgroundColor' => $statusColors[$schedule->status] ?? '#6b7280',
                'borderColor' => $statusColors[$schedule->status] ?? '#6b7280',
                'extendedProps' => [
                    'status' => $schedule->status,
                    'location' => $schedule->pickup_location,
                    'phone' => $schedule->contact_phone,
                ],
            ];
        });

        return response()->json($events);
    }
}

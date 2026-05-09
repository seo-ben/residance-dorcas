<?php

namespace App\Http\Controllers;

use App\Models\Chambre;
use App\Models\Reservation;
use App\Models\PeriodeIndisponibilite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoomManagementController extends Controller
{
    public function index(Request $request)
    {
        $dateDebut = $request->get('date_debut', Carbon::today()->format('Y-m-d'));
        $dateFin = $request->get('date_fin', Carbon::today()->addDays(30)->format('Y-m-d'));
        $statut = $request->get('statut', 'all');

        $query = Chambre::with([
            'typeChambre',
            'propriete',
            'detailsReservations.reservation' => function ($q) use ($dateDebut, $dateFin) {
                $q->whereBetween('date_arrivee', [$dateDebut, $dateFin])
                    ->orWhereBetween('date_depart', [$dateDebut, $dateFin])
                    ->orWhere(function ($q) use ($dateDebut, $dateFin) {
                        $q->where('date_arrivee', '<=', $dateDebut)
                            ->where('date_depart', '>=', $dateFin);
                    });
            },
            'periodesIndisponibilite' => function ($q) use ($dateDebut, $dateFin) {
                $q->whereBetween('date_debut', [$dateDebut, $dateFin])
                    ->orWhereBetween('date_fin', [$dateDebut, $dateFin])
                    ->orWhere(function ($q) use ($dateDebut, $dateFin) {
                        $q->where('date_debut', '<=', $dateDebut)
                            ->where('date_fin', '>=', $dateFin);
                    });
            }
        ]);

        if ($statut !== 'all') {
            $query->where('statut', $statut);
        }

        $appartement = $query->get();

        $statistiques = [
            'total_appartement' => Chambre::count(),
            'appartement_occupees' => DB::table('details_reservation')
                ->join('reservations', 'details_reservation.id_reservation', '=', 'reservations.id')
                ->where('reservations.statut', '!=', 'annulee')
                ->where('reservations.date_arrivee', '<=', Carbon::today())
                ->where('reservations.date_depart', '>=', Carbon::today())
                ->whereNotNull('details_reservation.id_chambre')
                ->distinct('details_reservation.id_chambre')
                ->count('details_reservation.id_chambre'),
            'appartement_en_maintenance' => PeriodeIndisponibilite::where('date_debut', '<=', Carbon::today())
                ->where('date_fin', '>=', Carbon::today())
                ->join('appartement', 'periodes_indisponibilite.id_chambre', '=', 'appartement.id')
                ->where('appartement.statut', 'maintenance')
                ->distinct('periodes_indisponibilite.id_chambre')
                ->count('periodes_indisponibilite.id_chambre'),
            'appartement_disponibles' => Chambre::where('statut', 'disponible')
                ->whereDoesntHave('detailsReservations', function ($q) {
                    $q->whereHas('reservation', function ($q2) {
                        $q2->where('statut', '!=', 'annulee')
                            ->where('date_arrivee', '<=', Carbon::today())
                            ->where('date_depart', '>=', Carbon::today());
                    });
                })
                ->whereDoesntHave('periodesIndisponibilite', function ($q) {
                    $q->where('date_debut', '<=', Carbon::today())
                        ->where('date_fin', '>=', Carbon::today());
                })
                ->count()
        ];

        return view('admin.rooms.index', compact('appartement', 'statistiques', 'dateDebut', 'dateFin', 'statut'));
    }

    public function show($id)
    {
        $chambre = Chambre::with([
            'typeChambre',
            'propriete',
            'detailsReservations.reservation.client.user',
            'detailsReservations.reservation.paiements',
            'periodesIndisponibilite'
        ])->findOrFail($id);

        $reservations = $chambre->detailsReservations()
            ->whereHas('reservation', function ($q) {
                $q->where('statut', '!=', 'annulee');
            })
            ->with(['reservation'])
            ->orderBy('created_at', 'asc')
            ->get()
            ->pluck('reservation')
            ->unique('id');

        $periodesIndisponibilite = $chambre->periodesIndisponibilite()
            ->orderBy('date_debut', 'asc')
            ->get();

        // Calculer les dates disponibles pour les 30 prochains jours
        $dateDebut = Carbon::today();
        $dateFin = Carbon::today()->addDays(30);
        $datesDisponibles = $this->getAvailableDates($chambre, $dateDebut, $dateFin);

        return view('admin.rooms.show', compact('chambre', 'reservations', 'periodesIndisponibilite', 'datesDisponibles', 'dateDebut', 'dateFin'));
    }

    public function createMaintenance(Request $request, $id)
    {
        $chambre = Chambre::findOrFail($id);

        $request->validate([
            'date_debut' => 'required|date|after_or_equal:today',
            'date_fin' => 'required|date|after:date_debut',
            'raison' => 'required|string|max:255'
        ]);

        // Vérifier les conflits avec les réservations existantes
        $hasConflict = $chambre->detailsReservations()
            ->whereHas('reservation', function ($q) use ($request) {
                $q->where('statut', '!=', 'annulee')
                    ->where(function ($q2) use ($request) {
                        $q2->whereBetween('date_arrivee', [$request->date_debut, $request->date_fin])
                            ->orWhereBetween('date_depart', [$request->date_debut, $request->date_fin])
                            ->orWhere(function ($q3) use ($request) {
                                $q3->where('date_arrivee', '<=', $request->date_debut)
                                    ->where('date_depart', '>=', $request->date_fin);
                            });
                    });
            })->exists();

        if ($hasConflict) {
            return back()->with('error', 'Impossible d\'ajouter la période de maintenance : des réservations existent pour ces dates.');
        }

        DB::beginTransaction();
        try {
            $periode = new PeriodeIndisponibilite([
                'id_chambre' => $chambre->id,
                'date_debut' => $request->date_debut,
                'date_fin' => $request->date_fin,
                'raison' => $request->raison,
                'cree_par' => Auth::id()
            ]);
            $periode->save();

            if (
                Carbon::parse($request->date_debut) <= Carbon::today() &&
                Carbon::parse($request->date_fin) >= Carbon::today()
            ) {
                $chambre->statut = 'maintenance';
                $chambre->save();
            }

            DB::commit();
            Log::info('Période de maintenance créée', [
                'chambre_id' => $chambre->id,
                'periode_id' => $periode->id,
                'user_id' => Auth::id()
            ]);

            return redirect()->route('admin.rooms.show', $chambre->id)
                ->with('success', 'Période de maintenance ajoutée avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Erreur lors de la création de la période de maintenance', [
                'chambre_id' => $chambre->id,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Erreur lors de l\'ajout de la période de maintenance.');
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $chambre = Chambre::findOrFail($id);

        $request->validate([
            'statut' => 'required|in:disponible,maintenance,indisponible'
        ]);

        // Vérifier si le changement en 'disponible' est valide
        if ($request->statut === 'disponible') {
            $hasActiveMaintenance = $chambre->periodesIndisponibilite()
                ->where('date_debut', '<=', Carbon::today())
                ->where('date_fin', '>=', Carbon::today())
                ->exists();

            if ($hasActiveMaintenance) {
                return back()->with('error', 'Impossible de passer la chambre en disponible : une période de maintenance est active.');
            }

            $hasActiveReservation = $chambre->detailsReservations()
                ->whereHas('reservation', function ($q) {
                    $q->where('statut', '!=', 'annulee')
                        ->where('date_arrivee', '<=', Carbon::today())
                        ->where('date_depart', '>=', Carbon::today());
                })->exists();

            if ($hasActiveReservation) {
                return back()->with('error', 'Impossible de passer la chambre en disponible : une réservation est active.');
            }
        }

        DB::beginTransaction();
        try {
            $chambre->statut = $request->statut;
            $chambre->save();

            DB::commit();
            Log::info('Statut de la chambre mis à jour', [
                'chambre_id' => $chambre->id,
                'nouveau_statut' => $request->statut,
                'user_id' => Auth::id()
            ]);

            return redirect()->route('admin.rooms.show', $chambre->id)
                ->with('success', 'Statut de la chambre mis à jour avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Erreur lors de la mise à jour du statut de la chambre', [
                'chambre_id' => $chambre->id,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Erreur lors de la mise à jour du statut.');
        }
    }

    public function deleteMaintenance($id, $periodeId)
    {
        $periode = PeriodeIndisponibilite::findOrFail($periodeId);
        $chambre = Chambre::findOrFail($id);

        if ($periode->id_chambre !== $chambre->id) {
            abort(403, 'Période de maintenance non associée à cette chambre.');
        }

        DB::beginTransaction();
        try {
            $periode->delete();

            $hasActiveMaintenance = $chambre->periodesIndisponibilite()
                ->where('date_debut', '<=', Carbon::today())
                ->where('date_fin', '>=', Carbon::today())
                ->exists();

            if (!$hasActiveMaintenance && $chambre->statut === 'maintenance') {
                $chambre->statut = 'disponible';
                $chambre->save();
            }

            DB::commit();
            Log::info('Période de maintenance supprimée', [
                'chambre_id' => $chambre->id,
                'periode_id' => $periodeId,
                'user_id' => Auth::id()
            ]);

            return redirect()->route('admin.rooms.show', $chambre->id)
                ->with('success', 'Période de maintenance supprimée avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Erreur lors de la suppression de la période de maintenance', [
                'chambre_id' => $chambre->id,
                'periode_id' => $periodeId,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Erreur lors de la suppression de la période de maintenance.');
        }
    }

    protected function getAvailableDates(Chambre $chambre, Carbon $startDate, Carbon $endDate)
    {
        $datesDisponibles = [];
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            $isAvailable = !$chambre->detailsReservations()
                ->whereHas('reservation', function ($q) use ($currentDate) {
                    $q->where('statut', '!=', 'annulee')
                        ->where('date_arrivee', '<=', $currentDate)
                        ->where('date_depart', '>=', $currentDate);
                })->exists();

            $isNotInMaintenance = !$chambre->periodesIndisponibilite()
                ->where('date_debut', '<=', $currentDate)
                ->where('date_fin', '>=', $currentDate)
                ->exists();

            if ($isAvailable && $isNotInMaintenance && $chambre->statut === 'disponible') {
                $datesDisponibles[] = $currentDate->format('d/m/Y'); // Format directly in controller
            }

            $currentDate->addDay();
        }

        return $datesDisponibles;
    }
}

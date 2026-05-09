<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $id_utilisateur
 * @property string $fonction
 * @property string $niveau_acces
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Avi> $avis
 * @property-read int|null $avis_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DemandeVisite> $demandesVisite
 * @property-read int|null $demandes_visite_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Paiement> $paiements
 * @property-read int|null $paiements_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ParametreSysteme> $parametresSysteme
 * @property-read int|null $parametres_systeme_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PeriodeIndisponibilite> $periodesIndisponibilite
 * @property-read int|null $periodes_indisponibilite_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Administrateur newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Administrateur newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Administrateur query()
 * @method static \Illuminate\Database\Eloquent\Builder|Administrateur whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Administrateur whereFonction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Administrateur whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Administrateur whereIdUtilisateur($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Administrateur whereNiveauAcces($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Administrateur whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperAdministrateur {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $id_client
 * @property int $id_reservation
 * @property int $id_chambre
 * @property int $note_globale
 * @property int|null $note_proprete
 * @property int|null $note_service
 * @property int|null $note_emplacement
 * @property string|null $commentaire
 * @property string $date_avis
 * @property string $statut
 * @property string|null $reponse_admin
 * @property string|null $date_reponse
 * @property int|null $id_admin_reponse
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Administrateur|null $administrateur
 * @property-read \App\Models\Client $client
 * @property-read \App\Models\Reservation $reservation
 * @method static \Illuminate\Database\Eloquent\Builder|Avi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Avi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Avi query()
 * @method static \Illuminate\Database\Eloquent\Builder|Avi whereCommentaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Avi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Avi whereDateAvis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Avi whereDateReponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Avi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Avi whereIdAdminReponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Avi whereIdChambre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Avi whereIdClient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Avi whereIdReservation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Avi whereNoteEmplacement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Avi whereNoteGlobale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Avi whereNoteProprete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Avi whereNoteService($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Avi whereReponseAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Avi whereStatut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Avi whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperAvi {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $id_propriete
 * @property int $id_type_chambre
 * @property string $numero_chambre
 * @property string|null $etage
 * @property string $prix_base
 * @property string $statut
 * @property string|null $notes
 * @property string|null $date_derniere_maintenance
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Avi> $avis
 * @property-read int|null $avis_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DemandeVisite> $demandesVisite
 * @property-read int|null $demandes_visite_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DetailReservation> $detailsReservations
 * @property-read int|null $details_reservations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Equipement> $equipements
 * @property-read int|null $equipements_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Media> $medias
 * @property-read int|null $medias_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PeriodeIndisponibilite> $periodesIndisponibilite
 * @property-read int|null $periodes_indisponibilite_count
 * @property-read \App\Models\Propriete $propriete
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tarif> $tarifs
 * @property-read int|null $tarifs_count
 * @property-read \App\Models\TypeChambre $typeChambre
 * @method static \Illuminate\Database\Eloquent\Builder|Chambre newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Chambre newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Chambre query()
 * @method static \Illuminate\Database\Eloquent\Builder|Chambre whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chambre whereDateDerniereMaintenance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chambre whereEtage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chambre whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chambre whereIdPropriete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chambre whereIdTypeChambre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chambre whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chambre whereNumeroChambre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chambre wherePrixBase($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chambre whereStatut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chambre whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperChambre {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id_chambre
 * @property int $id_equipement
 * @property int $quantite
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Chambre $chambre
 * @property-read \App\Models\Equipement|null $equipement
 * @method static \Illuminate\Database\Eloquent\Builder|ChambreEquipement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChambreEquipement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChambreEquipement query()
 * @method static \Illuminate\Database\Eloquent\Builder|ChambreEquipement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChambreEquipement whereIdChambre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChambreEquipement whereIdEquipement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChambreEquipement whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChambreEquipement whereQuantite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChambreEquipement whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperChambreEquipement {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $id_utilisateur
 * @property string|null $numero_piece_identite
 * @property string|null $type_piece
 * @property int $points_fidelite
 * @property string|null $preferences
 * @property string|null $notes_admin
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Avi> $avis
 * @property-read int|null $avis_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CommandeService> $commandesServices
 * @property-read int|null $commandes_services_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DemandeVisite> $demandesVisite
 * @property-read int|null $demandes_visite_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Reservation> $reservations
 * @property-read int|null $reservations_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Client newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Client newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Client query()
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereIdUtilisateur($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereNotesAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereNumeroPieceIdentite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client wherePointsFidelite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client wherePreferences($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereTypePiece($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperClient {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $id_reservation
 * @property int $id_client
 * @property string $date_commande
 * @property string $date_service_souhaitee
 * @property string $statut
 * @property string|null $notes_client
 * @property string|null $notes_admin
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Client $client
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DetailCommandeService> $details
 * @property-read int|null $details_count
 * @property-read \App\Models\Reservation $reservation
 * @method static \Illuminate\Database\Eloquent\Builder|CommandeService newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CommandeService newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CommandeService query()
 * @method static \Illuminate\Database\Eloquent\Builder|CommandeService whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommandeService whereDateCommande($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommandeService whereDateServiceSouhaitee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommandeService whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommandeService whereIdClient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommandeService whereIdReservation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommandeService whereNotesAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommandeService whereNotesClient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommandeService whereStatut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommandeService whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperCommandeService {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $id_reservation
 * @property string $reference
 * @property string|null $date_signature
 * @property string $date_debut
 * @property string $date_fin
 * @property string $montant_mensuel
 * @property string $depot_garantie
 * @property string $statut
 * @property string|null $fichier_contrat
 * @property string|null $conditions_speciales
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Reservation $reservation
 * @method static \Illuminate\Database\Eloquent\Builder|Contrat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contrat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contrat query()
 * @method static \Illuminate\Database\Eloquent\Builder|Contrat whereConditionsSpeciales($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contrat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contrat whereDateDebut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contrat whereDateFin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contrat whereDateSignature($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contrat whereDepotGarantie($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contrat whereFichierContrat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contrat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contrat whereIdReservation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contrat whereMontantMensuel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contrat whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contrat whereStatut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contrat whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperContrat {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $id_client
 * @property int $id_chambre
 * @property string $date_demande
 * @property string $date_visite_souhaitee
 * @property string|null $message
 * @property string $statut
 * @property string|null $date_confirmation
 * @property int|null $id_admin_confirmation
 * @property string|null $notes_admin
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $administrateur
 * @property-read \App\Models\Chambre $chambre
 * @property-read \App\Models\Client $client
 * @property-read \App\Models\Reservation|null $reservation
 * @method static \Illuminate\Database\Eloquent\Builder|DemandeVisite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DemandeVisite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DemandeVisite query()
 * @method static \Illuminate\Database\Eloquent\Builder|DemandeVisite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DemandeVisite whereDateConfirmation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DemandeVisite whereDateDemande($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DemandeVisite whereDateVisiteSouhaitee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DemandeVisite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DemandeVisite whereIdAdminConfirmation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DemandeVisite whereIdChambre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DemandeVisite whereIdClient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DemandeVisite whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DemandeVisite whereNotesAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DemandeVisite whereStatut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DemandeVisite whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperDemandeVisite {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $id_commande_service
 * @property int $id_service
 * @property int $quantite
 * @property string $prix_unitaire
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\CommandeService $commandeService
 * @property-read \App\Models\Service $service
 * @method static \Illuminate\Database\Eloquent\Builder|DetailCommandeService newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DetailCommandeService newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DetailCommandeService query()
 * @method static \Illuminate\Database\Eloquent\Builder|DetailCommandeService whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailCommandeService whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailCommandeService whereIdCommandeService($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailCommandeService whereIdService($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailCommandeService whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailCommandeService wherePrixUnitaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailCommandeService whereQuantite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailCommandeService whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperDetailCommandeService {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $id_reservation
 * @property int $id_chambre
 * @property string $prix_unitaire
 * @property int $quantite
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Chambre $chambre
 * @property-read \App\Models\Reservation $reservation
 * @method static \Illuminate\Database\Eloquent\Builder|DetailReservation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DetailReservation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DetailReservation query()
 * @method static \Illuminate\Database\Eloquent\Builder|DetailReservation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailReservation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailReservation whereIdChambre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailReservation whereIdReservation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailReservation whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailReservation wherePrixUnitaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailReservation whereQuantite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailReservation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperDetailReservation {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $nom
 * @property string|null $description
 * @property string|null $icone
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Chambre> $appartement
 * @property-read int|null $appartement_count
 * @method static \Illuminate\Database\Eloquent\Builder|Equipement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Equipement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Equipement query()
 * @method static \Illuminate\Database\Eloquent\Builder|Equipement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipement whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipement whereIcone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipement whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipement whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperEquipement {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int|null $id_utilisateur
 * @property string $action
 * @property string|null $description
 * @property string|null $adresse_ip
 * @property string|null $user_agent
 * @property string $date_action
 * @property string $niveau
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|LogSysteme newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LogSysteme newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LogSysteme query()
 * @method static \Illuminate\Database\Eloquent\Builder|LogSysteme whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogSysteme whereAdresseIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogSysteme whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogSysteme whereDateAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogSysteme whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogSysteme whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogSysteme whereIdUtilisateur($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogSysteme whereNiveau($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogSysteme whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogSysteme whereUserAgent($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperLogSysteme {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $id_reference
 * @property string $type_reference
 * @property string $type_media
 * @property string|null $titre
 * @property string|null $description
 * @property string $chemin_fichier
 * @property int $est_couverture
 * @property int $ordre
 * @property string $date_ajout
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Media newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Media newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Media query()
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereCheminFichier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereDateAjout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereEstCouverture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereIdReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereOrdre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereTitre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereTypeMedia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereTypeReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperMedia {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $team_id
 * @property int $user_id
 * @property string|null $role
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Membership newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Membership newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Membership query()
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperMembership {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $expediteur_id
 * @property int $destinataire_id
 * @property string|null $sujet
 * @property string $contenu
 * @property string $date_envoi
 * @property int $lu
 * @property string|null $date_lecture
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $destinataire
 * @property-read \App\Models\User $expediteur
 * @method static \Illuminate\Database\Eloquent\Builder|Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message query()
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereContenu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereDateEnvoi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereDateLecture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereDestinataireId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereExpediteurId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereLu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereSujet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperMessage {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $id_utilisateur
 * @property string $titre
 * @property string $message
 * @property string $type_notification
 * @property string $date_creation
 * @property int $lue
 * @property string|null $date_lecture
 * @property string|null $lien
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification query()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereDateCreation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereDateLecture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereIdUtilisateur($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereLien($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereLue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereTitre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereTypeNotification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperNotification {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $id_reservation
 * @property string $montant
 * @property string $date_paiement
 * @property string $methode_paiement
 * @property string|null $reference_transaction
 * @property string $statut
 * @property int|null $id_admin_validation
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Administrateur|null $administrateur
 * @property-read \App\Models\Reservation $reservation
 * @method static \Illuminate\Database\Eloquent\Builder|Paiement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Paiement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Paiement query()
 * @method static \Illuminate\Database\Eloquent\Builder|Paiement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paiement whereDatePaiement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paiement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paiement whereIdAdminValidation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paiement whereIdReservation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paiement whereMethodePaiement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paiement whereMontant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paiement whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paiement whereReferenceTransaction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paiement whereStatut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paiement whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperPaiement {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $cle
 * @property string $valeur
 * @property string|null $description
 * @property string $type_parametre
 * @property int $modifiable
 * @property string $date_modification
 * @property int|null $id_admin_modification
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Administrateur|null $administrateur
 * @method static \Illuminate\Database\Eloquent\Builder|ParametreSysteme newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ParametreSysteme newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ParametreSysteme query()
 * @method static \Illuminate\Database\Eloquent\Builder|ParametreSysteme whereCle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParametreSysteme whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParametreSysteme whereDateModification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParametreSysteme whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParametreSysteme whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParametreSysteme whereIdAdminModification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParametreSysteme whereModifiable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParametreSysteme whereTypeParametre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParametreSysteme whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParametreSysteme whereValeur($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperParametreSysteme {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $id_chambre
 * @property string $date_debut
 * @property string $date_fin
 * @property string $raison
 * @property int $id_admin_creation
 * @property string $date_creation
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Administrateur $administrateur
 * @property-read \App\Models\Chambre $chambre
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodeIndisponibilite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodeIndisponibilite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodeIndisponibilite query()
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodeIndisponibilite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodeIndisponibilite whereDateCreation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodeIndisponibilite whereDateDebut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodeIndisponibilite whereDateFin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodeIndisponibilite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodeIndisponibilite whereIdAdminCreation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodeIndisponibilite whereIdChambre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodeIndisponibilite whereRaison($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodeIndisponibilite whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperPeriodeIndisponibilite {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $titre
 * @property string|null $description
 * @property string|null $code_promo
 * @property string $type_reduction
 * @property string $valeur_reduction
 * @property string $date_debut
 * @property string $date_fin
 * @property string|null $conditions
 * @property int|null $limite_utilisation
 * @property int $nb_utilisations
 * @property string $statut
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion query()
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion whereCodePromo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion whereConditions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion whereDateDebut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion whereDateFin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion whereLimiteUtilisation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion whereNbUtilisations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion whereStatut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion whereTitre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion whereTypeReduction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion whereValeurReduction($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperPromotion {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $nom
 * @property string $adresse
 * @property string $ville
 * @property string $pays
 * @property string $code_postal
 * @property string|null $telephone
 * @property string|null $email
 * @property string|null $description
 * @property int|null $etoiles
 * @property string|null $latitude
 * @property string|null $longitude
 * @property string $date_ajout
 * @property string $statut
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Chambre> $appartement
 * @property-read int|null $appartement_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Media> $medias
 * @property-read int|null $medias_count
 * @method static \Illuminate\Database\Eloquent\Builder|Propriete newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Propriete newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Propriete query()
 * @method static \Illuminate\Database\Eloquent\Builder|Propriete whereAdresse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propriete whereCodePostal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propriete whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propriete whereDateAjout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propriete whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propriete whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propriete whereEtoiles($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propriete whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propriete whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propriete whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propriete whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propriete wherePays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propriete whereStatut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propriete whereTelephone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propriete whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propriete whereVille($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperPropriete {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $id_client
 * @property string $reference
 * @property \Illuminate\Support\Carbon $date_creation
 * @property \Illuminate\Support\Carbon $date_arrivee
 * @property \Illuminate\Support\Carbon $date_depart
 * @property string $statut
 * @property string $type_reservation
 * @property string $prix_total
 * @property string $prix_original
 * @property string|null $reduction_montant
 * @property string|null $reduction_pourcentage
 * @property string $acompte_paye
 * @property string|null $code_promo
 * @property string $reduction_appliquee
 * @property string|null $notes_client
 * @property string|null $notes_admin
 * @property int|null $id_demande_visite
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Avi> $avis
 * @property-read int|null $avis_count
 * @property-read \App\Models\Client $client
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CommandeService> $commandesServices
 * @property-read int|null $commandes_services_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Contrat> $contrat
 * @property-read int|null $contrat_count
 * @property-read \App\Models\DemandeVisite|null $demandeVisite
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DetailReservation> $details
 * @property-read int|null $details_count
 * @property-read mixed $date_arrivee_display
 * @property-read mixed $date_arrivee_format
 * @property-read mixed $date_depart_display
 * @property-read mixed $date_depart_format
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Paiement> $paiements
 * @property-read int|null $paiements_count
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation brouillon()
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation confirmee()
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation enAttentePaiement()
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation pourClient($clientId)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereAcomptePaye($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereCodePromo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereDateArrivee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereDateCreation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereDateDepart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereIdClient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereIdDemandeVisite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereNotesAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereNotesClient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation wherePrixOriginal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation wherePrixTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereReductionAppliquee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereReductionMontant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereReductionPourcentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereStatut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereTypeReservation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperReservation {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $nom
 * @property string|null $description
 * @property string $prix
 * @property int|null $duree_estimee
 * @property string $disponibilite
 * @property string|null $horaires_debut
 * @property string|null $horaires_fin
 * @property string $statut
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DetailCommandeService> $detailsCommandes
 * @property-read int|null $details_commandes_count
 * @method static \Illuminate\Database\Eloquent\Builder|Service newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Service newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Service query()
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereDisponibilite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereDureeEstimee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereHorairesDebut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereHorairesFin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service wherePrix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereStatut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperService {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $id_chambre
 * @property string $date_debut
 * @property string $date_fin
 * @property string $prix
 * @property string $type_tarif
 * @property string $pourcentage_reduction
 * @property int $minimum_nuits
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Chambre $chambre
 * @method static \Illuminate\Database\Eloquent\Builder|Tarif newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tarif newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tarif query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tarif whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tarif whereDateDebut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tarif whereDateFin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tarif whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tarif whereIdChambre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tarif whereMinimumNuits($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tarif whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tarif wherePourcentageReduction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tarif wherePrix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tarif whereTypeTarif($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tarif whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperTarif {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property bool $personal_team
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $owner
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TeamInvitation> $teamInvitations
 * @property-read int|null $team_invitations_count
 * @property-read \App\Models\Membership $membership
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\TeamFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Team newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Team newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Team query()
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team wherePersonalTeam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperTeam {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $team_id
 * @property string $email
 * @property string|null $role
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Team $team
 * @method static \Illuminate\Database\Eloquent\Builder|TeamInvitation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TeamInvitation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TeamInvitation query()
 * @method static \Illuminate\Database\Eloquent\Builder|TeamInvitation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TeamInvitation whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TeamInvitation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TeamInvitation whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TeamInvitation whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TeamInvitation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperTeamInvitation {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $id_utilisateur
 * @property string $token
 * @property string $date_creation
 * @property string|null $date_expiration
 * @property int $utilisé
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|TokenReinitialisation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TokenReinitialisation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TokenReinitialisation query()
 * @method static \Illuminate\Database\Eloquent\Builder|TokenReinitialisation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenReinitialisation whereDateCreation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenReinitialisation whereDateExpiration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenReinitialisation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenReinitialisation whereIdUtilisateur($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenReinitialisation whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenReinitialisation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenReinitialisation whereUtilisé($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperTokenReinitialisation {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $nom
 * @property string|null $description
 * @property int $capacite_standard
 * @property int $capacite_max
 * @property string|null $superficie
 * @property string|null $etage_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Chambre> $appartement
 * @property-read int|null $appartement_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Media> $medias
 * @property-read int|null $medias_count
 * @method static \Illuminate\Database\Eloquent\Builder|TypeChambre newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TypeChambre newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TypeChambre query()
 * @method static \Illuminate\Database\Eloquent\Builder|TypeChambre whereCapaciteMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeChambre whereCapaciteStandard($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeChambre whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeChambre whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeChambre whereEtageType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeChambre whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeChambre whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeChambre whereSuperficie($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeChambre whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperTypeChambre {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $email
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $two_factor_confirmed_at
 * @property string $name
 * @property string|null $prenom
 * @property string|null $telephone
 * @property string|null $adresse
 * @property string|null $ville
 * @property string|null $pays
 * @property string|null $code_postal
 * @property string|null $date_naissance
 * @property string $type_utilisateur
 * @property int|null $current_team_id
 * @property string|null $profile_photo_path
 * @property string $date_creation
 * @property string|null $derniere_connexion
 * @property string|null $statut
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Administrateur|null $administrateur
 * @property-read \App\Models\Client|null $client
 * @property-read \App\Models\Team|null $currentTeam
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LogSysteme> $logs
 * @property-read int|null $logs_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message> $messagesEnvoyes
 * @property-read int|null $messages_envoyes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message> $messagesRecus
 * @property-read int|null $messages_recus_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Notification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Team> $ownedTeams
 * @property-read int|null $owned_teams_count
 * @property-read string $profile_photo_url
 * @property-read \App\Models\Membership $membership
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Team> $teams
 * @property-read int|null $teams_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TokenReinitialisation> $tokensReinitialisation
 * @property-read int|null $tokens_reinitialisation_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAdresse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCodePostal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCurrentTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDateCreation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDateNaissance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDerniereConnexion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePrenom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfilePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTelephone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTypeUtilisateur($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereVille($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUser {}
}


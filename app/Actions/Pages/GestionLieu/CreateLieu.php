<?php

namespace App\Actions\Pages\GestionLieu;

use App\Models\Onou\Onou_cm_lieu;

class CreateLieu
{
    public function handle(array $validated, int $typePavilion, int $typeChambre, ?int $lieuId = null): Onou_cm_lieu
    {
        // If updating, fetch the existing record
        $lieu = $lieuId
            ? Onou_cm_lieu::findOrFail($lieuId)
            : new Onou_cm_lieu;

        // Fill the common fields
        $lieu->fill([
            'etablissement' => $validated['residence'],
            'type_lieu' => $validated['type_structure'],
            'sous_type_lieu' => $validated['sous_type'],
            'lieu' => $validated['structure_appartenance'],
            'etat' => $validated['etat'],
            'libelle_fr' => $validated['libelle_fr'],
            'libelle_ar' => $validated['libelle_ar'],
            'capacite_theorique' => $validated['capacite_theorique'],
            'capacite_reelle' => $validated['capacite_reelle'],
            'observation' => $validated['observation'],
        ]);

        $lieu->save();

        // Handle chambres if it's a pavilion
        if ((int) $validated['type_structure'] === $typePavilion) {
            $chambres = array_filter($validated['chambres'] ?? [], function ($chambre) {
                return ! is_null($chambre['from']) && ! is_null($chambre['to']) && ! is_null($chambre['type']);
            });

            foreach ($chambres as $chambre) {
                $from = (int) $chambre['from'];
                $to = (int) $chambre['to'];
                $type = (int) $chambre['type'];

                if ($from > $to) {
                    continue;
                }

                for ($i = $from; $i <= $to; $i++) {
                    Onou_cm_lieu::create([
                        'etablissement' => $validated['residence'],
                        'type_lieu' => $typeChambre,
                        'sous_type_lieu' => $validated['sous_type'],
                        'lieu' => $lieu->id,
                        'libelle_fr' => $validated['libelle_fr'].'_'.$i,
                        'libelle_ar' => $validated['libelle_ar'].'_'.$i,
                        'capacite_theorique' => $type,
                        'capacite_reelle' => $type,
                        'observation' => null,
                    ]);
                }
            }
        }

        return $lieu;
    }
}

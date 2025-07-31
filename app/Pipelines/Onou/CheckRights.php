<?php

namespace App\Pipelines\Onou;

use App\CommonClasses\Onou\Alerts;

class CheckRights extends Alerts
{
   protected ?string $title = null;
    protected ?string $type = 'checkAge';

    public function __construct()
    {
        $this->title = __('pipelines/onou/alerts.confirmite') . ' : ';
    }

    public function handle(array $demande, \Closure $next)
    {
        $checks = [
            'checkreinscription' => [
                'condition' => $demande['reinscription'],
                'success' => __('pipelines/onou/alerts.etudiant_inscrie'),
                'danger' => __('pipelines/onou/alerts.etudiant_not_inscrie'),
            ],
            'frais_inscription_paye' => [
                'condition' => $demande['frais_inscription_paye']??false,
                'success' => __('pipelines/onou/alerts.frais_inscription_paye'),
                'danger' => __('pipelines/onou/alerts.frais_inscription_non_paye'),
            ],
            'checkreabondan' => [
                'condition' => $demande['abondan'],
                'success' => null,
                'danger' => __('pipelines/onou/alerts.abondant'),
            ],
            'checkfrais_hebergement' => [
                'condition' => $demande['frais_hebergement'],
                'success' => __('pipelines/onou/alerts.paiement_hebergement_reguliere'),
                'danger' => __('pipelines/onou/alerts.paiement_hebergement_not_reguliere'),
            ],
            'checkdeuxieme_diplome' => [
                'condition' => $demande['deuxieme_diplome'],
                'success' => __('pipelines/onou/alerts.etudiant_pas_deuxieme_diplome'),
                'danger' => __('pipelines/onou/alerts.etudiant_a_deuxieme_diplome'),
            ],
            'checkretard_scolaire' => [
                'condition' => $demande['retard_scolaire'],
                'success' => __('pipelines/onou/alerts.etudiant_pas_retard_scholaire'),
                'danger' => __('pipelines/onou/alerts.etudiant_a_retard_scholaire'),
            ],
            'checkretard_niveau' => [
                'condition' => $demande['retard_niveau'],
                'success' => null,
                'danger' => __('pipelines/onou/alerts.etudiant_a_retard_scholaire_niveau'),
            ],
            'checkcles_remis' => [
                'condition' => !$demande['cles_remis'],
                'success' => __('pipelines/onou/alerts.cle_not_remise'),
                'danger' => __('pipelines/onou/alerts.cle_remise'),
            ],
        ];

        foreach ($checks as $type => $data) {
            $this->applyCheck($type, $data['condition'], $data['success'], $data['danger']);
        }

        // Vérification pour doctorat
        if (isset($demande['id_fnd'])) {
            $this->applyCheck(
                'checkreinscription_doctort',
                isset($demande['id_suivi_fnd']),
                __('pipelines/onou/alerts.etudiant_inscrie'),
                __('pipelines/onou/alerts.etudiant_doctorat_not_inscrie')
            );
        }

        $next($demande);
    }

    private function applyCheck(string $type, bool $condition, ?string $successMessage, string $dangerMessage): void
    {
        $this->type = $type;
        $this->status = $condition ? 'success' : 'danger';
        $this->message = $condition ? $successMessage : $dangerMessage;

        if (! empty($this->message)) {
            $this->flush_alert();
            $this->saveCheck();
        }
    }

    private function saveCheck(): void
    {
        $existing = session('checks', []);
        $existing[$this->type] = [
            'status' => $this->status,
            'message' => $this->message,
            'title' => $this->title,
        ];
        session(['checks' => $existing]);
    }
}

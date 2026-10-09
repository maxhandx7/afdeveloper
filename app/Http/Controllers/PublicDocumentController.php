<?php

namespace App\Http\Controllers;

use App\Enums\BillingDocumentStatus;
use App\Models\BillingDocument;
use App\Services\Billing\BillingDocuments;

/** Enlace que recibe el cliente: muestra el PDF sin iniciar sesión (el token es imposible de adivinar). */
class PublicDocumentController extends Controller
{
    public function __invoke(string $token, BillingDocuments $documents)
    {
        $doc = BillingDocument::where('public_token', $token)->firstOrFail();
        abort_if($doc->status === BillingDocumentStatus::Draft, 404);

        return $this->stream($doc, $documents);
    }

    /** Vista previa desde el panel (incluye borradores). */
    public function preview(BillingDocument $billingDocument, BillingDocuments $documents)
    {
        abort_unless(auth()->user()?->canAccessPanel(\Filament\Facades\Filament::getPanel('admin')), 403);

        return $this->stream($billingDocument, $documents);
    }

    private function stream(BillingDocument $doc, BillingDocuments $documents)
    {
        return response($documents->pdf($doc), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$doc->fileName().'"',
            'X-Robots-Tag' => 'noindex',
        ]);
    }
}

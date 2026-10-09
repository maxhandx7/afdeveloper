<?php

namespace App\Mail;

use App\Models\BillingDocument;
use App\Models\Business;
use App\Services\Billing\BillingDocuments;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BillingDocumentMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public BillingDocument $document) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "{$this->document->type->getLabel()} {$this->document->number} — ".Business::current()->name,
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.billing-document', with: [
            'business' => Business::current(),
        ]);
    }

    public function attachments(): array
    {
        $doc = $this->document;

        return [
            Attachment::fromData(fn () => app(BillingDocuments::class)->pdf($doc), $doc->fileName())
                ->withMime('application/pdf'),
        ];
    }
}

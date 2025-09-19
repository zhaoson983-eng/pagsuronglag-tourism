<?php

namespace App\Mail;

use App\Models\BusinessProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BusinessStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $business;
    public $status;
    public $notes;
    public $subject;
    public $actionUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(BusinessProfile $business, string $status, ?string $notes = null)
    {
        $this->business = $business;
        $this->status = $status;
        $this->notes = $notes;
        
        // Set appropriate subject based on status
        $this->subject = 'Your Business Profile Has Been ' . ucfirst($status);
        
        // Set the action URL for the email button (business owner landing)
        $this->actionUrl = route('business.my-shop');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.business-status-updated',
            with: [
                'business' => $this->business,
                'status' => $this->status,
                'notes' => $this->notes,
                'actionUrl' => $this->actionUrl,
                'displayableActionUrl' => str_replace(['http://', 'https://'], '', $this->actionUrl),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

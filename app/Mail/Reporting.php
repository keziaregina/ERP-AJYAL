<?php

namespace App\Mail;

use App\ReportSettings;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class Reporting extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $data;
    public $user;
    public $path;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ReportSettings $report_settings, $filename)
    {
        $this->data = $report_settings;
        $this->user = User::find($this->data->user_id);
        $this->path = $filename;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Reporting',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.report_setting',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [
            // Attachment::fromStorageDisk('public','report\target.pdf'),
            // Attachment::fromStorageDisk('public','report\target2.JPG'),
            // Attachment::fromStorageDisk('public','report\real.pdf'),
            Attachment::fromStorageDisk('public',$this->path),
        ];
    }
}

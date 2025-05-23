<?php

namespace App\Mail;

use App\User;
use App\ReportSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Facades\Storage;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\App;

class Reporting extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $data;
    public $user;
    public $path;
    public $type;
    public $title;

    public $report_type;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ReportSettings $report_settings, $filename,$type)
    {
        $this->data = $report_settings;

        $this->report_type = $report_settings->report_type;

        $this->user = User::find($this->data->user_id);
        $this->path = $filename;
        $this->type = $type;
        $this->title = $this->data->type;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Reporting '. $this->report_type,
            metadata: [
                'type' => $this->type,
            ],
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
            with: [
                'path' => $this->path,
                'interval' => $this->data->interval,
                'attachment_lang' => $this->data->attachment_lang,
                'title' => $this->title,
                'data' => $this->data,
                'user' => $this->user
            ],
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
            Attachment::fromStorageDisk('public',$this->path)->as(basename($this->path)) 
            ->withMime('application/pdf'), 
        ];
    }
}

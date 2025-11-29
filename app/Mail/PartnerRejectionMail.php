<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PartnerRejectionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $tenNhaXe;
    public $lyDoTuChoi;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($tenNhaXe, $lyDoTuChoi)
    {
        $this->tenNhaXe = $tenNhaXe;
        $this->lyDoTuChoi = $lyDoTuChoi;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Yêu cầu hợp tác bị từ chối - Bustrip')
                    ->view('emails.partner_rejection')
                    ->with([
                        'tenNhaXe' => $this->tenNhaXe,
                        'lyDoTuChoi' => $this->lyDoTuChoi,
                    ]);
    }
}

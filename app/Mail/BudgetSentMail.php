<?php
namespace App\Mail;

use App\Models\Budget;
use Illuminate\Mail\Mailable;

class BudgetSentMail extends Mailable
{
    public Budget $budget;

    public function __construct(Budget $budget)
    {
        $this->budget = $budget;
    }

    public function build()
    {
        return $this->subject('Seu orçamento está disponível')
            ->view('emails.budget-sent', ['budget' => $this->budget]);
    }
}

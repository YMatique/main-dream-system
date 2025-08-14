<?php

namespace App\Livewire\Website;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Contact extends Component
{
     public $name = '';
    public $email = '';
    public $phone = '';
    public $subject = '';
    public $message = '';
    public $service_type = '';

    protected function rules()
    {
        return [
            'name' => 'required|min:2|max:100',
            'email' => 'required|email|max:100',
            'phone' => 'required|min:9|max:20',
            'subject' => 'required|min:5|max:200',
            'service_type' => 'required|in:engineering,maintenance,technology,spare_parts,consultation,other',
            'message' => 'required|min:10|max:1000',
        ];
    }

    protected function messages()
    {
        return [
            'name.required' => __('messages.contact_form.validation.name_required'),
            'name.min' => __('messages.contact_form.validation.name_min'),
            'email.required' => __('messages.contact_form.validation.email_required'),
            'email.email' => __('messages.contact_form.validation.email_invalid'),
            'phone.required' => __('messages.contact_form.validation.phone_required'),
            'phone.min' => __('messages.contact_form.validation.phone_min'),
            'subject.required' => __('messages.contact_form.validation.subject_required'),
            'subject.min' => __('messages.contact_form.validation.subject_min'),
            'service_type.required' => __('messages.contact_form.validation.service_type_required'),
            'message.required' => 'A mensagem é obrigatória.',
            'message.min' => 'A mensagem deve ter pelo menos 10 caracteres.',
            'message.max' => 'A mensagem não pode exceder 1000 caracteres.',
        ];
    }

    public function submitForm()
    {
        $this->validate();

        try {
            // Prepare email data
            $emailData = [
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'subject' => $this->subject,
                'service_type' => $this->service_type,
                'message_content' => $this->message,
                'service_label' => $this->getServiceLabel($this->service_type),
            ];

            // Send email to company
            Mail::send('emails.contact-form', $emailData, function ($message) {
                $message->to(config('mail.contact_email', 'info@maingdream.co.mz'))
                        ->subject('Nova Mensagem de Contacto - MainGDream')
                        ->from($this->email, $this->name);
            });

            // Send auto-reply to customer
            Mail::send('emails.contact-auto-reply', $emailData, function ($message) {
                $message->to($this->email, $this->name)
                        ->subject('Obrigado pelo seu contacto - MainGDream')
                        ->from(config('mail.from.address', 'info@maingdream.co.mz'), 'MainGDream');
            });

            // Reset form
            $this->reset(['name', 'email', 'phone', 'subject', 'message', 'service_type']);
            
            session()->flash('success', __('messages.contact_form.success'));

        } catch (\Exception $e) {
            Log::error('Contact form error: ' . $e->getMessage());
            session()->flash('error', __('messages.contact_form.error'));
        }
    }

    private function getServiceLabel($serviceType)
    {
        $services = [
            'engineering' => __('messages.contact_form.service_options.engineering'),
            'maintenance' => __('messages.contact_form.service_options.maintenance'),
            'technology' => __('messages.contact_form.service_options.technology'),
            'spare_parts' => __('messages.contact_form.service_options.spare_parts'),
            'consultation' => __('messages.contact_form.service_options.consultation'),
            'other' => __('messages.contact_form.service_options.other'),
        ];

        return $services[$serviceType] ?? $serviceType;
    }

    public function render()
    {
        return view('livewire.website.contact')->layout('layouts.website')->title(__('messages.nav.contact') . ' - MainGDream');
    }
}

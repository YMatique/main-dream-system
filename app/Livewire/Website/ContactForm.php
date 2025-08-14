<?php

namespace App\Livewire\Website;

use App\Mail\ContactAutoReply;
use App\Mail\ContactFormMail;
// use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class ContactForm extends Component
{
    public $name;
    public $email;
    public $phone;
    public $subject;
    public $service_type = '';

    public $isSubmitting = false;
    public $showSuccess = false;
    public $showError = false;
    public $errorMessage = '';

    protected $rules = [
        'name' => 'required|string|min:2|max:100',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|min:9|max:20',
        'subject' => 'required|string|min:5|max:200',
        'service_type' => 'required|string',
    ];

    public function getValidationMessages()
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
        ];
        // return [
        //     'name.required' => 'O nome é obrigatório.',
        //     'name.min' => 'O nome deve ter pelo menos 2 caracteres.',
        //     'email.required' => 'O email é obrigatório.',
        //     'email.email' => 'Por favor, insira um email válido.',
        //     'phone.required' => 'O telefone é obrigatório.',
        //     'phone.min' => 'O telefone deve ter pelo menos 9 dígitos.',
        //     'subject.required' => 'O assunto é obrigatório.',
        //     'subject.min' => 'O assunto deve ter pelo menos 5 caracteres.',
        //     'service_type.required' => 'Por favor, selecione o tipo de serviço.',
        // ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, $this->rules, $this->getValidationMessages());
    }

    public function submit()
    {
        $this->isSubmitting = true;
        $this->showError = false;
        $this->showSuccess = false;

        try {
            // Validar dados
            $validatedData = Validator::make([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'subject' => $this->subject,
                'service_type' => $this->service_type,
            ], $this->rules, $this->getValidationMessages())->validate();

            // Aqui você pode implementar o envio do email
            // Exemplo básico:
            $this->sendContactEmail($validatedData);

            // Mostrar sucesso
            $this->showSuccess = true;
            $this->resetForm();
        } catch (\Exception $e) {
            // dd($e);
            $this->showError = true;
            $this->errorMessage = __('messages.contact_form.error');
        } finally {
            $this->isSubmitting = false;
        }
    }

    private function sendContactEmail($data)
    {
        $contactData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'subject' => $data['subject'],
            'service_type' => $data['service_type'],
            'message_content' => '',
            'service_label' => $this->getServiceLabel($data['service_type']),
            'date' => now()->format('d/m/Y H:i:s'),
        ];
        // Send email to company
        Mail::to(config('mail.contact_email', 'info@maingdream.com'))
            ->send(new ContactFormMail($contactData));

        // Send auto-reply to customer
        Mail::to($data['email'])
            ->send(new ContactAutoReply($contactData));

        // Por agora, apenas log
        logger('Nova mensagem de contacto', $data);
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
    public function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->subject = '';
        $this->service_type = '';
    }

    public function closeSuccess()
    {
        $this->showSuccess = false;
    }

    public function closeError()
    {
        $this->showError = false;
    }
    public function render()
    {
        return view('livewire.website.contact-form');
    }
}

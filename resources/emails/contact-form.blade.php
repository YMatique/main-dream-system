<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ __('messages.email.contact_form.subject') }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="background: #003366; color: white; padding: 20px; text-align: center;">
            <h1>MainGDream</h1>
            <p>{{ __('messages.footer.tagline') }}</p>
            <h2>{{ __('messages.email.contact_form.title') }}</h2>
        </div>
        
        <div style="background: #f8f9fa; padding: 20px; margin: 20px 0;">
            <h2>{{ __('messages.email.contact_form.details_title') }}</h2>
            
            <p><strong>{{ __('messages.email.contact_form.name_label') }}:</strong> {{ $contactData['name'] }}</p>
            <p><strong>{{ __('messages.email.contact_form.email_label') }}:</strong> {{ $contactData['email'] }}</p>
            <p><strong>{{ __('messages.email.contact_form.phone_label') }}:</strong> {{ $contactData['phone'] }}</p>
            <p><strong>{{ __('messages.email.contact_form.service_label') }}:</strong> {{ $contactData['service_label'] }}</p>
            <p><strong>{{ __('messages.email.contact_form.subject_label') }}:</strong> {{ $contactData['subject'] }}</p>
            
            <h3>{{ __('messages.email.contact_form.message_label') }}:</h3>
            <div style="background: white; padding: 15px; border-left: 4px solid #0066cc; margin-top: 10px;">
                {!! nl2br(e($contactData['message_content'])) !!}
            </div>
        </div>
        
        <div style="background: #e9ecef; padding: 15px; text-align: center; font-size: 12px; color: #666;">
            <p>{{ __('messages.email.contact_form.footer_text') }}</p>
            <p>{{ __('messages.email.contact_form.date_label') }}: {{ $contactData['date'] }}</p>
        </div>
    </div>
</body>
</html>
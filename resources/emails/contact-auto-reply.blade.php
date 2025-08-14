<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ __('messages.email.auto_reply.subject') }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="background: #003366; color: white; padding: 20px; text-align: center;">
            <h1>MainGDream</h1>
            <p>{{ __('messages.footer.tagline') }}</p>
        </div>
        
        <div style="padding: 20px;">
            <h2>{{ __('messages.email.auto_reply.greeting') }} {{ $contactData['name'] }},</h2>
            
            <p>{{ __('messages.email.auto_reply.thank_you') }} <strong>{{ $contactData['service_label'] }}</strong> {{ __('messages.email.auto_reply.response_time') }}</p>
            
            <p>{{ __('messages.email.auto_reply.response_promise') }}</p>
            
            <div style="background: #f8f9fa; padding: 15px; margin: 20px 0; border-radius: 5px;">
                <h3>{{ __('messages.email.auto_reply.summary_title') }}</h3>
                <p><strong>{{ __('messages.email.contact_form.subject_label') }}:</strong> {{ $contactData['subject'] }}</p>
                <p><strong>{{ __('messages.email.contact_form.service_label') }}:</strong> {{ $contactData['service_label'] }}</p>
            </div>
            
            <p>{{ __('messages.email.auto_reply.urgent_title') }}</p>
            <ul style="list-style: none; padding: 0; background: #fff3cd; padding: 15px; border-radius: 5px; border-left: 4px solid #ffc107;">
                <li style="margin-bottom: 8px;"><strong>{{ __('messages.email.auto_reply.phone_label') }}:</strong> {{ __('messages.contact.phone') }}</li>
                <li><strong>{{ __('messages.email.auto_reply.email_label') }}:</strong> {{ __('messages.contact.email') }}</li>
            </ul>
            
            <div style="margin-top: 30px;">
                <p>{{ __('messages.email.auto_reply.signature') }}<br>
                <strong>{{ __('messages.email.auto_reply.team_name') }}</strong></p>
            </div>
        </div>
        
        <div style="background: #e9ecef; padding: 15px; text-align: center; font-size: 12px; color: #666;">
            <p><strong>{{ __('messages.email.auto_reply.footer_company') }}</strong></p>
            <p>{{ __('messages.email.auto_reply.footer_address') }} | {{ __('messages.email.auto_reply.footer_website') }}</p>
        </div>
    </div>
</body>
</html>
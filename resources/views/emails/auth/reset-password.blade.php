<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar Senha - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f8fafc;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
            text-align: center;
            color: white;
        }
        .content {
            padding: 40px 30px;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white !important;
            padding: 16px 32px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
            transition: transform 0.2s;
        }
        .button:hover {
            transform: translateY(-2px);
        }
        .footer {
            background: #f8fafc;
            padding: 20px 30px;
            text-align: center;
            color: #666;
            font-size: 14px;
            border-top: 1px solid #e2e8f0;
        }
        .security-notice {
            background: #fef3cd;
            border: 1px solid #fcd34d;
            border-radius: 8px;
            padding: 16px;
            margin: 20px 0;
            color: #92400e;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🔐 Recuperar Senha</h1>
            <p>{{ config('app.name') }}</p>
        </div>
        
        <div class="content">
            <h2>Olá!</h2>
            <p>Recebemos uma solicitação para redefinir a senha da sua conta.</p>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $url }}" class="button">Redefinir Senha</a>
            </div>
            
            <p>Este link de recuperação expira em <strong>60 minutos</strong>.</p>
            
            <div class="security-notice">
                <strong>⚠️ Aviso de Segurança:</strong><br>
                Se você não solicitou a recuperação de senha, ignore este email. 
                Sua senha permanecerá inalterada.
            </div>
            
            <p>Se você está com problemas para clicar no botão, copie e cole o link abaixo no seu navegador:</p>
            <p style="word-break: break-all; color: #666; font-size: 14px;">{{ $url }}</p>
        </div>
        
        <div class="footer">
            <p>{{ config('app.name') }} - Sistema de Gestão<br>
            Este email foi enviado automaticamente, não responda.</p>
            <p style="margin-top: 20px; font-size: 12px; color: #999;">
                © {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.
            </p>
        </div>
    </div>
</body>
</html>
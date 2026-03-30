<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medication Recall Notice</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f3f4f6;
            padding: 20px;
            line-height: 1.6;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .header {
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }
        
        .header-icon {
            width: 60px;
            height: 60px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            margin-bottom: 15px;
        }
        
        .header h1 {
            font-size: 24px;
            font-weight: 700;
            margin: 0;
        }
        
        .header p {
            font-size: 14px;
            opacity: 0.95;
            margin-top: 5px;
        }
        
        .content {
            padding: 40px 30px;
        }
        
        .greeting {
            font-size: 18px;
            color: #111827;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .alert-box {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        
        .alert-box p {
            color: #92400e;
            margin: 0;
            font-size: 15px;
            line-height: 1.6;
        }
        
        .details-box {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }
        
        .details-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .details-row:last-child {
            border-bottom: none;
        }
        
        .details-label {
            font-weight: 600;
            color: #6b7280;
            font-size: 14px;
        }
        
        .details-value {
            color: #111827;
            font-weight: 600;
            font-size: 14px;
        }
        
        .message-content {
            color: #374151;
            font-size: 15px;
            line-height: 1.7;
            margin: 20px 0;
        }
        
        .action-button {
            display: inline-block;
            background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%);
            color: white;
            padding: 14px 32px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .action-button:hover {
            background: linear-gradient(135deg, #0f766e 0%, #0d9488 100%);
        }
        
        .contact-info {
            background-color: #eff6ff;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }
        
        .contact-info h3 {
            color: #1e40af;
            font-size: 16px;
            margin-bottom: 12px;
            font-weight: 600;
        }
        
        .contact-info p {
            color: #1e3a8a;
            font-size: 14px;
            margin: 8px 0;
        }
        
        .contact-info strong {
            color: #1e40af;
        }
        
        .footer {
            background-color: #1f2937;
            color: #9ca3af;
            padding: 30px;
            text-align: center;
            font-size: 13px;
        }
        
        .footer p {
            margin: 5px 0;
        }
        
        .footer-links {
            margin-top: 15px;
        }
        
        .footer-links a {
            color: #14b8a6;
            text-decoration: none;
            margin: 0 10px;
        }
        
        .footer-links a:hover {
            text-decoration: underline;
        }
        
        .divider {
            height: 1px;
            background-color: #e5e7eb;
            margin: 25px 0;
        }
        
        @media only screen and (max-width: 600px) {
            body {
                padding: 10px;
            }
            
            .content {
                padding: 25px 20px;
            }
            
            .header {
                padding: 30px 20px;
            }
            
            .action-button {
                display: block;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="header-icon">⚠️</div>
            <h1>Important Medication Recall Notice</h1>
            <p>Urgent Action Required</p>
        </div>
        
        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Dear {{ $customerName }},
            </div>
            
            <p class="message-content">
                We are contacting you regarding an important medication safety alert that requires your immediate attention.
            </p>
            
            <!-- Alert Message -->
            <div class="alert-box">
                <p>{{ $alertMessage }}</p>
            </div>
            
            <!-- Order Details -->
            <div class="details-box">
                <div class="details-row">
                    <span class="details-label">Order ID:</span>
                    <span class="details-value">#{{ str_pad($orderId, 7, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="details-row">
                    <span class="details-label">Affected Lot Number:</span>
                    <span class="details-value">{{ $lotNumber }}</span>
                </div>
                <div class="details-row">
                    <span class="details-label">Alert Date:</span>
                    <span class="details-value">{{ date('F j, Y') }}</span>
                </div>
            </div>
            
            <div class="divider"></div>
            
            <h3 style="color: #111827; font-size: 16px; font-weight: 600; margin-bottom: 12px;">
                What You Need to Do:
            </h3>
            
            <ul style="color: #374151; font-size: 15px; line-height: 1.8; margin-left: 20px; margin-bottom: 20px;">
                <li>Stop using the medication from the lot number listed above immediately</li>
                <li>Contact our pharmacy as soon as possible to arrange a return or replacement</li>
                <li>If you have experienced any adverse effects, please seek medical attention</li>
                <li>Keep the medication packaging for our records</li>
            </ul>
            
            <!-- Contact Information -->
            <div class="contact-info">
                <h3>📞 Contact Our Pharmacy</h3>
                <p><strong>Phone:</strong> (555) 123-4567</p>
                <p><strong>Email:</strong> support@pharmacovigilance.com</p>
                <p><strong>Hours:</strong> Monday - Friday, 8:00 AM - 6:00 PM</p>
            </div>
            
            <p class="message-content">
                Your health and safety are our top priority. We apologize for any inconvenience and appreciate your prompt attention to this matter.
            </p>
            
            <p style="color: #6b7280; font-size: 14px; margin-top: 30px;">
                Best regards,<br>
                <strong style="color: #111827;">Pharmacovigilance Team</strong>
            </p>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p><strong>Pharmacovigilance System</strong></p>
            <p>123 Pharmacy Street, Medical District, City, State 12345</p>
            <div class="footer-links">
                <a href="#">Privacy Policy</a> | 
                <a href="#">Contact Us</a> | 
                <a href="#">FAQ</a>
            </div>
            <p style="margin-top: 15px; font-size: 12px;">
                This is an automated notification from our pharmacovigilance system.<br>
                Please do not reply directly to this email.
            </p>
        </div>
    </div>
</body>
</html>

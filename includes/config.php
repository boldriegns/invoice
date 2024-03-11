<?php
// Debugging
ini_set('error_reporting', E_ALL);

// DATABASE INFORMATION
define('DATABASE_HOST', getenv('IP'));
define('DATABASE_NAME', 'invoice');
define('DATABASE_USER', 'root');
define('DATABASE_PASS', 'Bold38**');

// COMPANY INFORMATION
define('COMPANY_LOGO', 'images/logo.png');
define('COMPANY_LOGO_WIDTH', '300');
define('COMPANY_LOGO_HEIGHT', '90');
define('COMPANY_NAME','Invoice Mg System');
define('COMPANY_ADDRESS_1','Maseno,Kenya');
define('COMPANY_ADDRESS_2','kenya, 10100 nairobi');
define('COMPANY_ADDRESS_3','maseno');
define('COMPANY_COUNTY','Maseno');
define('COMPANY_POSTCODE','Kenya');

define('COMPANY_NUMBER','Company No: 0741094403'); // Company registration number
define('COMPANY_VAT', 'Company paybill: 522533'); // Company TAX/VAT number

// EMAIL DETAILS
define('EMAIL_FROM', 'brianriziki2020@gmail.com'); // Email address invoice emails will be sent from
define('EMAIL_NAME', 'Invoice Mg System'); // Email from address
define('EMAIL_SUBJECT', 'Invoice default email subject'); // Invoice email subject
define('EMAIL_BODY_INVOICE', 'Invoice default body'); // Invoice email body
define('EMAIL_BODY_QUOTE', 'Quote default body'); // Invoice email body
define('EMAIL_BODY_RECEIPT', 'Receipt default body'); // Invoice email body

// OTHER SETTINFS
define('INVOICE_PREFIX', 'MD'); // Prefix at start of invoice - leave empty '' for no prefix
define('INVOICE_INITIAL_VALUE', '1'); // Initial invoice order number (start of increment)
define('INVOICE_THEME', '#222222'); // Theme colour, this sets a colour theme for the PDF generate invoice
define('TIMEZONE', 'Africa/Nairobi'); // Timezone
define('DATE_FORMAT', 'YYYY/MM/DD'); // DD/MM/YYYY or MM/DD/YYYY
define('CURRENCY', 'ksh.'); // Currency symbol
define('ENABLE_VAT', true); // Enable TAX/VAT
define('VAT_INCLUDED', false); // Is VAT included or excluded?
define('VAT_RATE', '10'); // This is the percentage value

define('PAYMENT_DETAILS', 'Invoice Mg System'); // Payment information
define('FOOTER_NOTE', 'Invoice Management System');

// CONNECT TO THE DATABASE
$mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);

?>
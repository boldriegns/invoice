<?php
require('vendor/setasign/fpdf/fpdf.php');
include_once('includes/config.php');
class PDF extends FPDF {
    function Header() {
        $this->Image(COMPANY_LOGO, 10, 6, 100); // Adjust width and height as needed
        // Move to the right to make space for the logo
        $this->Cell(80);
        
        // Company information aligned to the right
        $this->SetFont('Arial', '', 13); // Set font size to 12 (adjust as needed)
    
        // Set starting position for right alignment
        $startX = $this->getX();
    
        // Company Name
        $this->SetX($startX);
        $this->MultiCell(0, 10, 'Company Name : ' . COMPANY_NAME, 0, 'R');
    
        // Company address1
        $this->SetX($startX);
        $this->MultiCell(0, 10, 'Company Location : ' . COMPANY_ADDRESS_1, 0, 'R');
    
        // Line break
        $this->Ln(20);
    }
    
    
    
    

    function Footer() {
   // Move to the left
   $this->SetY(-55);
   // Remaining company information aligned to the left
   	// Add a line break between sections
   $this->Ln(10);
   // Label for Customer Information/Details
   $this->SetFont('Arial', 'B', 16); // Set font to bold and size 12
   $this->Cell(0, 10, 'Payment Details', 0, 1, 'L');
   
   // Customer details
   $this->SetFont('Arial', '', 14); // Set font to regular and size 10
   $this->SetX(10);
   $this->Cell(0, 10,'Company county : ' . COMPANY_COUNTY . ', ' . COMPANY_POSTCODE);
   $this->Ln(); // Move to the next line
   $this->Cell(0, 10,COMPANY_NUMBER);
   $this->Ln(); // Move to the next line
   $this->Cell(0, 10, COMPANY_VAT);
    }
}
?>

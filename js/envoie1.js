async function fetchLogo() {
    const response = await fetch('../images/logo.png'); 
    return await response.arrayBuffer();
}

window.onload = async function() {
    let prevUrl = document.referrer;
    console.log(prevUrl);
    let sendDetails = JSON.parse(localStorage.getItem("sendDetails"));
    if (!sendDetails || !prevUrl.includes('ajouterEnvoi.php')) {
        return;
    }

    const { PDFDocument, rgb } = PDFLib;

    const logoBytes = await fetchLogo();
    const pdfDoc = await PDFDocument.create();
    const page = pdfDoc.addPage([420, 595]); 

    const logoImage = await pdfDoc.embedPng(logoBytes);
    const barcodeCanvas = document.createElement('canvas');
    JsBarcode(barcodeCanvas, sendDetails['numEnvoie'], { format: 'CODE128' });

    const barcodeDataUrl = barcodeCanvas.toDataURL('image/png');
    const barcodeImage = await pdfDoc.embedPng(barcodeDataUrl);

    
    const logoDims = logoImage.scale(0.5); 
    page.drawImage(logoImage, {
        x: 10, 
        y: page.getHeight() - 70 - 10, 
        width: 70,
        height: 70,
    });

    const barcodeDims = barcodeImage.scale(0.5); 

    page.drawImage(barcodeImage, {
        x: (page.getWidth() - barcodeDims.width+10) / 2,
        y: page.getHeight() - barcodeDims.height - 10, 
        width: barcodeDims.width,
        height: barcodeDims.height,
    });

    
    page.drawText("Information de l'envoi:", {
        x: (page.getWidth() - 150) / 2, 
        y: page.getHeight() - barcodeDims.height - 100, 
        size: 14,
        color: rgb(0, 0, 0),
    });

    const textYStart = page.getHeight() - barcodeDims.height - 120;
    let currentY = textYStart;

    const addText = (text, size = 12) => {
        page.drawText(text, {
            x: 50,
            y: currentY,
            size: size,
            color: rgb(0, 0, 0),
        });
        currentY -= size + 10;
    };

    addText(`Date d'envoi: ${sendDetails['sendDate']}`);
    addText(`Type: ${sendDetails['type']}`);
    addText(`Poids: ${sendDetails['weight']} g`);
    addText(`Prix: ${sendDetails['tarif']} DH`);
    addText(`Option sms: ${sendDetails['optionSms'] == 1 ? 'oui' : 'non'}`);

    addText(`----------------Information Client-------------------------`);
    addText(`Cin: ${sendDetails['cin']}`);
    addText(`Nom: ${sendDetails['firstNameClient']} ${sendDetails['lastNameClient']}`);
    addText(`Ville: ${sendDetails['cityClient']}`);
    addText(`Adresse: ${sendDetails['adressClient']}`);
    addText(`Telephone: ${sendDetails['phoneClient']}`);

    addText(`----------------Information Destinataire------------------`);
    addText(`Nom: ${sendDetails['firstNameDes']} ${sendDetails['lastNameDes']}`);
    addText(`Ville: ${sendDetails['cityDes']}`);
    addText(`Adresse: ${sendDetails['adressDes']}`);
    addText(`Telephone: ${sendDetails['phoneDes']}`);

    
    const pdfBytes = await pdfDoc.save();
    const blob = new Blob([pdfBytes], { type: 'application/pdf' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = sendDetails['numEnvoie']+'.pdf';
    link.click();
    localStorage.removeItem("sendDetails");
}
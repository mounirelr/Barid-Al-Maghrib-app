const { PDFDocument, rgb } = PDFLib;

        document.getElementById('downloadButton').addEventListener('click', async function(event) {
            event.preventDefault();
            await downloadReport();
        });

        async function downloadReport() {
            const content = document.querySelector('.content_page');
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;

            const canvas = await html2canvas(content, {
                scale: 5, 
                useCORS: true 
            });

            const imgData = canvas.toDataURL('image/png', 1.0); 
            const pdfDoc = await PDFDocument.create();
            const page = pdfDoc.addPage([595.28, 841.89]); 

            const img = await pdfDoc.embedPng(imgData);
            const { width, height } = img.scale(1);
            const imgWidth = 595.28; 
            const imgHeight = (height * imgWidth) / width;

            page.drawText(`Rapport du ${startDate} au ${endDate}`, {
                x: 50,
                y: 820,
                size: 16,
                color: rgb(0, 0, 0),
            });

            page.drawImage(img, {
                x: 0,
                y: 780 - imgHeight,
                width: imgWidth,
                height: imgHeight,
            });

            let heightLeft = imgHeight - 780;

            while (heightLeft > 0) {
                const newPage = pdfDoc.addPage([595.28, 841.89]);
                newPage.drawImage(img, {
                    x: 0,
                    y: 841.89 - heightLeft,
                    width: imgWidth,
                    height: heightLeft,
                });
                heightLeft -= 841.89;
            }

            const pdfBytes = await pdfDoc.save();
            const blob = new Blob([pdfBytes], { type: 'application/pdf' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `Rapport.pdf`;
            link.click();
        }
// Arabic font configuration for pdfMake
pdfMake.fonts = {
    Arabic: {
        normal: 'https://cdn.jsdelivr.net/npm/dejavu-fonts-ttf@2.37.3/ttf/DejaVuSans.ttf',
        bold: 'https://cdn.jsdelivr.net/npm/dejavu-fonts-ttf@2.37.3/ttf/DejaVuSans-Bold.ttf',
        italics: 'https://cdn.jsdelivr.net/npm/dejavu-fonts-ttf@2.37.3/ttf/DejaVuSans-Oblique.ttf',
        bolditalics: 'https://cdn.jsdelivr.net/npm/dejavu-fonts-ttf@2.37.3/ttf/DejaVuSans-BoldOblique.ttf'
    },
    Roboto: {
        normal: 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/fonts/Roboto/Roboto-Regular.ttf',
        bold: 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/fonts/Roboto/Roboto-Medium.ttf',
        italics: 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/fonts/Roboto/Roboto-Italic.ttf',
        bolditalics: 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/fonts/Roboto/Roboto-MediumItalic.ttf'
    }
};

// Function to check if text contains Arabic
function containsArabic(text) {
    return /[\u0600-\u06FF]/.test(text);
}

// Function to reverse Arabic text while preserving English
function reverseArabicWithEnglish(text) {
    if (!text) return text;
    
    // Split into words
    var words = text.toString().split(' ');
    
    // Process each word
    words = words.map(function(word) {
        if (containsArabic(word)) {
            return word.split('').reverse().join('');
        }
        return word;
    });
    
    // Reverse the order of words and join
    return words.reverse().join(' ');
}

// Override DataTables PDF button configuration
$.fn.dataTable.ext.buttons.pdfHtml5 = $.extend({}, $.fn.dataTable.ext.buttons.pdfHtml5, {
    exportOptions: {
        columns: ':visible',
        format: {
            body: function(data, row, column, node) {
                return containsArabic(data) ? reverseArabicWithEnglish(data) : data;
            }
        }
    },
    customize: function(doc) {
        // Set default font to Roboto
        doc.defaultStyle = {
            font: 'Roboto',
            fontSize: 9
        };
        
        // Set RTL for the whole document
        doc.pageMargins = [40, 40, 40, 40];
        doc.defaultStyle.direction = 'rtl';
        
        // Configure table layout
        doc.content[0].layout = {
            hLineWidth: function(i, node) { return 1; },
            vLineWidth: function(i, node) { return 1; },
            hLineColor: function(i, node) { return '#aaa'; },
            vLineColor: function(i, node) { return '#aaa'; },
            paddingLeft: function(i, node) { return 4; },
            paddingRight: function(i, node) { return 4; },
            paddingTop: function(i, node) { return 2; },
            paddingBottom: function(i, node) { return 2; }
        };

        // Set styles for table
        doc.styles = {
            tableHeader: {
                alignment: 'right',
                fontSize: 10,
                bold: true
            },
            tableBody: {
                alignment: 'right',
                fontSize: 9
            }
        };

        // Apply styles to table
        if (doc.content[0].table) {
            // Set equal column widths
            doc.content[0].table.widths = Array(doc.content[0].table.body[0].length).fill('*');
            
            // Apply styles to all cells
            doc.content[0].table.body.forEach(function(row, rowIndex) {
                row.forEach(function(cell, cellIndex) {
                    if (typeof cell === 'object') {
                        cell.alignment = 'right';
                        // Use Arabic font for cells containing Arabic text
                        if (typeof cell.text === 'string' && containsArabic(cell.text)) {
                            cell.font = 'Arabic';
                        }
                    }
                });
            });
        }
    }
}); 
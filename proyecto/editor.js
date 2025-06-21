tinymce.init({
selector: '#editor',
language: 'es_MX',
branding: false,
promotion: false,
min_height: 600,
statusbar: false,
plugins: 'image | searchreplace | link | pagebreak',
toolbar:'undo redo | styles forecolor | bold italic | alignleft aligncenter alignright alignjustify | outdent indent | image | link | searchreplace',
pagebreak_separator: '<div class="pdf-break"></div>',
license_key: 'gpl'
});

function exportarPDF() {
  const content = tinymce.get('editor').getContent();

  // Crear contenedor para el contenido con los saltos
  const wrapper = document.createElement('div');
  wrapper.innerHTML = content;
  wrapper.style.width = '210mm';
  wrapper.style.padding = '20mm';
  wrapper.style.boxSizing = 'border-box';
  wrapper.style.fontFamily = 'Arial, sans-serif';

  // Estilo para los saltos de página
  const pageBreaks = wrapper.querySelectorAll('.pdf-break');
  pageBreaks.forEach(pb => {
    pb.style.pageBreakBefore = 'always';
    pb.style.border = 'none';
    pb.style.height = '0px';
    pb.style.margin = '0';
  });

  document.body.appendChild(wrapper);

  const opt = {
    margin:       0,
    filename:     'boletin.pdf',
    image:        { type: 'jpeg', quality: 0.98 },
    html2canvas:  { scale: 2 },
    jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' },
    pagebreak:    { mode: ['css', 'legacy'] }
  };

  html2pdf().set(opt).from(wrapper).save().then(() => {
    document.body.removeChild(wrapper);
  });
}
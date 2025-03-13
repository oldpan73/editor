$(document).ready(function() {
    // Inizializzazione di Summernote con pulsante personalizzato "Libreria"
    $('#editortest').summernote({
      height: 300,
      callbacks: {
        onImageUpload: function(files) {
          for (var i = 0; i < files.length; i++) {
            uploadImage(files[i]);
          }
        },
        onPaste: function(event) {
            let clipboardData = (event.originalEvent || event).clipboardData;
            if (!clipboardData) return;
            
            let pastedData = clipboardData.getData('text/html') || clipboardData.getData('text/plain');
            if (pastedData.includes('<table')) {
              event.preventDefault();
              let formattedTable = cleanTable(pastedData);
              $('#editortest').summernote('pasteHTML', formattedTable);
            }
          }

      },
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'italic', 'underline', 'clear']],
        ['fontname', ['fontname']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture']],
        ['misc', ['fullscreen', 'codeview','mediaLibrary']]
      ],
      buttons: {
        mediaLibrary: function(context) {
          var ui = $.summernote.ui;
          var button = ui.button({
            contents: '<i class="note-icon-picture"></i> Libreria',
            tooltip: 'Apri Libreria Immagini',
            click: function () {
              $('#mediaLibraryModal').modal('show');
              loadYears();
              loadImages();
            }
          });
          return button.render();
        }
      }
    });

    function cleanTable(html) {
        let tempDiv = document.createElement("div");
        tempDiv.innerHTML = html;
        let table = tempDiv.querySelector("table");
        if (!table) return html; 
        
        table.removeAttribute("style");
        table.querySelectorAll("td, th").forEach(cell => {
          cell.removeAttribute("style");
          cell.classList.add("p-2", "border", "border-dark");
        });
        return table.outerHTML;
      }

    // Funzione per l'upload dell'immagine tramite drag & drop o pulsante
    function uploadImage(file) {
      var data = new FormData();
      data.append("file", file);
      $.ajax({
        url: 'upload.php',
        method: 'POST',
        data: data,
        contentType: false,
        processData: false,
        success: function(url) {
          // Inserisce l'immagine originale nell'editor
          $('#editortest').summernote('insertImage', url);
        },
        error: function(jqXHR, textStatus, errorThrown) {
          console.error("Errore durante il caricamento dell'immagine: " + textStatus + " " + errorThrown);
        }
      });
    }

    // Carica le immagini dalla libreria in base ai filtri
    function loadImages() {
      var year = $('#filterYear').val();
      var month = $('#filterMonth').val();
      $.ajax({
        url: 'list_images.php',
        type: 'GET',
        data: { year: year, month: month },
        success: function(data) {
          $('#mediaLibraryContent').html('<div class="row">' + data + '</div>');
        },
        error: function() {
          alert('Errore nel caricamento delle immagini');
        }
      });
    }

    // Carica gli anni disponibili dalla tabella multimedia
    function loadYears() {
      $.ajax({
        url: 'list_years.php',
        type: 'GET',
        success: function(data) {
          $('#filterYear').html('<option value="">Tutti gli anni</option>' + data);
        },
        error: function() {
          console.error('Errore nel caricamento degli anni.');
        }
      });
    }

    // Aggiorna la libreria quando cambiano i filtri
    $('#filterYear, #filterMonth').change(function() {
      loadImages();
    });

    // Gestione del click su un'immagine della libreria:
    // inserisce l'immagine originale nell'editor e chiude il modal.
    $(document).on('click', '.selectable-image', function() {
      var imgUrl = $(this).data('url');
      $('#editortest').summernote('insertImage', imgUrl);
      $('#mediaLibraryModal').modal('hide');
    });

    // (Opzionale) Gestione cancellazione immagine
    $(document).on('click', '.delete-image', function(e) {
      e.stopPropagation();
      var imgUrl = $(this).data('url');
      if(confirm("Sei sicuro di voler cancellare questa immagine?")) {
        $.ajax({
          url: 'delete_image.php',
          type: 'POST',
          data: { image: imgUrl },
          dataType: 'json',
          success: function(response) {
            if(response.status === 'success') {
              loadImages();
            } else {
              alert("Errore: " + response.message);
            }
          },
          error: function() {
            alert("Errore nella richiesta di cancellazione.");
          }
        });
      }
    });
  });
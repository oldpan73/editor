<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Esempo Editor WYSIWYG con Libreria Immagini, Thumbs, tabelle</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Summernote CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <h2>Editor</h2>
    <div id="note-editor">
    <form method="post" action="salva_testo.php">
      <textarea id="summernote" name="contenuto"></textarea>
      <button type="submit" class="btn btn-primary mt-3">Salva</button>
    </form></div>
  </div>

  <!-- Modal per la libreria immagini -->
  <div id="mediaLibraryModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Libreria Immagini</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
         <div class="modal-body">
            <!-- Filtri per anno e mese -->
            <div class="form-row mb-3">
              <div class="col-md-6">
                <select id="filterYear" class="form-control">
                  <option value="">Tutti gli anni</option>
                </select>
              </div>
              <div class="col-md-6">
                <select id="filterMonth" class="form-control">
                  <option value="">Tutti i mesi</option>
                  <option value="01">Gennaio</option>
                  <option value="02">Febbraio</option>
                  <option value="03">Marzo</option>
                  <option value="04">Aprile</option>
                  <option value="05">Maggio</option>
                  <option value="06">Giugno</option>
                  <option value="07">Luglio</option>
                  <option value="08">Agosto</option>
                  <option value="09">Settembre</option>
                  <option value="10">Ottobre</option>
                  <option value="11">Novembre</option>
                  <option value="12">Dicembre</option>
                </select>
              </div>
            </div>
            <!-- Area scrollabile per le immagini -->
            <div id="mediaLibraryContent" style="max-height: 100%; overflow-y: auto;">
              <!-- Le immagini verranno caricate qui via AJAX -->
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
         </div>
      </div>
    </div>
  </div>

  <!-- jQuery, Popper e Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Summernote JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>
  
  <script src="js/funzioni.js"></script>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <title>GrapesJS Page Editor</title>

    <!-- GrapesJS core -->
    <link href="{{ asset('grapesjs/css/grapes.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <script src="{{ asset('grapesjs/js/grapes.min.js') }}"></script>

    <!-- Plugins -->
    <script src="{{ asset('grapesjs/js/grapesjs-blocks-basic.js') }}"></script>
    <script src="{{ asset('grapesjs/js/grapesjs-preset-webpage.js') }}"></script>
    <script src="{{ asset('grapesjs/js/grapesjs-navbar.js') }}"></script>
    <script src="{{ asset('grapesjs/js/grapesjs-tabs.js') }}"></script>
    <script src="{{ asset('grapesjs/js/grapesjs-plugin-export.js') }}"></script>
    <script src="{{ asset('grapesjs/js/grapesjs-component-code-editor.js') }}"></script>
    <script src="{{ asset('grapesjs/js/grapesjs-plugin-forms.js') }}"></script>
    <script src="{{ asset('grapesjs/js/grapesjs-tooltip.js') }}"></script>
    <script src="{{ asset('grapesjs/js/grapesjs-custom-code.js') }}"></script>
    <script src="{{ asset('grapesjs/js/grapesjs-touch.js') }}"></script>
    <script src="{{ asset('grapesjs/js/grapesjs-parser-postcss.js') }}"></script>
    <script src="{{ asset('grapesjs/js/grapesjs-style-bg.js') }}"></script>
    <script src="{{ asset('grapesjs/js/grapesjs-style-filter.js') }}"></script>
    <script src="{{ asset('grapesjs/js/grapesjs-style-gradient.js') }}"></script>
    <script src="{{ asset('grapesjs/js/grapesjs-style-border.js') }}"></script>
    <script src="https://unpkg.com/grapesjs-style-shadow"></script>
    <script src="https://unpkg.com/grapesjs-style-timerange"></script>

    <style>
        body, html {
            height: 100%;
            margin: 0;
        }
        #gjs {
            height: 100vh;
        }
    </style>
</head>
<body>

<div id="gjs"></div>

<script>
const editor = grapesjs.init({
  container: '#gjs',
  height: '100%',
  fromElement: false,
  storageManager: { type: null, autosave: false, autoload: false },
  components: {!! json_encode(json_decode($page->components), JSON_UNESCAPED_SLASHES) !!},
  style: {!! json_encode(json_decode($page->styles), JSON_UNESCAPED_SLASHES) !!},
  canvas: {
    styles: ['https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap'],
  },
  plugins: [
    'gjs-blocks-basic',
    'gjs-preset-webpage',
    'grapesjs-navbar',
    'grapesjs-tabs',
    'grapesjs-plugin-export',
    'grapesjs-component-code-editor',
    'grapesjs-plugin-forms',
    'grapesjs-tooltip',
    'grapesjs-custom-code',
    'grapesjs-touch',
    'grapesjs-parser-postcss',
    'grapesjs-style-bg',
    'grapesjs-style-filter',
    'grapesjs-style-gradient',
    'grapesjs-style-border',
    'grapesjs-style-shadow',
    'grapesjs-style-timerange',
  ],
  pluginsOpts: {
    'gjs-blocks-basic': { flexGrid: true },
    'gjs-preset-webpage': {
      blocksBasicOpts: { flexGrid: true },
      navbarOpts: false,
      countdownOpts: false,
    },
    'grapesjs-component-code-editor': { codeViewTheme: 'material' },
  },
});

// Undo/Redo
editor.Panels.addButton('options', [
  {
    id: 'undo',
    className: 'fa fa-undo',
    command: 'core:undo',
    attributes: { title: 'Undo' },
  },
  {
    id: 'redo',
    className: 'fa fa-redo',
    command: 'core:redo',
    attributes: { title: 'Redo' },
  },
]);

// Save function
function savePage() {
  const html = editor.getHtml();
  const css = editor.getCss();
  const components = JSON.stringify(editor.getComponents());
  const styles = JSON.stringify(editor.getStyle());

  fetch('{{ route("admin.landingpage.pagesave.save", $page->id) }}', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({ html, css, components, styles })
  })
  .then(res => res.json())
  .then(data => alert('Page saved successfully!'))
  .catch(err => {
    console.error(err);
    alert('Error saving page');
  });
}

// Save button
editor.Panels.addButton('options', [{
  id: 'save-db',
  className: 'fa fa-save',
  command: {
    run: function () {
      savePage();
    }
  },
  attributes: { title: 'Save Page' }
}]);
</script>

</body>
</html>

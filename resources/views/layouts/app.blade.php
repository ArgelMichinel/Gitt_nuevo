<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta property="og:image" content="https://www.gittgetittoday.com/images/97543029_280123276722929_1855283596785352704_n.png">
<meta property="og:title"content="Gitt Get it Today">
<meta property="og:image:type" content="image/jpg">
<meta property="og:image:width" content="250">
<meta property="og:image:height" content="250">
<meta http-equiv="Expires" content="0">
<meta http-equiv="Last-Modified" content="0">
<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
<meta http-equiv="Pragma" content="no-cache">
<title>@yield('title')</title>
<link rel="stylesheet" href="{{ asset('Styles/SN-styles.css') }}">
<link rel="stylesheet" href="{{ asset('Styles/Footer-styles.css') }}">
<!Estilo agregado para poder alinear div facilmente. Se usa con ul class="nav justify-content-end">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<!Estilo agregado para el icono del menu">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<!Estilos de letras>
<link href="https://fonts.googleapis.com/css2?family=Permanent+Marker&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@1,500&display=swap" rel="stylesheet">
@yield('inc_head')
</head>

<style>

    
</style>

</style>

<body>
    
<main id="main">
<!*********************************************************************** Output>    
@yield('output')
    
<!*********************************************************************** Footer>
    <div class="footer-container">
        
    
    <x-footer></x-footer>
        
    </div>    
    
</main>

</body>
<script type="text/javascript" src="{{ asset('JS/H-Script.js') }}"></script>
@yield('inc_script_end')
<script>
    
    
</script>
</html>
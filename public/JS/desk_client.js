
function query_packets() {
    let activar = document.getElementById('screem-packets');
    let desactivar = document.getElementById('principal');

    
    document.getElementById('presentacion').style.background = "none";
    desactivar.style.display = "none";
    activar.style.display = "block";
    
    if (document.getElementById("envios").value == '-1') {
        document.getElementById('id01').style.display = "flex";
    }
}

function assing_credential() {
    window.location.href = "https://www.gittservices.com/curl.php"
}

////////////////////////////////////////////////
function activ_filter() {
    let filtros = document.getElementsByClassName('Checkbox-filter');
    let tr_dep_filtros = [];
    let termino;

    for (i=0; i < filtros.length; i++) {
        termino = filtros[i].parentElement.nextElementSibling;
        tr_dep_filtros.push(termino);
    }

    for (i=0; i < filtros.length; i++) {
    // If the checkbox is checked, display the output text
      if (filtros[i].checked == true){
        tr_dep_filtros[i].style.display = "block";
      } else {
        tr_dep_filtros[i].style.display = "none";
      }
    }
}

/////////////////////////////////////////////////
document.addEventListener('DOMContentLoaded',function(){  
    if (document.getElementById("envios").value != '-1') {
        document.getElementById('principal').style.display = "none";
        document.getElementById('screem-packets').style.display = "block";
        document.getElementById('presentacion').style.background = "none";
    }
});
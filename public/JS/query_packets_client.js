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

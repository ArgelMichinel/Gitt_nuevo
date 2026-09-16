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

/////////////////////////////////////
function cancel_ship(elemento) {

    let id_ship;
    
    id_ship = elemento.parentElement.parentElement.children[0].innerText;
    console.log(id_ship);
    document.getElementById('id01_id_ship').value = id_ship;
    document.getElementById('id01').style.display='block';

}

//////////////////////////////
document.addEventListener("DOMContentLoaded", function ()    {
    var TN_sticker = document.getElementsByClassName('fa fa-id-card-o');
    
    //let ele_select = document.getElementsByClassName(elemen.id);

    for (i=0; i < TN_sticker.length; i++) {
        TN_sticker[i].addEventListener("click", function() {
        var selec_shipnum = this.parentElement.parentElement;
        //console.log(selec_QR);
        selec_shipnum = selec_shipnum.children[0].innerText;
        
        //alert(selec_QR);
        //let foo = prompt('Copia el QR',selec_QR);
        window.open('Sticker_TN/' + selec_shipnum); 
        });
        
}});

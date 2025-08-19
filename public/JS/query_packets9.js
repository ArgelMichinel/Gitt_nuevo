var mat_list=[];

function activ_filter() {
    let filtros = document.getElementsByClassName('Checkbox-filter');
    let tr_dep_filtros = [];
    let termino;

    for (i=0; i < filtros.length; i++) {
        if ((i == 3) && (i == 5) && (i == 6) ){
            termino = '';
            tr_dep_filtros.push(termino);
        } else{
          termino = filtros[i].parentElement.nextElementSibling;
          tr_dep_filtros.push(termino);
        }
    }

    for (i=0; i < filtros.length; i++) {
    // If the checkbox is checked, display the output text
        if ((i != 3) && (i != 5) && (i != 6) ){
           // console.log('voy por i=' + i);
          if (filtros[i].checked == true){
            tr_dep_filtros[i].style.display = "block";
          } else {
            tr_dep_filtros[i].style.display = "none";
          }
        }

    }
}
////////////////////////////////
function activ_row(id_clicked) {
  let elemen = document.getElementById(id_clicked);
  //let ele_select = document.getElementsByClassName(elemen.id);
  console.log(id_clicked);

  /*if (elemen.checked == true){
    for (i=0; i < ele_select.length; i++) {
      ele_select[i].style.display = "block";
    }
  } else {
    for (i=0; i < ele_select.length; i++) {
      ele_select[i].style.display = "none";
    }
  }*/
}
//////////////////////////////
function select_all() {
    let tr_table = document.getElementById('data_table').children;

    for (i=0; i < tr_table.length; i++) {
        tr_table[i].firstElementChild.firstElementChild.checked = document.getElementById('selection_check').checked;
        //tr_dep_filtros.push(termino);
    }
}
/////////////////////////////
function constr_list() {
    let tr_table = document.getElementById('data_table').children;
    let text_num;
    let check_td;

    for (i=0; i < tr_table.length; i++) {
        check_td = tr_table[i].children[0].children[0];
        if (check_td.checked == true){
            text_num = tr_table[i].children[1].innerText;
            mat_list.push(text_num);
          }
        
    }
    
    /* let json_list = JSON.stringify(mat_list);
    
    document.getElementById('values_list').value = json_list; */

    let json_list = {
      "name": document.getElementById('name_list').value,
      "values": JSON.stringify(mat_list)
    }

    
    var Ajax;

    Ajax=new XMLHttpRequest();

    Ajax.onreadystatechange = function() {
        
        if (Ajax.readyState==4 && Ajax.status==200) {
          //console.log(Ajax.responseText);
            console.log(Ajax.responseText)
            document.getElementById("name_list").value = ''
      
            for (i=0; i < tr_table.length; i++) {
                check_td = tr_table[i].children[0].children[0];
                if (check_td.checked == true){
                    check_td.checked = false;
                  }
            }

            alert('Creada la lista exitosamente');

        }

    }

    let packete = JSON.stringify(json_list);
    //console.log(packete);
    //Ajax.open("POST","./prueba",true);
    Ajax.open("POST",window.location.href,true);
    Ajax.setRequestHeader("Content-Type","application/json");

    let csrfToken = document.getElementsByName('_token')[0].value;
    // Agregar el token CSRF en los encabezados
    Ajax.setRequestHeader("X-CSRF-TOKEN", csrfToken);
    
    Ajax.send( packete );

}

//////////////////////////////
document.addEventListener("DOMContentLoaded", function ()    {
    var QR_tags = document.getElementsByClassName('fa fa-qrcode');
    
    //let ele_select = document.getElementsByClassName(elemen.id);

    for (i=0; i < QR_tags.length; i++) {
        QR_tags[i].addEventListener("click", function() {
        var selec_QR = this.parentElement.parentElement;
        //console.log(selec_QR);
        selec_QR = selec_QR.children[selec_QR.children.length - 1].innerText;
        navigator.clipboard.writeText(selec_QR);
        
        //alert(selec_QR);
        let foo = prompt('Copia el QR',selec_QR);
        window.open('QRgenerator/' + btoa(encodeURIComponent(selec_QR))); // Se codificó a base64 para poderlo pasar sin problema por la URL
        });
        
}});

//////////////////////////////
document.addEventListener("DOMContentLoaded", function ()    {
    var QR_tags = document.getElementsByClassName('fa fa-eye');
    var myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
    
    //let ele_select = document.getElementsByClassName(elemen.id);

    /* for (i=0; i < QR_tags.length; i++) {
        QR_tags[i].addEventListener("click", function() {
        var selec_QR = this.parentElement.parentElement;
        //console.log(selec_QR);
        selec_QR = " Cadete ---  Administrador --- Fecha \n" +
                    selec_QR.children[20].innerText + ' - ' + selec_QR.children[21].innerText + ' - ' + selec_QR.children[22].innerText + '\n' +
                    selec_QR.children[23].innerText + ' - ' + selec_QR.children[24].innerText + ' - ' + selec_QR.children[25].innerText + '\n' +
                    selec_QR.children[26].innerText + ' - ' + selec_QR.children[27].innerText + ' - ' + selec_QR.children[28].innerText + '\n';
        
        alert(selec_QR);
        });
    } */

    for (i=0; i < QR_tags.length; i++) {
        QR_tags[i].addEventListener("click", function() {
        var selec_QR = this.parentElement.parentElement;
        //console.log(selec_QR);

        if (document.getElementById('filtro_basico').checked) {
            document.getElementById("mod_cad1").innerText = selec_QR.children[13].innerText
            document.getElementById("mod_cad2").innerText = selec_QR.children[16].innerText
            document.getElementById("mod_cad3").innerText = selec_QR.children[19].innerText
            document.getElementById("mod_adm1").innerText = selec_QR.children[14].innerText
            document.getElementById("mod_adm2").innerText = selec_QR.children[17].innerText
            document.getElementById("mod_adm3").innerText = selec_QR.children[20].innerText
            document.getElementById("mod_fec1").innerText = selec_QR.children[15].innerText
            document.getElementById("mod_fec2").innerText = selec_QR.children[18].innerText
            document.getElementById("mod_fec3").innerText = selec_QR.children[21].innerText
        } else {
            document.getElementById("mod_cad1").innerText = selec_QR.children[20].innerText
            document.getElementById("mod_cad2").innerText = selec_QR.children[23].innerText
            document.getElementById("mod_cad3").innerText = selec_QR.children[26].innerText
            document.getElementById("mod_adm1").innerText = selec_QR.children[21].innerText
            document.getElementById("mod_adm2").innerText = selec_QR.children[24].innerText
            document.getElementById("mod_adm3").innerText = selec_QR.children[27].innerText
            document.getElementById("mod_fec1").innerText = selec_QR.children[22].innerText
            document.getElementById("mod_fec2").innerText = selec_QR.children[25].innerText
            document.getElementById("mod_fec3").innerText = selec_QR.children[28].innerText
        }
        
        myModal.show();
        document.querySelector("body > div.modal-backdrop.fade.show").remove()
        });
    }
  
  });


//////////////////////////////
function cambio_cadete() {
  document.getElementById('cadete_definitivo').value = document.getElementById('dummy_cadete').value.slice(-6);
}


//////////////////////////////
function update_TN(elemento) {

  var elemento
  var fila = elemento.parentElement.parentElement;
  //console.log(fila);
  var idship = fila.children[1].innerText;
  console.log(JSON.stringify(idship));
  let json_id = {
      "envio": idship
    }

   fetch(window.location.href + '/update_TN', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': document.getElementsByName('_token')[0].value
      },
      body: JSON.stringify(json_id)
  })
  .then(response => {
    console.log(response.status)
    const texto = document.createElement("span");
    texto.textContent = "Completado";
    elemento.parentNode.replaceChild(texto, elemento);
  })
  .catch(error => console.error('Error:', error));

}
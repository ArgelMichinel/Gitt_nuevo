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

    fetch(window.location.href, {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': document.getElementsByName('_token')[0].value
      },
      body: JSON.stringify(json_list)
  })
  .then(response => response.json())
  .then(data => {
    console.log(data)
    document.getElementById("name_list").value = ''
      
      for (i=0; i < tr_table.length; i++) {
          check_td = tr_table[i].children[0].children[0];
          if (check_td.checked == true){
              check_td.checked = false;
            }
      }

      alert(data.message)
  })
  .catch(error => console.error('Error:', error));

    
    /* document.getElementById('submit_list').click(); */

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
        window.open('QRgenerator/' + encodeURIComponent(selec_QR));
        });
        
    }});

    //////////////////////////////
    document.addEventListener("DOMContentLoaded", function ()    {
        var QR_tags = document.getElementsByClassName('fa fa-eye');
        
        //let ele_select = document.getElementsByClassName(elemen.id);
    
        for (i=0; i < QR_tags.length; i++) {
            QR_tags[i].addEventListener("click", function() {
            var selec_QR = this.parentElement.parentElement;
            //console.log(selec_QR);
            selec_QR = " Cadete ---  Administrador --- Fecha \n" +
                       selec_QR.children[19].innerText + ' - ' + selec_QR.children[20].innerText + ' - ' + selec_QR.children[21].innerText + '\n' +
                       selec_QR.children[22].innerText + ' - ' + selec_QR.children[23].innerText + ' - ' + selec_QR.children[24].innerText + '\n' +
                       selec_QR.children[25].innerText + ' - ' + selec_QR.children[26].innerText + ' - ' + selec_QR.children[27].innerText + '\n';
            
            alert(selec_QR);
            });
            
        }});
    

    //////////////////////////////
    function cambio_cadete() {
      document.getElementById('cadete_definitivo').value = document.getElementById('dummy_cadete').value.slice(-6);
    }

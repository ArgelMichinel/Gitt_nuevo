document.addEventListener('DOMContentLoaded',function(){  
    constr_list();
});
////////////////////////////// 

function select_data() {
    let activar = document.getElementById('data_container');
    let desactivar = document.getElementById('client-list');

    desactivar.style.display = "none";
    activar.style.display = "block";
}

////////////////////////////// 
document.addEventListener('DOMContentLoaded',function(){  
    constr_list();
});

function constr_list() {
    let client_icon = document.getElementsByClassName('fa fa-user-circle-o');

    for (i = 0; i < client_icon.length; i++) {
        client_icon[i].onclick = function() {
            let selec_client = this.parentElement.nextElementSibling.innerHTML;
            console.log(selec_client);
            let name_client = this.parentElement.nextElementSibling.nextElementSibling.innerHTML;
            document.getElementById("client-selected").innerHTML = name_client;
            select_data();
            request_data(selec_client);
        }
    }
    
}

/////////////////////////////////////////////////////
function request_data(selec_client) {

    var Ajax;

    Ajax=new XMLHttpRequest();

    Ajax.onreadystatechange = function() {
        let ship_dat;
        
        if (Ajax.readyState==4 && Ajax.status==200) {
            document.getElementById("data-client").innerHTML = Ajax.responseText;
            
        }

    }

    Ajax.open("GET","./user_id.php?id_user="+selec_client,true);

    Ajax.send();

}

/////////////////////////////////////////////////////

function constr_list() {
    let trash_icon = document.getElementsByClassName('fa fa-trash');

    for (i = 0; i < trash_icon.length; i++) {
        trash_icon[i].onclick = function() {
            let selec_client = this.parentElement.parentElement.children[1];
            document.getElementById("del_client").value = selec_client.innerHTML;
            document.getElementById("id01").style.display = 'block';
        }
    }
    
}
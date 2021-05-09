 $(document).ready(function(){ 
	$("#mygrid").jqGrid({ //creazione l'instanza di un oggetto jqgrid
		url:'http://localhost/esPhpScuola/apiMie/student.php', //url del server REST
		datatype: "json", //specifico il formato di dati che si aspetta per riempire la griglia
		colNames:['id', 'name', 'surname', 'sidi_code', 'tax_code'], //imposto nomi delle colonne della griglia
		colModel:[//definisco le proprietà delle varie colonne
			{name:'id',index:'id', width:55, editable: false, sorttype: 'int'},
			{name:'name',index:'name', width:100, editable: true},	
            {name:'surname',index:'surname', width:100, editable: true},	        
            {name:'sidi_code',index:'sidi_code', width:100, editable: true},	   
			{name:'tax_code',index:'tax_code', width:120, sortable:false, editable: true}		
		],
		rowNum:10, //numero massimo di righe visualizzate automaticamente
		loadonce: true,    
		rowList:[10,20,30],
		pager: '#mypager', //indico l'id del pager successivamente configurato 
		sortname: 'id', //ordino le righe per l'id dell'impiegato
        viewrecords: true,        
		sortorder: "asc", 
        editable: true, 
        multiselect: true, // abilito la selezione multiriga
		caption:"Manage students" //intestazione della tabella        

	});


$("#mygrid").jqGrid('navGrid','#mypager', //specifico le proprietà del pager per i vari pulsanti e le rispettive callback
{
    edit:true, edittitle: "Edit", width: 500,
    add:true, addtitle: "Add", width: 500,
    del:true,
    search: true,
    refresh: true,
    refreshstate: "current",
    reloadGridOptions: { fromServer: true },
    view:true
},

{ //gestione del pulsante relativo alla modifica dei dati di un impiegato
    editCaption: "Edit", 
    edittext: "Edit", 
    mtype: "PUT", //imposto il metodo che verrà utilizzato per la richiesta http al server REST
    closeOnEscape: true, 
    closeAfterEdit: true, 
    savekey: [true, 13], 
    errorTextFormat: commonError, 
    width: "500", 
    reloadAfterSubmit: true, 
    bottominfo: "Please, update the data", //imposto la descrizione situata nella parte inferiore
    top: "60", 
    left: "5", 
    right: "5",
    afterSubmit: function () { location.reload(true); }, //specifico che dopo la submit venga effettuato il reload della griglia
    onclickSubmit: function (options, postdata) { //specifico la funzione da richiamare al click del rispettivo bottone
        EditPost(options,postdata);
    }
},

{//gestione del pulsante relativo all'inserimento dei dati di un impiegato
    addCaption: "Add", 
    addtext: "Add", 
    mtype: "POST", //imposto il metodo che verrà utilizzato per la richiesta http al server REST
    closeOnEscape: true, 
    closeAfterEdit: true, 
    savekey: [true, 13], 
    errorTextFormat: commonError, 
    width: "500", 
    reloadAfterSubmit: true, 
    bottominfo: "Please, insert the data", //imposto la descrizione situata nella parte inferiore
    top: "60", 
    left: "5", 
    right: "5",
    afterSubmit: function () { location.reload(true); }, //specifico che dopo la submit venga effettuato il reload della griglia
    onclickSubmit: function (options, postdata) { //specifico la funzione da richiamare al click del rispettivo bottone
        AddPost(options,postdata);
    }
},

{//gestione del pulsante relativo alla cancellazione dei dati di un impiegato
    deleteCaption: "Delete", 
    deletetext: "Delete", 
    mtype: "DELETE", //imposto il metodo che verrà utilizzato per la richiesta http al server REST
    closeOnEscape: true, 
    closeAfterEdit: true, 
    savekey: [true, 13], 
    errorTextFormat: commonError, 
    width: "500", 
    reloadAfterSubmit: true, 
    top: "60", 
    left: "5", 
    right: "5",
    onclickSubmit: function (options, postdata) { //specifico la funzione da richiamare al click del rispettivo bottone
        DeletePost(options,postdata);
    }   
}
);



function commonError(data) { //funzione per l'avviso di eventuali errori
    return "Errore!!!!";
}

function EditPost(options,postdata) { //funzione che effetua l'aggiornamento dei dati inviando una richiesta http tramite metodo put
    var selRowId = jQuery("#mygrid").getGridParam('selrow'); //ricavo l'id della riga selezionata
    celValue = jQuery("#mygrid").jqGrid ('getCell', selRowId, 'id'); //ricavo l'id dell'impiegato selezionato
    //alert("userID:" +userID);  // test
    options.url = "http://localhost/esPhpScuola/apiMie/student.php/?id="+ celValue; //setto l'url aggiungendo l'id dell'impiegato
    //console.log(postdata);  // test
}
function AddPost(options,postdata) {//funzione che effetua l'inserimento dei dati inviando una richiesta http tramite metodo post
    options.url = "http://localhost/esPhpScuola/apiMie/student.php";
   // console.log(postdata); //test
}
function DeletePost(options,postdata) {//funzione che effetua la rimozione dei dati inviando una richiesta http tramite metodo delete
    var selRowIds = jQuery("#mygrid").jqGrid('getGridParam', 'selarrrow'); //ricavo l'id della riga selezionata
    $.each( selRowIds, function( index, value ){
        celValue = jQuery("#mygrid").jqGrid ('getCell', value, 'id'); //ricavo l'id dell'impiegato selezionato
        var urlREST = "http://localhost/esPhpScuola/apiMie/student.php/?id="+celValue ; 
        $.ajax({ type: "DELETE",url: urlREST });
    
    });
 
}
});

$.extend($.jgrid.edit, { //estensione delle funzionalità di jqgrid
    closeOnEscape: true,
    closeAfterEdit: true,
    closeAfterAdd: true,
    reloadAfterSubmit: false,
    recreateForm: true,
    datatype: "json",
    ajaxEditOptions: { contentType: "application/json" },
    serializeEditData: function (postData) { //converto in stringa JSON
      return JSON.stringify(postData)
    }
});


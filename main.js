var login="";
var data = { filter : "future"};
var askedSearch = "";

function get_list(data,status,x){
	$("#atListView").html(data);
	console.log("get_list");
	try {
		$('#atListView').listview("refresh");
	}
	catch(error){
		//$('#atListView').listview();
		//$('#atListView').listview("refresh");
	}
	$(".commentblock").hide();
	$.mobile.loading("hide");
}


function add_comment(n) {
	var text = $("#add_"+n).val();
	var data = { id : n, text : text, action : 'add_comment'};
	$.get("list.php",data,get_list,"html");
	$.mobile.loading("show");
}

function modify_receive(data) {
	$("#mid").html(data["rowid"]);
	$("#mDate").val(data["date"]);
	$("#mTime").val(data["time"]);
	$("#mLoc").val(data["location"]);
	$("#mTopic").val(data["topic"]);
	$("body").pagecontainer('change','#modify');
	$.mobile.loading("hide");
}

function actionAtelier(n,action){

	var data = { id : n, action : action, filter : "null" };
	$.get("list.php",data,get_list,"html");
	$.mobile.loading("show");
}

function commentaire(n){
	$("#c_"+n).toggle();
}

function log_validate(data,status,x){
	if ( data == "OK" ) {
		if (askedSearch  == "" ) {
			$("body").pagecontainer('change','#home');
		} else {
			transformSearch(askedSearch);
		}
		login = $("#trigramme").val();
		$(".login").html(login);
		//$(divID).parent().hide();
	} else {
		$("#logingMessage").html(data);
	}
	$.mobile.loading("hide");
}

function action(n) {
	var action = $("#select_"+n).val();
	$.mobile.loading("show");
	if (action == "modify") {
		var data = { id : n, action : "data" , filter : "null" };
		$.getJSON("modify.php",data,modify_receive);
	} else {
		var data = { id : n, action : action, filter : "null" };
		$.get("list.php",data,get_list,"html");
	}
}

function new_validate(data,status,x){
	if ( data == "OK" ) {
		$("#newMessage").empty();
		$.mobile.navigate("#list");
		console.log("log :" + location); 
	} else {
		$("#newMessage").html(data);
	}
	$.mobile.loading("hide");
}

function modify_validate(data,status,x){
	if ( data == "OK" ) {
		$("#mMessage").empty();
		//$("body").pagecontainer('change','#list');
		$.mobile.navigate("#list");
	} else {
		$("#mMessage").html(data);
		$.mobile.loading("hide");
	}
}

function chpasswd_validate(data,status,x){
	if (data == "OK") {
	} else {
		$("#npMessage").html(data);
	}
	$.mobile.loading("hide");
}

function sendpasswd_validate(data,status,x){
	if (data == "OK") {
		$("#logingMessage").html("email evoyé");
	} else {
		$("#logingMessage").html(data);
	}
	$.mobile.loading("hide");
}

function sendpwd(){
		$.mobile.loading("show");
		var data = { action : "send", login : $("#trigramme").val() };
		$.get("password.php",data,sendpasswd_validate,"html");
};

function transformSearch(search) {
	if ( search.substring(0,9) == "?atelier=" ) {
		id = search.substring(9);
		data = {id:id, filter: "unique"};
		$.get("list.php",data,get_list,"html");
		location = "#list";
	}
}

function logout(data,status,x){
//	$("body").html("Vous êtes déconnectés.");
	login="";
}

function modifyEntry() {
	var search = location.search;
	$(".login").html(login);
	if ( login == "" ) {
		$.mobile.navigate("#login");
		askedSearch = search;
		return;
	}

	if ( search != "" ) {
		transformSearch(search);
		return;
	}

	if ( login != "" && location.hash=="") {
		$.mobile.navigate("#home");
		return;
	}
	hashHandler();
}

function hashHandler() {
	var hash = location.hash;
	if ( hash == "#list" ) {
		$.get("list.php",data,get_list,"html");
		data={filter : "future"}
	}

	if ( hash == "#logout" ) {
		$.get("logout.php","",logout,"html");
	}
}

$("document").ready(function() {
	modifyEntry();

	$("#perso_atelier").click(function(){
		data = {filter : "private" };
	});
	
	$("#menu_toutatelier").click(function(){
		data = {filter : "all" };
	});
	
	$("#no_cr").click(function(){
		data = {filter : "nocr" };
	});

	$("#loginbut").click(function(){
		var data = { login : $("#trigramme").val(), pwd : $("#pwd").val() };
		$.get("login.php",data,log_validate,"html");
		$.mobile.loading("show");
	});
	
	$("#npassword").click(function(){
		var data = { action : "change", pwd : $("#npwd").val() };
		$.get("password.php",data,chpasswd_validate,"html");
		$.mobile.loading("show");
	});

	$("#newbut").click(function(){
		var personstab = [];
		$( ".person" ).each(function(index){
			var value = this.checked;
			var trigramme = this.name;
			if (value == true) {
				personstab.push(trigramme);
			}
		});
		persons = personstab.join(",");

		var data = { 
				date : $("#newDate").val(),
				time : $("#newTime").val(),
				loc  : $("#newLoc").val(),
				topic  : $("#newTopic").val(),
				comment: $("#newComment").val(),
				action : "create",
				persons : persons
		};

		$.get("newat.php",data,new_validate,"html");
		$.mobile.loading("show");
	});
	
	$("#modifybut").click(function(event){
		event.preventDefault ();
		var data = { 
				date : $("#mDate").val(),
				time : $("#mTime").val(),
				loc  : $("#mLoc").val(),
				topic  : $("#mTopic").val(),
				action : "modify",
				id  : $("#mid").html()
		};

		$.get("newat.php",data,modify_validate,"html");
		$.mobile.loading("show");
	});
	
	
	$("#menu_add").click(function(event){
		event.preventDefault ();
		$("#newMessage").empty();
		$("#newDate").val("");
		$("#newTime").val("");
		$("#newLoc").val("");
		$("#newTopic").val("");
		$.mobile.navigate("#add");
		$.mobile.loading("show");
	});
	
	$( window ).on( "navigate", function( event, data ) {

	});
	
	$( window ).hashchange(function() {
		hashHandler();
	});
});
